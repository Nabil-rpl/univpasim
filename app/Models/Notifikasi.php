<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
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

            Log::info('Notifikasi ditandai sebagai dibaca', [
                'notifikasi_id' => $this->id,
                'user_id' => $this->user_id
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
            'pengembalian_baru' => 'box-arrow-in-left',
            'pengembalian_sukses' => 'check-circle',
            'reminder_deadline' => 'alarm',
            'terlambat' => 'exclamation-triangle-fill',
            'buku_tersedia' => 'bell-fill',
            'denda_belum_dibayar' => 'cash-coin',
            'denda_lunas' => 'check-circle-fill',
            'laporan_baru' => 'file-earmark-text',
            'stok_menipis' => 'exclamation-circle',
            'sistem' => 'info-circle-fill',
            default => 'bell'
        };
    }

    /**
     * ✅ FIXED: Get warna badge berdasarkan tipe notifikasi
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
            'pengembalian_baru' => 'warning',
            'pengembalian_sukses' => 'success', // ✅ Untuk denda lunas juga
            'reminder_deadline' => 'warning',
            'terlambat' => 'danger',
            'buku_tersedia' => 'info',
            'denda_belum_dibayar' => 'warning',
            'denda_lunas' => 'success', // ✅ Tetap ada untuk backward compatibility
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
        Carbon::setLocale('id');
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
     * ✅ FIXED: Static method untuk membuat notifikasi baru dengan logging detail
     */
    public static function kirim($userId, $tipe, $judul, $isi, $data = null, $url = null, $prioritas = 'normal', $dibuatOleh = null)
    {
        try {
            Log::info('========== MULAI KIRIM NOTIFIKASI ==========', [
                'user_id' => $userId,
                'tipe' => $tipe,
                'judul' => $judul,
                'prioritas' => $prioritas
            ]);

            // ✅ Validasi user ada
            $user = User::find($userId);
            if (!$user) {
                Log::error('❌ User tidak ditemukan', [
                    'user_id' => $userId
                ]);
                return false;
            }

            Log::info('✅ User ditemukan', [
                'user_id' => $userId,
                'user_name' => $user->name,
                'user_role' => $user->role
            ]);

            // ✅ Create notifikasi
            $notif = self::create([
                'user_id' => $userId,
                'tipe' => $tipe,
                'judul' => $judul,
                'isi' => $isi,
                'data' => $data,
                'url' => $url,
                'prioritas' => $prioritas,
                'dibuat_oleh' => $dibuatOleh,
                'dibaca' => false,
            ]);

            Log::info('✅ Notifikasi berhasil dibuat', [
                'notifikasi_id' => $notif->id,
                'user_id' => $userId,
                'tipe' => $tipe,
                'created_at' => $notif->created_at->format('Y-m-d H:i:s')
            ]);

            // ✅ Verifikasi notifikasi tersimpan di database
            $verified = self::find($notif->id);
            if ($verified) {
                Log::info('✅ Notifikasi terverifikasi tersimpan di database', [
                    'notifikasi_id' => $verified->id
                ]);
            } else {
                Log::error('❌ Notifikasi tidak ditemukan setelah create');
            }

            return $notif;

        } catch (\Exception $e) {
            Log::error('❌ ERROR MEMBUAT NOTIFIKASI', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'tipe' => $tipe,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Static method untuk kirim notifikasi ke multiple users
     */
    public static function kirimKeMultipleUsers($userIds, $tipe, $judul, $isi, $data = null, $url = null, $prioritas = 'normal', $dibuatOleh = null)
    {
        try {
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
                    'dibaca' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            $result = self::insert($notifikasi);

            Log::info('Notifikasi batch berhasil dikirim', [
                'jumlah' => count($notifikasi),
                'tipe' => $tipe
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Error kirim batch notifikasi', [
                'error' => $e->getMessage(),
                'tipe' => $tipe
            ]);
            return false;
        }
    }

    /**
     * ✅ Static method untuk kirim ke semua petugas (TANPA CEK STATUS)
     */
    public static function kirimKePetugas($tipe, $judul, $isi, $data = null, $url = null, $prioritas = 'normal', $dibuatOleh = null)
    {
        try {
            Log::info('========== MULAI KIRIM NOTIFIKASI KE PETUGAS ==========', [
                'tipe' => $tipe,
                'judul' => $judul
            ]);

            // ✅ Ambil semua petugas dan admin (TANPA CEK STATUS)
            $petugasIds = User::whereIn('role', ['petugas', 'admin'])->pluck('id');

            if ($petugasIds->isEmpty()) {
                Log::warning('❌ TIDAK ADA PETUGAS/ADMIN DITEMUKAN');
                return false;
            }

            Log::info('✅ Petugas ditemukan', [
                'jumlah' => $petugasIds->count(),
                'ids' => $petugasIds->toArray()
            ]);

            $result = self::kirimKeMultipleUsers($petugasIds, $tipe, $judul, $isi, $data, $url, $prioritas, $dibuatOleh);

            if ($result) {
                Log::info('✅ Notifikasi berhasil dikirim ke semua petugas', [
                    'jumlah_penerima' => $petugasIds->count()
                ]);

                // ✅ Verifikasi notifikasi tersimpan
                $countBaru = self::where('tipe', $tipe)
                    ->where('created_at', '>=', now()->subMinute())
                    ->count();

                Log::info('✅ Verifikasi: Notifikasi baru di database', [
                    'jumlah' => $countBaru
                ]);
            } else {
                Log::error('❌ Gagal insert notifikasi ke database');
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('❌ ERROR KIRIM NOTIFIKASI KE PETUGAS', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Static method untuk kirim ke semua mahasiswa
     */
    public static function kirimKeMahasiswa($tipe, $judul, $isi, $data = null, $url = null, $prioritas = 'normal', $dibuatOleh = null)
    {
        $mahasiswaIds = User::where('role', 'mahasiswa')->pluck('id');
        
        if ($mahasiswaIds->isEmpty()) {
            Log::warning('Tidak ada mahasiswa ditemukan');
            return false;
        }

        return self::kirimKeMultipleUsers($mahasiswaIds, $tipe, $judul, $isi, $data, $url, $prioritas, $dibuatOleh);
    }

    /**
     * Static method untuk kirim ke mahasiswa tertentu
     */
    public static function kirimKeMahasiswaTertentu($mahasiswaId, $tipe, $judul, $isi, $data = null, $url = null, $prioritas = 'normal', $dibuatOleh = null)
    {
        try {
            $mahasiswa = User::where('id', $mahasiswaId)
                ->where('role', 'mahasiswa')
                ->first();

            if (!$mahasiswa) {
                Log::warning('Mahasiswa tidak ditemukan', [
                    'mahasiswa_id' => $mahasiswaId
                ]);
                return false;
            }

            return self::kirim($mahasiswaId, $tipe, $judul, $isi, $data, $url, $prioritas, $dibuatOleh);

        } catch (\Exception $e) {
            Log::error('Error kirim notifikasi ke mahasiswa', [
                'mahasiswa_id' => $mahasiswaId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Hapus notifikasi lama (lebih dari X hari)
     */
    public static function hapusNotifikasiLama($hari = 30)
    {
        try {
            $deleted = self::where('created_at', '<', now()->subDays($hari))
                ->where('dibaca', true)
                ->delete();

            Log::info('Notifikasi lama berhasil dihapus', [
                'jumlah' => $deleted,
                'hari' => $hari
            ]);

            return $deleted;
        } catch (\Exception $e) {
            Log::error('Error hapus notifikasi lama', [
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }
}