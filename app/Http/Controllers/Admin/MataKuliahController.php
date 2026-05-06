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
        $request->validate([
            'kode_mk' => 'required|string|unique:mata_kuliah,kode_mk',
            'nama' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester_id' => 'required|exists:semester,id',
        ]);

        MataKuliah::create($request->all());
        return back()->with('success', 'Mata Kuliah berhasil ditambahkan!');
    }

    public function update(Request $request, MataKuliah $matkul)
    {
        $request->validate([
            'kode_mk' => 'required|string|unique:mata_kuliah,kode_mk,' . $matkul->id,
            'nama' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester_id' => 'required|exists:semester,id',
        ]);

        try {
            $matkul->update($request->all());
            return back()->with('success', 'Data Mata Kuliah berhasil diupdate!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update data! Pesan: ' . $e->getMessage());
        }
    }

    public function destroy(MataKuliah $matkul)
    {

        // dd($matkul);
        // 1. Cek manual relasi yang lu tau
        if ($matkul->kelasPerkuliahan()->exists()) {
            return back()->with('error', 'Gagal hapus! Mata kuliah ini masih digunakan di jadwal kelas.');
        }

        try {
            // 2. Coba hapus
            $matkul->delete();
            return back()->with('success', 'Mata Kuliah berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            // 3. Kalau ada Foreign Key lain yang nyangkut, pesan errornya bakal keluar di sini
            return back()->with('error', 'Gagal hapus! Masih ada data lain yang terikat dengan matkul ini di database.');
        }
    }
}
