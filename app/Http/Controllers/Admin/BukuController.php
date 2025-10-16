<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    // Tampilkan semua data buku
    public function index()
    {
        $buku = Buku::latest()->get(); // variabel tunggal, tapi isinya banyak data
        return view('admin.buku.index', compact('buku'));
    }

    // Tampilkan detail satu buku
    public function show(Buku $buku)
    {
        return view('admin.buku.show', compact('buku'));
    }

    // Hapus buku
    public function destroy(Buku $buku)
    {
        $buku->delete();
        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}
