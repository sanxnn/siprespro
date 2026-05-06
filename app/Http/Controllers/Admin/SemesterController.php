<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::latest()->paginate(10);
        return view('dashboard.admin.semester', compact('semesters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:20', // Contoh: 2023/2024
        ]);

        Semester::create($request->all());
        return back()->with('success', 'Data Semester berhasil ditambah!');
    }

    public function update(Request $request, Semester $semester)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:20',
        ]);

        $semester->update($request->all());
        return back()->with('success', 'Data Semester berhasil diupdate!');
    }

    public function setAktif($id)
    {
        DB::transaction(function () use ($id) {
            // Step 1: Setel SEMUA jadi nonaktif dulu
            Semester::query()->update(['status' => 'nonaktif']);

            // Step 2: Setel yang dipilih jadi aktif
            $semester = Semester::findOrFail($id);
            $semester->update(['status' => 'aktif']);
        });

        return back()->with('success', 'Semester ' . Semester::find($id)->nama . ' sekarang menjadi semester aktif.');
    }

    public function destroy(Semester $semester)
    {
        // Cek dulu apakah ada golongan atau matkul yang pakai semester ini
        if ($semester->golongan()->exists() || $semester->mataKuliah()->exists()) {
            return back()->with('error', 'Gagal hapus! Semester ini masih digunakan di data lain.');
        }

        $semester->delete();
        return back()->with('success', 'Data Semester berhasil dihapus!');
    }
}
