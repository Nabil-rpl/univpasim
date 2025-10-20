<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:mahasiswa']);
    }

    /**
     * Tampilkan semua buku
     */
    public function index(Request $request)
    {
        $query = Buku::query();

        // Fitur pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('judul', 'like', "%{$search}%")
                ->orWhere('penulis', 'like', "%{$search}%")
                ->orWhere('penerbit', 'like', "%{$search}%");
        }

        // Urutkan dari terbaru
        $buku = $query->orderBy('created_at', 'desc')->paginate(8);

        return view('mahasiswa.buku.index', compact('buku'));
    }

    /**
     * Tampilkan detail satu buku
     */
    public function show($id)
    {
        $buku = Buku::findOrFail($id);
        return view('mahasiswa.buku.show', compact('buku'));
    }
}
