<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;

class BukuController extends Controller
{
    // 🔹 Tampilkan semua data buku
    public function index()
    {
        $buku = Buku::latest()->paginate(10); // pakai pagination
        return view('admin.buku.index', compact('buku'));
    }

    // 🔹 Tampilkan detail buku dengan relasi QR Code dan Petugas
    public function show(Buku $buku)
    {
        // Load relasi qrCode dan petugas yang membuat QR Code
        $buku->load(['qrCode.petugas']);
        
        return view('admin.buku.show', compact('buku'));
    }

    // 🔹 Hapus buku
    public function destroy(Buku $buku)
    {
        // Hapus QR Code terkait jika ada
        if ($buku->qrCode) {
            $buku->qrCode->delete();
        }
        
        $buku->delete();
        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}