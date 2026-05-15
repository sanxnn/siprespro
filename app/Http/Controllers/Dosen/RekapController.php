<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\KelasPerkuliahan;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function index()
    {
        $dosenId = Auth::user()->dosen_id;

        // Eager load data dasar
        $kelas = KelasPerkuliahan::with(['mataKuliah', 'golongans.mahasiswas'])
            ->where('dosen_id', $dosenId)
            ->get();

        $tanggalHariIni = date('Y-m-d');

        $dataRekap = $kelas->map(function ($k) use ($tanggalHariIni) {
            // 1. Ambil semua mahasiswa unik di kelas ini
            $allMahasiswa = $k->golongans->flatMap(function ($golongan) {
                return $golongan->mahasiswas;
            })->unique('id');

            $totalMhs = $allMahasiswa->count();

            // 2. CRITICAL DATABASE FIX: Langsung query ke DB biar urusan tanggal di-handle MySQL secara mutlak!
            // Mengambil ID pertemuan yang statusnya 'ditutup' DAN tanggalnya hari ini ke belakang (termasuk jam yang udah beres hari ini)
            $pertemuanSelesaiIds = DB::table('pertemuan')
                ->where('kelas_perkuliahan_id', $k->id)
                ->where('status', 'ditutup')
                ->whereDate('tanggal', '<=', $tanggalHariIni)
                ->pluck('id')
                ->toArray();

            $totalPertemuan = count($pertemuanSelesaiIds);

            // 3. Hitung record presensi yang AMAN (Hadir, Sakit, Izin) hanya pada sesi yang valid di atas
            $totalPresensiAman = 0;
            if ($totalPertemuan > 0) {
                $totalPresensiAman = Presensi::whereIn('pertemuan_id', $pertemuanSelesaiIds)
                    ->whereIn(DB::raw('LOWER(status)'), ['hadir', 'sakit', 'izin'])
                    ->count();
            }

            // 4. Hitung kapasitas maksimal real berdasarkan sesi yang emang UDAH JALAN
            $kapasitasMaksimal = $totalMhs * $totalPertemuan;

            // 5. Rumus persentase kelas
            if ($totalPertemuan > 0 && $totalMhs > 0) {
                $persentaseKelas = ($totalPresensiAman / $kapasitasMaksimal) * 100;
                if ($persentaseKelas > 100)
                    $persentaseKelas = 100;
            } else {
                $persentaseKelas = 100; // Kalau belum ada pertemuan yang selesai, default 100% biar clean
            }

            return [
                'id' => $k->id,
                'kode' => $k->mataKuliah->kode_mk,
                'matkul' => $k->mataKuliah->nama,
                'kelas' => $k->nama_kelas,
                'total_mhs' => $totalMhs,
                'pertemuan_jalan' => $totalPertemuan, // Sekarang nilainya pasti nge-detect Sesi 1 yang udah ditutup tadi!
                'persentase' => $persentaseKelas
            ];
        });

        return view('dashboard.dosen.rekap', compact('dataRekap'));
    }
}