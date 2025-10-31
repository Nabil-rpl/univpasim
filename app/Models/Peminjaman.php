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

    /**
     * Relasi ke User (sebagai peminjam - mahasiswa atau pengguna_luar)
     */
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    /**
     * Relasi ke Buku
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    /**
     * Relasi ke User (sebagai petugas)
     */
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    /**
     * Cek apakah peminjaman terlambat
     */
    public function isTerlambat()
    {
        if ($this->status == 'dikembalikan') {
            return false;
        }

        if (!$this->tanggal_deadline) {
            return false;
        }

        return Carbon::now()->greaterThan($this->tanggal_deadline);
    }

    /**
     * Hitung berapa hari terlambat
     */
    public function getHariTerlambat()
    {
        if (!$this->isTerlambat()) {
            return 0;
        }

        return Carbon::now()->diffInDays($this->tanggal_deadline);
    }

    /**
     * Hitung denda keterlambatan
     * Misalnya: Rp 2.000 per hari
     */
    public function hitungDenda()
    {
        $hariTerlambat = $this->getHariTerlambat();
        $dendaPerHari = 2000; // Sesuaikan dengan kebijakan perpustakaan

        return $hariTerlambat * $dendaPerHari;
    }

    /**
     * Scope untuk peminjaman yang sedang dipinjam
     */
    public function scopeDipinjam($query)
    {
        return $query->where('status', 'dipinjam');
    }

    /**
     * Scope untuk peminjaman yang sudah dikembalikan
     */
    public function scopeDikembalikan($query)
    {
        return $query->where('status', 'dikembalikan');
    }

    /**
     * Scope untuk peminjaman yang terlambat
     */
    public function scopeTerlambat($query)
    {
        return $query->where('status', 'dipinjam')
            ->whereNotNull('tanggal_deadline')
            ->whereDate('tanggal_deadline', '<', now());
    }
}