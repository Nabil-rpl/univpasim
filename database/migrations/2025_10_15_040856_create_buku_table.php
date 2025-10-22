<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->string('penerbit');
            $table->integer('tahun_terbit');
            $table->string('kategori')->nullable(); // kolom baru untuk kategori buku berdasarkan jurusan kuliah
            $table->integer('stok')->default(1);
            $table->string('foto')->nullable(); // kolom untuk menyimpan nama/path foto buku
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('buku');
    }
};