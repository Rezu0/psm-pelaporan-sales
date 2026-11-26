<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanBagikanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporanSayaController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SegmenController;
use App\Http\Controllers\SemuaLaporanController;
use Illuminate\Support\Facades\Route;

Route::get('/bagikan/laporan/{id}', LaporanBagikanController::class)
    ->middleware('signed')
    ->name('laporan.bagikan');

// HALAMAN AWAL
Route::get('/', fn () => redirect()->route('login'));

// TAMU (belum login): login, register, Google
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/register/google', [AuthController::class, 'showRegisterGoogle'])->name('register.google');
    Route::post('/register/google', [AuthController::class, 'registerGoogle']);

    Route::get('/auth/google', [AuthController::class, 'googleRedirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [AuthController::class, 'googleCallback']);
});


// SEMUA PERAN (sales, owner, admin), asal akun masih aktif
Route::middleware(['auth', 'peran'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profil
    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');

    // Edit dan hapus laporan (sales hanya laporannya sendiri, dicek di controller)
    Route::get('/laporan/{id}/edit', [LaporanController::class, 'edit'])->whereNumber('id')->name('laporan.edit');
    Route::put('/laporan/{id}', [LaporanController::class, 'update'])->whereNumber('id')->name('laporan.update');
    Route::delete('/laporan/{id}', [LaporanController::class, 'destroy'])->whereNumber('id')->name('laporan.destroy');
});

// SALES SAJA
Route::middleware(['auth', 'peran:sales'])->group(function () {
    Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');
    Route::get('/laporan-saya', [LaporanSayaController::class, 'index'])->name('laporan.saya');

    Route::get('/laporan/create', [LaporanController::class, 'create'])->name('laporan.create');
    Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
});

// ADMIN DAN OWNER (digabung)
Route::middleware(['auth', 'peran:admin,owner'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/semua-laporan', [SemuaLaporanController::class, 'index'])->name('semua-laporan.index');
    Route::get('/semua-laporan/{bulan}', [SemuaLaporanController::class, 'bulan'])
        ->where('bulan', '[0-9]{4}-(0[1-9]|1[0-2])')
        ->name('semua-laporan.bulan');
});


// OWNER SAJA
Route::middleware(['auth', 'peran:owner'])->group(function () {
    // Kelola akun
    Route::get('/kelola-akun', [AkunController::class, 'index'])->name('akun.index');
    Route::get('/kelola-akun/{uuid}/edit', [AkunController::class, 'edit'])->name('akun.edit');
    Route::put('/kelola-akun/{uuid}', [AkunController::class, 'update'])->name('akun.update');
    Route::patch('/kelola-akun/{uuid}/status', [AkunController::class, 'ubahStatus'])->name('akun.status');
});

// ADMIN SAJA
Route::middleware(['auth', 'peran:admin'])->group(function () {
    // Kelola segmen
    Route::get('/kelola-segmen', [SegmenController::class, 'index'])->name('segmen.index');
    Route::post('/kelola-segmen', [SegmenController::class, 'store'])->name('segmen.store');
    Route::put('/kelola-segmen/{id}', [SegmenController::class, 'update'])->name('segmen.update');
    Route::delete('/kelola-segmen/{id}', [SegmenController::class, 'destroy'])->name('segmen.destroy');
});

