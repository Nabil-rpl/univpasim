<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('nim')->nullable()->unique();
            $table->string('no_hp')->nullable(); // Tambahan untuk pengguna luar
            $table->text('alamat')->nullable(); // Tambahan untuk pengguna luar
            $table->string('password');
            $table->enum('role', ['admin', 'petugas', 'mahasiswa', 'pengguna_luar'])->default('mahasiswa');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};