<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Presensi;
use App\Models\Pertemuan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PresensiController extends Controller
{

    public function index(Request $request)
    {
        $dosens = Dosen::orderBy('nama', 'asc')->get();

        $query = Pertemuan::with(['kelasPerkuliahan.mataKuliah', 'kelasPerkuliahan.dosen', 'lokasi'])
            ->withCount([
                'presensis as total_hadir' => function ($q) {
                    $q->where('status', 'hadir'); },
                'presensis as total_sakit' => function ($q) {
                    $q->where('status', 'sakit'); },
                'presensis as total_izin' => function ($q) {
                    $q->where('status', 'izin'); },
                'presensis as total_alfa' => function ($q) {
                    $q->where('status', 'alfa'); },
            ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('materi', 'like', "%{$search}%")
                    ->orWhereHas('kelasPerkuliahan.mataKuliah', function ($sub) use ($search) {
                        $sub->where('nama', 'like', "%{$search}%")
                            ->orWhere('kode_mk', 'like', "%{$search}%");
                    })
                    ->orWhereHas('kelasPerkuliahan.dosen', function ($sub) use ($search) {
                        $sub->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('dosen_id')) {
            $query->whereHas('kelasPerkuliahan', function ($q) {
                $q->where('dosen_id', request('dosen_id'));
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $logs = $query->orderBy('status', 'asc')
            ->orderBy('tanggal', 'desc')
            ->paginate(10)
            ->withQueryString();

        foreach ($logs as $log) {
            $kelasId = $log->kelas_perkuliahan_id;
            $totalMhs = Mahasiswa::whereHas('golongan.kelasPerkuliahan', function ($q) use ($kelasId) {
                $q->where('kelas_perkuliahan.id', $kelasId);
            })->count();

            $recorded = $log->total_hadir + $log->total_sakit + $log->total_izin + $log->total_alfa;
            
            if ($log->status === 'ditutup' || $log->statusAbsensi === 'Selesai' || $log->statusAbsensi === 'Ditutup (Manual)') {
                $unrecorded = max(0, $totalMhs - $recorded);
                $log->total_alfa += $unrecorded;
            }
        }

        return view('dashboard.admin.presensi.index', compact('logs', 'dosens'));
    }

    public function show(Pertemuan $presensi)
    {
        $pertemuan = $presensi;
        $pertemuan->load(['kelasPerkuliahan.mataKuliah', 'kelasPerkuliahan.dosen', 'lokasi']);
        
        $kelas = $pertemuan->kelasPerkuliahan;
        $mahasiswas = Mahasiswa::whereHas('golongan.kelasPerkuliahan', function ($q) use ($kelas) {
            $q->where('kelas_perkuliahan.id', $kelas->id);
        })->get();

        $presensis = Presensi::where('pertemuan_id', $pertemuan->id)->get()->keyBy('mahasiswa_id');

        $data = $mahasiswas->map(function($mhs) use ($presensis, $pertemuan) {
            $p = $presensis->get($mhs->id);
            if ($p) {
                return $p;
            } else {
                $p = new Presensi();
                $p->id = 'alpha-' . $mhs->id;
                $p->mahasiswa_id = $mhs->id;
                $p->pertemuan_id = $pertemuan->id;
                
                // If closed, they are ALFA, else they are 'Belum Absen'
                $isClosed = $pertemuan->status === 'ditutup' || $pertemuan->statusAbsensi === 'Selesai' || $pertemuan->statusAbsensi === 'Ditutup (Manual)';
                $p->status = $isClosed ? 'alfa' : 'Belum Absen';
                
                $p->waktu_presensi = null;
                $p->latitude = null;
                $p->longitude = null;
                $p->mahasiswa = $mhs;
                return $p;
            }
        });
        
        return view('dashboard.admin.presensi.show', compact('pertemuan', 'data'));
    }

    
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'mahasiswa_id' => 'required_if:is_alpha,true',
            'pertemuan_id' => 'required_if:is_alpha,true',
        ]);
        try {
            if (str_contains($id, 'alpha-')) {
                if ($request->status === 'alpha') {
                    return back()->with('info', 'Status tetap ALPHA, tidak ada perubahan.');
                }
                Presensi::create([
                    'mahasiswa_id' => $request->mahasiswa_id,
                    'pertemuan_id' => $request->pertemuan_id,
                    'status' => $request->status,
                    'waktu_presensi' => now(),
                ]);
            } else {
                $presensi = Presensi::findOrFail($id);
                $presensi->update(['status' => $request->status]);
            }
            return back()->with('success', 'Status presensi berhasil dikoreksi Admin.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            if (str_contains($id, 'alpha-')) {
                return back()->with('error', 'Data Alpha tidak bisa dihapus karena tidak ada di database.');
            }
            Presensi::findOrFail($id)->delete();
            return back()->with('success', 'Record presensi berhasil dihapus (Mahasiswa otomatis menjadi ALPHA kembali).');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data.');
        }
    }

    public function demo(Request $request)
    {
        $pertemuanId = $request->pertemuan_id;
        $status = $request->status;
        if ($status === 'alpha' && $request->filled('pertemuan_id')) {
            $pertemuan = Pertemuan::findOrFail($pertemuanId);
            $query = Mahasiswa::whereHas('golongan.kelasPerkuliahan', function ($q) use ($pertemuan) {
                $q->where('id', $pertemuan->kelas_perkuliahan_id);
            })
                ->whereNotExists(function ($q) use ($pertemuanId) {
                    $q->select(DB::raw(1))
                        ->from('presensi')
                        ->whereRaw('mahasiswa.id = presensi.mahasiswa_id')
                        ->where('presensi.pertemuan_id', $pertemuanId);
                })
                ->select('mahasiswa.*');
            $mahasiswasAlpha = $query->paginate(20)->withQueryString();
            $presensis = $mahasiswasAlpha->through(function ($mhs) use ($pertemuanId) {
                $p = new Presensi();
                $p->id = 'alpha-' . $mhs->id;
                $p->mahasiswa_id = $mhs->id;
                $p->pertemuan_id = $pertemuanId;
                $p->status = 'alpha';
                $p->waktu_presensi = null;
                $p->latitude = null;
                $p->longitude = null;
                $p->mahasiswa = $mhs;
                return $p;
            });
        } else {
            $query = Presensi::with([
                'mahasiswa.golongan',
                'pertemuan.kelasPerkuliahan.mataKuliah'
            ]);
            if ($request->filled('pertemuan_id')) {
                $query->where('pertemuan_id', $pertemuanId);
            }
            if ($request->filled('status')) {
                $query->where('status', $status);
            }
            if ($request->filled('mata_kuliah_id')) {
                $query->whereHas('pertemuan.kelasPerkuliahan', function ($q) use ($request) {
                    $q->where('mata_kuliah_id', $request->mata_kuliah_id);
                });
            }
            $presensis = $query->latest('waktu_presensi')->paginate(20)->withQueryString();
        }
        $pertemuans = Pertemuan::with('kelasPerkuliahan.mataKuliah')->latest()->get();
        $mataKuliahs = MataKuliah::orderBy('nama', 'asc')->get();
        return view('dashboard.admin.presensi', compact('presensis', 'pertemuans', 'mataKuliahs'));
    }
    
}