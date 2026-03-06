<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // 🎯 Redirect berdasarkan role user
            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Login berhasil! Selamat datang, Admin.');
            }

            if ($user->isDosen()) {
                return redirect()->route('dosen.dashboard')->with('success', 'Login berhasil! Selamat datang, Dosen.');
            }

            if ($user->isMahasiswa()) {
                return redirect()->route('mahasiswa.dashboard')->with('success', 'Login berhasil! Selamat datang, Mahasiswa.');
            }

            // Fallback kalau role nggak dikenali (security)
            Auth::logout();
            return back()->withErrors(['email' => 'Role user tidak valid. Hubungi admin.']);
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ✅ Redirect ke ROOT (/) karena login page lu di situ
        return redirect('/')->with('success', 'Anda telah logout.');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Link reset password telah dikirim ke email Anda.')
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, $token = null)
    {

        if (!$token) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Link reset password tidak valid atau telah kedaluwarsa.']);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}