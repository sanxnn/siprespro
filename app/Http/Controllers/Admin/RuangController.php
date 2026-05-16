<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Ruang;
use Illuminate\Http\Request;
class RuangController extends Controller
{
    public function index()
    {
        $ruangs = Ruang::latest()->paginate(10);
        return view('dashboard.admin.ruang', compact('ruangs'));
    }
    public function store(Request $request)
    {
        $messages = [
            'nama.required' => 'Nama ruang jangan dikosongin, Bos!',
            'nama.unique' => 'Ruangan ini sudah ada di gedung tersebut, cari nama lain.',
            'kapasitas.required' => 'Kapasitas harus diisi.',
            'kapasitas.integer' => 'Kapasitas harus berupa angka bulat.',
            'kapasitas.min' => 'Kapasitas minimal 1 orang.',
            'gedung.required' => 'Gedung wajib diisi.',
        ];
        $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('ruang', 'nama')->where(function ($query) use ($request) {
                    return $query->where('gedung', $request->gedung);
                }),
            ],
            'kapasitas' => 'required|integer|min:1',
            'gedung' => 'required|string|max:255',
        ], $messages);
        try {
            Ruang::create($request->all());
            return back()->with('success', 'Ruang baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal nambah ruang: ' . $e->getMessage());
        }
    }
    public function update(Request $request, Ruang $ruang)
    {
        $messages = [
            'nama.required' => 'Nama ruang jangan dikosongin, Bos!',
            'nama.unique' => 'Nama ruangan ini sudah ada di gedung tersebut.',
            'kapasitas.required' => 'Kapasitas harus diisi.',
            'kapasitas.integer' => 'Kapasitas harus berupa angka.',
            'kapasitas.min' => 'Kapasitas minimal 1.',
            'gedung.required' => 'Gedung wajib diisi.',
        ];
        $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('ruang', 'nama')
                    ->ignore($ruang->id)
                    ->where(function ($query) use ($request) {
                        return $query->where('gedung', $request->gedung);
                    }),
            ],
            'kapasitas' => 'required|integer|min:1',
            'gedung' => 'required|string|max:255',
        ], $messages);
        try {
            $ruang->update($request->all());
            return back()->with('success', 'Data ruang berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }
    public function destroy(Ruang $ruang)
    {
        if ($ruang->kelasPerkuliahan()->exists()) {
            return back()->with('error', 'Gagal hapus! Ruangan ini masih digunakan dalam jadwal kelas.');
        }
        $ruang->delete();
        return back()->with('success', 'Ruangan berhasil dihapus!');
    }
}
