<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Golongan;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class GolonganController extends Controller
{
    public function index()
    {
        $golongans = Golongan::with('semester')
            ->withCount('mahasiswas')
            ->latest()
            ->paginate(10);
        $semesters = Semester::all();
        return view('dashboard.admin.golongan', compact('golongans', 'semesters'));
    }
    public function store(Request $request)
    {
        $messages = [
            'nama.required' => 'Nama golongan tidak boleh kosong.',
            'nama.max' => 'Nama golongan maksimal 50 karakter.',
            'nama.unique' => 'Golongan ini sudah ada di semester tersebut!',
            'semester_id.required' => 'Semester wajib dipilih.',
        ];
        $request->validate([
            'nama' => [
                'required',
                'string',
                'max:50',
                Rule::unique('golongan', 'nama')->where(function ($query) use ($request) {
                    return $query->where('semester_id', $request->semester_id);
                }),
            ],
            'semester_id' => 'required|exists:semester,id',
        ], $messages);
        try {
            Golongan::create($request->all());
            return back()->with('success', "Golongan {$request->nama} berhasil ditambahkan!");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan golongan: ' . $e->getMessage());
        }
    }
    public function update(Request $request, Golongan $golongan)
    {
        $messages = [
            'nama.required' => 'Nama golongan tidak boleh kosong.',
            'nama.unique' => 'Golongan ini sudah ada di semester tersebut!',
            'semester_id.required' => 'Semester wajib dipilih.',
            'semester_id.exists' => 'Semester tidak ditemukan.',
        ];
        $request->validate([
            'nama' => [
                'required',
                'string',
                'max:50',
                Rule::unique('golongan', 'nama')
                    ->where(function ($query) use ($request) {
                        return $query->where('semester_id', $request->semester_id);
                    })
                    ->ignore($golongan->id),
            ],
            'semester_id' => 'required|exists:semester,id',
        ], $messages);
        try {
            $golongan->update($request->all());
            return back()->with('success', "Data golongan {$request->nama} berhasil diupdate!");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }
    }
    public function destroy(Golongan $golongan)
    {
        if ($golongan->mahasiswas()->exists()) {
            return back()->with('error', 'Gagal hapus! Masih ada mahasiswa di golongan ini.');
        }
        $golongan->delete();
        return back()->with('success', 'Golongan berhasil dihapus!');
    }
}
