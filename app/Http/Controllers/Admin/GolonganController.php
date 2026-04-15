<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Golongan;
use App\Models\Semester;
use Illuminate\Http\Request;

class GolonganController extends Controller
{
    public function index()
    {
        $golongans = Golongan::with('semester')->latest()->paginate(10);
        $semesters = Semester::all(); // Buat pilihan di modal
        return view('dashboard.admin.golongan', compact('golongans', 'semesters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'semester_id' => 'required|exists:semester,id',
        ]);

        Golongan::create($request->all());
        return back()->with('success', 'Golongan baru berhasil ditambahkan!');
    }

    public function update(Request $request, Golongan $golongan)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'semester_id' => 'required|exists:semester,id',
        ]);

        $golongan->update($request->all());
        return back()->with('success', 'Data golongan berhasil diupdate!');
    }

    public function destroy(Golongan $golongan)
    {
        // Cek relasi ke mahasiswa atau kelas sebelum hapus
        if ($golongan->mahasiswa()->exists()) {
            return back()->with('error', 'Gagal hapus! Masih ada mahasiswa di golongan ini.');
        }

        $golongan->delete();
        return back()->with('success', 'Golongan berhasil dihapus!');
    }

}
