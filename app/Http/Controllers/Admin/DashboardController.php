<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Mahasiswa, Dosen, Presensi, MataKuliah, Golongan, Lokasi, Jadwal, Semester, KelasPerkuliahan, Pertemuan};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();
        $semesterAktif = Semester::where('status', 'aktif')->first() ?? Semester::latest()->first();

        $statsHariIni = Presensi::whereDate('waktu_presensi', $today)
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as hadir')
            ->first();

        $stats = [
            'totalMahasiswa' => Mahasiswa::count(), // Key diganti agar sesuai Blade
            'totalDosen' => Dosen::count(),     // Key diganti agar sesuai Blade
            'totalMataKuliah' => MataKuliah::count(),// Key diganti agar sesuai Blade
            'totalGolongan' => Golongan::count(),  // Key diganti agar sesuai Blade
            'totalLokasi' => Lokasi::count(),    // Key diganti agar sesuai Blade
        ];

        $presensiHariIni = $statsHariIni->total ?? 0;
        $hadirHariIni = $statsHariIni->hadir ?? 0;
        $tingkatKehadiran = $presensiHariIni > 0
            ? round(($hadirHariIni / $presensiHariIni) * 100, 1)
            : 0;

        $kelasAktif = $semesterAktif
            ? KelasPerkuliahan::whereHas('mataKuliah', fn($q) => $q->where('semester_id', $semesterAktif->id))->count()
            : KelasPerkuliahan::count();

        $recentPresensi = Presensi::with(['pertemuan.kelasPerkuliahan.mataKuliah', 'mahasiswa'])
            ->whereDate('waktu_presensi', $today)
            ->latest('waktu_presensi')
            ->take(5)
            ->get()
            ->map(function ($p) {
                return [
                    'mahasiswa_nama' => optional($p->mahasiswa)->nama ?? 'Mahasiswa Dihapus',
                    'mahasiswa_nim' => optional($p->mahasiswa)->nim ?? '-',
                    'kelas_nama' => optional($p->pertemuan->kelasPerkuliahan)->nama_kelas ?? '-',
                    'matkul_nama' => optional($p->pertemuan->kelasPerkuliahan->mataKuliah)->nama ?? '-',
                    'status' => $p->status,
                    'time_diff' => $p->waktu_presensi->diffForHumans()
                ];
            });

        $data = [
            'formattedDate' => now()->translatedFormat('l, j F Y'),
            'hariIniEnum' => strtolower(now()->translatedFormat('l')),
            'attendanceTrend' => $this->getAttendanceTrend(7),
            'jadwalHariIni' => $this->getJadwalHariIni(),
            'quickActions' => $this->getQuickActions(),
        ];

        return view('dashboard.admin.index', array_merge(
            $stats,
            $data,
            [
                'semesterAktif' => $semesterAktif,
                'presensiHariIni' => $presensiHariIni,
                'hadirHariIni' => $hadirHariIni,
                'tingkatKehadiran' => $tingkatKehadiran,
                'kelasAktif' => $kelasAktif,
                'recentPresensi' => $recentPresensi
            ]
        ));
    }

    private function getQuickActions(): array
    {
        return [
            [
                'title' => 'Kelola Mahasiswa',
                'desc' => 'Tambah, edit, hapus data mahasiswa',
                'icon' => 'fa-users',
                'url' => route('admin.mahasiswa.index'),
                'can' => true
            ],
            [
                'title' => 'Kelola Dosen',
                'desc' => 'Kelola data dosen pengampu',
                'icon' => 'fa-chalkboard-teacher',
                'url' => route('admin.dosen.index'),
                'can' => true
            ],
            [
                'title' => 'Kelola Jadwal',
                'desc' => 'Atur kelas perkuliahan',
                'icon' => 'fa-calendar-alt',
                'url' => route('admin.kelas-perkuliahan.index'),
                'can' => true
            ],
            [
                'title' => 'Laporan Presensi',
                'desc' => 'Export & cetak laporan',
                'icon' => 'fa-file-export',
                'url' => route('admin.presensi.index'),
                'can' => true
            ],
        ];
    }

    private function getAttendanceTrend(int $days): array
    {
        $startDate = now()->subDays($days - 1)->startOfDay();

        $rawStats = Presensi::where('waktu_presensi', '>=', $startDate)
            ->selectRaw('DATE(waktu_presensi) as date')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as hadir')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $trend = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = now()->subDays($i);
            $dateStr = $d->format('Y-m-d');
            $stat = $rawStats->get($dateStr);

            $total = $stat->total ?? 0;
            $hadir = (int) ($stat->hadir ?? 0);

            $trend[] = [
                'label' => $d->translatedFormat('d M'),
                'day_full' => $d->translatedFormat('l'),
                'day_short' => $d->translatedFormat('D'),
                'total' => $total,
                'hadir' => $hadir,
                'percentage' => ($total > 0) ? round(($hadir / $total) * 100) : 0
            ];
        }

        return $trend;
    }

    private function getJadwalHariIni(): array
    {
        $now = now();
        $today = $now->toDateString();
        $time = $now->toTimeString();

        return Pertemuan::with(['kelasPerkuliahan.mataKuliah', 'kelasPerkuliahan.ruang', 'lokasi'])
            ->whereDate('tanggal', $today)
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($p) use ($time) {
                $status = 'mendatang';

                if ($time >= $p->jam_mulai && $time <= $p->jam_selesai) {
                    $status = 'berlangsung';
                } elseif ($time > $p->jam_selesai) {
                    $status = 'selesai';
                }

                return [
                    'matkul_nama' => $p->kelasPerkuliahan?->mataKuliah?->nama ?? 'Mata Kuliah Tidak Ditemukan',
                    'kelas_nama' => $p->kelasPerkuliahan?->nama_kelas ?? '-',
                    'ruangan' => $p->kelasPerkuliahan?->ruang?->nama ?? '-',
                    'jam_mulai' => substr($p->jam_mulai, 0, 5),
                    'jam_selesai' => substr($p->jam_selesai, 0, 5),
                    'lokasi_detail' => ($p->lokasi?->nama ?? '-') . ' (' . ($p->kelasPerkuliahan?->ruang?->gedung ?? 'Gedung -') . ')',
                    'status' => $status,
                    'pertemuan_ke' => $p->pertemuan_ke
                ];
            })->toArray();
    }
}