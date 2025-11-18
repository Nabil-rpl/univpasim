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
     * Relasi ke Pengembalian
     */
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }

    /**
     * Relasi ke Perpanjangan
     */
    public function perpanjangan()
    {
        return $this->hasMany(Perpanjangan::class);
    }

    /**
     * Cek apakah sudah pernah mengajukan perpanjangan yang menunggu
     */
    public function hasPerpanjanganMenunggu()
    {
        return $this->perpanjangan()->where('status', 'menunggu')->exists();
    }

    /**
     * Cek apakah bisa diperpanjang
     * Syarat: 
     * - status dipinjam
     * - belum terlambat (tidak dikenakan denda)
     * - belum ada perpanjangan menunggu
     * - max 1x perpanjang yang disetujui
     */
    public function bisakahDiperpanjang()
    {
        // Cek status harus dipinjam
        if ($this->status !== 'dipinjam') {
            return false;
        }

        // ✅ TAMBAHAN: Cek apakah sudah terlambat (melewati deadline)
        if ($this->isTerlambat()) {
            return false;
        }

        // Cek apakah ada perpanjangan yang masih menunggu
        if ($this->hasPerpanjanganMenunggu()) {
            return false;
        }

        // Maksimal 1x perpanjangan yang disetujui
        $jumlahPerpanjanganDisetujui = $this->perpanjangan()->where('status', 'disetujui')->count();
        if ($jumlahPerpanjanganDisetujui >= 1) {
            return false;
        }

        return true;
    }

    /**
     * Dapatkan alasan mengapa tidak bisa diperpanjang
     */
    public function alasanTidakBisaDiperpanjang()
    {
        if ($this->status !== 'dipinjam') {
            return 'Buku sudah dikembalikan';
        }

        if ($this->isTerlambat()) {
            return 'Peminjaman sudah melewati batas waktu dan dikenakan denda. Harap segera mengembalikan buku.';
        }

        if ($this->hasPerpanjanganMenunggu()) {
            return 'Masih ada pengajuan perpanjangan yang menunggu persetujuan';
        }

        $jumlahPerpanjanganDisetujui = $this->perpanjangan()->where('status', 'disetujui')->count();
        if ($jumlahPerpanjanganDisetujui >= 1) {
            return 'Buku sudah pernah diperpanjang 1 kali (maksimal perpanjangan tercapai)';
        }

        return 'Tidak dapat diperpanjang';
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
     * Misalnya: Rp 5.000 per hari
     */
    public function hitungDenda()
    {
        $hariTerlambat = $this->getHariTerlambat();
        $dendaPerHari = 5000;

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