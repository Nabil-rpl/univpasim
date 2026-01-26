<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian';

    protected $fillable = [
        'peminjaman_id',
        'petugas_id',
        'tanggal_pengembalian',
        'denda',
        'denda_dibayar',
        'denda_dibayar_pada',
        'catatan_pembayaran',
    ];

    protected $casts = [
        'tanggal_pengembalian' => 'date',
        'denda_dibayar' => 'boolean',
        'denda_dibayar_pada' => 'datetime',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}