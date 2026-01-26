<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Log;

class CekPeminjamanTerlambat extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'peminjaman:cek-terlambat';

    /**
     * The console command description.
     */
    protected $description = 'Cek peminjaman yang melewati deadline dan kirim notifikasi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Mengecek peminjaman yang terlambat...');

        // Ambil semua peminjaman yang masih dipinjam dan melewati deadline
        $peminjamanTerlambat = Peminjaman::with(['mahasiswa', 'buku'])
            ->where('status', 'dipinjam')
            ->whereNotNull('tanggal_deadline')
            ->where('tanggal_deadline', '<', now())
            ->get();

        if ($peminjamanTerlambat->isEmpty()) {
            $this->info('✅ Tidak ada peminjaman yang terlambat');
            return 0;
        }

        $this->info("📚 Ditemukan {$peminjamanTerlambat->count()} peminjaman terlambat");

        $notifikasiDikirim = 0;

        foreach ($peminjamanTerlambat as $peminjaman) {
            try {
                // Hitung berapa hari terlambat
                $hariTerlambat = now()->diffInDays($peminjaman->tanggal_deadline);
                $denda = $hariTerlambat * 5000;

                // Cek apakah sudah ada notifikasi keterlambatan untuk peminjaman ini hari ini
                // Cek berdasarkan tipe, user_id, data->peminjaman_id, dan tanggal
                $sudahAdaNotifikasiHariIni = Notifikasi::where('user_id', $peminjaman->mahasiswa_id)
                    ->where('tipe', 'terlambat')
                    ->where('data->peminjaman_id', $peminjaman->id)
                    ->whereDate('created_at', now()->toDateString())
                    ->exists();

                if ($sudahAdaNotifikasiHariIni) {
                    $this->warn("⏭️  Notifikasi untuk {$peminjaman->mahasiswa->name} sudah dikirim hari ini");
                    continue;
                }

                // Kirim notifikasi
                Notifikasi::kirim(
                    $peminjaman->mahasiswa_id,
                    'terlambat',
                    "⚠️ Buku Terlambat Dikembalikan: {$peminjaman->buku->judul}",
                    "PERINGATAN! Buku yang Anda pinjam telah melewati batas waktu pengembalian.\n\n" .
                    "📚 Buku: {$peminjaman->buku->judul}\n" .
                    "✍️ Penulis: {$peminjaman->buku->penulis}\n" .
                    "📅 Tanggal Pinjam: " . $peminjaman->tanggal_pinjam->translatedFormat('d F Y') . "\n" .
                    "⏰ Deadline: " . $peminjaman->tanggal_deadline->translatedFormat('d F Y, H:i') . " WIB\n" .
                    "⏱️ Terlambat: {$hariTerlambat} hari\n" .
                    "💰 Denda Sementara: Rp " . number_format($denda, 0, ',', '.') . "\n\n" .
                    "⚠️ SEGERA KEMBALIKAN BUKU!\n" .
                    "• Denda akan terus bertambah Rp 5.000 per hari\n" .
                    "• Hubungi petugas perpustakaan jika ada kendala\n" .
                    "• Keterlambatan dapat mempengaruhi catatan peminjaman Anda",
                    [
                        'peminjaman_id' => $peminjaman->id,
                        'buku_id' => $peminjaman->buku_id,
                        'hari_terlambat' => $hariTerlambat,
                        'denda' => $denda,
                        'deadline' => $peminjaman->tanggal_deadline->format('Y-m-d H:i:s')
                    ],
                    route('mahasiswa.peminjaman.show', $peminjaman->id),
                    'mendesak'
                );

                $notifikasiDikirim++;
                $this->info("✉️  Notifikasi dikirim ke: {$peminjaman->mahasiswa->name}");

                Log::info('Notifikasi keterlambatan dikirim', [
                    'peminjaman_id' => $peminjaman->id,
                    'mahasiswa_id' => $peminjaman->mahasiswa_id,
                    'mahasiswa_name' => $peminjaman->mahasiswa->name,
                    'buku' => $peminjaman->buku->judul,
                    'hari_terlambat' => $hariTerlambat,
                    'denda' => $denda
                ]);

            } catch (\Exception $e) {
                $this->error("❌ Gagal mengirim notifikasi untuk peminjaman ID {$peminjaman->id}: {$e->getMessage()}");
                
                Log::error('Gagal mengirim notifikasi keterlambatan', [
                    'peminjaman_id' => $peminjaman->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $this->info("✅ Selesai! {$notifikasiDikirim} notifikasi berhasil dikirim");
        
        Log::info('Cek peminjaman terlambat selesai', [
            'total_terlambat' => $peminjamanTerlambat->count(),
            'notifikasi_dikirim' => $notifikasiDikirim
        ]);

        return 0;
    }
}