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
        $totalKapasitasMhsHariIni = Mahasiswa::whereHas('golongan.kelasPerkuliahan.pertemuans', function ($q) use ($today) {
            $q->whereDate('tanggal', $today);
        })->count();
        $hadirHariIni = Presensi::whereDate('waktu_presensi', $today)
            ->whereIn(DB::raw('LOWER(status)'), ['hadir', 'sakit', 'izin'])
            ->count();
        $tingkatKehadiran = $totalKapasitasMhsHariIni > 0
            ? round(($hadirHariIni / $totalKapasitasMhsHariIni) * 100, 1)
            : 100; 
        $presensiHariIni = Presensi::whereDate('waktu_presensi', $today)->count();
        $stats = [
            'totalMahasiswa' => Mahasiswa::count(),
            'totalDosen' => Dosen::count(),
            'totalMataKuliah' => MataKuliah::count(),
            'totalGolongan' => Golongan::count(),
            'totalLokasi' => Lokasi::count(),
        ];
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
                'recentPresensi' => $recentPresensi,
                'totalKapasitas' => $totalKapasitasMhsHariIni
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
        $trend = [];
        $hariIni = now()->format('Y-m-d');
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $d = now()->subDays($i);
            $pertemuanHariItu = Pertemuan::whereDate('tanggal', $date)->get();
            $kapasitasMhsHariItu = 0;
            foreach ($pertemuanHariItu as $p) {
                $kapasitasMhsHariItu += Mahasiswa::whereHas('golongan.kelasPerkuliahan', function ($q) use ($p) {
                    $q->where('kelas_perkuliahan.id', $p->kelas_perkuliahan_id);
                })->count();
            }
            $hadirHariItu = Presensi::whereDate('waktu_presensi', $date)
                ->whereIn(DB::raw('LOWER(status)'), ['hadir', 'sakit', 'izin'])
                ->count();
            $percentage = $kapasitasMhsHariItu > 0
                ? round(($hadirHariItu / $kapasitasMhsHariItu) * 100)
                : 0;
            $trend[] = [
                'label' => $d->translatedFormat('d M'),
                'day_full' => $d->translatedFormat('l'),
                'day_short' => $d->translatedFormat('D'),
                'hadir' => $hadirHariItu,
                'total' => $kapasitasMhsHariItu,
                'percentage' => $percentage
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