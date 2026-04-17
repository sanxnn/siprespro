<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\KelasPerkuliahan;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::with(['kelasPerkuliahan.mataKuliah', 'lokasi'])->latest()->paginate(10);
        $kelases = KelasPerkuliahan::with('mataKuliah')->get();
        $lokasis = Lokasi::all();
        
        // Buat daftar hari sesuai Enum di DB lu
        $hari_list = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];

        return view('dashboard.admin.jadwal', compact('jadwals', 'kelases', 'lokasis', 'hari_list'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_perkuliahan_id' => 'required|exists:kelas_perkuliahan,id',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'lokasi_id' => 'required|exists:lokasi,id',
        ]);

        Jadwal::create($request->all());
        return back()->with('success', 'Jadwal kuliah berhasil diset!');
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'kelas_perkuliahan_id' => 'required|exists:kelas_perkuliahan,id',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'lokasi_id' => 'required|exists:lokasi,id',
        ]);

        $jadwal->update($request->all());
        return back()->with('success', 'Jadwal berhasil diupdate!');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return back()->with('success', 'Jadwal berhasil dihapus!');
    }
}