<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perpanjangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->onDelete('cascade');
            $table->date('tanggal_perpanjangan');
            $table->date('tanggal_deadline_lama');
            $table->date('tanggal_deadline_baru');
            $table->integer('durasi_tambahan')->default(3);
            $table->enum('status', ['disetujui', 'ditolak', 'menunggu'])->default('menunggu');
            $table->text('alasan')->nullable();
            $table->text('catatan_petugas')->nullable();
            $table->foreignId('diproses_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perpanjangan');
    }
};