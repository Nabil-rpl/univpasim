<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'mahasiswa_id',
        'buku_id',
        'petugas_id',
        'tanggal_pinjam',
        'durasi_hari',
        'tanggal_deadline',
        'tanggal_kembali',
        'status',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_deadline' => 'datetime',
        'tanggal_kembali' => 'datetime',
    ];
    

    // Relasi
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'peminjaman_id');
    }

    // Helper: Cek apakah terlambat
    public function isTerlambat()
    {
        if ($this->status === 'dikembalikan') {
            return false;
        }
        
        return $this->tanggal_deadline && Carbon::now()->isAfter($this->tanggal_deadline);
    }

    // Helper: Hitung hari keterlambatan
    public function getHariTerlambat()
    {
        if (!$this->isTerlambat()) {
            return 0;
        }

        return Carbon::now()->diffInDays($this->tanggal_deadline);
    }

    // Helper: Hitung denda (Rp 5.000 per hari)
    public function hitungDenda()
    {
        $hariTerlambat = $this->getHariTerlambat();
        $dendaPerHari = 5000;
        
        return $hariTerlambat * $dendaPerHari;
    }
}