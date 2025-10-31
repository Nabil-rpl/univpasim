<?php

namespace App\Http\Controllers\PenggunaLuar;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:pengguna_luar']);
    }

    /**
     * Tampilkan halaman riwayat peminjaman
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Query peminjaman berdasarkan user login
        $query = Peminjaman::where('mahasiswa_id', $user->id)
            ->with(['buku', 'petugas']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal pinjam
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_sampai);
        }

        // Filter berdasarkan bulan (opsional)
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_pinjam', $request->bulan);
        }

        // Filter berdasarkan tahun (opsional)
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_pinjam', $request->tahun);
        }

        // Pencarian berdasarkan judul buku
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('buku', function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%");
            });
        }

        // Ambil data dan urutkan dari terbaru
        $peminjaman = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistik peminjaman
        $totalPeminjaman = Peminjaman::where('mahasiswa_id', $user->id)->count();
        $peminjamanAktif = Peminjaman::where('mahasiswa_id', $user->id)
            ->where('status', 'dipinjam')
            ->count();
        $peminjamanSelesai = Peminjaman::where('mahasiswa_id', $user->id)
            ->where('status', 'dikembalikan')
            ->count();

        return view('pengguna-luar.peminjaman.riwayat', compact(
            'peminjaman', 
            'user',
            'totalPeminjaman',
            'peminjamanAktif',
            'peminjamanSelesai'
        ));
    }
}