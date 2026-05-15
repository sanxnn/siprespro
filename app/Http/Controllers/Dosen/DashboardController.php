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
        $jamSekarang = now()->format('H:i:s');

        // 1. Stats Cards
        $totalKelas = \App\Models\KelasPerkuliahan::where('dosen_id', $dosenId)->count();

        // HITUNG SESI SELESAI (Kombinasi Tanggal lampau ATAU Hari ini tapi Jam-nya udah lewat)
        $pertemuanSelesai = \App\Models\Pertemuan::whereHas('kelasPerkuliahan', function ($q) use ($dosenId) {
            $q->where('dosen_id', $dosenId);
        })
            ->where('status', 'ditutup')
            ->where(function ($q) use ($hariIni, $jamSekarang) {
                $q->whereDate('tanggal', '<', $hariIni) // Kelas hari-hari kemarin
                    ->orWhere(function ($sub) use ($hariIni, $jamSekarang) {
                        $sub->whereDate('tanggal', $hariIni) // Kelas hari ini
                            ->where('jam_selesai', '<=', $jamSekarang); // Tapi jamnya udah lewat!
                    });
            })
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

        // Tarik ID kelas untuk filter raw data query presensi di bawah
        $kelasIds = \App\Models\KelasPerkuliahan::where('dosen_id', $dosenId)->pluck('id')->toArray();

        $totalPresensiAman = 0;
        $kapasitasMaksimalTotal = 0;

        // FIX FIX FIX: Panggil Eloquent dengan Eager Loading biar object-nya dapet Cuk!
        $allKelasWithRelations = \App\Models\KelasPerkuliahan::with(['golongans.mahasiswas', 'pertemuans'])
            ->where('dosen_id', $dosenId)
            ->get();

        foreach ($allKelasWithRelations as $kelas) {
            // Hitung mahasiswa unik di dalam satu kelas perkuliahan
            $mhsCount = $kelas->golongans->flatMap(function ($g) {
                return $g->mahasiswas;
            })->unique('id')->count();

            // Filter sesi yang bener-bener udah lewat jam selesainya dan berstatus ditutup
            $sesiSelesaiCount = $kelas->pertemuans->where('status', 'ditutup')
                ->filter(function ($p) use ($hariIni, $jamSekarang) {
                    return $p->tanggal < $hariIni || ($p->tanggal == $hariIni && $p->jam_selesai <= $jamSekarang);
                })
                ->count();

            // Akumulasikan kapasitas maksimal total mhs harian
            $kapasitasMaksimalTotal += ($mhsCount * $sesiSelesaiCount);
        }

        // Hitung seluruh data presensi AMAN (Hadir, Sakit, Izin) di semua sesi yang sudah ditutup lewat jamnya
        if ($kapasitasMaksimalTotal > 0) {
            $totalPresensiAman = \App\Models\Presensi::whereHas('pertemuan', function ($q) use ($kelasIds, $hariIni, $jamSekarang) {
                $q->whereIn('kelas_perkuliahan_id', $kelasIds)
                    ->where('status', 'ditutup')
                    ->where(function ($query) use ($hariIni, $jamSekarang) {
                        $query->whereDate('tanggal', '<', $hariIni)
                            ->orWhere(function ($sub) use ($hariIni, $jamSekarang) {
                                $sub->whereDate('tanggal', $hariIni)
                                    ->where('jam_selesai', '<=', $jamSekarang);
                            });
                    });
            })
                ->whereIn(DB::raw('LOWER(status)'), ['hadir', 'sakit', 'izin'])
                ->count();

            $avgKehadiran = round(($totalPresensiAman / $kapasitasMaksimalTotal) * 100);
            if ($avgKehadiran > 100)
                $avgKehadiran = 100;
        } else {
            $avgKehadiran = 100; // Default 100% jika belum ada track record pertemuan selesai
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