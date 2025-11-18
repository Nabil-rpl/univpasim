<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perpanjangan extends Model
{
    use HasFactory;

    protected $table = 'perpanjangan';

    protected $fillable = [
        'peminjaman_id',
        'tanggal_perpanjangan',
        'tanggal_deadline_lama',
        'tanggal_deadline_baru',
        'durasi_tambahan',
        'status',
        'alasan',
        'catatan_petugas',
        'diproses_oleh',
    ];

    protected $casts = [
        'tanggal_perpanjangan' => 'datetime',
        'tanggal_deadline_lama' => 'datetime',
        'tanggal_deadline_baru' => 'datetime',
    ];

    /**
     * Relasi ke Peminjaman
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    /**
     * Relasi ke User (petugas yang memproses)
     */
    public function petugas()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    /**
     * Scope untuk perpanjangan yang menunggu
     */
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    /**
     * Scope untuk perpanjangan yang disetujui
     */
    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    /**
     * Scope untuk perpanjangan yang ditolak
     */
    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }

    /**
     * Cek apakah perpanjangan masih bisa dibatalkan
     */
    public function bisaDibatalkan()
    {
        // Hanya bisa dibatalkan jika statusnya disetujui dan peminjaman masih aktif
        return $this->status === 'disetujui' 
            && $this->peminjaman 
            && $this->peminjaman->status === 'dipinjam';
    }

    /**
     * Get badge class untuk status
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'menunggu' => 'warning',
            'disetujui' => 'success',
            'ditolak' => 'danger',
            'dibatalkan' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Get icon untuk status
     */
    public function getStatusIcon()
    {
        return match($this->status) {
            'menunggu' => 'hourglass-split',
            'disetujui' => 'check-circle-fill',
            'ditolak' => 'x-circle-fill',
            'dibatalkan' => 'dash-circle-fill',
            default => 'question-circle'
        };
    }

    /**
     * Get label untuk status
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            'menunggu' => 'Menunggu',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'dibatalkan' => 'Dibatalkan',
            default => 'Unknown'
        };
    }
}