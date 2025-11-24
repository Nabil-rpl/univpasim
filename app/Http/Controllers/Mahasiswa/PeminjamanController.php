<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Mahasiswa as MahasiswaModel;
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
        
        // ✅ Set locale Carbon ke Indonesia
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

        // ✅ HANYA load 'buku' dan 'petugas' (tidak ada pengembalian)
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

        // ✅ HANYA load 'buku' dan 'petugas'
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
     * ✅ FIXED - Tanpa relasi pengembalian
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

            // ✅ HANYA load 'buku' dan 'petugas'
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
     * Proses pinjam buku
     * ✅ UPDATED: Menggunakan timezone WIB (Asia/Jakarta)
     */
    public function pinjam($id)
    {
        try {
            Log::info('Pinjam buku dipanggil', ['buku_id' => $id, 'user_id' => Auth::id()]);

            $user = Auth::user();
            $mahasiswa = MahasiswaModel::where('email', $user->email)->first();

            if (!$mahasiswa) {
                Log::error('Mahasiswa tidak ditemukan', ['email' => $user->email]);
                return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan. Silakan hubungi admin.');
            }

            Log::info('Mahasiswa ditemukan', ['mahasiswa_id' => $mahasiswa->id]);

            DB::beginTransaction();

            $buku = Buku::findOrFail($id);
            Log::info('Buku ditemukan', ['buku' => $buku->judul, 'stok' => $buku->stok]);

            // Cek stok buku
            if ($buku->stok <= 0) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Stok buku tidak tersedia.');
            }

            // Cek apakah mahasiswa sudah meminjam buku ini
            $sudahPinjam = Peminjaman::where('mahasiswa_id', $user->id)
                ->where('buku_id', $buku->id)
                ->where('status', 'dipinjam')
                ->exists();

            if ($sudahPinjam) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Anda sudah meminjam buku ini dan belum mengembalikannya.');
            }

            // Cek batas maksimal peminjaman
            $jumlahPinjaman = Peminjaman::where('mahasiswa_id', $user->id)
                ->where('status', 'dipinjam')
                ->count();

            if ($jumlahPinjaman >= 3) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Anda sudah mencapai batas maksimal peminjaman (3 buku).');
            }

            // ✅ FIXED: Gunakan Carbon dengan timezone Asia/Jakarta secara eksplisit
            $tanggalPinjam = Carbon::now('Asia/Jakarta');
            $tanggalDeadline = Carbon::now('Asia/Jakarta')->addDays(3);

            // Log untuk debugging
            Log::info('Waktu peminjaman', [
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

            Log::info('Peminjaman dibuat', ['peminjaman_id' => $peminjaman->id]);

            // Kurangi stok buku
            $buku->decrement('stok');
            Log::info('Stok buku dikurangi', ['stok_baru' => $buku->fresh()->stok]);

            DB::commit();

            // ✅ Format tanggal dalam Bahasa Indonesia
            return redirect()->route('mahasiswa.peminjaman.index')
                ->with('success', "Berhasil meminjam buku '{$buku->judul}'. Harap kembalikan sebelum " . $tanggalDeadline->translatedFormat('l, d F Y H:i') . ' WIB');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat meminjam buku', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}