<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'nim',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+ auto hash password
    ];

    /**
     * Scope untuk filter berdasarkan role
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopePetugas($query)
    {
        return $query->where('role', 'petugas');
    }

    public function scopeMahasiswa($query)
    {
        return $query->where('role', 'mahasiswa');
    }

    /**
     * Check apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check apakah user adalah petugas
     */
    public function isPetugas()
    {
        return $this->role === 'petugas';
    }

    /**
     * Check apakah user adalah mahasiswa
     */
    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }

    /**
     * Get display name untuk user
     * Mahasiswa: Nama (NIM)
     * Lainnya: Nama
     */
    public function getDisplayNameAttribute()
    {
        if ($this->isMahasiswa() && $this->nim) {
            return "{$this->name} ({$this->nim})";
        }
        return $this->name;
    }

    /**
     * Get identifier untuk login
     * Mahasiswa: NIM atau Email
     * Lainnya: Email
     */
    public function getLoginIdentifierAttribute()
    {
        if ($this->isMahasiswa() && $this->nim) {
            return $this->nim;
        }
        return $this->email;
    }

    /**
     * Relasi ke Peminjaman (jika ada)
     * Uncomment jika sudah ada model Peminjaman
     */
    // public function peminjamans()
    // {
    //     return $this->hasMany(Peminjaman::class);
    // }

    /**
     * Get role badge color
     */
    public function getRoleBadgeAttribute()
    {
        return match($this->role) {
            'admin' => 'success',
            'petugas' => 'info',
            'mahasiswa' => 'warning',
            default => 'secondary'
        };
    }

    /**
     * Get role label in Indonesian
     */
    public function getRoleLabelAttribute()
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'petugas' => 'Petugas',
            'mahasiswa' => 'Mahasiswa',
            default => 'Unknown'
        };
    }
}