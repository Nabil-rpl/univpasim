<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // ✅ Set timezone untuk migration ini
        DB::statement("SET time_zone = '+07:00'");
        
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade');
            $table->foreignId('petugas_id')->nullable()->constrained('users')->onDelete('set null');
            
            // ✅ PERBAIKAN: Gunakan dateTime agar jam tersimpan
            $table->dateTime('tanggal_pinjam');
            $table->integer('durasi_hari')->default(3);
            $table->dateTime('tanggal_deadline')->nullable();
            $table->dateTime('tanggal_kembali')->nullable();
            
            $table->enum('status', ['dipinjam', 'dikembalikan'])->default('dipinjam');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('peminjaman');
    }
};