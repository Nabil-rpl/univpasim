<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            
            // User penerima notifikasi
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            
            // Jenis notifikasi
            $table->enum('tipe', [
                'peminjaman_baru',           // Untuk petugas: ada peminjaman baru
                'peminjaman_disetujui',      // Untuk mahasiswa: peminjaman disetujui
                'peminjaman_ditolak',        // Untuk mahasiswa: peminjaman ditolak
                'perpanjangan_baru',         // Untuk petugas: ada pengajuan perpanjangan
                'perpanjangan_disetujui',    // Untuk mahasiswa: perpanjangan disetujui
                'perpanjangan_ditolak',      // Untuk mahasiswa: perpanjangan ditolak
                'reminder_deadline',         // Untuk mahasiswa: pengingat deadline (H-1, H-2)
                'terlambat',                 // Untuk mahasiswa: sudah terlambat mengembalikan
                'pengembalian_sukses',       // Untuk mahasiswa: pengembalian berhasil
                'buku_tersedia',             // Untuk mahasiswa: buku yang ditunggu tersedia
                'denda_belum_dibayar',       // Untuk mahasiswa: ada denda yang belum dibayar
                'user_baru',                 // Untuk admin: ada user baru yang mendaftar
                'buku_baru',                 // Untuk admin: ada buku baru ditambahkan
                'laporan_baru',              // Untuk admin: ada laporan baru
                'stok_menipis',              // Untuk admin/petugas: stok buku menipis
                'sistem'                      // Untuk semua: notifikasi sistem umum
            ]);
            
            // Judul dan isi notifikasi
            $table->string('judul');
            $table->text('isi');
            
            // Data terkait (JSON untuk fleksibilitas)
            // Contoh: {"peminjaman_id": 1, "buku_id": 2, "denda": 5000}
            $table->json('data')->nullable();
            
            // URL tujuan ketika notifikasi diklik (opsional)
            $table->string('url')->nullable();
            
            // Status baca
            $table->boolean('dibaca')->default(false);
            $table->timestamp('dibaca_pada')->nullable();
            
            // Prioritas notifikasi
            $table->enum('prioritas', ['rendah', 'normal', 'tinggi', 'mendesak'])
                ->default('normal');
            
            // User yang membuat notifikasi (untuk tracking)
            $table->foreignId('dibuat_oleh')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['user_id', 'dibaca']);
            $table->index(['user_id', 'created_at']);
            $table->index('tipe');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};