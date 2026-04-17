<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index()
    {
        $lokasis = Lokasi::latest()->paginate(10);
        return view('dashboard.admin.lokasi', compact('lokasis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_meter' => 'required|integer|min:5',
        ]);

        Lokasi::create($request->all());
        return back()->with('success', 'Titik lokasi berhasil ditambahkan jancok!');
    }

    public function update(Request $request, Lokasi $lokasi)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_meter' => 'required|integer|min:5',
        ]);

        $lokasi->update($request->all());
        return back()->with('success', 'Lokasi berhasil diupdate!');
    }

    public function destroy(Lokasi $lokasi)
    {
        $lokasi->delete();
        return back()->with('success', 'Lokasi berhasil dihapus!');
    }
}
