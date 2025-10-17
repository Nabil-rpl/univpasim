<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Buku;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('judul', 'like', "%{$search}%")
                ->orWhere('penulis', 'like', "%{$search}%");
        }

        $buku = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('petugas.buku.index', compact('buku'));
    }

    public function create()
    {
        return view('petugas.buku.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
            'stok' => 'required|integer|min:1',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto_buku', 'public');
            $validated['foto'] = $path;
        }

        Buku::create($validated);

        return redirect()->route('petugas.buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }


    public function edit(Buku $buku)
    {
        return view('petugas.buku.edit', compact('buku'));
    }

    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
            'stok' => 'required|integer|min:1',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // hapus foto lama jika ada
            if ($buku->foto && Storage::exists('public/' . $buku->foto)) {
                Storage::delete('public/' . $buku->foto);
            }
            $path = $request->file('foto')->store('foto_buku', 'public');
            $validated['foto'] = $path;
        }

        $buku->update($validated);

        return redirect()->route('petugas.buku.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    public function show(Buku $buku)
    {
        return view('petugas.buku.show', compact('buku'));
    }



    public function destroy(Buku $buku)
    {
        $buku->delete();
        return redirect()->route('petugas.buku.index')->with('success', 'Buku berhasil dihapus!');
    }

    // app/Models/Buku.php
    public function qrCode()
    {
        return $this->hasOne(\App\Models\QRCode::class, 'buku_id');
    }
}
