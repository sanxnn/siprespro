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
        $messages = [
            'nama.required' => 'Nama lokasi wajib diisi, Bos!',
            'latitude.required' => 'Koordinat Latitude jangan kosong.',
            'latitude.numeric' => 'Latitude harus berupa angka.',
            'longitude.required' => 'Koordinat Longitude jangan kosong.',
            'longitude.numeric' => 'Longitude harus berupa angka.',
            'latitude.unique' => 'Titik koordinat ini sudah terdaftar di sistem.',
            'radius_meter.required' => 'Radius wajib diisi.',
            'radius_meter.integer' => 'Radius harus berupa angka bulat.',
            'radius_meter.min' => 'Radius minimal 5 meter, jangan pelit-pelit!',
        ];
        $request->validate([
            'nama' => 'required|string|max:255',
            'latitude' => [
                'required',
                'numeric',
                \Illuminate\Validation\Rule::unique('lokasi', 'latitude')->where(function ($query) use ($request) {
                    return $query->where('longitude', $request->longitude);
                }),
            ],
            'longitude' => 'required|numeric',
            'radius_meter' => 'required|integer|min:5',
        ], $messages);
        try {
            Lokasi::create($request->all());
            return back()->with('success', 'Titik lokasi berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal nambah lokasi: ' . $e->getMessage());
        }
    }
    public function update(Request $request, Lokasi $lokasi)
    {
        $messages = [
            'nama.required' => 'Nama lokasi wajib diisi, Bos!',
            'latitude.required' => 'Koordinat Latitude jangan kosong.',
            'latitude.numeric' => 'Latitude harus berupa angka.',
            'latitude.unique' => 'Titik koordinat ini sudah terdaftar di sistem.',
            'longitude.required' => 'Koordinat Longitude jangan kosong.',
            'longitude.numeric' => 'Longitude harus berupa angka.',
            'radius_meter.required' => 'Radius wajib diisi.',
            'radius_meter.integer' => 'Radius harus berupa angka bulat.',
            'radius_meter.min' => 'Radius minimal 5 meter.',
        ];
        $request->validate([
            'nama' => 'required|string|max:255',
            'latitude' => [
                'required',
                'numeric',
                \Illuminate\Validation\Rule::unique('lokasi', 'latitude')
                    ->ignore($lokasi->id)
                    ->where(function ($query) use ($request) {
                        return $query->where('longitude', $request->longitude);
                    }),
            ],
            'longitude' => 'required|numeric',
            'radius_meter' => 'required|integer|min:5',
        ], $messages);
        try {
            $lokasi->update($request->all());
            return back()->with('success', 'Lokasi berhasil diupdate!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal update lokasi: ' . $e->getMessage());
        }
    }
    public function destroy(Lokasi $lokasi)
    {
        $lokasi->delete();
        return back()->with('success', 'Lokasi berhasil dihapus!');
    }
}
