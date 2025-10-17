<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'stok',
        'foto', // kolom foto ikut dimasukkan
    ];

    protected $casts = [
        'tahun_terbit' => 'integer',
        'stok' => 'integer',
    ];

    // 🔗 Relasi ke QR Code (satu buku punya satu kode QR)
    public function qrCode()
    {
        return $this->hasOne(QrCode::class);
    }

    // 🔗 Relasi ke Peminjaman (satu buku bisa dipinjam banyak kali)
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
