<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pertemuan;
use App\Models\KelasPerkuliahan;
use Illuminate\Http\Request;

class PertemuanController extends Controller
{
    public function index()
    {
        $pertemuans = Pertemuan::with('kelasPerkuliahan.mataKuliah')->latest()->paginate(10);
        $kelases = KelasPerkuliahan::with('mataKuliah')->get();
        return view('dashboard.dosen.pertemuan', compact('pertemuans', 'kelases'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_perkuliahan_id' => 'required|exists:kelas_perkuliahan,id',
            'pertemuan_ke' => 'required|integer',
            'tanggal' => 'required|date',
            'materi' => 'required|string',
            'status' => 'required|in:dibuka,ditutup',
        ]);

        // Cek unique index (kelas_id + pertemuan_ke)
        $exists = Pertemuan::where('kelas_perkuliahan_id', $request->kelas_perkuliahan_id)
                            ->where('pertemuan_ke', $request->pertemuan_ke)
                            ->exists();
        
        if($exists) return back()->with('error', 'Pertemuan ke-'.$request->pertemuan_ke.' sudah ada di kelas ini cok!');

        Pertemuan::create($request->all());
        return back()->with('success', 'Pertemuan berhasil dijadwalkan!');
    }

    public function update(Request $request, Pertemuan $pertemuan)
    {
        $request->validate([
            'status' => 'required|in:dibuka,ditutup',
            'materi' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        $pertemuan->update($request->all());
        return back()->with('success', 'Data pertemuan berhasil diupdate!');
    }

    public function destroy(Pertemuan $pertemuan)
    {
        if ($pertemuan->presensi()->exists()) {
            return back()->with('error', 'Gagal! Sudah ada mahasiswa yang absen di pertemuan ini.');
        }
        $pertemuan->delete();
        return back()->with('success', 'Pertemuan dihapus!');
    }
}