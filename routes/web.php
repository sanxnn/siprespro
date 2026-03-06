<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// =====================
// GUEST ROUTES (Belum Login)
// =====================
// ✅ PAKAI CUSTOM MIDDLEWARE SAJA (JANGAN 'guest'!)
Route::middleware('redirect.if.auth.role')->group(function () {

    // Login page di ROOT (/)
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'authenticate'])->name('authenticate');

    // Forgot & Reset Password
    Route::prefix('forgot-password')->group(function () {
        Route::get('/', [AuthController::class, 'forgotPassword'])->name('password.request');
        Route::post('/', [AuthController::class, 'sendResetLink'])->name('password.email');
    });

    Route::prefix('reset-password')->group(function () {
        Route::get('/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
        Route::post('/', [AuthController::class, 'updatePassword'])->name('password.update');
    });
});

// =====================
// AUTH ROUTES (Sudah Login)
// =====================
// ✅ PAKAI 'auth' MIDDLEWARE BAWAAN LARAVEL
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ADMIN DASHBOARD
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', fn() => view('dashboard.admin.index'))->name('dashboard');
    });

    // DOSEN DASHBOARD
    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', fn() => view('dashboard.dosen.index'))->name('dashboard');
    });

    // MAHASISWA DASHBOARD
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', fn() => view('dashboard.mahasiswa.index'))->name('dashboard');
    });
});