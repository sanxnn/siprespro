<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware('redirect.if.auth.role')->group(function () {

    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'authenticate'])->name('authenticate');

    Route::prefix('forgot-password')->group(function () {
        Route::get('/', [AuthController::class, 'forgotPassword'])->name('password.request');
        Route::post('/', [AuthController::class, 'sendResetLink'])->name('password.email');
    });

    Route::prefix('reset-password')->group(function () {
        Route::get('/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
        Route::post('/', [AuthController::class, 'updatePassword'])->name('password.update');
    });
});


Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('mahasiswa', Admin\MahasiswaController::class);
        Route::resource('dosen', Admin\DosenController::class);
        Route::get('users/export/excel', [Admin\UserController::class, 'exportExcel'])->name('users.export.excel');
        Route::resource('users', Admin\UserController::class);
    });

    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', fn() => view('dashboard.dosen.index'))->name('dashboard');
    });

    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', fn() => view('dashboard.mahasiswa.index'))->name('dashboard');
    });
});