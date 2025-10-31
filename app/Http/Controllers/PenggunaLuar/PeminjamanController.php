<?php

namespace App\Http\Controllers\PenggunaLuar;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PeminjamanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:pengguna_luar']);
    }

    /**
     * Tampilkan daftar peminjaman pengguna luar (halaman index)
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil data peminjaman pengguna luar dengan pagination
        $peminjamans = Peminjaman::where('mahasiswa_id', $user->id)
            ->with(['buku', 'petugas'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pengguna-luar.peminjaman.index', compact('peminjamans', 'user'));
    }

    /**
     * Riwayat peminjaman dengan filter
     */
    public function riwayat(Request $request)
    {
        $user = Auth::user();

        // Query berdasarkan user login
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

        return view('pengguna-luar.peminjaman.riwayat', compact('peminjaman', 'user'));
    }

    /**
     * Detail peminjaman
     */
    public function show($id)
    {
        $user = Auth::user();

        $peminjaman = Peminjaman::where('mahasiswa_id', $user->id)
            ->where('id', $id)
            ->with(['buku', 'petugas'])
            ->firstOrFail();

        return view('pengguna-luar.peminjaman.show', compact('peminjaman', 'user'));
    }

    /**
     * Proses pinjam buku (dipanggil dari route pengguna-luar.buku.pinjam)
     */
    public function pinjam($id)
    {
        try {
            // Log untuk debugging
            Log::info('Pinjam buku dipanggil (Pengguna Luar)', ['buku_id' => $id, 'user_id' => Auth::id()]);

            $user = Auth::user();

            // Validasi role pengguna luar
            if ($user->role !== 'pengguna_luar') {
                Log::error('User bukan pengguna luar', ['role' => $user->role]);
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk meminjam buku.');
            }

            Log::info('Pengguna luar terverifikasi', ['user_id' => $user->id, 'name' => $user->name]);

            DB::beginTransaction();

            $buku = Buku::findOrFail($id);
            Log::info('Buku ditemukan', ['buku' => $buku->judul, 'stok' => $buku->stok]);

            // Cek stok buku
            if ($buku->stok <= 0) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Stok buku tidak tersedia.');
            }

            // Cek apakah pengguna luar sudah meminjam buku ini dan belum dikembalikan
            $sudahPinjam = Peminjaman::where('mahasiswa_id', $user->id)
                ->where('buku_id', $buku->id)
                ->where('status', 'dipinjam')
                ->exists();

            if ($sudahPinjam) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Anda sudah meminjam buku ini dan belum mengembalikannya.');
            }

            // Cek batas maksimal peminjaman untuk pengguna luar (misalnya 2 buku)
            $jumlahPinjaman = Peminjaman::where('mahasiswa_id', $user->id)
                ->where('status', 'dipinjam')
                ->count();

            if ($jumlahPinjaman >= 2) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Anda sudah mencapai batas maksimal peminjaman (2 buku).');
            }

            // Hitung tanggal deadline (7 hari dari tanggal pinjam untuk pengguna luar)
            $tanggalDeadline = now()->addDays(7);

            // Buat peminjaman baru
            $peminjaman = Peminjaman::create([
                'mahasiswa_id' => $user->id,
                'buku_id' => $buku->id,
                'petugas_id' => null,
                'tanggal_pinjam' => now(),
                'tanggal_deadline' => $tanggalDeadline,
                'tanggal_kembali' => null,
                'status' => 'dipinjam',
            ]);

            Log::info('Peminjaman dibuat', ['peminjaman_id' => $peminjaman->id]);

            // Kurangi stok buku
            $buku->decrement('stok');
            Log::info('Stok buku dikurangi', ['stok_baru' => $buku->fresh()->stok]);

            DB::commit();

            return redirect()->route('pengguna-luar.peminjaman.index')
                ->with('success', "Berhasil meminjam buku '{$buku->judul}'. Harap dikembalikan sebelum " . $tanggalDeadline->format('d/m/Y') . ".");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat meminjam buku (Pengguna Luar)', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan peminjaman (jika status masih 'dipinjam' dan belum disetujui petugas)
     */
    public function batal($id)
    {
        try {
            $user = Auth::user();

            DB::beginTransaction();

            $peminjaman = Peminjaman::where('mahasiswa_id', $user->id)
                ->where('id', $id)
                ->where('status', 'dipinjam')
                ->firstOrFail();

            // Kembalikan stok buku
            $peminjaman->buku->increment('stok');

            // Hapus peminjaman atau ubah status
            $peminjaman->delete();

            DB::commit();

            Log::info('Peminjaman dibatalkan', ['peminjaman_id' => $id, 'user_id' => $user->id]);

            return redirect()->route('pengguna-luar.peminjaman.index')
                ->with('success', 'Peminjaman berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat membatalkan peminjaman', [
                'error' => $e->getMessage(),
                'peminjaman_id' => $id
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}