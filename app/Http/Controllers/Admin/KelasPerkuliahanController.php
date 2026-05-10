<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\KelasPerkuliahan;
use App\Models\MataKuliah;
use App\Models\Ruang;
use App\Models\Golongan;
use App\Models\Semester;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class KelasPerkuliahanController extends Controller
{
    public function index()
    {
        // 1. Ambil Semester Aktif (Pondasi Utama)
        $semesterAktif = Semester::aktif()->first();

        // 2. Query Kelas dengan Eager Loading & Pagination (Hanya yang semester aktif)
        $kelases = KelasPerkuliahan::with(['mataKuliah', 'dosen', 'ruang', 'golongans'])
            ->whereHas('mataKuliah', function ($q) use ($semesterAktif) {
                $q->where('semester_id', $semesterAktif?->id);
            })
            ->latest()
            ->paginate(10);

        // 3. Dropdown Mata Kuliah (Hanya yang Aktif di Semester ini)
        $matkulFiltered = MataKuliah::where('semester_id', $semesterAktif?->id)->get();

        // 4. Data Master Standar
        $dosens = Dosen::all();
        $ruangs = Ruang::all();
        $golongans = Golongan::all(); // Untuk fallback atau keperluan lain

        // 5. Logic Mapping Angkatan & Golongan (Kunci Filter lo)
        $currentYear = date('Y');
        $currentMonth = date('n');
        $isGanjil = ($currentMonth >= 8);
        $angkatanList = [];

        for ($i = 0; $i < 4; $i++) {
            $tahun = $isGanjil ? ($currentYear - $i) : ($currentYear - $i - 1);
            $yearDiff = $currentYear - $tahun;
            $semAngka = $isGanjil ? ($yearDiff * 2) + 1 : ($yearDiff * 2);

            if ($semAngka > 0 && $semAngka <= 8) {
                // Tarik ID Golongan yang emang ada mahasiswanya di angkatan ini
                $golonganIds = Mahasiswa::where('angkatan', $tahun)
                    ->whereNotNull('golongan_id')
                    ->distinct()
                    ->pluck('golongan_id');

                // Ambil data golongannya
                $golonganFiltered = Golongan::whereIn('id', $golonganIds)->get();

                if ($golonganFiltered->count() > 0) {
                    $angkatanList[] = [
                        'tahun' => $tahun,
                        'semester_nama' => $semAngka,
                        'data_golongan' => $golonganFiltered
                    ];
                }
            }
        }

        return view('dashboard.admin.kelas-perkuliahan', compact(
            'kelases',
            'dosens',
            'ruangs',
            'golongans',
            'angkatanList',
            'semesterAktif',
            'matkulFiltered'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:dosen,id',
            'ruang_id' => 'required|exists:ruang,id',
            'nama_kelas' => 'required|string|max:100',
            'tipe_kelas' => 'required|in:reguler,gabungan',
            'golongan_ids' => 'required|array|min:1',
            'golongan_ids.*' => 'exists:golongan,id',
        ]);

        // ATURAN KETAT: Reguler cuma boleh 1 golongan
        if ($request->tipe_kelas === 'reguler' && count($request->golongan_ids) > 1) {
            return back()->with('error', 'Tipe kelas Reguler hanya diperbolehkan memilih 1 golongan.');
        }

        DB::beginTransaction();
        try {
            $kelas = KelasPerkuliahan::create($request->only([
                'mata_kuliah_id',
                'dosen_id',
                'ruang_id',
                'nama_kelas',
                'tipe_kelas'
            ]));

            $kelas->golongans()->sync($request->golongan_ids);

            DB::commit();
            return back()->with('success', 'Kelas perkuliahan berhasil dibuka!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat kelas: ' . $e->getMessage());
        }
    }

    public function update(Request $request, KelasPerkuliahan $kela)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:dosen,id',
            'ruang_id' => 'required|exists:ruang,id',
            'nama_kelas' => 'required|string|max:100',
            'tipe_kelas' => 'required|in:reguler,gabungan',
            'golongan_ids' => 'required|array|min:1',
            'golongan_ids.*' => 'exists:golongan,id',
        ]);

        // ATURAN KETAT: Update pun harus validasi jumlah golongan
        if ($request->tipe_kelas === 'reguler' && count($request->golongan_ids) > 1) {
            throw ValidationException::withMessages([
                'golongan_ids' => 'Perubahan gagal! Tipe reguler tidak boleh memiliki lebih dari 1 golongan.'
            ]);
        }

        DB::beginTransaction();
        try {
            $kela->update($request->only([
                'mata_kuliah_id',
                'dosen_id',
                'ruang_id',
                'nama_kelas',
                'tipe_kelas'
            ]));

            $kela->golongans()->sync($request->golongan_ids);

            DB::commit();
            return back()->with('success', 'Data kelas berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    public function destroy(KelasPerkuliahan $kela)
    {
        DB::beginTransaction();
        try {
            // Cek dulu apakah kelas ini sudah punya jadwal atau presensi
            if ($kela->jadwals()->exists() || $kela->pertemuans()->exists()) {
                return back()->with('error', 'Kelas tidak bisa dihapus karena sudah memiliki jadwal atau data pertemuan!');
            }

            // Hapus relasi pivot dulu (otomatis sebenernya kalo di DB pake cascade, tapi ini buat safety)
            $kela->golongans()->detach();
            $kela->delete();

            DB::commit();
            return back()->with('success', 'Kelas berhasil dihapus secara permanen!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}