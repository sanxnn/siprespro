<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\KelasPerkuliahan;
use App\Models\Pertemuan;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function index()
    {
        $mahasiswa = auth()->user()->mahasiswa;

        if (!$mahasiswa) {
            abort(403, 'Data mahasiswa tidak ditemukan.');
        }

        $presensis = Presensi::with([
            'pertemuan.kelasPerkuliahan.mataKuliah',
            'pertemuan.kelasPerkuliahan.dosen',
        ])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->latest('waktu_presensi')
            ->paginate(10);

        return view('dashboard.mahasiswa.presensi.index', compact(
            'mahasiswa',
            'presensis'
        ));
    }

public function form()
{
    $mahasiswa = auth()->user()->mahasiswa;

    if (!$mahasiswa) {
        abort(403, 'Data mahasiswa tidak ditemukan.');
    }

    $today = Carbon::today();

    $kelasIds = KelasPerkuliahan::whereHas('golongans', function ($q) use ($mahasiswa) {
        $q->where('kelas_golongan.golongan_id', $mahasiswa->golongan_id);
    })->pluck('id');

    $pertemuans = Pertemuan::with([
    'kelasPerkuliahan.mataKuliah',
    'kelasPerkuliahan.dosen',
    'kelasPerkuliahan.ruang',
    'kelasPerkuliahan.jadwals.lokasi',
    'presensis' => function ($q) use ($mahasiswa) {
        $q->where('mahasiswa_id', $mahasiswa->id);
    },
])
->whereIn('kelas_perkuliahan_id', $kelasIds)
->whereDate('tanggal', $today)
->where('status', 'dibuka')
->get()
->sortBy(function ($pertemuan) {
    $now = now()->format('H:i:s');

    $sudahPresensi = $pertemuan->presensis->isNotEmpty();
    $belumMulai = $pertemuan->presensi_mulai && $now < $pertemuan->presensi_mulai;
    $sudahSelesai = $pertemuan->presensi_selesai && $now > $pertemuan->presensi_selesai;

    if (!$sudahPresensi && !$belumMulai && !$sudahSelesai) {
        return 1; // aktif
    }

    if (!$sudahPresensi && $belumMulai) {
        return 2; // belum mulai
    }

    if ($sudahPresensi) {
        return 3; // sudah presensi
    }

    return 4; // sudah selesai
})
->values();
    return view('dashboard.mahasiswa.presensi.form', compact(
        'mahasiswa',
        'pertemuans'
    ));
}
    public function store(Request $request)
    {
        $request->validate([
            'pertemuan_id' => 'required|exists:pertemuan,id',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $mahasiswa = auth()->user()->mahasiswa;

        if (!$mahasiswa) {
            abort(403, 'Data mahasiswa tidak ditemukan.');
        }

        $pertemuan = Pertemuan::with([
            'kelasPerkuliahan.golongans',
            'kelasPerkuliahan.jadwals.lokasi',
        ])->findOrFail($request->pertemuan_id);

        $allowed = $pertemuan->kelasPerkuliahan->golongans
            ->pluck('id')
            ->contains($mahasiswa->golongan_id);

        if (!$allowed) {
            return back()->with('error', 'Anda tidak terdaftar di kelas ini.');
        }

        if ($pertemuan->status !== 'dibuka') {
            return back()->with('error', 'Presensi sudah ditutup.');
        }

        $now = now()->format('H:i:s');

        if ($pertemuan->presensi_mulai && $now < $pertemuan->presensi_mulai) {
            return back()->with('error', 'Presensi belum dibuka. Mulai pukul ' . substr($pertemuan->presensi_mulai, 0, 5) . '.');
        }

        if ($pertemuan->presensi_selesai && $now > $pertemuan->presensi_selesai) {
            return back()->with('error', 'Presensi sudah berakhir pada pukul ' . substr($pertemuan->presensi_selesai, 0, 5) . '.');
        }

        $already = Presensi::where('pertemuan_id', $pertemuan->id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->exists();

        if ($already) {
            return back()->with('error', 'Anda sudah melakukan presensi.');
        }

        $jadwal = $pertemuan->kelasPerkuliahan->jadwals->first();

        if (!$jadwal || !$jadwal->lokasi) {
            return back()->with('error', 'Lokasi presensi belum diatur.');
        }

        $lokasi = $jadwal->lokasi;

        $jarak = $this->hitungJarak(
            $request->latitude,
            $request->longitude,
            $lokasi->latitude,
            $lokasi->longitude
        );

        if ($jarak > $lokasi->radius_meter) {
            return back()->with(
                'error',
                'Anda berada di luar radius presensi. Jarak Anda sekitar ' . round($jarak) . ' meter.'
            );
        }

        Presensi::create([
            'pertemuan_id' => $pertemuan->id,
            'mahasiswa_id' => $mahasiswa->id,
            'status' => 'hadir',
            'waktu_presensi' => now(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return back()->with('success', 'Presensi berhasil dilakukan.');
    }

    private function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;

        $lat1 = deg2rad((float) $lat1);
        $lon1 = deg2rad((float) $lon1);
        $lat2 = deg2rad((float) $lat2);
        $lon2 = deg2rad((float) $lon2);

        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($lat1) * cos($lat2) *
            sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}