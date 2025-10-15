<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->string('kode_unik')->unique();
            $table->string('gambar_qr')->nullable();

            // Relasi ke user (petugas pembuat QR)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Relasi opsional ke buku
            $table->foreignId('buku_id')->nullable()->constrained('buku')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('qr_codes');
    }
};
