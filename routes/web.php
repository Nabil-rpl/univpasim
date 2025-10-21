<?php

use Illuminate\Support\Facades\Route;

// Controllers Utama
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Admin
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\QRCodeController;
use App\Http\Controllers\Admin\MahasiswaController;

// Petugas
use App\Http\Controllers\Petugas\LaporanController;
use App\Http\Controllers\Petugas\PetugasController;
use App\Http\Controllers\Petugas\QRCodeController as PetugasQRCodeController;
use App\Http\Controllers\Petugas\BukuController as PetugasBukuController;
use App\Http\Controllers\Petugas\PeminjamanController as PetugasPeminjamanController;
use App\Http\Controllers\Petugas\PengembalianController; // ✅ Tambahkan ini

// Mahasiswa
use App\Http\Controllers\Mahasiswa\MahasiswaController as MahasiswaUserController;
use App\Http\Controllers\Mahasiswa\BukuController as MahasiswaBukuController;
use App\Http\Controllers\Mahasiswa\PeminjamanController as MahasiswaPeminjamanController;
use App\Http\Controllers\Mahasiswa\RiwayatController as MahasiswaRiwayatController;
use App\Http\Controllers\Mahasiswa\QRScannerController;


// ============================================
// 🏠 HALAMAN UTAMA
// ============================================
Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
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
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        // CRUD User
        Route::resource('users', UserController::class);

        // CRUD Mahasiswa
        Route::resource('mahasiswa', MahasiswaController::class);
        Route::get('/mahasiswa/export', [MahasiswaController::class, 'export'])->name('mahasiswa.export');

        // CRUD Buku
        Route::resource('buku', BukuController::class);

        // CRUD Peminjaman
        Route::controller(PeminjamanController::class)->group(function () {
            Route::get('/peminjaman', 'index')->name('peminjaman.index');
            Route::post('/peminjaman', 'store')->name('peminjaman.store');
            Route::put('/peminjaman/{id}', 'update')->name('peminjaman.update');
            Route::delete('/peminjaman/{id}', 'destroy')->name('peminjaman.destroy');
        });

        // QRCode
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
Route::middleware(['auth', 'role:petugas'])
    ->prefix('petugas')
    ->as('petugas.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [PetugasController::class, 'index'])->name('dashboard');

        // CRUD Buku
        Route::resource('buku', PetugasBukuController::class);

        // Regenerate QR Code
        Route::post('buku/{buku}/regenerate-qr', [PetugasBukuController::class, 'regenerateQR'])
            ->name('buku.regenerateQR');

        // Peminjaman
        Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
            Route::get('/', [PetugasPeminjamanController::class, 'index'])->name('index');
            Route::get('/create', [PetugasPeminjamanController::class, 'create'])->name('create');
            Route::post('/', [PetugasPeminjamanController::class, 'store'])->name('store');
            Route::get('/{id}', [PetugasPeminjamanController::class, 'show'])->name('show');
            Route::put('/{id}/kembalikan', [PetugasPeminjamanController::class, 'kembalikan'])->name('kembalikan');
            Route::delete('/{id}', [PetugasPeminjamanController::class, 'destroy'])->name('destroy');
        });

        // ✅ Pengembalian Routes - TAMBAHAN BARU
        Route::prefix('pengembalian')->name('pengembalian.')->group(function () {
            Route::get('/', [PengembalianController::class, 'index'])->name('index');
            Route::get('/search', [PengembalianController::class, 'search'])->name('search');
            Route::get('/riwayat', [PengembalianController::class, 'riwayat'])->name('riwayat');
            Route::get('/{peminjaman_id}', [PengembalianController::class, 'show'])->name('show');
            Route::post('/{peminjaman_id}', [PengembalianController::class, 'store'])->name('store');
        });

        // Laporan
        Route::resource('laporan', LaporanController::class);

        // QR Code
        Route::get('/qrcode', [PetugasQRCodeController::class, 'index'])->name('qrcode.index');
        Route::get('/qrcode/generate/{type}/{id}', [PetugasQRCodeController::class, 'generate'])->name('qrcode.generate');
        Route::delete('/qrcode/{id}', [PetugasQRCodeController::class, 'destroy'])->name('qrcode.destroy');
    });


// ============================================
// 🎓 MAHASISWA
// ============================================
Route::middleware(['auth', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->as('mahasiswa.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [MahasiswaUserController::class, 'index'])->name('dashboard');

        // Buku
        Route::get('/buku', [MahasiswaBukuController::class, 'index'])->name('buku.index');
        Route::get('/buku/{id}', [MahasiswaBukuController::class, 'show'])->name('buku.show');
        Route::post('/buku/{id}/pinjam', [MahasiswaPeminjamanController::class, 'pinjam'])->name('buku.pinjam');

        // Peminjaman
        Route::get('/peminjaman', [MahasiswaPeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/riwayat', [MahasiswaPeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');
        Route::get('/peminjaman/{id}', [MahasiswaPeminjamanController::class, 'show'])->name('peminjaman.show');

        // Riwayat (jika ingin route terpisah)
        Route::get('/riwayat', [MahasiswaRiwayatController::class, 'index'])->name('riwayat.index');

        // QR Scanner Routes
        Route::get('/qr-scanner', [QRScannerController::class, 'index'])->name('qr.scanner');
        Route::post('/qr-scanner/preview', [QRScannerController::class, 'preview'])->name('qr.preview');
        Route::post('/qr-scanner/process', [QRScannerController::class, 'process'])->name('qr.process');

        // Pengaturan Mahasiswa
        Route::get('/pengaturan', [\App\Http\Controllers\Mahasiswa\PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::post('/pengaturan/update', [\App\Http\Controllers\Mahasiswa\PengaturanController::class, 'update'])->name('pengaturan.update');
    });
