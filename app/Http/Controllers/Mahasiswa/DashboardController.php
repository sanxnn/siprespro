<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
// use ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data mahasiswa + nama semester + nama golongan berdasarkan user login
        $user = Auth::user();

        $mahasiswa = DB::table('mahasiswa')
            ->join('semester', 'mahasiswa.semester_id', '=', 'semester.id')
            ->join('golongan', 'mahasiswa.golongan_id', '=', 'golongan.id')
            ->where('mahasiswa.id', $user->mahasiswa_id)
            ->select(
                'mahasiswa.*',
                'semester.nama as nama_semester',
                'golongan.nama as nama_golongan'
            )
            ->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // 2. Ambil semua ID kelas perkuliahan yang diikuti oleh golongan si mahasiswa
        $kelasIds = DB::table('kelas_golongan')
            ->where('golongan_id', $mahasiswa->golongan_id)
            ->pluck('kelas_perkuliahan_id')
            ->toArray();

        // 3. Statistik Kehadiran (SaaS Metrics Style)
        // Total pertemuan yang terjadwal/ada untuk semua kelas si mahasiswa
        $totalPertemuan = DB::table('pertemuan')
            ->whereIn('kelas_perkuliahan_id', $kelasIds)
            ->count();

        $kehadiran = DB::table('presensi')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->select(
                DB::raw("COUNT(CASE WHEN status = 'Hadir' THEN 1 END) as hadir"),
                DB::raw("COUNT(CASE WHEN status = 'Sakit' THEN 1 END) as sakit"),
                DB::raw("COUNT(CASE WHEN status = 'Izin' THEN 1 END) as izin"),
                DB::raw("COUNT(CASE WHEN status = 'Alpha' THEN 1 END) as alpha")
            )->first();

        $persentaseKehadiran = $totalPertemuan > 0
            ? round(($kehadiran->hadir / $totalPertemuan) * 100, 1)
            : 100;

        // 4. Kelas & Pertemuan Hari Ini (Langsung tembak ke tabel pertemuan berdasarkan tanggal sekarang)
        $tanggalHariIni = date('Y-m-d');

        // 4. Kelas & Pertemuan Hari Ini
        $tanggalHariIni = date('Y-m-d');

        $jadwalHariIni = DB::table('pertemuan')
            ->join('kelas_perkuliahan', 'pertemuan.kelas_perkuliahan_id', '=', 'kelas_perkuliahan.id')
            ->join('mata_kuliah', 'kelas_perkuliahan.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->join('dosen', 'kelas_perkuliahan.dosen_id', '=', 'dosen.id')
            ->join('lokasi', 'pertemuan.lokasi_id', '=', 'lokasi.id')
            ->leftJoin('presensi', function ($join) use ($mahasiswa) {
                $join->on('pertemuan.id', '=', 'presensi.pertemuan_id')
                    ->where('presensi.mahasiswa_id', '=', $mahasiswa->id);
            })
            ->whereIn('pertemuan.kelas_perkuliahan_id', $kelasIds)
            ->where('pertemuan.tanggal', $tanggalHariIni)
            ->select(
                'pertemuan.id as pertemuan_id',
                'pertemuan.pertemuan_ke',
                'pertemuan.jam_mulai',
                'pertemuan.jam_selesai',
                'pertemuan.status as status_buka_absen',
                'mata_kuliah.nama as nama_mk', // <--- GANTI INI COK! Biar di blade kebaca $jadwal->nama_mk
                'mata_kuliah.kode_mk',         // Ambil aja kode_mk nya kalau misal nanti butuh disandingkan
                'mata_kuliah.sks',
                'dosen.nama as nama_dosen',
                'lokasi.nama as ruangan',
                'presensi.status as status_absen_mhs'
            )
            ->orderBy('pertemuan.jam_mulai', 'asc')
            ->get();

        // 5. Log Aktivitas Presensi Terakhir (Riwayat Singkat)
        $riwayatTerakhir = DB::table('presensi')
            ->join('pertemuan', 'presensi.pertemuan_id', '=', 'pertemuan.id')
            ->join('kelas_perkuliahan', 'pertemuan.kelas_perkuliahan_id', '=', 'kelas_perkuliahan.id')
            ->join('mata_kuliah', 'kelas_perkuliahan.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->where('presensi.mahasiswa_id', $mahasiswa->id)
            ->select(
                'mata_kuliah.nama as nama_mk',
                'pertemuan.tanggal',
                'presensi.status',
                'presensi.waktu_presensi as updated_at'
            )
            ->orderBy('presensi.waktu_presensi', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.mahasiswa.index', compact(
            'mahasiswa',
            'kehadiran',
            'totalPertemuan',
            'persentaseKehadiran',
            'jadwalHariIni',
            'riwayatTerakhir'
        ));
    }
}
