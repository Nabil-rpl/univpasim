<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nim',
        'no_hp',
        'alamat',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi dengan Mahasiswa
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'email', 'email');
    }

    // Accessor untuk display name
    public function getDisplayNameAttribute()
    {
        if ($this->role === 'mahasiswa' && $this->mahasiswa) {
            return $this->mahasiswa->nama;
        }
        return $this->name;
    }

    // Accessor untuk NIM
    public function getNimAttribute($value)
    {
        if ($this->role === 'mahasiswa' && $this->mahasiswa) {
            return $this->mahasiswa->nim;
        }
        return $value;
    }

    // Method untuk cek role
    public function isPenggunaLuar()
    {
        return $this->role === 'pengguna_luar';
    }

    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }

    public function isPetugas()
    {
        return $this->role === 'petugas';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Relasi dengan laporan
    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'dibuat_oleh');
    }

    // Relasi dengan peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'mahasiswa_id');
    }

    public function peminjamanDiproses()
    {
        return $this->hasMany(Peminjaman::class, 'petugas_id');
    }
}