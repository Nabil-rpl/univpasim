<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\QRCodeController;
use App\Http\Controllers\Petugas\LaporanController;
use App\Http\Controllers\Petugas\QRCodeController as PetugasQRCodeController;

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
// 📊 ADMIN
// ============================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // ✅ CRUD User & Buku
    Route::resource('users', UserController::class);
    Route::resource('buku', BukuController::class);

    // ✅ CRUD Peminjaman (hanya 1 halaman index)
    Route::controller(PeminjamanController::class)->group(function () {
        Route::get('/peminjaman', 'index')->name('peminjaman.index');
        Route::post('/peminjaman', 'store')->name('peminjaman.store');
        Route::put('/peminjaman/{id}', 'update')->name('peminjaman.update');
        Route::delete('/peminjaman/{id}', 'destroy')->name('peminjaman.destroy');
    });

    // ✅ CRUD QRCode
    Route::controller(QRCodeController::class)->group(function () {
        Route::get('/qrcodes', 'index')->name('qrcodes.index');
        Route::get('/qrcodes/create', 'create')->name('qrcodes.create');
        Route::post('/qrcodes', 'store')->name('qrcodes.store');
        Route::get('/qrcodes/{id}/edit', 'edit')->name('qrcodes.edit');
        Route::put('/qrcodes/{id}', 'update')->name('qrcodes.update');
        Route::delete('/qrcodes/{id}', 'destroy')->name('qrcodes.destroy');
    });
});

// ============================================
// 👮 PETUGAS
// ============================================
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->as('petugas.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'petugas'])->name('dashboard');
    Route::resource('laporan', LaporanController::class);

    // ✅ QR Code Petugas
    Route::get('/qrcode', [PetugasQRCodeController::class, 'index'])->name('qrcode.index');
    Route::get('/qrcode/generate/{type}/{id}', [PetugasQRCodeController::class, 'generate'])->name('qrcode.generate');
    Route::delete('/qrcode/{id}', [PetugasQRCodeController::class, 'destroy'])->name('qrcode.destroy');
});

// ============================================
// 🎓 MAHASISWA
// ============================================
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->as('mahasiswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'mahasiswa'])->name('dashboard');
});
