<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $dosenId = auth()->user()->dosen_id;
        $semesterAktif = \App\Models\Semester::where('status', 'aktif')->first(); // Sesuaikan flag aktif lo

        // Statistik
        $totalKelas = \App\Models\KelasPerkuliahan::where('dosen_id', $dosenId)->count();
        $pertemuanSelesai = \App\Models\Pertemuan::whereHas('kelasPerkuliahan', function ($q) use ($dosenId) {
            $q->where('dosen_id', $dosenId);
        })->count();

        // Presensi Hari Ini di Kelas Dosen Ini
        $presensiHariIni = \App\Models\Presensi::whereDate('waktu_presensi', date('Y-m-d'))
            ->whereHas('pertemuan.kelasPerkuliahan', function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            })->count();

        // Log Presensi Terbaru
        $recentPresensi = \App\Models\Presensi::with(['mahasiswa.golongan', 'pertemuan.kelasPerkuliahan.mataKuliah'])
            ->whereHas('pertemuan.kelasPerkuliahan', function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            })
            ->latest()->take(10)->get();

        // Jadwal Hari Ini
        $hariIni = strtolower(now()->translatedFormat('l'));
        $jadwalHariIni = \App\Models\Jadwal::with(['kelasPerkuliahan.mataKuliah', 'kelasPerkuliahan.ruang'])
            ->whereHas('kelasPerkuliahan', function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            })
            ->where('hari', $hariIni)
            ->get();

        // Data Tambahan
        // DI CONTROLLER
        $totalMahasiswaDiampu = \App\Models\Mahasiswa::whereHas('golongan.kelasPerkuliahan', function ($q) use ($dosenId) {
            $q->where('dosen_id', $dosenId);
        })->distinct()->count();

        $avgKehadiran = 85; // Ini contoh, lo bisa hitung pake query avg presensi

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
