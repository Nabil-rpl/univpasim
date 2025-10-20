<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:mahasiswa']);
    }

    public function index()
    {
        $user = Auth::user(); // data user login
        $mahasiswa = Mahasiswa::where('nim', $user->nim)->first(); // ambil detail mahasiswa

        // Ambil semua data buku dan peminjaman
        $totalBuku = Buku::count();

        // Buku yang tersedia = total buku - buku yang sedang dipinjam
        $bukuDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $bukuTersedia = $totalBuku - $bukuDipinjam;

        // Data peminjaman milik mahasiswa yang login
        $peminjaman = Peminjaman::where('mahasiswa_id', $user->id)->get();
        $jumlahPeminjaman = $peminjaman->count();

        // Jumlah peminjaman aktif (status = dipinjam)
        $peminjamanAktif = Peminjaman::where('mahasiswa_id', $user->id)
            ->where('status', 'dipinjam')
            ->count();

        // Riwayat peminjaman terakhir (5 data terbaru)
        $riwayatPeminjaman = Peminjaman::where('mahasiswa_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('mahasiswa.dashboard', compact(
            'mahasiswa',
            'totalBuku',
            'bukuTersedia',
            'peminjamanAktif',
            'riwayatPeminjaman',
            'jumlahPeminjaman'
        ));
    }

    public function buku()
    {
        $buku = Buku::orderBy('judul', 'asc')->paginate(10);
        return view('mahasiswa.buku.index', compact('buku'));
    }

    public function showBuku($id)
    {
        $buku = Buku::findOrFail($id);
        return view('mahasiswa.buku.show', compact('buku'));
    }

    public function peminjaman()
    {
        $mahasiswa = Auth::user();
        $peminjaman = Peminjaman::where('mahasiswa_id', $mahasiswa->id)->get();

        return view('mahasiswa.peminjaman.index', compact('peminjaman', 'mahasiswa'));
    }
}
