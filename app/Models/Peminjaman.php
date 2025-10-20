<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'mahasiswa_id',
        'buku_id',
        'petugas_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
    ];

    // Relasi ke mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    // Relasi ke buku
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    // Relasi ke petugas (user)
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}