<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Semester;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index()
    {
        $matkuls = MataKuliah::with('semester')->latest()->paginate(10);
        $semesters = Semester::all();
        return view('dashboard.admin.mata-kuliah', compact('matkuls', 'semesters'));
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

        $matkul->update($request->all());
        return back()->with('success', 'Data Mata Kuliah berhasil diupdate!');
    }

    public function destroy(MataKuliah $matkul)
    {
        // Cek jika matkul sudah masuk ke kelas perkuliahan
        if ($matkul->kelasPerkuliahan()->exists()) {
            return back()->with('error', 'Gagal hapus! Mata kuliah ini sudah digunakan dalam jadwal kelas.');
        }

        $matkul->delete();
        return back()->with('success', 'Mata Kuliah berhasil dihapus!');
    }
}
