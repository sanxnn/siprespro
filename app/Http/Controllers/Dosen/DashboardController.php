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
        $totalKelas = \App\Models\KelasPerkuliahan::where('dosen_id', $dosenId)->count();
        $pertemuanSelesai = \App\Models\Pertemuan::whereHas('kelasPerkuliahan', function ($q) use ($dosenId) {
            $q->where('dosen_id', $dosenId);
        })
            ->where('status', 'ditutup')
            ->where(function ($q) use ($hariIni, $jamSekarang) {
                $q->whereDate('tanggal', '<', $hariIni) 
                    ->orWhere(function ($sub) use ($hariIni, $jamSekarang) {
                        $sub->whereDate('tanggal', $hariIni) 
                            ->where('jam_selesai', '<=', $jamSekarang); 
                    });
            })
            ->count();
        $presensiHariIni = \App\Models\Presensi::whereDate('waktu_presensi', $hariIni)
            ->whereHas('pertemuan.kelasPerkuliahan', function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            })->count();
        $totalMahasiswaDiampu = \App\Models\Mahasiswa::whereHas('golongan.kelasPerkuliahan', function ($q) use ($dosenId) {
            $q->where('dosen_id', $dosenId);
        })->distinct()->count();
        $jadwalHariIni = \App\Models\Pertemuan::with(['kelasPerkuliahan.mataKuliah', 'kelasPerkuliahan.ruang', 'lokasi'])
            ->whereHas('kelasPerkuliahan', function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            })
            ->whereDate('tanggal', $hariIni)
            ->orderBy('jam_mulai')
            ->get();
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
        $kelasIds = \App\Models\KelasPerkuliahan::where('dosen_id', $dosenId)->pluck('id')->toArray();
        $totalPresensiAman = 0;
        $kapasitasMaksimalTotal = 0;
        $allKelasWithRelations = \App\Models\KelasPerkuliahan::with(['golongans.mahasiswas', 'pertemuans'])
            ->where('dosen_id', $dosenId)
            ->get();
        foreach ($allKelasWithRelations as $kelas) {
            $mhsCount = $kelas->golongans->flatMap(function ($g) {
                return $g->mahasiswas;
            })->unique('id')->count();
            $sesiSelesaiCount = $kelas->pertemuans->where('status', 'ditutup')
                ->filter(function ($p) use ($hariIni, $jamSekarang) {
                    return $p->tanggal < $hariIni || ($p->tanggal == $hariIni && $p->jam_selesai <= $jamSekarang);
                })
                ->count();
            $kapasitasMaksimalTotal += ($mhsCount * $sesiSelesaiCount);
        }
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
            $avgKehadiran = 100; 
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