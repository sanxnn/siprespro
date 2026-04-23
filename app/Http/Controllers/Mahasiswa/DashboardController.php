<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\KelasPerkuliahan;
use App\Models\Presensi;
use App\Models\Pertemuan;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            abort(403, 'Data mahasiswa tidak ditemukan.');
        }

        $golonganId = $mahasiswa->golongan_id;
        $today = Carbon::today();
        Carbon::setLocale('id');

        $kelasIds = collect();

        if ($golonganId) {
            $kelasIds = KelasPerkuliahan::whereHas('golongans', function ($query) use ($golonganId) {
                $query->where('golongan.id', $golonganId);
            })->pluck('id');
        }

        $hariIni = Str::lower($today->translatedFormat('l'));

        $jadwalHariIni = Jadwal::with([
            'kelasPerkuliahan.mataKuliah',
            'kelasPerkuliahan.dosen',
            'kelasPerkuliahan.ruang',
            'lokasi'
        ])
        ->when($kelasIds->isNotEmpty(), function ($query) use ($kelasIds) {
            $query->whereIn('kelas_perkuliahan_id', $kelasIds);
        }, function ($query) {
            $query->whereRaw('1 = 0');
        })
        ->where('hari', $hariIni)
        ->orderBy('jam_mulai')
        ->get();

        $pertemuanHariIni = Pertemuan::with([
            'kelasPerkuliahan.mataKuliah',
            'kelasPerkuliahan.dosen'
        ])
        ->when($kelasIds->isNotEmpty(), function ($query) use ($kelasIds) {
            $query->whereIn('kelas_perkuliahan_id', $kelasIds);
        }, function ($query) {
            $query->whereRaw('1 = 0');
        })
        ->whereDate('tanggal', $today)
        ->get();

        $totalHadir = Presensi::where('mahasiswa_id', $mahasiswa->id)->where('status', 'hadir')->count();
        $totalIzin = Presensi::where('mahasiswa_id', $mahasiswa->id)->where('status', 'izin')->count();
        $totalSakit = Presensi::where('mahasiswa_id', $mahasiswa->id)->where('status', 'sakit')->count();
        $totalAlpha = Presensi::where('mahasiswa_id', $mahasiswa->id)->where('status', 'alpha')->count();

        $riwayatTerbaru = Presensi::with([
            'pertemuan.kelasPerkuliahan.mataKuliah',
            'pertemuan.kelasPerkuliahan.dosen'
        ])
        ->where('mahasiswa_id', $mahasiswa->id)
        ->latest('waktu_presensi')
        ->take(5)
        ->get();

        return view('dashboard.mahasiswa.index', compact(
            'mahasiswa',
            'jadwalHariIni',
            'pertemuanHariIni',
            'totalHadir',
            'totalIzin',
            'totalSakit',
            'totalAlpha',
            'riwayatTerbaru'
        ));
    }
}