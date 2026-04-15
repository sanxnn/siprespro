<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruang;
use Illuminate\Http\Request;

class RuangController extends Controller
{
    public function index()
    {
        $ruangs = Ruang::latest()->paginate(10);
        return view('dashboard.admin.ruang', compact('ruangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'gedung' => 'required|string|max:255',
        ]);

        Ruang::create($request->all());
        return back()->with('success', 'Ruang baru berhasil ditambahkan!');
    }

    public function update(Request $request, Ruang $ruang)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'gedung' => 'required|string|max:255',
        ]);

        $ruang->update($request->all());
        return back()->with('success', 'Data ruang berhasil diperbarui!');
    }

    public function destroy(Ruang $ruang)
    {
        // Cek relasi ke kelas_perkuliahan sebelum hapus
        if ($ruang->kelasPerkuliahan()->exists()) {
            return back()->with('error', 'Gagal hapus! Ruangan ini masih digunakan dalam jadwal kelas.');
        }

        $ruang->delete();
        return back()->with('success', 'Ruangan berhasil dihapus!');
    }
}
