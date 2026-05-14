<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $dosenId = auth()->user()->dosen_id;
        $semesterAktif = \App\Models\Semester::where('status', 'aktif')->first();
        $hariIni = now()->format('Y-m-d');

        // 1. Stats Cards
        $totalKelas = \App\Models\KelasPerkuliahan::where('dosen_id', $dosenId)->count();

        $pertemuanSelesai = \App\Models\Pertemuan::whereHas('kelasPerkuliahan', function ($q) use ($dosenId) {
            $q->where('dosen_id', $dosenId);
        })->where('status', 'selesai')->count(); // Pastiin ada kolom status di Pertemuan

        $presensiHariIni = \App\Models\Presensi::whereDate('waktu_presensi', $hariIni)
            ->whereHas('pertemuan.kelasPerkuliahan', function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            })->count();

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
                // Kita tambahin diffForHumans buat 'time_diff' kayak di Admin tadi
                $presensi->time_diff = $presensi->waktu_presensi->diffForHumans();
                return $presensi;
            });

        // Hitung Rata-Rata Kehadiran Dinamis
        $totalPresensiInput = \App\Models\Presensi::whereHas('pertemuan.kelasPerkuliahan', function ($q) use ($dosenId) {
            $q->where('dosen_id', $dosenId);
        })->count();

        $totalHadir = \App\Models\Presensi::where('status', 'hadir')
            ->whereHas('pertemuan.kelasPerkuliahan', function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            })->count();

        // Hindari Division by Zero (pembagian dengan nol)
        $avgKehadiran = $totalPresensiInput > 0
            ? round(($totalHadir / $totalPresensiInput) * 100)
            : 0;

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
