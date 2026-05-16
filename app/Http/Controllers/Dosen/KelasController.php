<?php
namespace App\Http\Controllers\Dosen;
use App\Exports\PresensiSesiExport;
use App\Http\Controllers\Controller;
use App\Models\KelasPerkuliahan;
use App\Models\Lokasi;
use App\Models\Pertemuan;
use App\Models\Presensi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
class KelasController extends Controller
{
    public function index()
    {
        $dosenId = auth()->user()->dosen_id;
        if (!$dosenId) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda belum terhubung dengan data Dosen.');
        }
        $semesterAktif = \App\Models\Semester::where('status', 'aktif')->first();
        $kelases = KelasPerkuliahan::with(['mataKuliah', 'ruang', 'golongans'])
            ->where('dosen_id', $dosenId)
            ->latest()
            ->get();
        return view('dashboard.dosen.kelas.index', compact('kelases', 'semesterAktif'));
    }
    public function show($id)
    {
        $dosenId = auth()->user()->dosen_id;
        $kelas = KelasPerkuliahan::with([
            'mataKuliah',
            'ruang',
            'golongans',
            'pertemuans' => function ($q) {
                $q->orderBy('pertemuan_ke', 'asc');
            },
            'pertemuans.lokasi'
        ])
            ->where('dosen_id', $dosenId)
            ->findOrFail($id);
        $lokasis = Lokasi::orderBy('nama', 'asc')->get();
        $pertemuanAktif = $kelas->pertemuans()
            ->where('status', 'dibuka')
            ->whereDate('tanggal', now())
            ->first();
        return view('dashboard.dosen.kelas.show', compact('kelas', 'lokasis', 'pertemuanAktif'));
    }
    public function storePertemuan(Request $request, $id)
    {
        $messages = [
            'pertemuan_ke.required' => 'Urutan pertemuan wajib diisi.',
            'pertemuan_ke.integer' => 'Urutan pertemuan harus berupa angka.',
            'tanggal.required' => 'Tanggal pertemuan tidak boleh kosong.',
            'jam_mulai.required' => 'Jam mulai wajib ditentukan.',
            'jam_selesai.required' => 'Jam selesai wajib ditentukan.',
            'lokasi_id.required' => 'Lokasi absensi belum dipilih.',
            'lokasi_id.exists' => 'Lokasi yang dipilih tidak valid.',
            'materi.required' => 'Materi atau topik pertemuan wajib diisi.',
        ];
        $request->validate([
            'pertemuan_ke' => 'required|integer',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'lokasi_id' => 'required|exists:lokasi,id',
            'materi' => 'required'
        ], $messages);
        DB::beginTransaction();
        try {
            $exists = Pertemuan::where('kelas_perkuliahan_id', $id)
                ->where('pertemuan_ke', $request->pertemuan_ke)
                ->exists();
            if ($exists) {
                throw new Exception('Pertemuan ke-' . $request->pertemuan_ke . ' sudah terdaftar untuk kelas ini.');
            }
            Pertemuan::create([
                'kelas_perkuliahan_id' => $id,
                'pertemuan_ke' => $request->pertemuan_ke,
                'tanggal' => $request->tanggal,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'lokasi_id' => $request->lokasi_id,
                'materi' => $request->materi,
                'status' => 'ditutup'
            ]);
            DB::commit();
            return back()->with('success', 'Sesi pertemuan ke-' . $request->pertemuan_ke . ' berhasil dibuat!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
    public function updatePertemuan(Request $request, $id)
    {
        $messages = [
            'pertemuan_ke.required' => 'Urutan pertemuan wajib diisi.',
            'pertemuan_ke.integer' => 'Urutan pertemuan harus berupa angka.',
            'tanggal.required' => 'Tanggal pertemuan tidak boleh kosong.',
            'jam_mulai.required' => 'Jam mulai wajib ditentukan.',
            'jam_selesai.required' => 'Jam selesai wajib ditentukan.',
            'lokasi_id.required' => 'Lokasi absensi belum dipilih.',
            'lokasi_id.exists' => 'Lokasi yang dipilih tidak valid.',
            'materi.required' => 'Materi atau topik pertemuan wajib diisi.',
        ];
        $request->validate([
            'pertemuan_ke' => 'required|integer',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'lokasi_id' => 'required|exists:lokasi,id',
            'materi' => 'required|string'
        ], $messages);
        try {
            $pertemuan = Pertemuan::findOrFail($id);
            if ($pertemuan->pertemuan_ke != $request->pertemuan_ke) {
                $exists = Pertemuan::where('kelas_perkuliahan_id', $pertemuan->kelas_perkuliahan_id)
                    ->where('pertemuan_ke', $request->pertemuan_ke)
                    ->where('id', '!=', $id)
                    ->exists();
                if ($exists) {
                    return back()->with('error', "Gagal! Sesi ke-{$request->pertemuan_ke} sudah ada.");
                }
            }
            $pertemuan->update([
                'pertemuan_ke' => $request->pertemuan_ke,
                'tanggal' => $request->tanggal,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'lokasi_id' => $request->lokasi_id,
                'materi' => $request->materi,
            ]);
            return back()->with('success', "Data sesi pertemuan ke-{$pertemuan->pertemuan_ke} berhasil diperbarui!");
        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem saat memperbarui data.');
        }
    }
    public function destroyPertemuan($id)
    {
        try {
            $pertemuan = Pertemuan::withCount('presensis')->findOrFail($id);
            if ($pertemuan->presensis_count > 0) {
                return back()->with('error', "Sesi tidak bisa dihapus! Sudah terdapat {$pertemuan->presensis_count} data presensi mahasiswa.");
            }
            $nomorSesi = $pertemuan->pertemuan_ke;
            $pertemuan->delete();
            return back()->with('success', "Sesi pertemuan ke-{$nomorSesi} berhasil dihapus permanen.");
        } catch (Exception $e) {
            return back()->with('error', 'Gagal menghapus data. Sesi mungkin masih terhubung dengan data lain.');
        }
    }
    public function togglePertemuan($id)
    {
        try {
            $p = Pertemuan::findOrFail($id);
            $hariIni = now()->format('Y-m-d');
            $tglPertemuan = \Carbon\Carbon::parse($p->tanggal)->format('Y-m-d');
            if ($p->status !== 'dibuka') {
                if ($tglPertemuan !== $hariIni) {
                    return back()->with('error', "Gagal! Sesi presensi hanya bisa dibuka pada tanggal jadwal perkuliahan (" . \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') . ").");
                }
                $p->status = 'dibuka';
                $message = "Sesi presensi pertemuan ke-{$p->pertemuan_ke} BERHASIL DIBUKA.";
            } else {
                $p->status = 'ditutup';
                $message = "Sesi presensi pertemuan ke-{$p->pertemuan_ke} BERHASIL DITUTUP.";
            }
            $p->save();
            return back()->with('success', $message);
        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem saat mengubah status pertemuan.');
        }
    }
    public function showPertemuan($id)
    {
        $pertemuan = DB::table('pertemuan')
            ->join('kelas_perkuliahan', 'pertemuan.kelas_perkuliahan_id', '=', 'kelas_perkuliahan.id')
            ->join('mata_kuliah', 'kelas_perkuliahan.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->join('lokasi', 'pertemuan.lokasi_id', '=', 'lokasi.id')
            ->where('pertemuan.id', $id)
            ->select(
                'pertemuan.*',
                'kelas_perkuliahan.nama_kelas',
                'mata_kuliah.nama as nama_mk',
                'lokasi.nama as nama_lokasi'
            )
            ->first();
        if (!$pertemuan) {
            return redirect()->back()->with('error', 'Sesi pertemuan tidak ditemukan.');
        }
        $golonganIds = DB::table('kelas_golongan')
            ->where('kelas_perkuliahan_id', $pertemuan->kelas_perkuliahan_id)
            ->pluck('golongan_id')
            ->toArray();
        $mahasiswas = DB::table('mahasiswa')
            ->whereIn('golongan_id', $golonganIds)
            ->orderBy('nim', 'asc')
            ->get();
        $presensis = DB::table('presensi')
            ->where('pertemuan_id', $id)
            ->get()
            ->keyBy('mahasiswa_id');
        return view('dashboard.dosen.pertemuan', compact('pertemuan', 'mahasiswas', 'presensis'));
    }
    public function exportExcel($pertemuan_id)
    {
        $pertemuan = DB::table('pertemuan')
            ->join('kelas_perkuliahan', 'pertemuan.kelas_perkuliahan_id', '=', 'kelas_perkuliahan.id')
            ->join('mata_kuliah', 'kelas_perkuliahan.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->where('pertemuan.id', $pertemuan_id)
            ->select('pertemuan.pertemuan_ke', 'mata_kuliah.nama as nama_mk')
            ->first();
        if (!$pertemuan) {
            return redirect()->back()->with('error', 'Data pertemuan tidak ditemukan.');
        }
        $namaFile = 'Rekap_' . str_replace(' ', '_', $pertemuan->nama_mk) . '_Pertemuan_Ke-' . $pertemuan->pertemuan_ke . '.xlsx';
        return Excel::download(new PresensiSesiExport($pertemuan_id), $namaFile);
    }
}