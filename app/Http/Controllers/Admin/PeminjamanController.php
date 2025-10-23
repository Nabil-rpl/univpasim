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
     * Tampilkan daftar peminjaman (Read Only for Admin)
     * Admin hanya bisa melihat data, tidak bisa create/update/delete
     */
    public function index()
    {
        // Ambil semua peminjaman dengan relasi mahasiswa, buku, dan petugas
        // Urutkan berdasarkan yang terbaru
        $peminjamans = Peminjaman::with(['mahasiswa', 'buku', 'petugas'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    /**
     * Tampilkan detail peminjaman (optional - jika diperlukan)
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with(['mahasiswa', 'buku', 'petugas'])
            ->findOrFail($id);

        return view('admin.peminjaman.show', compact('peminjaman'));
    }
}