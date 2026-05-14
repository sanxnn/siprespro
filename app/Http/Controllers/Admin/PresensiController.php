<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Presensi;
use App\Models\Pertemuan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Presensi::with([
            'mahasiswa',
            'pertemuan.kelasPerkuliahan.mataKuliah',
            'pertemuan.kelasPerkuliahan.semester'
        ]);

        if ($request->filled('pertemuan_id')) {
            $query->where('pertemuan_id', $request->pertemuan_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('mata_kuliah_id')) {
            $query->whereHas('pertemuan.kelasPerkuliahan', function ($q) use ($request) {
                $q->where('mata_kuliah_id', $request->mata_kuliah_id);
            });
        }

        $presensis = $query->latest()->paginate(20)->withQueryString();

        $pertemuans = Pertemuan::with('kelasPerkuliahan.mataKuliah')->latest()->get();
        $mataKuliahs = MataKuliah::orderBy('nama', 'asc')->get();

        return view('dashboard.admin.presensi', compact('presensis', 'pertemuans', 'mataKuliahs'));
    }

    public function update(Request $request, Presensi $presensi)
    {
        $messages = [
            'status.required' => 'Status kehadiran harus dipilih.',
            'status.in' => 'Status yang dipilih tidak sesuai dengan kategori sistem.',
        ];

        $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpha',
        ], $messages);

        try {
            $presensi->update([
                'status' => $request->status
            ]);

            return back()->with('success', 'Status presensi mahasiswa berhasil diperbarui.');

        } catch (\Exception $e) {
            \Log::error("Gagal update presensi: " . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan pada sistem saat memperbarui data.');
        }
    }

    public function destroy(Presensi $presensi)
    {
        try {
            $presensi->delete();

            return back()->with('success', 'Data presensi mahasiswa berhasil dihapus dari sistem.');

        } catch (\Exception $e) {
            \Log::error("Gagal menghapus data presensi: " . $e->getMessage());

            return back()->with('error', 'Gagal menghapus data. Data mungkin masih terikat dengan laporan lain.');
        }
    }
}
