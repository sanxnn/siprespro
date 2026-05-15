<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        if ($user->role === 'mahasiswa') {
            $user->load('mahasiswa.golongan', 'mahasiswa.semester');
        } elseif ($user->role === 'dosen') {
            $user->load('dosen');
        }

        return view('dashboard.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. VALIDASI DATA AKUN UTAMA (Hanya Nama, Email tidak boleh di-update)
        $rules = [
            'name' => 'required|string|max:255',
        ];

        // Validasi ganti password jika diisi
        if ($request->filled('password')) {
            $rules['current_password'] = 'required';
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        // 2. VALIDASI DATA TAMBAHAN (Tanpa NIM/NIDN/NIP karena dikunci)
        if ($user->role === 'mahasiswa') {
            $rules['angkatan'] = 'required|numeric|digits:4';
            $rules['tanggal_lahir'] = 'required|date';
            $rules['nik'] = 'required|numeric|digits:16|unique:mahasiswa,nik,' . $user->mahasiswa->id;
            $rules['no_hp'] = 'required|string|max:15|unique:mahasiswa,no_hp,' . $user->mahasiswa->id;
            $rules['alamat'] = 'required|string|max:500';
        } elseif ($user->role === 'dosen') {
            $rules['jabatan'] = 'required|string|max:100';
            $rules['tanggal_lahir'] = 'required|date';
            $rules['nik'] = 'required|numeric|digits:16|unique:dosen,nik,' . $user->dosen->id;
            $rules['no_hp'] = 'required|string|max:15|unique:dosen,no_hp,' . $user->dosen->id;
            $rules['alamat'] = 'required|string|max:500';
        }

        $request->validate($rules, [
            'password.confirmed' => 'Konfirmasi password baru kagak cocok, su!',
            'nik.digits' => 'NIK itu wajib 16 digit jancok, cek lagi.',
            'nik.unique' => 'NIK ini sudah terdaftar di sistem.',
            'no_hp.unique' => 'Nomor HP ini sudah terpakai.',
            'current_password.required' => 'Password lama wajib diisi jika ingin mengganti sandi baru.'
        ]);

        // 3. CHECK PASSWORD LAMA
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama lu salah, su!']);
            }
            $user->password = Hash::make($request->password);
        }

        // 4. TRANSAKSI DATABASE (Aman, Konsisten, Terproteksi)
        DB::transaction(function () use ($user, $request) {
            // Update tabel users (Hanya nama dan password yang boleh berubah)
            $user->name = $request->name;
            $user->save();

            // Update tabel detail profile pendukung (Sesuai role)
            if ($user->role === 'mahasiswa') {
                $user->mahasiswa->update([
                    'nama' => $request->name,
                    'angkatan' => $request->angkatan,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'nik' => $request->nik,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                ]);
            } elseif ($user->role === 'dosen') {
                $user->dosen->update([
                    'nama' => $request->name,
                    'jabatan' => $request->jabatan,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'nik' => $request->nik,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                ]);
            }
        });

        return back()->with('success', 'Profile premium lu berhasil diperbarui tanpa ngerusak data kampus, Cuk!');
    }
}