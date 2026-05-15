<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $dosenId = auth()->user()->dosen_id;
        $semesterAktif = \App\Models\Semester::where('status', 'aktif')->first();
        $hariIni = now()->format('Y-m-d');

        // 1. Stats Cards
        $totalKelas = \App\Models\KelasPerkuliahan::where('dosen_id', $dosenId)->count();

        // CRITICAL FIX SESI SELESAI: Hanya hitung status 'ditutup' DAN tanggalnya hari ini ke belakang
        $pertemuanSelesai = \App\Models\Pertemuan::whereHas('kelasPerkuliahan', function ($q) use ($dosenId) {
            $q->where('dosen_id', $dosenId);
        })
            ->where('status', 'ditutup')
            ->whereDate('tanggal', '<=', $hariIni)
            ->count();

        $presensiHariIni = \App\Models\Presensi::whereDate('waktu_presensi', $hariIni)
            ->whereHas('pertemuan.kelasPerkuliahan', function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            })->count();

        // Total mahasiswa unik yang diajar oleh dosen ini
        $totalMahasiswaDiampu = \App\Models\Mahasiswa::whereHas('golongan.kelasPerkuliahan', function ($q) use ($dosenId) {
            $q->where('dosen_id', $dosenId);
        })->distinct()->count();


        // 2. Jadwal Mengajar Hari Ini
        $jadwalHariIni = \App\Models\Pertemuan::with(['kelasPerkuliahan.mataKuliah', 'kelasPerkuliahan.ruang', 'lokasi'])
            ->whereHas('kelasPerkuliahan', function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            })
            ->whereDate('tanggal', $hariIni)
            ->orderBy('jam_mulai')
            ->get();


        // 3. Aktivitas Presensi Terbaru (Real-time Feed)
        $recentPresensi = \App\Models\Presensi::with(['mahasiswa', 'pertemuan.kelasPerkuliahan.mataKuliah'])
            ->whereHas('pertemuan.kelasPerkuliahan', function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            })
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($presensi) {
                $presensi->time_diff = $presensi->waktu_presensi->diffForHumans();
                return $presensi;
            });


        // 4. CRITICAL FIX: HITUNG AVG KEHADIRAN SECARA PROPORSIONAL & REAL (SaaS Standard)

        // Ambil semua ID kelas yang diampu dosen ini
        $kelasIds = \App\Models\KelasPerkuliahan::where('dosen_id', $dosenId)->pluck('id')->toArray();

        $totalPresensiAman = 0;
        $kapasitasMaksimalTotal = 0;

        // Looping per kelas untuk dapet angka kapasitas maksimal real (Mhs x Sesi Selesai per kelas)
        $allKelas = \App\Models\KelasPerkuliahan::with(['pertemuans', 'golongans.mahasiswas'])
            ->whereIn('id', $kelasIds)
            ->get();

        foreach ($allKelas as $k) {
            $mhsCount = $k->golongans->flatMap(function ($g) {
                return $g->mahasiswas; })->unique('id')->count();
            $sesiSelesaiCount = $k->pertemuans->where('status', 'ditutup')->where('tanggal', '<=', $hariIni)->count();

            // Kapasitas maksimal kumulatif kelas ini
            $kapasitasMaksimalTotal += ($mhsCount * $sesiSelesaiCount);
        }

        // Hitung seluruh data presensi AMAN (Hadir, Sakit, Izin) di semua kelas dosen ini pada sesi yang sudah ditutup
        if ($kapasitasMaksimalTotal > 0) {
            $totalPresensiAman = \App\Models\Presensi::whereHas('pertemuan', function ($q) use ($kelasIds, $hariIni) {
                $q->whereIn('kelas_perkuliahan_id', $kelasIds)
                    ->where('status', 'ditutup')
                    ->whereDate('tanggal', '<=', $hariIni);
            })
                ->whereIn(DB::raw('LOWER(status)'), ['hadir', 'sakit', 'izin'])
                ->count();

            $avgKehadiran = round(($totalPresensiAman / $kapasitasMaksimalTotal) * 100);
            if ($avgKehadiran > 100)
                $avgKehadiran = 100;
        } else {
            $avgKehadiran = 100; // Default 100% kalau emang belum ada sesi yang ditutup/berjalan
        }


        return view('dashboard.dosen.index', compact(
            'semesterAktif',
            'totalKelas',
            'pertemuanSelesai',
            'presensiHariIni',
            'recentPresensi',
            'jadwalHariIni',
            'totalMahasiswaDiampu',
            'avgKehadiran'
        ));
    }
}
