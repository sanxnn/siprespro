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
        $messages = [
            'nama.required' => 'Nama semester wajib diisi.',
            'nama.string' => 'Nama semester harus berupa teks.',
            'nama.max' => 'Nama semester maksimal 255 karakter.',
            'tahun_ajaran.required' => 'Tahun ajaran tidak boleh kosong.',
            'tahun_ajaran.unique' => 'Tahun ajaran sudah terdaftar.',
            'tahun_ajaran.max' => 'Tahun ajaran maksimal 20 karakter.',
        ];

        $request->validate([
            'nama' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:20|unique:semester,tahun_ajaran',
        ], $messages);

        try {
            Semester::create($request->all());

            return back()->with('success', 'Data Semester berhasil ditambah!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambah data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Semester $semester)
    {
        $messages = [
            'nama.required' => 'Nama semester wajib diisi.',
            'nama.string' => 'Nama semester harus berupa teks.',
            'nama.max' => 'Nama semester maksimal 255 karakter.',
            'tahun_ajaran.required' => 'Tahun ajaran tidak boleh kosong.',
            'tahun_ajaran.max' => 'Tahun ajaran maksimal 20 karakter.',
        ];

        $request->validate([
            'nama' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:20',
        ], $messages);

        try {
            $semester->update($request->all());
            return back()->with('success', 'Data Semester berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function setAktif($id)
    {
        try {
            DB::transaction(function () use ($id) {
                Semester::query()->update(['status' => 'nonaktif']);

                $semester = Semester::findOrFail($id);
                $semester->update(['status' => 'aktif']);
            });

            $namaSemester = Semester::find($id)->nama;
            return back()->with('success', "Semester {$namaSemester} sekarang menjadi semester aktif.");

        } catch (\Exception $e) {
            return back()->with('error', "Gagal mengubah status semester: " . $e->getMessage());
        }
    }

    public function destroy(Semester $semester)
    {
        if ($semester->status === 'aktif') {
            return back()->with('error', 'Gagal hapus! Semester yang sedang AKTIF tidak boleh dihapus.');
        }

        if ($semester->mahasiswas()->exists() || $semester->mataKuliahs()->exists()) {
            return back()->with('error', 'Gagal hapus! Semester ini masih memiliki data Mahasiswa atau Mata Kuliah terkait.');
        }

        $semester->delete();
        return back()->with('success', 'Semester berhasil dihapus.');
    }
}
