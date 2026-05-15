<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        // 1. Ambil data mahasiswa untuk dapet golongan_id
        $mahasiswa = DB::table('mahasiswa')
            ->where('id', $user->mahasiswa_id)
            ->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // 2. Ambil semua ID kelas perkuliahan berdasarkan golongan si mahasiswa
        $kelasIds = DB::table('kelas_golongan')
            ->where('golongan_id', $mahasiswa->golongan_id)
            ->pluck('kelas_perkuliahan_id')
            ->toArray();

        $tanggalHariIni = date('Y-m-d');

        // 3. Tarik data pertemuan khusus HARI INI yang BELUM DI-ABSEN
        $pertemuanHariIni = DB::table('pertemuan')
            ->join('kelas_perkuliahan', 'pertemuan.kelas_perkuliahan_id', '=', 'kelas_perkuliahan.id')
            ->join('mata_kuliah', 'kelas_perkuliahan.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->join('dosen', 'kelas_perkuliahan.dosen_id', '=', 'dosen.id')
            ->join('lokasi', 'pertemuan.lokasi_id', '=', 'lokasi.id')
            ->leftJoin('presensi', function ($join) use ($mahasiswa) {
                $join->on('pertemuan.id', '=', 'presensi.pertemuan_id')
                    ->where('presensi.mahasiswa_id', '=', $mahasiswa->id);
            })
            ->whereIn('pertemuan.kelas_perkuliahan_id', $kelasIds)
            ->where('pertemuan.tanggal', $tanggalHariIni)
            ->where('pertemuan.status', 'dibuka') // Tambahan: Hanya tampilkan kalau dosen buka absen
            ->whereNull('presensi.status') // CRITICAL: Kalau udh absen apapun itu langsung kosong/hilang Cuk!
            ->select(
                'pertemuan.id as pertemuan_id',
                'pertemuan.pertemuan_ke',
                'pertemuan.jam_mulai',
                'pertemuan.jam_selesai',
                'pertemuan.status as status_buka_absen',
                'mata_kuliah.nama as nama_mk',
                'mata_kuliah.kode_mk',
                'mata_kuliah.sks',
                'dosen.nama as nama_dosen',
                'lokasi.nama as ruangan',
                'presensi.status as status_absen_mhs'
            )
            ->orderBy('pertemuan.jam_mulai', 'asc')
            ->get();

        return view('dashboard.mahasiswa.presensi.index', compact('pertemuanHariIni'));
    }


    public function isiPresensi($pertemuan_id)
    {
        $user = Auth::user();

        // 1. Ambil data pertemuan beserta kelas, matkul, dan lokasinya
        $pertemuan = DB::table('pertemuan')
            ->join('kelas_perkuliahan', 'pertemuan.kelas_perkuliahan_id', '=', 'kelas_perkuliahan.id')
            ->join('mata_kuliah', 'kelas_perkuliahan.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->join('dosen', 'kelas_perkuliahan.dosen_id', '=', 'dosen.id')
            ->join('lokasi', 'pertemuan.lokasi_id', '=', 'lokasi.id')
            ->where('pertemuan.id', $pertemuan_id)
            ->select(
                'pertemuan.*',
                'mata_kuliah.nama as nama_mk',
                'mata_kuliah.kode_mk',
                'dosen.nama as nama_dosen',
                'lokasi.nama as nama_lokasi'
            )
            ->first();

        if (!$pertemuan) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Pertemuan tidak ditemukan.');
        }

        // 2. Proteksi Master Switch Dosen (Harus 'dibuka')
        if ($pertemuan->status !== 'dibuka') {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Absensi untuk kelas ini sudah ditutup atau belum dibuka oleh dosen.');
        }

        // 3. Cek apakah mahasiswa ini sudah absen sebelumnya di pertemuan ini
        $sudahAbsen = DB::table('presensi')
            ->where('pertemuan_id', $pertemuan_id)
            ->where('mahasiswa_id', $user->mahasiswa_id)
            ->exists();

        if ($sudahAbsen) {
            return redirect()->route('mahasiswa.dashboard')->with('warning', 'Kamu sudah mengisi presensi pada pertemuan ini.');
        }

        return view('dashboard.mahasiswa.presensi.show', compact('pertemuan'));
    }

    public function simpanPresensi(Request $request, $pertemuan_id)
    {
        // Validasi format input
        $request->validate([
            'status' => 'required|in:hadir,sakit,izin',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $user = Auth::user();
        $tanggalHariIni = date('Y-m-d');

        // 1. Ambil data pertemuan beserta data lokasi terkait
        $pertemuan = DB::table('pertemuan')
            ->where('id', $pertemuan_id)
            ->first();

        // 2. Cek apakah data pertemuan ada dan Master Switch Dosen aktif ('dibuka')
        if (!$pertemuan || $pertemuan->status !== 'dibuka') {
            return redirect()->route('mahasiswa.presensi.index')->with('error', 'Gagal! Absensi belum dibuka oleh dosen atau sudah ditutup.');
        }

        // 3. Antisipasi Tanggal (Hanya bisa absen di hari yang sama dengan tanggal pertemuan)
        // Ini biar mahasiswa gak bisa nembak form pertemuan hari kemarin yang dosennya lupa klik 'ditutup'
        if ($pertemuan->tanggal !== $tanggalHariIni) {
            return redirect()->route('mahasiswa.presensi.index')->with('error', 'Gagal! Sesi absensi untuk pertemuan tanggal ' . date('d-m-Y', strtotime($pertemuan->tanggal)) . ' sudah kedaluwarsa.');
        }

        // 4. Validasi Geolocation menggunakan Method Model Lokasi lu (Hanya untuk status 'hadir')
        if ($request->status === 'hadir') {
            if (empty($request->latitude) || empty($request->longitude)) {
                return redirect()->back()->with('error', 'Gagal! Koordinat GPS Anda tidak terdeteksi. Pastikan GPS aktif.');
            }

            // Instansiasi model Lokasi berdasarkan lokasi_id dari pertemuan
            $lokasiKampus = Lokasi::find($pertemuan->lokasi_id);

            if (!$lokasiKampus) {
                return redirect()->back()->with('error', 'Sistem Error: Data koordinat ruangan tidak ditemukan.');
            }

            // Validasi radius lewat model sakti lu
            if (!$lokasiKampus->isWithinRadius($request->latitude, $request->longitude)) {
                return redirect()->back()->with('error', 'Presensi Ditolak! Anda berada di luar area radius kelas ' . $lokasiKampus->nama . ' (Maksimal ' . $lokasiKampus->radius_meter . ' meter).');
            }
        }

        // 5. Cek Double-Submit demi mencegah anomali data ganda
        $sudahAbsen = DB::table('presensi')
            ->where('pertemuan_id', $pertemuan_id)
            ->where('mahasiswa_id', $user->mahasiswa_id)
            ->exists();

        if ($sudahAbsen) {
            return redirect()->route('mahasiswa.presensi.index')->with('warning', 'Aksi diblokir! Anda terdeteksi sudah melakukan absensi sebelumnya.');
        }

        // 6. Eksekusi Inject Ke Database
        DB::table('presensi')->insert([
            'pertemuan_id' => $pertemuan_id,
            'mahasiswa_id' => $user->mahasiswa_id,
            'waktu_presensi' => now(),
            'status' => $request->status,
            'latitude' => $request->status === 'hadir' ? $request->latitude : null,
            'longitude' => $request->status === 'hadir' ? $request->longitude : null,
            'created_at' => now(),
        ]);

        return redirect()->route('mahasiswa.presensi.index')->with('success', 'Berhasil! Data kehadiran Anda telah tervalidasi oleh sistem.');
    }

    public function riwayat()
    {
        $user = Auth::user();
        $mahasiswa = DB::table('mahasiswa')->where('id', $user->mahasiswa_id)->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $kelasIds = DB::table('kelas_golongan')
            ->where('golongan_id', $mahasiswa->golongan_id)
            ->pluck('kelas_perkuliahan_id')
            ->toArray();

        // Ambil data rekap kehadiran per Mata Kuliah
        $riwayatPresensi = DB::table('kelas_perkuliahan')
            ->join('mata_kuliah', 'kelas_perkuliahan.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->join('dosen', 'kelas_perkuliahan.dosen_id', '=', 'dosen.id')
            ->whereIn('kelas_perkuliahan.id', $kelasIds)

            // CRITICAL FIX: Hanya munculkan matakuliah yang SUDAH PERNAH DIABSEN oleh mahasiswa ini
            // Matakuliah yang belum mulai (kayak yang tanggal 18) otomatis tertendang asu!
            ->whereExists(function ($query) use ($mahasiswa) {
                $query->select(DB::raw(1))
                    ->from('presensi')
                    ->join('pertemuan', 'presensi.pertemuan_id', '=', 'pertemuan.id')
                    ->whereColumn('pertemuan.kelas_perkuliahan_id', 'kelas_perkuliahan.id')
                    ->where('presensi.mahasiswa_id', $mahasiswa->id);
            })

            ->select(
                'kelas_perkuliahan.id as kelas_id',
                'mata_kuliah.nama as nama_mk',
                'mata_kuliah.kode_mk',
                'dosen.nama as nama_dosen',

                // TOTAL PERTEMUAN: Hitung yang sudah dilewati/diabsen oleh mhs ini
                DB::raw("(SELECT COUNT(*) FROM presensi JOIN pertemuan ON presensi.pertemuan_id = pertemuan.id 
                      WHERE pertemuan.kelas_perkuliahan_id = kelas_perkuliahan.id 
                      AND presensi.mahasiswa_id = {$mahasiswa->id}) as total_pertemuan"),

                // Hitung detail status masing-masing
                DB::raw("(SELECT COUNT(*) FROM presensi JOIN pertemuan ON presensi.pertemuan_id = pertemuan.id 
                      WHERE pertemuan.kelas_perkuliahan_id = kelas_perkuliahan.id 
                      AND presensi.mahasiswa_id = {$mahasiswa->id} 
                      AND LOWER(presensi.status) = 'hadir') as jumlah_hadir"),

                DB::raw("(SELECT COUNT(*) FROM presensi JOIN pertemuan ON presensi.pertemuan_id = pertemuan.id 
                      WHERE pertemuan.kelas_perkuliahan_id = kelas_perkuliahan.id 
                      AND presensi.mahasiswa_id = {$mahasiswa->id} 
                      AND LOWER(presensi.status) = 'sakit') as jumlah_sakit"),

                DB::raw("(SELECT COUNT(*) FROM presensi JOIN pertemuan ON presensi.pertemuan_id = pertemuan.id 
                      WHERE pertemuan.kelas_perkuliahan_id = kelas_perkuliahan.id 
                      AND presensi.mahasiswa_id = {$mahasiswa->id} 
                      AND LOWER(presensi.status) = 'izin') as jumlah_izin"),

                DB::raw("(SELECT COUNT(*) FROM presensi JOIN pertemuan ON presensi.pertemuan_id = pertemuan.id 
                      WHERE pertemuan.kelas_perkuliahan_id = kelas_perkuliahan.id 
                      AND presensi.mahasiswa_id = {$mahasiswa->id} 
                      AND LOWER(presensi.status) = 'alfa') as jumlah_alfa")
            )
            ->get();

        return view('dashboard.mahasiswa.riwayat.index', compact('riwayatPresensi'));
    }

    public function detailRiwayat($kelas_id)
    {
        $user = Auth::user();
        $mahasiswa = DB::table('mahasiswa')->where('id', $user->mahasiswa_id)->first();

        $kelas = DB::table('kelas_perkuliahan')
            ->join('mata_kuliah', 'kelas_perkuliahan.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->join('dosen', 'kelas_perkuliahan.dosen_id', '=', 'dosen.id')
            ->where('kelas_perkuliahan.id', $kelas_id)
            ->select('mata_kuliah.nama as nama_mk', 'mata_kuliah.kode_mk', 'dosen.nama as nama_dosen', 'kelas_perkuliahan.id as kelas_id')
            ->first();

        // CRITICAL FIX: Pakai JOIN biasa (Inner Join) ke tabel presensi si mahasiswa
        // Jadi kalau mhs BELUM ABSEN (termasuk kelas besok/minggu depan), TIDAK AKAN MUNCUL!
        $daftarPertemuan = DB::table('pertemuan')
            ->join('presensi', function ($join) use ($mahasiswa) {
                $join->on('pertemuan.id', '=', 'presensi.pertemuan_id')
                    ->where('presensi.mahasiswa_id', '=', $mahasiswa->id);
            })
            ->where('pertemuan.kelas_perkuliahan_id', $kelas_id)
            ->select(
                'pertemuan.pertemuan_ke',
                'pertemuan.tanggal',
                'pertemuan.jam_mulai',
                DB::raw('LOWER(presensi.status) as status_absen')
            )
            ->orderBy('pertemuan.pertemuan_ke', 'asc')
            ->get();

        return view('dashboard.mahasiswa.riwayat.show', compact('kelas', 'daftarPertemuan'));
    }
}
