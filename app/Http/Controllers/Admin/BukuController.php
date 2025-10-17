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

    // 🔹 Tampilkan detail buku
    public function show(Buku $buku)
    {
        return view('admin.buku.show', compact('buku'));
    }

    // 🔹 Hapus buku
    public function destroy(Buku $buku)
    {
        $buku->delete();
        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}
