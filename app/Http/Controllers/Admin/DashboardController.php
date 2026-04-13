<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Presensi;
use App\Models\Pertemuan;
use App\Models\KelasPerkuliahan;
use App\Models\MataKuliah;
use App\Models\Golongan;
use App\Models\Lokasi;
use App\Models\Jadwal;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        // 🔹 Get Active Semester (latest or configurable)
        $semesterAktif = Semester::latest('created_at')->first();

        // 🔹 STATS CARDS
        $totalMahasiswa = Mahasiswa::count();
        $totalDosen = Dosen::count();
        
        // Presensi Hari Ini (berdasarkan waktu_presensi)
        $today = Carbon::today();
        $presensiHariIni = Presensi::whereDate('waktu_presensi', $today)->count();
        
        // Hitung tingkat kehadiran hari ini
        $totalPresensiToday = Presensi::whereDate('waktu_presensi', $today)->count();
        $hadirToday = Presensi::whereDate('waktu_presensi', $today)
            ->where('status', 'hadir')
            ->count();
        $tingkatKehadiran = $totalPresensiToday > 0 
            ? round(($hadirToday / $totalPresensiToday) * 100, 1) 
            : 0;

        // 🔹 SECONDARY STATS
        $totalMataKuliah = MataKuliah::count();
        $kelasAktif = $semesterAktif 
            ? KelasPerkuliahan::whereHas('mataKuliah', fn($q) => 
                $q->where('semester_id', $semesterAktif->id)
              )->count()
            : KelasPerkuliahan::count();
        $totalGolongan = Golongan::count();
        $totalLokasi = Lokasi::count();

        // 🔹 RECENT PRESENSI ACTIVITIES (Last 5)
        $recentPresensi = Presensi::with([
                'pertemuan.kelasPerkuliahan.mataKuliah',
                'pertemuan.kelasPerkuliahan.dosen',
                'mahasiswa'
            ])
            ->whereDate('waktu_presensi', $today)
            ->latest('waktu_presensi')
            ->take(5)
            ->get()
            ->map(function ($presensi) {
                $pertemuan = $presensi->pertemuan;
                $kelas = $pertemuan?->kelasPerkuliahan;
                $mahasiswa = $presensi->mahasiswa;
                
                return [
                    'id' => $presensi->id,
                    'mahasiswa_nama' => $mahasiswa?->nama ?? 'Unknown',
                    'mahasiswa_nim' => $mahasiswa?->nim ?? '-',
                    'kelas_nama' => $kelas?->nama_kelas ?? '-',
                    'matkul_nama' => $kelas?->mataKuliah?->nama ?? '-',
                    'status' => $presensi->status,
                    'waktu_presensi' => $presensi->waktu_presensi,
                    'time_diff' => $presensi->waktu_presensi?->diffForHumans() ?? '-',
                ];
            });

        // 🔹 ATTENDANCE TREND (7 Days)
        $attendanceTrend = $this->getAttendanceTrend(7);

        // 🔹 JADWAL HARI INI
        $jadwalHariIni = $this->getJadwalHariIni();

        // 🔹 QUICK ACTIONS (with permission check)
        $quickActions = [
            [
                'title' => 'Kelola Mahasiswa',
                'desc' => 'Tambah, edit, hapus data mahasiswa',
                'icon' => 'fa-users',
                'url' => '',
                'can' => true, // Adjust with your policy
            ],
            [
                'title' => 'Kelola Dosen',
                'desc' => 'Kelola data dosen pengampu',
                'icon' => 'fa-chalkboard-teacher',
                'url' => '',
                'can' => true,
            ],
            [
                'title' => 'Kelola Jadwal',
                'desc' => 'Atur jadwal perkuliahan',
                'icon' => 'fa-calendar-alt',
                'url' => '',
                'can' => true,
            ],
            [
                'title' => 'Laporan Presensi',
                'desc' => 'Export & cetak laporan',
                'icon' => 'fa-file-export',
                'url' => '',
                'can' => true,
            ],
        ];

        // 🔹 Format tanggal untuk view (Indonesian locale)
        Carbon::setLocale('id');
        $formattedDate = $today->translatedFormat('l, j F Y');
        $hariIniEnum = Str::lower($today->translatedFormat('l')); // senin, selasa, etc

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

    /**
     * Get attendance trend for last N days
     * Calculates percentage of 'hadir' status per day
     */
    private function getAttendanceTrend(int $days = 7): array
    {
        $trend = [];
        $today = Carbon::today();

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            
            // Query presensi per date
            $stats = Presensi::selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as hadir
                ')
                ->whereDate('waktu_presensi', $date)
                ->first();
            
            $total = $stats?->total ?? 0;
            $hadir = $stats?->hadir ?? 0;
            $percentage = $total > 0 ? round(($hadir / $total) * 100) : 0;
            
            $trend[] = [
                'date' => $date,
                'day_short' => $date->translatedFormat('D'), // Sen, Sel, etc
                'day_full' => $date->translatedFormat('l'),
                'percentage' => $percentage,
                'total' => $total,
                'hadir' => $hadir,
            ];
        }

        return $trend;
    }

    /**
     * Get today's schedule based on day enum and time
     */
    private function getJadwalHariIni(): array
    {
        $today = Carbon::now();
        $hariEnum = Str::lower($today->translatedFormat('l')); // senin, selasa, etc
        $currentTime = $today->format('H:i:s');
        
        $jadwal = Jadwal::with([
                'kelasPerkuliahan.mataKuliah',
                'kelasPerkuliahan.dosen',
                'kelasPerkuliahan.ruang',
                'lokasi'
            ])
            ->where('hari', $hariEnum)
            ->whereHas('kelasPerkuliahan', function($q) {
                // Optional: filter by active semester
                // $q->whereHas('mataKuliah', fn($qm) => 
                //     $qm->where('semester_id', Semester::latest()->value('id'))
                // );
            })
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($j) use ($currentTime) {
                $kp = $j->kelasPerkuliahan;
                $jamMulai = $j->jam_mulai ? substr($j->jam_mulai, 0, 5) : '-';
                $jamSelesai = $j->jam_selesai ? substr($j->jam_selesai, 0, 5) : '-';
                
                // Determine status: berlangsung, berikutnya, selesai
                $status = 'mendatang';
                if ($jamMulai !== '-' && $jamSelesai !== '-') {
                    if ($currentTime >= $jamMulai && $currentTime <= $jamSelesai) {
                        $status = 'berlangsung';
                    } elseif ($currentTime > $jamSelesai) {
                        $status = 'selesai';
                    } else {
                        $status = 'berikutnya';
                    }
                }
                
                return [
                    'id' => $j->id,
                    'matkul_nama' => $kp?->mataKuliah?->nama ?? '-',
                    'matkul_kode' => $kp?->mataKuliah?->kode_mk ?? '-',
                    'dosen_nama' => $kp?->dosen?->nama ?? '-',
                    'kelas_nama' => $kp?->nama_kelas ?? '-',
                    'tipe_kelas' => $kp?->tipe_kelas ?? 'reguler',
                    'ruangan' => $kp?->ruang?->nama ?? '-',
                    'gedung' => $kp?->ruang?->gedung ?? '',
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                    'lokasi_nama' => $j->lokasi?->nama ?? '-',
                    'lokasi_detail' => $j->lokasi ? 
                        "{$j->lokasi->nama}, {$kp?->ruang?->gedung}" : '-',
                    'status' => $status,
                    'latitude' => $j->lokasi?->latitude,
                    'longitude' => $j->lokasi?->longitude,
                ];
            });

        return $jadwal->toArray();
    }
}