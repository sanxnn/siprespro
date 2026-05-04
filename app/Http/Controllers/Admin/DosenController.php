<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DosenExport;
use App\Http\Controllers\Controller;
use App\Models\{Dosen, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash};
use Maatwebsite\Excel\Facades\Excel;


class DosenController extends Controller
{
    public function index(Request $request)
    {
        $query = Dosen::with(['user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($qu) => $qu->where('email', 'like', "%{$search}%"));
            });
        }

        // Filter Status Akun (Active/Inactive)
        if ($request->filled('status')) {
            $query->whereHas('user', fn($qu) => $qu->where('is_active', $request->status));
        }

        $stats = [
            'total_dosen' => Dosen::count(),
            'active_dosen' => Dosen::whereHas('user', fn($q) => $q->where('is_active', true))->count(),
        ];

        $dosens = $query->latest()->paginate(10)->withQueryString();

        return view('dashboard.admin.dosen', compact('dosens', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:dosen,nip',
            'nidn' => 'nullable|unique:dosen,nidn',
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'nik' => 'nullable|numeric',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Create User Account
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->nip), // Default password = NIP
                'role' => 'dosen',
                'is_active' => true,
            ]);

            // 2. Create Dosen Profile (Semua field masuk)
            $dosen = Dosen::create($request->all());

            // 3. Link Profile to User
            $user->update(['dosen_id' => $dosen->id]);
        });

        return back()->with('success', "Dosen {$request->nama} berhasil didaftarkan!");
    }

    public function update(Request $request, Dosen $dosen)
    {
        $request->validate([
            'nama' => 'required',
            'email' => "required|email|unique:users,email,{$dosen->user->id}",
            'nip' => "required|unique:dosen,nip,{$dosen->id}",
        ]);

        DB::transaction(function () use ($request, $dosen) {
            $dosen->update($request->all());
            $dosen->user->update(['email' => $request->email]);
        });

        return back()->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen)
    {
        DB::transaction(function () use ($dosen) {
            if ($dosen->user)
                $dosen->user->delete();
            $dosen->delete();
        });
        return back()->with('success', 'Data dosen berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new DosenExport($request->role), 'dosen-siprespro-' . date('Y-m-d') . '.xlsx');
    }
}