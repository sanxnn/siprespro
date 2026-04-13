<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Mahasiswa, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash};

class MahasiswaController extends Controller
{
    public function index()
{
    // Ambil data mahasiswa dengan relasinya
    $mahasiswas = Mahasiswa::with(['user', 'golongan', 'semester'])->latest()->paginate(10);
    
    // Ambil data untuk dropdown di modal
    $golongans = \App\Models\Golongan::all();
    $semesters = \App\Models\Semester::all();

    return view('dashboard.admin.mahasiswa', compact('mahasiswas', 'golongans', 'semesters'));
}

    public function store(Request $request)
{
    $request->validate([
        'nim'           => 'required|unique:mahasiswa,nim',
        'nama'          => 'required',
        'email'         => 'required|email|unique:users,email',
        'angkatan'      => 'required|numeric',
        'semester_id'   => 'nullable|exists:semester,id',
        'golongan_id'   => 'nullable|exists:golongan,id',
        'nik'           => 'nullable|numeric',
    ]);

    DB::transaction(function () use ($request) {
        // 1. Akun Login
        $user = User::create([
            'email'    => $request->email,
            'password' => Hash::make($request->nim),
            'role'     => 'mahasiswa',
            'is_active'=> true,
        ]);

        // 2. Profil Lengkap
        $mhs = Mahasiswa::create($request->all());

        // 3. Link
        $user->update(['mahasiswa_id' => $mhs->id]);
    });

    return back()->with('success', 'Mahasiswa ' . $request->nama . ' berhasil didaftarkan!');
}
    

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'nama' => 'required',
            'email' => "required|email|unique:users,email,{$mahasiswa->user->id}",
        ]);

        DB::transaction(function () use ($request, $mahasiswa) {
            $mahasiswa->update($request->only(['nama', 'angkatan', 'semester_id']));
            $mahasiswa->user->update(['email' => $request->email]);
        });

        return back()->with('success', 'Data Mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        DB::transaction(function () use ($mahasiswa) {
            $mahasiswa->user->delete(); // Hapus user otomatis hapus profile (jika ada cascade)
            $mahasiswa->delete();
        });
        return back()->with('success', 'Data Mahasiswa berhasil dihapus.');
    }
}