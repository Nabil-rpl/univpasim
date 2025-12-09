<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'tipe',
        'judul',
        'isi',
        'data',
        'url',
        'dibaca',
        'dibaca_pada',
        'prioritas',
        'dibuat_oleh',
    ];

    protected $casts = [
        'data' => 'array',
        'dibaca' => 'boolean',
        'dibaca_pada' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke User (penerima)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke User (pembuat)
     */
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Tandai notifikasi sebagai dibaca
     */
    public function tandaiDibaca()
    {
        if (!$this->dibaca) {
            $this->update([
                'dibaca' => true,
                'dibaca_pada' => now(),
            ]);
        }
        return $this;
    }

    /**
     * Tandai notifikasi sebagai belum dibaca
     */
    public function tandaiTidakDibaca()
    {
        $this->update([
            'dibaca' => false,
            'dibaca_pada' => null,
        ]);
        return $this;
    }

    /**
     * Scope untuk notifikasi yang belum dibaca
     */
    public function scopeBelumDibaca($query)
    {
        return $query->where('dibaca', false);
    }

    /**
     * Scope untuk notifikasi yang sudah dibaca
     */
    public function scopeSudahDibaca($query)
    {
        return $query->where('dibaca', true);
    }

    /**
     * Scope untuk notifikasi berdasarkan tipe
     */
    public function scopeTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }

    /**
     * Scope untuk notifikasi berdasarkan prioritas
     */
    public function scopePrioritas($query, $prioritas)
    {
        return $query->where('prioritas', $prioritas);
    }

    /**
     * Scope untuk notifikasi hari ini
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope untuk notifikasi minggu ini
     */
    public function scopeMingguIni($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Get icon berdasarkan tipe notifikasi
     */
    public function getIcon()
    {
        return match($this->tipe) {
            'peminjaman_baru' => 'book-fill',
            'peminjaman_disetujui' => 'check-circle-fill',
            'peminjaman_ditolak' => 'x-circle-fill',
            'perpanjangan_baru' => 'arrow-clockwise',
            'perpanjangan_disetujui' => 'check2-circle',
            'perpanjangan_ditolak' => 'x-octagon',
            'reminder_deadline' => 'alarm',
            'terlambat' => 'exclamation-triangle-fill',
            'pengembalian_sukses' => 'check-circle',
            'buku_tersedia' => 'bell-fill',
            'denda_belum_dibayar' => 'cash-coin',
            'user_baru' => 'person-plus-fill',
            'buku_baru' => 'journal-plus',
            'laporan_baru' => 'file-earmark-text',
            'stok_menipis' => 'exclamation-circle',
            'sistem' => 'info-circle-fill',
            default => 'bell'
        };
    }

    /**
     * Get warna badge berdasarkan tipe notifikasi
     */
    public function getBadgeColor()
    {
        return match($this->tipe) {
            'peminjaman_baru' => 'primary',
            'peminjaman_disetujui' => 'success',
            'peminjaman_ditolak' => 'danger',
            'perpanjangan_baru' => 'info',
            'perpanjangan_disetujui' => 'success',
            'perpanjangan_ditolak' => 'danger',
            'reminder_deadline' => 'warning',
            'terlambat' => 'danger',
            'pengembalian_sukses' => 'success',
            'buku_tersedia' => 'info',
            'denda_belum_dibayar' => 'warning',
            'user_baru' => 'primary',
            'buku_baru' => 'success',
            'laporan_baru' => 'info',
            'stok_menipis' => 'warning',
            'sistem' => 'secondary',
            default => 'light'
        };
    }

    /**
     * Get warna berdasarkan prioritas
     */
    public function getPrioritasColor()
    {
        return match($this->prioritas) {
            'mendesak' => 'danger',
            'tinggi' => 'warning',
            'normal' => 'primary',
            'rendah' => 'secondary',
            default => 'light'
        };
    }

    /**
     * Get waktu relatif (contoh: 2 jam yang lalu)
     */
    public function getWaktuRelatif()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Cek apakah notifikasi baru (kurang dari 1 jam)
     */
    public function isBaru()
    {
        return $this->created_at->diffInHours(now()) < 1;
    }

    /**
     * Static method untuk membuat notifikasi baru
     */
    public static function kirim($userId, $tipe, $judul, $isi, $data = null, $url = null, $prioritas = 'normal', $dibuatOleh = null)
    {
        return self::create([
            'user_id' => $userId,
            'tipe' => $tipe,
            'judul' => $judul,
            'isi' => $isi,
            'data' => $data,
            'url' => $url,
            'prioritas' => $prioritas,
            'dibuat_oleh' => $dibuatOleh,
        ]);
    }

    /**
     * Static method untuk kirim notifikasi ke multiple users
     */
    public static function kirimKeMultipleUsers($userIds, $tipe, $judul, $isi, $data = null, $url = null, $prioritas = 'normal', $dibuatOleh = null)
    {
        $notifikasi = [];
        
        foreach ($userIds as $userId) {
            $notifikasi[] = [
                'user_id' => $userId,
                'tipe' => $tipe,
                'judul' => $judul,
                'isi' => $isi,
                'data' => json_encode($data),
                'url' => $url,
                'prioritas' => $prioritas,
                'dibuat_oleh' => $dibuatOleh,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        return self::insert($notifikasi);
    }

    /**
     * Static method untuk kirim ke semua petugas
     */
    public static function kirimKePetugas($tipe, $judul, $isi, $data = null, $url = null, $prioritas = 'normal', $dibuatOleh = null)
    {
        $petugasIds = User::whereIn('role', ['petugas', 'admin'])->pluck('id');
        return self::kirimKeMultipleUsers($petugasIds, $tipe, $judul, $isi, $data, $url, $prioritas, $dibuatOleh);
    }

    /**
     * Static method untuk kirim ke semua mahasiswa
     */
    public static function kirimKeMahasiswa($tipe, $judul, $isi, $data = null, $url = null, $prioritas = 'normal', $dibuatOleh = null)
    {
        $mahasiswaIds = User::whereIn('role', ['mahasiswa', 'pengguna_luar'])->pluck('id');
        return self::kirimKeMultipleUsers($mahasiswaIds, $tipe, $judul, $isi, $data, $url, $prioritas, $dibuatOleh);
    }

    /**
     * Static method untuk kirim ke semua user
     */
    public static function kirimKeSemuaUser($tipe, $judul, $isi, $data = null, $url = null, $prioritas = 'normal', $dibuatOleh = null)
    {
        $allUserIds = User::pluck('id');
        return self::kirimKeMultipleUsers($allUserIds, $tipe, $judul, $isi, $data, $url, $prioritas, $dibuatOleh);
    }

    /**
     * Hapus notifikasi lama (lebih dari X hari)
     */
    public static function hapusNotifikasiLama($hari = 30)
    {
        return self::where('created_at', '<', now()->subDays($hari))
            ->where('dibaca', true)
            ->delete();
    }
}