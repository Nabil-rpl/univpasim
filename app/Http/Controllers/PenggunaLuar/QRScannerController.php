<?php

namespace App\Http\Controllers\PenggunaLuar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\QRCode;
use App\Models\Peminjaman;
use Carbon\Carbon;

class QRScannerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:pengguna_luar']);
    }

    /**
     * Tampilkan halaman scanner
     */
    public function index()
    {
        return view('pengguna-luar.qr-scanner.index');
    }

    /**
     * Proses hasil scan QR Code dengan durasi peminjaman
     */
    public function process(Request $request)
    {
        try {
            // Log request data
            Log::info('QR Process - Pengguna Luar - Request Data:', [
                'qr_code' => $request->qr_code,
                'durasi_hari' => $request->durasi_hari,
                'user_id' => Auth::id()
            ]);

            // Validasi input - durasi maksimal 7 hari untuk pengguna luar
            $validated = $request->validate([
                'qr_code' => 'required|string',
                'durasi_hari' => 'required|integer|min:1|max:7'
            ]);

            $kodeQR = $validated['qr_code'];
            $durasiHari = $validated['durasi_hari'];

            // Cari QR Code di database
            $qrCode = QRCode::where('kode_unik', $kodeQR)->first();

            if (!$qrCode) {
                Log::warning('QR Code not found:', ['kode' => $kodeQR]);
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid!'
                ], 404);
            }

            if (!$qrCode->buku_id) {
                Log::warning('QR Code tidak memiliki buku_id:', ['qr_id' => $qrCode->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code ini tidak terhubung dengan buku manapun!'
                ], 400);
            }

            // Load relasi buku
            $buku = $qrCode->buku;

            if (!$buku) {
                Log::error('Buku tidak ditemukan untuk buku_id:', ['buku_id' => $qrCode->buku_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Buku tidak ditemukan!'
                ], 404);
            }

            // Cek stok buku
            if ($buku->stok < 1) {
                Log::info('Stok buku habis:', ['buku_id' => $buku->id, 'stok' => $buku->stok]);
                return response()->json([
                    'success' => false,
                    'message' => 'Maaf, stok buku habis!'
                ], 400);
            }

            // Cek apakah pengguna luar sudah meminjam buku yang sama dan belum dikembalikan
            $peminjamanAktif = Peminjaman::where('mahasiswa_id', Auth::id())
                ->where('buku_id', $buku->id)
                ->where('status', 'dipinjam')
                ->exists();

            if ($peminjamanAktif) {
                Log::info('Peminjaman aktif sudah ada:', [
                    'user_id' => Auth::id(),
                    'buku_id' => $buku->id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Anda masih memiliki peminjaman aktif untuk buku ini!'
                ], 400);
            }

            // Cek batas maksimal peminjaman untuk pengguna luar (2 buku)
            $jumlahPeminjamanAktif = Peminjaman::where('mahasiswa_id', Auth::id())
                ->where('status', 'dipinjam')
                ->count();

            if ($jumlahPeminjamanAktif >= 2) {
                Log::info('Batas peminjaman tercapai:', [
                    'user_id' => Auth::id(),
                    'jumlah_peminjaman' => $jumlahPeminjamanAktif
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah mencapai batas maksimal peminjaman (2 buku). Harap kembalikan buku terlebih dahulu.'
                ], 400);
            }

            // Mulai database transaction
            DB::beginTransaction();

            try {
                // Hitung tanggal deadline
                $tanggalPinjam = Carbon::now();
                $tanggalDeadline = $tanggalPinjam->copy()->addDays($durasiHari);

                // Buat peminjaman baru
                $peminjaman = Peminjaman::create([
                    'mahasiswa_id' => Auth::id(),
                    'buku_id' => $buku->id,
                    'tanggal_pinjam' => $tanggalPinjam,
                    'durasi_hari' => $durasiHari,
                    'tanggal_deadline' => $tanggalDeadline,
                    'status' => 'dipinjam',
                ]);

                // Kurangi stok buku
                $buku->decrement('stok');

                DB::commit();

                Log::info('Peminjaman berhasil dibuat (Pengguna Luar):', [
                    'peminjaman_id' => $peminjaman->id,
                    'user_id' => Auth::id(),
                    'buku_id' => $buku->id,
                    'durasi_hari' => $durasiHari
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Peminjaman berhasil!',
                    'data' => [
                        'peminjaman_id' => $peminjaman->id,
                        'judul_buku' => $buku->judul,
                        'tanggal_pinjam' => $peminjaman->tanggal_pinjam->locale('id')->format('d F Y'),
                        'tanggal_deadline' => $peminjaman->tanggal_deadline->locale('id')->format('d F Y'),
                        'durasi_hari' => $durasiHari,
                        'sisa_kuota' => 2 - ($jumlahPeminjamanAktif + 1)
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error (Pengguna Luar):', [
                'errors' => $e->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . json_encode($e->errors())
            ], 422);

        } catch (\Exception $e) {
            Log::error('QR Process Error (Pengguna Luar):', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tampilkan detail buku dari QR Code (preview sebelum pinjam)
     */
    public function preview(Request $request)
    {
        try {
            $request->validate([
                'qr_code' => 'required|string'
            ]);

            $qrCode = QRCode::where('kode_unik', $request->qr_code)->first();

            if (!$qrCode || !$qrCode->buku_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid!'
                ], 404);
            }

            $buku = $qrCode->buku;

            if (!$buku) {
                return response()->json([
                    'success' => false,
                    'message' => 'Buku tidak ditemukan!'
                ], 404);
            }

            // Cek kuota peminjaman pengguna luar
            $jumlahPeminjamanAktif = Peminjaman::where('mahasiswa_id', Auth::id())
                ->where('status', 'dipinjam')
                ->count();

            $sisaKuota = 2 - $jumlahPeminjamanAktif;

            return response()->json([
                'success' => true,
                'data' => [
                    'judul' => $buku->judul,
                    'penulis' => $buku->penulis,
                    'penerbit' => $buku->penerbit,
                    'tahun_terbit' => $buku->tahun_terbit,
                    'stok' => $buku->stok,
                    'foto' => $buku->foto ? asset('storage/' . $buku->foto) : null,
                    'sisa_kuota' => $sisaKuota,
                    'max_durasi' => 7,
                    'info' => 'Pengguna luar dapat meminjam maksimal 2 buku dengan durasi maksimal 7 hari'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('QR Preview Error (Pengguna Luar):', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat preview'
            ], 500);
        }
    }
}