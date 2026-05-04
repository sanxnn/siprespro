<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MahasiswaExport;
use App\Http\Controllers\Controller;
use App\Models\{Golongan, Mahasiswa, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash};
use Maatwebsite\Excel\Facades\Excel;


class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        // Eager load relasi biar kenceng (N+1 avoidance)
        $query = Mahasiswa::with(['user', 'golongan', 'semester']);

        // Filter Search (Nama, NIM, Email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($qu) => $qu->where('email', 'like', "%{$search}%"));
            });
        }

        // Filter Golongan
        if ($request->filled('golongan')) {
            $query->where('golongan_id', $request->golongan);
        }

        // Filter Angkatan
        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }

        // Statistik Mahasiswa
        $stats = [
            'total' => Mahasiswa::count(),
            'per_golongan' => Golongan::withCount('mahasiswas')->get(),
            'new_this_month' => Mahasiswa::whereMonth('created_at', now()->month)->count(),
        ];

        $mahasiswas = $query->latest()->paginate(10)->withQueryString();
        $golongans = Golongan::all(); // Untuk dropdown filter

        return view('dashboard.admin.mahasiswa', compact('mahasiswas', 'stats', 'golongans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|unique:mahasiswa,nim',
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'angkatan' => 'required|numeric',
            'semester_id' => 'nullable|exists:semester,id',
            'golongan_id' => 'nullable|exists:golongan,id',
            'nik' => 'nullable|numeric',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Akun Login
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->nim),
                'role' => 'mahasiswa',
                'is_active' => true,
            ]);

            // 2. Profil Lengkap
            $mhs = Mahasiswa::create($request->all());

            // 3. Link
            $user->update(['mahasiswa_id' => $mhs->id]);
        });

        return back()->with('success', 'Mahasiswa ' . $request->nama . ' berhasil didaftarkan!');
    }


    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'nama' => 'required',
            'email' => "required|email|unique:users,email,{$mahasiswa->user->id}",
        ]);

        DB::transaction(function () use ($request, $mahasiswa) {
            $mahasiswa->update($request->only(['nama', 'angkatan', 'semester_id']));
            $mahasiswa->user->update(['email' => $request->email]);
        });

        return back()->with('success', 'Data Mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        DB::transaction(function () use ($mahasiswa) {
            $mahasiswa->user->delete(); // Hapus user otomatis hapus profile (jika ada cascade)
            $mahasiswa->delete();
        });
        return back()->with('success', 'Data Mahasiswa berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new MahasiswaExport($request->role), 'mahasiswa-siprespro-' . date('Y-m-d') . '.xlsx');
    }
}