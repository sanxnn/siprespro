<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Semester;
use Illuminate\Http\Request;
class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
        $semesterAktif = Semester::where('status', 'aktif')->first();
        $query = MataKuliah::with('semester');
        if ($request->get('view') === 'all') {
        } elseif ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        } else {
            if ($semesterAktif) {
                $query->where('semester_id', $semesterAktif->id);
            }
        }
        $matkuls = $query->latest()->paginate(10)->withQueryString();
        $semesters = Semester::orderBy('nama', 'desc')->get();
        return view('dashboard.admin.mata-kuliah', compact('matkuls', 'semesters', 'semesterAktif'));
    }
    public function store(Request $request)
    {
        $messages = [
            'kode_mk.required' => 'Kode MK wajib diisi!',
            'kode_mk.unique' => 'Kode MK ini sudah ada yang punya.',
            'nama.required' => 'Nama mata kuliah jangan dikosongin.',
            'nama.max' => 'Nama MK maksimal 255 karakter.',
            'sks.required' => 'SKS wajib diisi.',
            'sks.integer' => 'SKS harus berupa angka.',
            'sks.min' => 'SKS minimal 1.',
            'sks.max' => 'SKS maksimal 6.',
            'semester_id.required' => 'Pilih semester dulu!',
            'semester_id.exists' => 'Semester tidak ditemukan.',
        ];
        $request->validate([
            'kode_mk' => 'required|string|unique:mata_kuliah,kode_mk',
            'nama' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester_id' => 'required|exists:semester,id',
        ], $messages);
        try {
            MataKuliah::create($request->all());
            return back()->with('success', 'Mata Kuliah berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal simpan data: ' . $e->getMessage());
        }
    }
    public function update(Request $request, MataKuliah $matkul)
    {
        $messages = [
            'kode_mk.required' => 'Kode MK wajib diisi',
            'kode_mk.unique' => 'Kode MK ini sudah dipakai mata kuliah lain.',
            'nama.required' => 'Nama mata kuliah jangan dikosongin.',
            'nama.max' => 'Nama MK maksimal 255 karakter.',
            'sks.required' => 'SKS wajib diisi.',
            'sks.integer' => 'SKS harus berupa angka.',
            'sks.min' => 'SKS minimal 1.',
            'sks.max' => 'SKS maksimal 6.',
            'semester_id.required' => 'Pilih semester dulu!',
            'semester_id.exists' => 'Semester tidak ditemukan di database.',
        ];
        $request->validate([
            'kode_mk' => 'required|string|unique:mata_kuliah,kode_mk,' . $matkul->id,
            'nama' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester_id' => 'required|exists:semester,id',
        ], $messages);
        try {
            $matkul->update($request->all());
            return back()->with('success', 'Data Mata Kuliah berhasil diupdate!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal update data! ' . $e->getMessage());
        }
    }
    public function destroy(MataKuliah $matkul)
    {
        if ($matkul->kelasPerkuliahan()->exists()) {
            return back()->with('error', 'Gagal hapus! Mata kuliah ini masih digunakan di jadwal kelas.');
        }
        try {
            $matkul->delete();
            return back()->with('success', 'Mata Kuliah berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Gagal hapus! Masih ada data lain yang terikat dengan matkul ini di database.');
        }
    }
}
