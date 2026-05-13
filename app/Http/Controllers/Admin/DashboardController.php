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
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
        $today = Carbon::today();
        $semesterAktif = Semester::latest()->first();

        // 🔹 STATS UTAMA (Cek kehadiran hari ini dalam 1 query)
        $statsHariIni = Presensi::whereDate('waktu_presensi', $today)
            ->selectRaw('COUNT(*) as total, SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as hadir')
            ->first();

        $totalMahasiswa = Mahasiswa::count();
        $totalDosen = Dosen::count();
        $presensiHariIni = $statsHariIni->total ?? 0;
        $tingkatKehadiran = $presensiHariIni > 0 ? round(($statsHariIni->hadir / $presensiHariIni) * 100, 1) : 0;

        // 🔹 SECONDARY STATS
        $totalMataKuliah = MataKuliah::count();
        $kelasAktif = $semesterAktif ? KelasPerkuliahan::whereHas('mataKuliah', fn($q) => $q->where('semester_id', $semesterAktif->id))->count() : KelasPerkuliahan::count();
        $totalGolongan = Golongan::count();
        $totalLokasi = Lokasi::count();

        // 🔹 RECENT ACTIVITIES (Mapping sesuai struktur $item lu di view)
        $recentPresensi = Presensi::with(['pertemuan.kelasPerkuliahan.mataKuliah', 'mahasiswa'])
            ->whereDate('waktu_presensi', $today)
            ->latest('waktu_presensi')
            ->take(5)
            ->get()
            ->map(function ($p) {
                return [
                    'mahasiswa_nama' => $p->mahasiswa?->nama ?? 'N/A',
                    'mahasiswa_nim' => $p->mahasiswa?->nim ?? '-',
                    'kelas_nama' => $p->pertemuan?->kelasPerkuliahan?->nama_kelas ?? '-',
                    'matkul_nama' => $p->pertemuan?->kelasPerkuliahan?->mataKuliah?->nama ?? '-',
                    'status' => $p->status,
                    'time_diff' => $p->waktu_presensi->diffForHumans()
                ];
            });

        // 🔹 ATTENDANCE TREND (Single Query Optimization)
        $attendanceTrend = $this->getAttendanceTrend(7);

        // 🔹 JADWAL HARI INI
        $jadwalHariIni = $this->getJadwalHariIni();

        // 🔹 QUICK ACTIONS (Mapping URL)
        $quickActions = [
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

        $formattedDate = $today->translatedFormat('l, j F Y');
        $hariIniEnum = Str::lower($today->translatedFormat('l'));

        return view('dashboard.admin.index', compact(
            'semesterAktif',
            'totalMahasiswa',
            'totalDosen',
            'presensiHariIni',
            'tingkatKehadiran',
            'totalMataKuliah',
            'kelasAktif',
            'totalGolongan',
            'totalLokasi',
            'recentPresensi',
            'attendanceTrend',
            'jadwalHariIni',
            'quickActions',
            'formattedDate',
            'hariIniEnum'
        ));
    }

    private function getAttendanceTrend(int $days): array
    {
        $startDate = Carbon::today()->subDays($days - 1);
        $rawStats = Presensi::whereDate('waktu_presensi', '>=', $startDate)
            ->selectRaw('DATE(waktu_presensi) as date, COUNT(*) as total, SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as hadir')
            ->groupBy('date')->get()->keyBy('date');

        $trend = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = Carbon::today()->subDays($i);
            $dateStr = $d->format('Y-m-d');
            $stat = $rawStats->get($dateStr);
            $trend[] = [
                'date' => $d,
                'day_full' => $d->translatedFormat('l'),
                'day_short' => $d->translatedFormat('D'),
                'total' => $stat->total ?? 0,
                'hadir' => $stat->hadir ?? 0,
                'percentage' => ($stat && $stat->total > 0) ? round(($stat->hadir / $stat->total) * 100) : 0
            ];
        }
        return $trend;
    }

    private function getJadwalHariIni(): array
    {
        $now = Carbon::now();
        $today = $now->format('Y-m-d'); // 2026-05-13
        $time = $now->format('H:i:s');

        // Sekarang query ke Model Pertemuan
        return Pertemuan::with(['kelasPerkuliahan.mataKuliah', 'kelasPerkuliahan.ruang', 'lokasi'])
            ->where('tanggal', $today) // Filter berdasarkan tanggal hari ini
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($p) use ($time) {
                $status = 'mendatang';

                // Logic status berdasarkan jam operasional pertemuan
                if ($time >= $p->jam_mulai && $time <= $p->jam_selesai) {
                    $status = 'berlangsung';
                } elseif ($time > $p->jam_selesai) {
                    $status = 'selesai';
                }

                return [
                    'matkul_nama' => $p->kelasPerkuliahan->mataKuliah->nama ?? '-',
                    'kelas_nama' => $p->kelasPerkuliahan->nama_kelas ?? '-',
                    'ruangan' => $p->kelasPerkuliahan->ruang->nama ?? '-',
                    'jam_mulai' => substr($p->jam_mulai, 0, 5),
                    'jam_selesai' => substr($p->jam_selesai, 0, 5),
                    // Karena lokasi_id sudah ada di table pertemuan, langsung panggil $p->lokasi
                    'lokasi_detail' => ($p->lokasi->nama ?? '-') . ', ' . ($p->kelasPerkuliahan->ruang->gedung ?? ''),
                    'status' => $status,
                    'pertemuan_ke' => $p->pertemuan_ke // Tambahan info buat admin
                ];
            })->toArray();
    }
}