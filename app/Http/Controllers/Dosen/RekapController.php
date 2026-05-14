<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\KelasPerkuliahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekapController extends Controller
{
    public function index()
    {
        $dosenId = Auth::user()->dosen_id;

        $kelas = KelasPerkuliahan::with(['mataKuliah', 'ruang', 'pertemuans', 'golongans.mahasiswas'])
            ->where('dosen_id', $dosenId)
            ->get();

        $dataRekap = $kelas->map(function ($k) {
            $allMahasiswa = $k->golongans->flatMap(function ($golongan) {
                return $golongan->mahasiswas;
            })->unique('id');

            $totalMhs = $allMahasiswa->count();
            $totalPertemuan = $k->pertemuans->where('status', 'selesai')->count();

            $totalPresensiHadir = \App\Models\Presensi::whereIn('pertemuan_id', $k->pertemuans->pluck('id'))
                ->where('status', 'hadir')
                ->count();

            $kapasitasMaksimal = $totalMhs * max($totalPertemuan, 1);
            $persentaseKelas = ($kapasitasMaksimal > 0) ? ($totalPresensiHadir / $kapasitasMaksimal) * 100 : 0;

            return [
                'id' => $k->id,
                'kode' => $k->mataKuliah->kode_mk,
                'matkul' => $k->mataKuliah->nama,
                'kelas' => $k->nama_kelas,
                'total_mhs' => $totalMhs,
                'pertemuan_jalan' => $totalPertemuan,
                'persentase' => $persentaseKelas
            ];
        });

        return view('dashboard.dosen.rekap', compact('dataRekap'));
    }
}
