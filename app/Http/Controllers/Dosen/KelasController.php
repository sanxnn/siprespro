<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
// use App\Models\Jadwal; // <--- HAPUS/BUANG INI COK, UDAH JADI ALMARHUM
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
        $semesterAktif = \App\Models\Semester::where('status', 'aktif')->first(); // Pastikan ambil yang is_aktif
        return view('dashboard.dosen.kelas.index', compact('kelases', 'semesterAktif'));
    }

    public function show($id)
    {
        // GANTI 'jadwals.lokasi' jadi 'pertemuans.lokasi'
        $kelas = KelasPerkuliahan::with(['mataKuliah', 'ruang', 'golongans', 'pertemuans.lokasi'])
            ->where('dosen_id', Auth::user()->dosen_id)
            ->findOrFail($id);

        $lokasis = Lokasi::all();

        // Ambil pertemuan yang status manualnya dibuka DAN tanggal hari ini (Opsional)
        $pertemuanAktif = $kelas->pertemuans()
            ->where('status', 'dibuka')
            ->where('tanggal', now()->format('Y-m-d'))
            ->first();

        return view('dashboard.dosen.kelas.show', compact('kelas', 'lokasis', 'pertemuanAktif'));
    }

    // HAPUS TOTAL METHOD storeJadwal KARENA SUDAH GABUNG KE PERTEMUAN

    public function storePertemuan(Request $request, $id)
    {
        // Sesuaikan validasi dengan migrasi baru lo (ada jam & lokasi)
        $request->validate([
            'pertemuan_ke' => 'required|integer',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'lokasi_id' => 'required|exists:lokasi,id',
            'materi' => 'required'
        ]);

        Pertemuan::create([
            'kelas_perkuliahan_id' => $id,
            'pertemuan_ke' => $request->pertemuan_ke,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'lokasi_id' => $request->lokasi_id,
            'materi' => $request->materi,
            'status' => 'ditutup' // Biar dosen buka manual pas di kelas
        ]);

        return back()->with('success', 'Sesi pertemuan berhasil dibuat!');
    }

    public function togglePertemuan($id)
    {
        $p = Pertemuan::findOrFail($id);
        $p->status = ($p->status == 'dibuka') ? 'ditutup' : 'dibuka';
        $p->save();

        return back()->with('success', 'Status akses presensi berhasil diubah!');
    }
}