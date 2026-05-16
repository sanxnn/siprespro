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
        $kelas = KelasPerkuliahan::with(['mataKuliah', 'golongans.mahasiswas'])
            ->where('dosen_id', $dosenId)
            ->get();
        $tanggalHariIni = date('Y-m-d');
        $dataRekap = $kelas->map(function ($k) use ($tanggalHariIni) {
            $allMahasiswa = $k->golongans->flatMap(function ($golongan) {
                return $golongan->mahasiswas;
            })->unique('id');
            $totalMhs = $allMahasiswa->count();
            $pertemuanSelesaiIds = DB::table('pertemuan')
                ->where('kelas_perkuliahan_id', $k->id)
                ->where('status', 'ditutup')
                ->whereDate('tanggal', '<=', $tanggalHariIni)
                ->pluck('id')
                ->toArray();
            $totalPertemuan = count($pertemuanSelesaiIds);
            $totalPresensiAman = 0;
            if ($totalPertemuan > 0) {
                $totalPresensiAman = Presensi::whereIn('pertemuan_id', $pertemuanSelesaiIds)
                    ->whereIn(DB::raw('LOWER(status)'), ['hadir', 'sakit', 'izin'])
                    ->count();
            }
            $kapasitasMaksimal = $totalMhs * $totalPertemuan;
            if ($totalPertemuan > 0 && $totalMhs > 0) {
                $persentaseKelas = ($totalPresensiAman / $kapasitasMaksimal) * 100;
                if ($persentaseKelas > 100)
                    $persentaseKelas = 100;
            } else {
                $persentaseKelas = 0; 
            }
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