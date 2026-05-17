<?php
namespace App\Http\Controllers\Admin;
use App\Exports\MahasiswaExport;
use App\Http\Controllers\Controller;
use App\Models\{Golongan, Mahasiswa, Semester, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash};
use Maatwebsite\Excel\Facades\Excel;
class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::with(['user', 'golongan', 'semester']);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($qu) => $qu->where('email', 'like', "%{$search}%"));
            });
        }
        if ($request->filled('golongan')) {
            $query->where('golongan_id', $request->golongan);
        }
        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }
        $stats = [
            'total' => Mahasiswa::count(),
            'per_golongan' => Golongan::withCount('mahasiswas')->get(),
            'new_this_month' => Mahasiswa::whereMonth('created_at', now()->month)->count(),
        ];
        $mahasiswas = $query->latest()->paginate(10)->withQueryString();
        $semesterAktif = Semester::where('status', 'aktif')->first();
        $golongans = [];
        if ($semesterAktif) {
            $golongans = Golongan::where('semester_id', $semesterAktif->id)->get();
        }
        return view('dashboard.admin.mahasiswa', compact('mahasiswas', 'stats', 'golongans'));
    }
    public function store(Request $request)
    {
        $generatedEmail = strtolower($request->nim) . '@student.polije.ac.id';
        $request->merge(['email' => $generatedEmail]);
        $messages = [
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'nama.required' => 'Nama mahasiswa tidak boleh kosong.',
            'email.unique' => 'Email (NIM) sudah terdaftar dalam sistem.',
            'angkatan.required' => 'Tahun angkatan wajib diisi.',
            'angkatan.numeric' => 'Angkatan harus berupa angka tahun.',
            'golongan_id.exists' => 'Golongan tidak valid.',
        ];
        $request->validate([
            'nim' => 'required|unique:mahasiswa,nim',
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'angkatan' => 'required|numeric',
            'semester_id' => 'nullable|exists:semester,id',
            'golongan_id' => 'nullable|exists:golongan,id',
            'nik' => 'nullable|numeric',
        ], $messages);
        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'email' => $request->email,
                    'password' => Hash::make($request->nim),
                    'role' => 'mahasiswa',
                    'is_active' => true,
                ]);
                $mhs = Mahasiswa::create($request->all());
                $user->update(['mahasiswa_id' => $mhs->id]);
            });
            return back()->with('success', "Mahasiswa {$request->nama} berhasil didaftarkan!");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mendaftarkan mahasiswa: ' . $e->getMessage());
        }
    }
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $generatedEmail = strtolower($request->nim ?? $mahasiswa->nim) . '@student.polije.ac.id';
        $request->merge(['email' => $generatedEmail]);
        $messages = [
            'nama.required' => 'Nama mahasiswa tidak boleh kosong.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM ini sudah digunakan mahasiswa lain.',
            'email.unique' => 'Email (NIM) sudah terdaftar di sistem.',
        ];
        $request->validate([
            'nim' => "required|unique:mahasiswa,nim,{$mahasiswa->id}",
            'nama' => 'required',
            'email' => "required|email|unique:users,email,{$mahasiswa->user->id}",
            'angkatan' => 'required|numeric',
        ], $messages);
        try {
            DB::transaction(function () use ($request, $mahasiswa) {
                $mahasiswa->update($request->only([
                    'nim',
                    'nama',
                    'angkatan',
                    'semester_id',
                    'golongan_id',
                    'nik',
                    'no_hp',
                    'alamat'
                ]));
                $mahasiswa->user->update([
                    'email' => $request->email,
                ]);
            });
            return back()->with('success', "Data mahasiswa {$mahasiswa->nama} berhasil diperbarui.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }
    public function destroy(Mahasiswa $mahasiswa)
    {
        try {
            DB::transaction(function () use ($mahasiswa) {
                if ($mahasiswa->user) {
                    $mahasiswa->user->delete();
                }
                $mahasiswa->delete();
            });
            return back()->with('success', "Data mahasiswa {$mahasiswa->nama} berhasil dihapus.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
    public function exportExcel(Request $request)
    {
        return Excel::download(new MahasiswaExport($request->role), 'mahasiswa-siprespro-' . date('Y-m-d') . '.xlsx');
    }
}