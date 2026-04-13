<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['mahasiswa', 'dosen'])->whereNotIn('role', ['admin']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhereHas('mahasiswa', fn($qm) => $qm->where('nama', 'like', "%{$search}%")->orWhere('nim', 'like', "%{$search}%"))
                    ->orWhereHas('dosen', fn($qd) => $qd->where('nama', 'like', "%{$search}%")->orWhere('nip', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('role'))
            $query->where('role', $request->role);
        if ($request->filled('status'))
            $query->where('is_active', $request->status);

        // Hitung Stats
        $stats = [
            'mahasiswa' => User::where('role', 'mahasiswa')->count(),
            'dosen' => User::where('role', 'dosen')->count(),
            'active' => User::whereNotIn('role', ['admin'])->where('is_active', true)->count(),
            'inactive' => User::whereNotIn('role', ['admin'])->where('is_active', false)->count(),
            'new_mahasiswa' => User::where('role', 'mahasiswa')->whereDate('created_at', '>=', now()->subDays(30))->count(),
        ];

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('dashboard.admin.users', compact('users', 'stats'));
    }

    public function update(Request $request, User $user)
    {
        // Validasi status harus 0 atau 1
        $request->validate([
            'is_active' => 'required|in:0,1',
        ]);

        try {
            $user->update([
                'is_active' => $request->is_active
            ]);

            $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
            
            return back()->with('success', "Akses akun {$user->email} berhasil {$statusText}!");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update status: ' . $e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new UsersExport($request->role), 'users-siprespro-' . date('Y-m-d') . '.xlsx');
    }
}