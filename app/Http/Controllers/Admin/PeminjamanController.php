<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;

class PeminjamanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Tampilkan daftar peminjaman (Read Only)
     */
    public function index()
    {
        // Ambil semua peminjaman dengan relasi mahasiswa, buku, dan petugas
        $peminjamans = Peminjaman::with(['mahasiswa', 'buku', 'petugas'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.peminjaman.index', compact('peminjamans'));
    }
}