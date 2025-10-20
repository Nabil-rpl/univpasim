<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Mahasiswa as MahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /**
     * Tampilkan riwayat peminjaman mahasiswa (halaman index)
     */
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = MahasiswaModel::where('email', $user->email)->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Ambil semua data peminjaman mahasiswa
        $peminjamans = Peminjaman::where('mahasiswa_id', $mahasiswa->id)
            ->with(['buku', 'petugas'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('mahasiswa.peminjaman.index', compact('peminjamans', 'mahasiswa'));
    }

    /**
     * Tampilkan daftar buku yang bisa dipinjam (halaman pinjam buku)
     */
    public function daftarBuku(Request $request)
    {
        $query = Buku::query()->where('stok', '>', 0);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('penulis', 'like', "%{$search}%")
                    ->orWhere('penerbit', 'like', "%{$search}%");
            });
        }

        $bukus = $query->orderBy('judul')->paginate(12);

        // Data mahasiswa login
        $user = Auth::user();
        $mahasiswa = MahasiswaModel::where('email', $user->email)->first();

        // Buku yang sedang dipinjam
        $peminjamanAktif = [];
        if ($mahasiswa) {
            $peminjamanAktif = Peminjaman::where('mahasiswa_id', $mahasiswa->id)
                ->where('status', 'dipinjam')
                ->pluck('buku_id')
                ->toArray();
        }

        return view('mahasiswa.peminjaman.daftar-buku', compact('bukus', 'mahasiswa', 'peminjamanAktif'));
    }

    /**
     * Proses pinjam buku
     */
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id',
        ]);

        $user = Auth::user();
        $mahasiswa = MahasiswaModel::where('email', $user->email)->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan. Silakan hubungi admin.');
        }

        DB::beginTransaction();
        try {
            $buku = Buku::findOrFail($request->buku_id);

            // Cek stok buku
            if ($buku->stok <= 0) {
                return redirect()->back()->with('error', 'Stok buku tidak tersedia.');
            }

            // Cek apakah mahasiswa sudah meminjam buku ini dan belum dikembalikan
            $sudahPinjam = Peminjaman::where('mahasiswa_id', $mahasiswa->id)
                ->where('buku_id', $buku->id)
                ->where('status', 'dipinjam')
                ->exists();

            if ($sudahPinjam) {
                return redirect()->back()->with('error', 'Anda sudah meminjam buku ini.');
            }

            // Cek batas maksimal peminjaman
            $jumlahPinjaman = Peminjaman::where('mahasiswa_id', $mahasiswa->id)
                ->where('status', 'dipinjam')
                ->count();

            if ($jumlahPinjaman >= 3) {
                return redirect()->back()->with('error', 'Anda sudah mencapai batas maksimal peminjaman (3 buku).');
            }

            // Buat peminjaman baru
            Peminjaman::create([
                'mahasiswa_id' => $mahasiswa->id,
                'buku_id' => $buku->id,
                'petugas_id' => null,
                'tanggal_pinjam' => now(),
                'tanggal_kembali' => null,
                'status' => 'dipinjam',
            ]);

            // Kurangi stok buku
            $buku->decrement('stok');

            DB::commit();
            return redirect()->route('mahasiswa.peminjaman.index')
                ->with('success', "Berhasil meminjam buku '{$buku->judul}'.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Detail peminjaman
     */
    public function show($id)
    {
        $user = Auth::user();
        $mahasiswa = MahasiswaModel::where('email', $user->email)->first();

        $peminjaman = Peminjaman::where('mahasiswa_id', $mahasiswa->id)
            ->where('id', $id)
            ->with(['buku', 'petugas'])
            ->firstOrFail();

        return view('mahasiswa.peminjaman.show', compact('peminjaman'));
    }
    public function riwayat()
    {
        $mahasiswa = auth()->user();
        $peminjaman = \App\Models\Peminjaman::where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.peminjaman.riwayat', compact('peminjaman', 'mahasiswa'));
    }
    public function pinjam($id)
    {
        $mahasiswa = auth()->user();

        // Cek apakah buku ada
        $buku = \App\Models\Buku::findOrFail($id);

        // Cek stok
        if ($buku->stok <= 0) {
            return redirect()->back()->with('error', 'Stok buku habis!');
        }

        // Cek apakah sudah meminjam buku ini dan belum dikembalikan
        $sudahPinjam = \App\Models\Peminjaman::where('mahasiswa_id', $mahasiswa->id)
            ->where('buku_id', $buku->id)
            ->where('status', 'dipinjam')
            ->exists();

        if ($sudahPinjam) {
            return redirect()->back()->with('error', 'Kamu sudah meminjam buku ini dan belum mengembalikannya.');
        }

        // Simpan data peminjaman
        \App\Models\Peminjaman::create([
            'mahasiswa_id' => $mahasiswa->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now(),
            'status' => 'dipinjam',
        ]);

        // Kurangi stok buku
        $buku->decrement('stok');

        return redirect()->route('mahasiswa.peminjaman.index')->with('success', 'Buku berhasil dipinjam!');
    }
}
