<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\KelasPerkuliahan;
use App\Models\MataKuliah;
use App\Models\Ruang;
use Illuminate\Http\Request;

class KelasPerkuliahanController extends Controller
{
    public function index()
    {
        // Pake eager loading biar gak berat
        $kelases = KelasPerkuliahan::with(['mataKuliah', 'dosen', 'ruang'])->latest()->paginate(10);
        
        // Data buat dropdown di modal
        $matkuls = MataKuliah::all();
        $dosens = Dosen::all();
        $ruangs = Ruang::all();

        return view('dashboard.admin.kelas-perkuliahan', compact('kelases', 'matkuls', 'dosens', 'ruangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id'       => 'required|exists:dosen,id',
            'ruang_id'       => 'required|exists:ruang,id',
            'nama_kelas'     => 'required|string|max:100',
            'tipe_kelas'     => 'required|in:reguler,gabungan', // Sesuai Enum lu
        ]);

        KelasPerkuliahan::create($request->all());
        return back()->with('success', 'Kelas perkuliahan berhasil dibuat!');
    }

    public function update(Request $request, KelasPerkuliahan $kela)
    {

        // dd($request->all(), $kela->id);
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id'       => 'required|exists:dosen,id',
            'ruang_id'       => 'required|exists:ruang,id',
            'nama_kelas'     => 'required|string|max:100',
            'tipe_kelas'     => 'required|in:reguler,gabungan',
        ]);

        $kela->update($request->all());
        return back()->with('success', 'Data kelas berhasil diupdate!');
    }

    public function destroy(KelasPerkuliahan $kela)
    {

        $kela->delete();
        return back()->with('success', 'Kelas berhasil dihapus!');
    }

}
