<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            abort(403, 'Data mahasiswa tidak ditemukan.');
        }

        return view('dashboard.mahasiswa.profil.index', compact(
            'user',
            'mahasiswa'
        ));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            abort(403, 'Data mahasiswa tidak ditemukan.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'nomor_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        // update user
        $user->update([
            'email' => $request->email,
        ]);

        // update mahasiswa
        $mahasiswa->update([
            'nama' => $request->nama,
            'nomor_hp' => $request->nomor_hp,
            'alamat' => $request->alamat,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}