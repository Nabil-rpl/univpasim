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

class PeminjamanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:mahasiswa']);
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

        // ✅ GUNAKAN $user->id (konsisten dengan pinjam())
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

        // ✅ SUDAH BENAR - menggunakan $user->id
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
     * ✅ DIPERBAIKI - sekarang konsisten menggunakan $user->id
     */
    public function show($id)
    {
        $user = Auth::user();
        $mahasiswa = MahasiswaModel::where('email', $user->email)->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // ✅ SPESIFIKKAN TABEL untuk menghindari ambiguitas
        $peminjaman = Peminjaman::where('peminjaman.mahasiswa_id', $user->id)
            ->where('peminjaman.id', $id)
            ->with(['buku', 'petugas'])
            ->firstOrFail();

        return view('mahasiswa.peminjaman.show', compact('peminjaman', 'mahasiswa'));
    }

    /**
     * Proses pinjam buku (dipanggil dari route mahasiswa.buku.pinjam)
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

            // ✅ Cek peminjaman menggunakan $user->id (konsisten!)
            $sudahPinjam = Peminjaman::where('mahasiswa_id', $user->id)
                ->where('buku_id', $buku->id)
                ->where('status', 'dipinjam')
                ->exists();

            if ($sudahPinjam) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Anda sudah meminjam buku ini dan belum mengembalikannya.');
            }

            // ✅ Cek batas maksimal menggunakan $user->id
            $jumlahPinjaman = Peminjaman::where('mahasiswa_id', $user->id)
                ->where('status', 'dipinjam')
                ->count();

            if ($jumlahPinjaman >= 3) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Anda sudah mencapai batas maksimal peminjaman (3 buku).');
            }

            // ✅ Buat peminjaman dengan Auth::id() / $user->id
            $peminjaman = Peminjaman::create([
                'mahasiswa_id' => $user->id, // atau Auth::id()
                'buku_id' => $buku->id,
                'petugas_id' => null,
                'tanggal_pinjam' => now(),
                'tanggal_kembali' => null,
                'status' => 'dipinjam',
            ]);

            Log::info('Peminjaman dibuat', ['peminjaman_id' => $peminjaman->id]);

            // Kurangi stok buku
            $buku->decrement('stok');
            Log::info('Stok buku dikurangi', ['stok_baru' => $buku->fresh()->stok]);

            DB::commit();

            return redirect()->route('mahasiswa.peminjaman.index')
                ->with('success', "Berhasil meminjam buku '{$buku->judul}'.");
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