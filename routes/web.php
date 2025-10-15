<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\QRCodeController; // ✅ Admin QRCodeController
use App\Http\Controllers\Petugas\LaporanController;
use App\Http\Controllers\Petugas\QRCodeController as PetugasQRCodeController; // ✅ Alias supaya tidak bentrok

// ============================================
// 🏠 HALAMAN UTAMA
// ============================================
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'petugas' => redirect()->route('petugas.dashboard'),
            'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
})->name('home');

// ============================================
// 🔐 AUTH ROUTES
// ============================================
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// ============================================
// 📊 DASHBOARD ROUTES
// ============================================

// 🔹 ADMIN
Route::group(['middleware' => ['auth', 'role:admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // ✅ CRUD Users, Buku, Peminjaman
    Route::resource('users', UserController::class);
    Route::resource('bukus', BukuController::class);
    Route::resource('peminjamans', PeminjamanController::class);

    // ✅ CRUD QR Codes (khusus admin)
    Route::get('/qrcodes', [QRCodeController::class, 'index'])->name('qrcodes.index');
    Route::get('/qrcodes/create', [QRCodeController::class, 'create'])->name('qrcodes.create');
    Route::post('/qrcodes', [QRCodeController::class, 'store'])->name('qrcodes.store');
    Route::get('/qrcodes/{id}/edit', [QRCodeController::class, 'edit'])->name('qrcodes.edit');
    Route::put('/qrcodes/{id}', [QRCodeController::class, 'update'])->name('qrcodes.update');
    Route::delete('/qrcodes/{id}', [QRCodeController::class, 'destroy'])->name('qrcodes.destroy');
});

// 🔹 PETUGAS
Route::group(['middleware' => ['auth', 'role:petugas'], 'prefix' => 'petugas', 'as' => 'petugas.'], function () {
    Route::get('/dashboard', [DashboardController::class, 'petugas'])->name('dashboard');

    // ✅ CRUD Laporan
    Route::resource('laporan', LaporanController::class);

    // ✅ QR Code Management (fitur petugas)
    Route::get('/qrcode', [PetugasQRCodeController::class, 'index'])->name('qrcode.index');
    Route::get('/qrcode/generate/{type}/{id}', [PetugasQRCodeController::class, 'generate'])->name('qrcode.generate');
    Route::delete('/qrcode/{id}', [PetugasQRCodeController::class, 'destroy'])->name('qrcode.destroy');
});

// 🔹 MAHASISWA
Route::group(['middleware' => ['auth', 'role:mahasiswa'], 'prefix' => 'mahasiswa', 'as' => 'mahasiswa.'], function () {
    Route::get('/dashboard', [DashboardController::class, 'mahasiswa'])->name('dashboard');
});
