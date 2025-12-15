<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Mahasiswa as MahasiswaModel;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:mahasiswa']);
        
        Carbon::setLocale('id');
    }

    /**
     * Tampilkan daftar peminjaman mahasiswa (halaman index)
     */
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = MahasiswaModel::where('email', $user->email)->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $peminjamans = Peminjaman::where('mahasiswa_id', $user->id)
            ->with(['buku', 'petugas'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('mahasiswa.peminjaman.index', compact('peminjamans', 'mahasiswa'));
    }

    /**
     * Riwayat peminjaman dengan filter
     */
    public function riwayat(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = MahasiswaModel::where('email', $user->email)->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $query = Peminjaman::where('mahasiswa_id', $user->id)
            ->with(['buku', 'petugas']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tanggal pinjam
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_sampai);
        }

        // Pagination
        $peminjaman = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('mahasiswa.peminjaman.riwayat', compact('peminjaman', 'mahasiswa'));
    }

    /**
     * Detail peminjaman
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            $mahasiswa = MahasiswaModel::where('email', $user->email)->first();

            if (!$mahasiswa) {
                return redirect()->route('mahasiswa.peminjaman.riwayat')
                    ->with('error', 'Data mahasiswa tidak ditemukan.');
            }

            $peminjaman = Peminjaman::where('mahasiswa_id', $user->id)
                ->where('id', $id)
                ->with(['buku', 'petugas'])
                ->first();

            // Cek apakah peminjaman ditemukan
            if (!$peminjaman) {
                return redirect()->route('mahasiswa.peminjaman.riwayat')
                    ->with('error', 'Data peminjaman tidak ditemukan.');
            }

            // Cek apakah peminjaman milik user yang login
            if ($peminjaman->mahasiswa_id != $user->id) {
                return redirect()->route('mahasiswa.peminjaman.riwayat')
                    ->with('error', 'Anda tidak memiliki akses ke peminjaman ini.');
            }

            return view('mahasiswa.peminjaman.show', compact('peminjaman', 'mahasiswa'));

        } catch (\Exception $e) {
            Log::error('Error menampilkan detail peminjaman', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('mahasiswa.peminjaman.riwayat')
                ->with('error', 'Terjadi kesalahan saat mengambil data peminjaman.');
        }
    }

    /**
     * ✅ Proses pinjam buku - FIXED WITH NOTIFICATION
     */
    public function pinjam($id)
    {
        try {
            Log::info('========== MULAI PROSES PEMINJAMAN BUKU ==========', [
                'buku_id' => $id, 
                'user_id' => Auth::id()
            ]);

            $user = Auth::user();
            $mahasiswa = MahasiswaModel::where('email', $user->email)->first();

            if (!$mahasiswa) {
                Log::error('❌ Mahasiswa tidak ditemukan', ['email' => $user->email]);
                return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan. Silakan hubungi admin.');
            }

            Log::info('✅ Mahasiswa ditemukan', ['mahasiswa_id' => $mahasiswa->id]);

            DB::beginTransaction();

            $buku = Buku::findOrFail($id);
            Log::info('✅ Buku ditemukan', ['buku' => $buku->judul, 'stok' => $buku->stok]);

            // Cek stok buku
            if ($buku->stok <= 0) {
                DB::rollBack();
                Log::warning('❌ Stok buku habis');
                return redirect()->back()->with('error', 'Stok buku tidak tersedia.');
            }

            // Cek apakah mahasiswa sudah meminjam buku ini
            $sudahPinjam = Peminjaman::where('mahasiswa_id', $user->id)
                ->where('buku_id', $buku->id)
                ->where('status', 'dipinjam')
                ->exists();

            if ($sudahPinjam) {
                DB::rollBack();
                Log::warning('❌ Mahasiswa sudah meminjam buku ini');
                return redirect()->back()->with('error', 'Anda sudah meminjam buku ini dan belum mengembalikannya.');
            }

            // Cek batas maksimal peminjaman
            $jumlahPinjaman = Peminjaman::where('mahasiswa_id', $user->id)
                ->where('status', 'dipinjam')
                ->count();

            if ($jumlahPinjaman >= 3) {
                DB::rollBack();
                Log::warning('❌ Batas maksimal peminjaman tercapai', ['jumlah' => $jumlahPinjaman]);
                return redirect()->back()->with('error', 'Anda sudah mencapai batas maksimal peminjaman (3 buku).');
            }

            $tanggalPinjam = Carbon::now('Asia/Jakarta');
            $tanggalDeadline = Carbon::now('Asia/Jakarta')->addDays(3);

            Log::info('⏰ Waktu peminjaman', [
                'tanggal_pinjam' => $tanggalPinjam->format('Y-m-d H:i:s'),
                'tanggal_deadline' => $tanggalDeadline->format('Y-m-d H:i:s'),
                'timezone' => $tanggalPinjam->timezone->getName()
            ]);

            // Buat peminjaman baru
            $peminjaman = Peminjaman::create([
                'mahasiswa_id' => $user->id,
                'buku_id' => $buku->id,
                'petugas_id' => null,
                'tanggal_pinjam' => $tanggalPinjam,
                'durasi_hari' => 3,
                'tanggal_deadline' => $tanggalDeadline,
                'tanggal_kembali' => null,
                'status' => 'dipinjam',
            ]);

            Log::info('✅ Peminjaman berhasil dibuat', ['peminjaman_id' => $peminjaman->id]);

            // Kurangi stok buku
            $buku->decrement('stok');
            Log::info('✅ Stok buku dikurangi', ['stok_lama' => $buku->stok + 1, 'stok_baru' => $buku->fresh()->stok]);

            // ✅✅✅ KIRIM NOTIFIKASI KE SEMUA PETUGAS/ADMIN - FIXED ✅✅✅
            Log::info('📧 Memulai pengiriman notifikasi ke petugas...');
            
            $notifikasiDikirim = Notifikasi::kirimKePetugas(
                'peminjaman_baru',
                "📚 Peminjaman Baru: {$buku->judul}",
                "Mahasiswa {$user->name} ({$mahasiswa->nim}) telah meminjam buku \"{$buku->judul}\".\n\n" .
                "📋 Detail Peminjaman:\n" .
                "• Peminjam: {$user->name}\n" .
                "• NIM: {$mahasiswa->nim}\n" .
                "• Buku: {$buku->judul}\n" .
                "• Tanggal Pinjam: " . $tanggalPinjam->translatedFormat('d F Y, H:i') . " WIB\n" .
                "• Deadline: " . $tanggalDeadline->translatedFormat('d F Y, H:i') . " WIB\n" .
                "• Durasi: 3 hari\n\n" .
                "Silakan cek detail peminjaman untuk informasi lebih lanjut.",
                [
                    'peminjaman_id' => $peminjaman->id,
                    'buku_id' => $buku->id,
                    'mahasiswa_id' => $user->id,
                    'judul_buku' => $buku->judul,
                    'nama_mahasiswa' => $user->name,
                    'nim' => $mahasiswa->nim,
                    'tanggal_pinjam' => $tanggalPinjam->format('Y-m-d H:i:s'),
                    'tanggal_deadline' => $tanggalDeadline->format('Y-m-d H:i:s'),
                ],
                route('petugas.peminjaman.show', $peminjaman->id),
                'tinggi', // prioritas tinggi karena peminjaman baru
                $user->id
            );

            if ($notifikasiDikirim) {
                Log::info('✅✅✅ NOTIFIKASI BERHASIL DIKIRIM KE PETUGAS');
                
                // Verifikasi notifikasi tersimpan
                $jumlahNotifikasi = \App\Models\Notifikasi::where('tipe', 'peminjaman_baru')
                    ->where('created_at', '>=', now()->subMinute())
                    ->count();
                    
                Log::info('✅ Verifikasi: Jumlah notifikasi baru di database', [
                    'jumlah' => $jumlahNotifikasi
                ]);
            } else {
                Log::error('❌ GAGAL MENGIRIM NOTIFIKASI KE PETUGAS');
            }

            // ✅ KIRIM NOTIFIKASI KONFIRMASI KE MAHASISWA
            Notifikasi::kirim(
                $user->id,
                'peminjaman_sukses',
                '✅ Peminjaman Berhasil',
                "Anda berhasil meminjam buku \"{$buku->judul}\".\n\n" .
                "Harap kembalikan sebelum {$tanggalDeadline->translatedFormat('l, d F Y H:i')} WIB.\n\n" .
                "Keterlambatan pengembalian akan dikenakan denda.",
                [
                    'peminjaman_id' => $peminjaman->id,
                    'buku_id' => $buku->id,
                ],
                route('mahasiswa.peminjaman.show', $peminjaman->id),
                'normal'
            );

            Log::info('✅ Notifikasi konfirmasi dikirim ke mahasiswa');

            DB::commit();

            Log::info('========== PROSES PEMINJAMAN SELESAI ==========');

            return redirect()->route('mahasiswa.peminjaman.index')
                ->with('success', "Berhasil meminjam buku '{$buku->judul}'. Harap kembalikan sebelum " . 
                       $tanggalDeadline->translatedFormat('l, d F Y H:i') . ' WIB');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌❌❌ ERROR FATAL SAAT MEMINJAM BUKU', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * ✅ Perpanjangan peminjaman
     */
    public function perpanjang($id)
    {
        DB::beginTransaction();
        try {
            Log::info('========== MULAI PROSES PERPANJANGAN ==========', ['peminjaman_id' => $id]);

            $user = Auth::user();
            $peminjaman = Peminjaman::where('id', $id)
                ->where('mahasiswa_id', $user->id)
                ->where('status', 'dipinjam')
                ->with(['buku', 'mahasiswa'])
                ->firstOrFail();

            // Validasi: cek apakah sudah pernah diperpanjang
            if ($peminjaman->sudah_diperpanjang) {
                return redirect()->back()
                    ->with('error', 'Peminjaman ini sudah pernah diperpanjang.');
            }

            $buku = $peminjaman->buku;
            $mahasiswa = MahasiswaModel::where('email', $user->email)->first();

            // Perpanjang deadline 3 hari lagi
            $deadlineBaru = Carbon::parse($peminjaman->tanggal_deadline)->addDays(3);
            
            $peminjaman->update([
                'tanggal_deadline' => $deadlineBaru,
                'sudah_diperpanjang' => true,
                'tanggal_perpanjangan' => now(),
            ]);

            Log::info('✅ Peminjaman berhasil diperpanjang', [
                'peminjaman_id' => $peminjaman->id,
                'deadline_baru' => $deadlineBaru->format('Y-m-d H:i:s')
            ]);

            // ✅ KIRIM NOTIFIKASI KE SEMUA PETUGAS
            Notifikasi::kirimKePetugas(
                'perpanjangan_baru',
                "🔄 Perpanjangan: {$buku->judul}",
                "Mahasiswa {$user->name} ({$mahasiswa->nim}) telah memperpanjang peminjaman buku \"{$buku->judul}\".\n\n" .
                "📋 Detail:\n" .
                "• Deadline Baru: " . $deadlineBaru->translatedFormat('d F Y, H:i') . " WIB\n" .
                "• Durasi Tambahan: 3 hari",
                [
                    'peminjaman_id' => $peminjaman->id,
                    'buku_id' => $buku->id,
                    'mahasiswa_id' => $user->id,
                    'deadline_baru' => $deadlineBaru->format('Y-m-d H:i:s'),
                ],
                route('petugas.peminjaman.show', $peminjaman->id),
                'normal',
                $user->id
            );

            // ✅ NOTIFIKASI KE MAHASISWA
            Notifikasi::kirim(
                $user->id,
                'perpanjangan_sukses',
                '✅ Perpanjangan Berhasil',
                "Peminjaman buku \"{$buku->judul}\" berhasil diperpanjang.\n\n" .
                "Deadline baru: {$deadlineBaru->translatedFormat('l, d F Y H:i')} WIB",
                [
                    'peminjaman_id' => $peminjaman->id,
                ],
                route('mahasiswa.peminjaman.show', $peminjaman->id),
                'normal'
            );

            DB::commit();

            Log::info('========== PROSES PERPANJANGAN SELESAI ==========');

            return redirect()->back()
                ->with('success', 'Peminjaman berhasil diperpanjang. Deadline baru: ' . 
                       $deadlineBaru->translatedFormat('l, d F Y H:i') . ' WIB');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error perpanjangan', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperpanjang peminjaman.');
        }
    }

    /**
     * ✅ Kembalikan buku
     */
    public function kembalikan($id)
    {
        DB::beginTransaction();
        try {
            Log::info('========== MULAI PROSES PENGEMBALIAN ==========', ['peminjaman_id' => $id]);

            $user = Auth::user();
            $peminjaman = Peminjaman::where('id', $id)
                ->where('mahasiswa_id', $user->id)
                ->where('status', 'dipinjam')
                ->with(['buku'])
                ->firstOrFail();

            $buku = $peminjaman->buku;
            $mahasiswa = MahasiswaModel::where('email', $user->email)->first();
            $tanggalKembali = Carbon::now('Asia/Jakarta');

            // Hitung denda jika terlambat
            $denda = 0;
            $terlambat = false;
            $hariTerlambat = 0;

            if ($tanggalKembali->greaterThan($peminjaman->tanggal_deadline)) {
                $hariTerlambat = $tanggalKembali->diffInDays($peminjaman->tanggal_deadline);
                $denda = $hariTerlambat * 1000; // Rp 1000 per hari
                $terlambat = true;
                
                Log::warning('⚠️ Pengembalian terlambat', [
                    'hari_terlambat' => $hariTerlambat,
                    'denda' => $denda
                ]);
            }

            // Update peminjaman
            $peminjaman->update([
                'tanggal_kembali' => $tanggalKembali,
                'status' => 'dikembalikan',
                'denda' => $denda,
                'hari_terlambat' => $hariTerlambat,
            ]);

            // Kembalikan stok buku
            $buku->increment('stok');

            Log::info('✅ Pengembalian berhasil', [
                'peminjaman_id' => $peminjaman->id,
                'terlambat' => $terlambat,
                'denda' => $denda,
                'stok_baru' => $buku->fresh()->stok
            ]);

            // ✅ KIRIM NOTIFIKASI KE SEMUA PETUGAS
            $pesanPetugas = $terlambat 
                ? "Mahasiswa {$user->name} ({$mahasiswa->nim}) mengembalikan buku \"{$buku->judul}\" dengan keterlambatan {$hariTerlambat} hari.\n\n" .
                  "💰 Denda: Rp " . number_format($denda, 0, ',', '.')
                : "Mahasiswa {$user->name} ({$mahasiswa->nim}) mengembalikan buku \"{$buku->judul}\" tepat waktu.";

            Notifikasi::kirimKePetugas(
                'pengembalian_baru',
                $terlambat ? '⚠️ Pengembalian Terlambat: ' . $buku->judul : '📥 Pengembalian: ' . $buku->judul,
                $pesanPetugas,
                [
                    'peminjaman_id' => $peminjaman->id,
                    'buku_id' => $buku->id,
                    'mahasiswa_id' => $user->id,
                    'terlambat' => $terlambat,
                    'denda' => $denda,
                    'hari_terlambat' => $hariTerlambat,
                ],
                route('petugas.peminjaman.show', $peminjaman->id),
                $terlambat ? 'tinggi' : 'normal',
                $user->id
            );

            // ✅ NOTIFIKASI KE MAHASISWA
            $pesanMahasiswa = $terlambat
                ? "Buku \"{$buku->judul}\" telah dikembalikan dengan keterlambatan {$hariTerlambat} hari.\n\n" .
                  "💰 Denda: Rp " . number_format($denda, 0, ',', '.') . "\n\n" .
                  "Harap segera membayar denda di perpustakaan."
                : "Buku \"{$buku->judul}\" telah dikembalikan tepat waktu.\n\nTerima kasih!";

            Notifikasi::kirim(
                $user->id,
                $terlambat ? 'pengembalian_terlambat' : 'pengembalian_sukses',
                '✅ Pengembalian Berhasil',
                $pesanMahasiswa,
                [
                    'peminjaman_id' => $peminjaman->id,
                    'denda' => $denda,
                ],
                route('mahasiswa.peminjaman.show', $peminjaman->id),
                $terlambat ? 'tinggi' : 'normal'
            );

            DB::commit();

            Log::info('========== PROSES PENGEMBALIAN SELESAI ==========');

            $successMessage = $terlambat 
                ? "Buku berhasil dikembalikan. Terlambat {$hariTerlambat} hari. Denda: Rp " . number_format($denda, 0, ',', '.')
                : 'Buku berhasil dikembalikan tepat waktu. Terima kasih!';

            return redirect()->route('mahasiswa.peminjaman.index')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error pengembalian', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengembalikan buku.');
        }
    }
}