<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticatedByRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ✅ Kalau user SUDAH login → redirect ke dashboard sesuai role
        if (Auth::check()) {
            $user = Auth::user();

            $redirectUrl = match ($user->role) {
                'admin' => '/admin/dashboard',
                'dosen' => '/dosen/dashboard',
                'mahasiswa' => '/mahasiswa/dashboard',
                default => '/', // fallback ke login kalau role nggak dikenali
            };

            return redirect($redirectUrl);
        }

        // ✅ Kalau user BELUM login → lanjut ke halaman yang diminta (/)
        return $next($request);
    }
}
