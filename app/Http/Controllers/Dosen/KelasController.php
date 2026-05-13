<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\KelasPerkuliahan;
use App\Models\Lokasi;
use App\Models\Pertemuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{
    public function index()
    {
        $kelases = KelasPerkuliahan::with(['mataKuliah', 'ruang', 'golongans'])
            ->where('dosen_id', Auth::user()->dosen_id)->get();
        $semesterAktif = \App\Models\Semester::latest()->first();
        return view('dashboard.dosen.kelas.index', compact('kelases', 'semesterAktif'));
    }

    public function show($id)
    {
        $kelas = KelasPerkuliahan::with(['mataKuliah', 'ruang', 'golongans', 'jadwals.lokasi', 'pertemuans'])
            ->where('dosen_id', Auth::user()->dosen_id)
            ->findOrFail($id);

        $lokasis = Lokasi::all();
        // Cek pertemuan mana yang lagi 'dibuka'
        $pertemuanAktif = $kelas->pertemuans()->where('status', 'dibuka')->first();

        return view('dashboard.dosen.kelas.show', compact('kelas', 'lokasis', 'pertemuanAktif'));
    }

    public function storeJadwal(Request $request, $id)
    {
        $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'lokasi_id' => 'required'
        ]);

        Jadwal::create([
            'kelas_perkuliahan_id' => $id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'lokasi_id' => $request->lokasi_id
        ]);

        return back()->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function storePertemuan(Request $request, $id)
    {
        $request->validate([
            'pertemuan_ke' => 'required|integer',
            'tanggal' => 'required|date',
            'materi' => 'required'
        ]);

        Pertemuan::create([
            'kelas_perkuliahan_id' => $id,
            'pertemuan_ke' => $request->pertemuan_ke,
            'tanggal' => $request->tanggal,
            'materi' => $request->materi,
            'status' => 'ditutup' // Default ditutup, dosen buka manual nanti
        ]);

        return back()->with('success', 'Pertemuan berhasil dikonfigurasi!');
    }

    public function togglePertemuan($id)
    {
        $p = Pertemuan::findOrFail($id);
        $p->status = ($p->status == 'dibuka') ? 'ditutup' : 'dibuka';
        $p->save();

        return back()->with('success', 'Status presensi diperbarui!');
    }
}
