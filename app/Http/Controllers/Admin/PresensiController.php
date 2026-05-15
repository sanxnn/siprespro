<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Presensi;
use App\Models\Pertemuan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $pertemuanId = $request->pertemuan_id;
        $status = $request->status;

        // Jika Admin memfilter status 'alpha' dan memilih pertemuan spesifik
        if ($status === 'alpha' && $request->filled('pertemuan_id')) {
            $pertemuan = Pertemuan::findOrFail($pertemuanId);

            // Cari mahasiswa yang se-golongan dengan kelas ini tapi TIDAK absen di pertemuan ini
            $query = Mahasiswa::whereHas('golongan.kelasPerkuliahan', function ($q) use ($pertemuan) {
                $q->where('id', $pertemuan->kelas_perkuliahan_id);
            })
                ->whereNotExists(function ($q) use ($pertemuanId) {
                    $q->select(DB::raw(1))
                        ->from('presensi')
                        ->whereRaw('mahasiswa.id = presensi.mahasiswa_id')
                        ->where('presensi.pertemuan_id', $pertemuanId);
                })
                ->select('mahasiswa.*');

            // Bungkus ke pagination bayangan agar struktur variabel di Blade tidak rusak
            $mahasiswasAlpha = $query->paginate(20)->withQueryString();

            // Transformasi data Alpha biar formatnya mirip dengan object Presensi di Blade
            $presensis = $mahasiswasAlpha->through(function ($mhs) use ($pertemuanId) {
                $p = new Presensi();
                $p->id = 'alpha-' . $mhs->id; // ID Dummy
                $p->mahasiswa_id = $mhs->id;
                $p->pertemuan_id = $pertemuanId;
                $p->status = 'alpha';
                $p->waktu_presensi = null;
                $p->latitude = null;
                $p->longitude = null;
                $p->mahasiswa = $mhs; // Tempel relasi mahasiswa
                return $p;
            });

        } else {
            // Query reguler untuk Hadir, Sakit, Izin, atau Semua Status
            $query = Presensi::with([
                'mahasiswa.golongan',
                'pertemuan.kelasPerkuliahan.mataKuliah'
            ]);

            if ($request->filled('pertemuan_id')) {
                $query->where('pertemuan_id', $pertemuanId);
            }

            if ($request->filled('status')) {
                // Jika pilih 'alpha' global tanpa milih pertemuan, filter data kosong atau handle case-by-case
                $query->where('status', $status);
            }

            if ($request->filled('mata_kuliah_id')) {
                $query->whereHas('pertemuan.kelasPerkuliahan', function ($q) use ($request) {
                    $q->where('mata_kuliah_id', $request->mata_kuliah_id);
                });
            }

            $presensis = $query->latest('waktu_presensi')->paginate(20)->withQueryString();
        }

        // Dropdown data pendukung dengan penamaan kelas yang super detail
        $pertemuans = Pertemuan::with('kelasPerkuliahan.mataKuliah')->latest()->get();
        $mataKuliahs = MataKuliah::orderBy('nama', 'asc')->get();

        return view('dashboard.admin.presensi', compact('presensis', 'pertemuans', 'mataKuliahs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'mahasiswa_id' => 'required_if:is_alpha,true',
            'pertemuan_id' => 'required_if:is_alpha,true',
        ]);

        try {
            // Jika status aslinya Alpha (data dummy), berarti kita disuruh membuat record BARU di database
            if (str_contains($id, 'alpha-')) {
                if ($request->status === 'alpha') {
                    return back()->with('info', 'Status tetap ALPHA, tidak ada perubahan.');
                }

                Presensi::create([
                    'mahasiswa_id' => $request->mahasiswa_id,
                    'pertemuan_id' => $request->pertemuan_id,
                    'status' => $request->status,
                    'waktu_presensi' => now(),
                ]);
            } else {
                // Update record presensi biasa
                $presensi = Presensi::findOrFail($id);
                $presensi->update(['status' => $request->status]);
            }

            return back()->with('success', 'Status presensi berhasil dikoreksi Admin.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            if (str_contains($id, 'alpha-')) {
                return back()->with('error', 'Data Alpha tidak bisa dihapus karena tidak ada di database.');
            }

            Presensi::findOrFail($id)->delete();
            return back()->with('success', 'Record presensi berhasil dihapus (Mahasiswa otomatis menjadi ALPHA kembali).');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data.');
        }
    }
}