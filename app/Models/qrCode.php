<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QRCode extends Model
{
    use HasFactory;

    protected $table = 'qr_codes'; // ✅ Ubah dari 'qrcodes' ke 'qr_codes'

    protected $fillable = [
        'kode_unik',
        'gambar_qr',
        'dibuat_oleh',
        'buku_id',
    ];

    /**
     * Relasi ke Buku
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    /**
     * Relasi ke User (pembuat QR Code)
     */
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}