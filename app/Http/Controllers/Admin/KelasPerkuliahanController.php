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
        // Pake scope 'aktif' (pastiin di model Semester ada scopeAktif)
        $semesterAktif = Semester::where('status', 'aktif')->first();

        // Query Kelas Perkuliahan - Tambahin pagination filter biar konsisten
        $kelases = KelasPerkuliahan::with(['mataKuliah', 'dosen', 'ruang', 'golongans'])
            ->when($semesterAktif, function ($q) use ($semesterAktif) {
                return $q->whereHas('mataKuliah', function ($query) use ($semesterAktif) {
                    $query->where('semester_id', $semesterAktif->id);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Filter Matkul cuma buat semester aktif
        $matkulFiltered = $semesterAktif
            ? MataKuliah::where('semester_id', $semesterAktif->id)->get()
            : collect();

        $dosens = Dosen::all();
        $ruangs = Ruang::all();
        $golongans = Golongan::all();

        // --- LOGIC ANGKATAN (Optimasi dikit biar gak terlalu banyak query) ---
        $currentYear = date('Y');
        $currentMonth = date('n');
        $isGanjil = ($currentMonth >= 8);
        $angkatanList = [];

        for ($i = 0; $i < 4; $i++) {
            $tahun = $isGanjil ? ($currentYear - $i) : ($currentYear - $i - 1);
            $yearDiff = $currentYear - $tahun;
            $semAngka = $isGanjil ? ($yearDiff * 2) + 1 : ($yearDiff * 2);

            if ($semAngka > 0 && $semAngka <= 8) {
                // Ambil golongan yang emang ada mahasiswanya di angkatan itu
                $golonganFiltered = Golongan::whereHas('mahasiswas', function ($q) use ($tahun) {
                    $q->where('angkatan', $tahun);
                })->get();

                if ($golonganFiltered->isNotEmpty()) {
                    $angkatanList[] = [
                        'tahun' => $tahun,
                        'semester_nama' => "Semester " . $semAngka, // Tambahin teks biar jelas di view
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
        $messages = [
            'mata_kuliah_id.required' => 'Mata kuliah wajib dipilih.',
            'mata_kuliah_id.exists' => 'Data mata kuliah tidak ditemukan.',
            'dosen_id.required' => 'Dosen pengajar harus ditentukan.',
            'ruang_id.required' => 'Ruangan kelas wajib dipilih.',
            'nama_kelas.required' => 'Nama kelas tidak boleh kosong.',
            'tipe_kelas.required' => 'Tipe kelas harus ditentukan.',
            'golongan_ids.required' => 'Silakan pilih minimal satu golongan.',
            'golongan_ids.array' => 'Format data golongan tidak valid.',
        ];

        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:dosen,id',
            'ruang_id' => 'required|exists:ruang,id',
            'nama_kelas' => 'required|string|max:100',
            'tipe_kelas' => 'required|in:reguler,gabungan',
            'golongan_ids' => 'required|array|min:1',
            'golongan_ids.*' => 'exists:golongan,id',
        ], $messages);

        if ($request->tipe_kelas === 'reguler' && count($request->golongan_ids) > 1) {
            return back()
                ->withInput()
                ->with('error', 'Tipe kelas reguler hanya diperbolehkan untuk satu golongan.');
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
            return back()->with('success', 'Kelas perkuliahan berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error Store Kelas: " . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem saat menyimpan data.');
        }
    }

    public function update(Request $request, KelasPerkuliahan $kela)
    {
        $messages = [
            'mata_kuliah_id.required' => 'Mata kuliah wajib dipilih.',
            'dosen_id.required' => 'Dosen pengajar wajib dipilih.',
            'ruang_id.required' => 'Ruangan kelas wajib ditentukan.',
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'tipe_kelas.required' => 'Tipe kelas wajib ditentukan.',
            'golongan_ids.required' => 'Pilih minimal satu golongan.',
        ];

        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:dosen,id',
            'ruang_id' => 'required|exists:ruang,id',
            'nama_kelas' => 'required|string|max:100',
            'tipe_kelas' => 'required|in:reguler,gabungan',
            'golongan_ids' => 'required|array|min:1',
            'golongan_ids.*' => 'exists:golongan,id',
        ], $messages);

        if ($request->tipe_kelas === 'reguler' && count($request->golongan_ids) > 1) {
            return back()
                ->withInput()
                ->with('error', 'Perubahan gagal. Tipe kelas reguler hanya diperbolehkan memiliki satu golongan.');
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
            return back()->with('success', 'Data kelas perkuliahan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Update Kelas Error: " . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem saat memperbarui data.');
        }
    }

    public function destroy(KelasPerkuliahan $kela)
    {
        DB::beginTransaction();
        try {
            if ($kela->jadwals()->exists() || $kela->pertemuans()->exists()) {
                return back()->with('error', 'Kelas tidak bisa dihapus karena sudah memiliki jadwal atau data pertemuan!');
            }

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