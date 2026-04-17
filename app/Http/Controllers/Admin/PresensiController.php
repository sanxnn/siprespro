<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\Pertemuan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        // Filter logic biar gampang nyari data
        $query = Presensi::with(['pertemuan.kelasPerkuliahan.mataKuliah', 'mahasiswa']);

        if ($request->pertemuan_id) {
            $query->where('pertemuan_id', $request->pertemuan_id);
        }

        $presensis = $query->latest()->paginate(20);
        $pertemuans = Pertemuan::with('kelasPerkuliahan.mataKuliah')->get();

        return view('dashboard.admin.presensi', compact('presensis', 'pertemuans'));
    }

    public function update(Request $request, Presensi $presensi)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpha',
        ]);

        $presensi->update(['status' => $request->status]);
        return back()->with('success', 'Status presensi berhasil dikoreksi!');
    }

    public function destroy(Presensi $presensi)
    {
        $presensi->delete();
        return back()->with('success', 'Data presensi dihapus jancok!');
    }
}
