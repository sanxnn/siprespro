<?php
namespace App\Http\Controllers\Mahasiswa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    public function index()
    {
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
        $kelasIds = DB::table('kelas_golongan')
            ->where('golongan_id', $mahasiswa->golongan_id)
            ->pluck('kelas_perkuliahan_id')
            ->toArray();
        $totalPertemuan = DB::table('presensi')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->count();
        $kehadiran = DB::table('presensi')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->select(
                DB::raw("COUNT(CASE WHEN LOWER(status) = 'hadir' THEN 1 END) as hadir"),
                DB::raw("COUNT(CASE WHEN LOWER(status) = 'sakit' THEN 1 END) as sakit"),
                DB::raw("COUNT(CASE WHEN LOWER(status) = 'izin' THEN 1 END) as izin"),
                DB::raw("COUNT(CASE WHEN LOWER(status) IN ('alpha', 'alfa') THEN 1 END) as alpha")
            )->first();
        $totalMasuk = $kehadiran->hadir + $kehadiran->sakit + $kehadiran->izin;
        $persentaseKehadiran = $totalPertemuan > 0
            ? round(($totalMasuk / $totalPertemuan) * 100, 1)
            : 0;
        $tanggalHariIni = date('Y-m-d');
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
                'mata_kuliah.nama as nama_mk',
                'mata_kuliah.kode_mk',
                'mata_kuliah.sks',
                'dosen.nama as nama_dosen',
                'lokasi.nama as ruangan',
                'presensi.status as status_absen_mhs'
            )
            ->orderBy('pertemuan.jam_mulai', 'asc')
            ->get();
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
