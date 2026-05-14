<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\KelasPerkuliahan;

class JadwalController extends Controller
{
    public function index()
    {
        $mahasiswa = auth()->user()->mahasiswa;

        if (!$mahasiswa) {
            abort(403, 'Data mahasiswa tidak ditemukan.');
        }

        $golonganId = $mahasiswa->golongan_id;

        // Ambil kelas berdasarkan golongan
        $kelasIds = collect();

        if ($golonganId) {
            $kelasIds = KelasPerkuliahan::whereHas('golongans', function ($q) use ($golonganId) {
                $q->where('golongan.id', $golonganId);
            })->pluck('id');
        }

        // Ambil jadwal
        $jadwals = Jadwal::with([
            'kelasPerkuliahan.mataKuliah',
            'kelasPerkuliahan.dosen',
            'kelasPerkuliahan.ruang',
            'lokasi'
        ])
        ->when($kelasIds->isNotEmpty(), function ($q) use ($kelasIds) {
            $q->whereIn('kelas_perkuliahan_id', $kelasIds);
        }, function ($q) {
            $q->whereRaw('1=0');
        })
        ->orderByRaw("FIELD(hari, 'senin','selasa','rabu','kamis','jumat','sabtu')")
        ->orderBy('jam_mulai')
        ->get();

        return view('dashboard.mahasiswa.jadwal', compact('jadwals', 'mahasiswa'));
    }

    public function semester()
{
    $mahasiswa = auth()->user()->mahasiswa;

    if (!$mahasiswa) {
        abort(403, 'Data mahasiswa tidak ditemukan.');
    }

    $golonganId = $mahasiswa->golongan_id;

    $kelasIds = collect();

    if ($golonganId) {
        $kelasIds = \App\Models\KelasPerkuliahan::whereHas('golongans', function ($q) use ($golonganId) {
            $q->where('kelas_golongan.golongan_id', $golonganId);
        })->pluck('id');
    }

    $jadwals = \App\Models\Jadwal::with([
        'kelasPerkuliahan.mataKuliah',
        'kelasPerkuliahan.dosen',
        'kelasPerkuliahan.ruang',
        'lokasi'
    ])
    ->when($kelasIds->isNotEmpty(), function ($q) use ($kelasIds) {
        $q->whereIn('kelas_perkuliahan_id', $kelasIds);
    }, function ($q) {
        $q->whereRaw('1 = 0');
    })
    ->orderByRaw("FIELD(hari, 'senin','selasa','rabu','kamis','jumat','sabtu','minggu')")
    ->orderBy('jam_mulai')
    ->get();

    return view('dashboard.mahasiswa.jadwal-semester', compact(
        'mahasiswa',
        'jadwals'
    ));
}
}

