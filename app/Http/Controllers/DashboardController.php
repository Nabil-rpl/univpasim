<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Laporan;
use App\Models\QRCode;
use App\Models\Buku;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    /**
     * Dashboard Admin dengan data user management
     */
    public function admin()
    {
        // Ambil semua data user
        $users = User::orderBy('created_at', 'desc')->get();
        
        // Kirim ke view admin.dashboard
        return view('admin.dashboard', compact('users'));
    }

    /**
     * Dashboard Petugas
     */
    public function petugas()
    {
        $user = Auth::user();

        // Ambil semua laporan milik petugas yang login
        $laporans = Laporan::where('dibuat_oleh', $user->id)->get();

        // Ambil semua QR code milik petugas
        $qrcodes = QRCode::where('user_id', $user->id)->get();

        // Kirim ke view petugas.dashboard
        return view('petugas.dashboard', compact('laporans', 'qrcodes'));
    }

    /**
     * Dashboard Mahasiswa
     */
    public function mahasiswa()
    {
        return view('mahasiswa.dashboard');
    }

    /**
     * Dashboard Pengguna Luar
     */
    public function penggunaLuar()
    {
        $user = Auth::user();

        // Statistik
        $totalBuku = Buku::count();
        $bukuTersedia = Buku::where('stok', '>', 0)->count();
        $peminjamanAktif = Peminjaman::where('mahasiswa_id', $user->id)
            ->where('status', 'dipinjam')
            ->count();

        // Riwayat peminjaman terakhir (5 data)
        $riwayatPeminjaman = Peminjaman::where('mahasiswa_id', $user->id)
            ->with(['buku'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('pengguna-luar.dashboard', compact(
            'totalBuku',
            'bukuTersedia',
            'peminjamanAktif',
            'riwayatPeminjaman'
        ));
    }
}