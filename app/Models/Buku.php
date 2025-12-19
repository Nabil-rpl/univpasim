<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'kode_buku',      // ✅ Tambahkan ini
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'kategori',
        'stok',
        'foto',
    ];

    /**
     * Relasi ke Peminjaman
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }

    /**
     * Relasi ke QR Code
     */
    public function qrCode()
    {
        return $this->hasOne(QRCode::class, 'buku_id');
    }

    /**
     * Accessor untuk mendapatkan kode buku
     * Ambil dari relasi qrCode.kode_unik
     */
    public function getKodeBukuAttribute($value)
    {
        // Jika kode_buku ada langsung di tabel buku, return itu
        if (!empty($value)) {
            return $value;
        }

        // Ambil dari relasi qrCode.kode_unik
        if ($this->relationLoaded('qrCode') && $this->qrCode) {
            return $this->qrCode->kode_unik ?? null;
        }

        // Jika relasi belum di-load, load dulu
        if ($this->qrCode()->exists()) {
            return $this->qrCode->kode_unik ?? null;
        }

        return null;
    }
}