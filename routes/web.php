<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Dosen;
use App\Http\Controllers\Dosen\DashboardController;
use App\Http\Controllers\Dosen\KelasController;
use App\Http\Controllers\Dosen\RekapController;
use App\Http\Controllers\Mahasiswa\PresensiController;
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

        // dashboard
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // manajemen user
        Route::get('users/export/excel', [Admin\UserController::class, 'exportExcel'])->name('users.export.excel');
        Route::resource('users', Admin\UserController::class);
        Route::get('mahasiswa/export/excel', [Admin\MahasiswaController::class, 'exportExcel'])->name('mahasiswa.export.excel');
        Route::resource('mahasiswa', Admin\MahasiswaController::class);
        Route::get('dosen/export/excel', [Admin\DosenController::class, 'exportExcel'])->name('dosen.export.excel');
        Route::resource('dosen', Admin\DosenController::class);

        // master akademik
        Route::post('semester/{semester}/set-aktif', [Admin\SemesterController::class, 'setAktif'])->name('semester.set-aktif');
        Route::resource('semester', Admin\SemesterController::class);
        Route::resource('golongan', Admin\GolonganController::class);
        Route::resource('mata-kuliah', Admin\MataKuliahController::class)->parameters([
            'mata-kuliah' => 'matkul'
        ]);
        Route::resource('ruang', Admin\RuangController::class);
        Route::resource('lokasi', Admin\LokasiController::class);

        // assign kelas perkuliahan
        Route::resource('kelas-perkuliahan', Admin\KelasPerkuliahanController::class)
            ->parameters(['kelas-perkuliahan' => 'kela']);

        // presensi
        Route::resource('presensi', Admin\PresensiController::class);

    });

    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function () {

        // dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('kelas', KelasController::class);
        Route::post('/kelas/{kela}/pertemuan', [KelasController::class, 'storePertemuan'])->name('kelas.pertemuan.store');
        Route::patch('/pertemuan/{pertemuan}', [KelasController::class, 'updatePertemuan'])->name('pertemuan.update');
        Route::delete('/pertemuan/{pertemuan}', [KelasController::class, 'destroyPertemuan'])->name('pertemuan.destroy');
        Route::patch('/pertemuan/{pertemuan}/toggle', [KelasController::class, 'togglePertemuan'])->name('pertemuan.toggle');
        Route::get('/pertemuan/{pertemuan}', [KelasController::class, 'showPertemuan'])->name('pertemuan.show');
        Route::get('/dosen/pertemuan/{pertemuan_id}/export-excel', [KelasController::class, 'exportExcel'])
            ->name('pertemuan.export-excel');

        Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');

    });

    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Mahasiswa\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');

        Route::get('/presensi/show/{pertemuan_id}', [PresensiController::class, 'isiPresensi'])->name('presensi.show');
        Route::post('/presensi/simpan/{pertemuan_id}', [PresensiController::class, 'simpanPresensi'])->name('presensi.simpan');

        Route::get('/presensi/riwayat', [PresensiController::class, 'riwayat'])->name('presensi.riwayat');
        Route::get('/presensi/riwayat/{kelas_id}', [PresensiController::class, 'detailRiwayat'])->name('presensi.detail-riwayat');
    });
});