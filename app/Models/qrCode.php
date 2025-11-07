<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QRCode extends Model
{
    use HasFactory;

    protected $table = 'qr_codes';

    protected $fillable = [
        'kode_unik',
        'gambar_qr',
        'dibuat_oleh',
        'buku_id',
    ];

    /**
     * Relasi dengan Buku
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    /**
     * Relasi dengan User (Petugas yang membuat QR Code)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Relasi dengan User sebagai Petugas (alias untuk user)
     */
    public function petugas()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}