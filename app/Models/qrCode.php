<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QRCode extends Model
{
    use HasFactory;

    // ✅ Tentukan nama tabel manual
    protected $table = 'qr_codes';

    protected $fillable = [
        'kode_unik',
        'gambar_qr',
        'user_id',
        'buku_id',
    ];

    // Relasi ke user (petugas)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke buku
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
