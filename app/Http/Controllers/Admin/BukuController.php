<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        $bukus = Buku::all();
        return view('admin.bukus.index', compact('bukus'));
    }

    public function create()
    {
        return view('admin.bukus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'tahun' => 'required|numeric|min:1900|max:' . date('Y'),
            'penerbit' => 'required|string|max:255',
        ]);

        Buku::create($request->all());
        return redirect()->route('bukus.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function show(Buku $buku)
    {
        return view('admin.bukus.show', compact('buku'));
    }

    public function edit(Buku $buku)
    {
        return view('admin.bukus.edit', compact('buku'));
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'tahun' => 'required|numeric|min:1900|max:' . date('Y'),
            'penerbit' => 'required|string|max:255',
        ]);

        $buku->update($request->all());
        return redirect()->route('bukus.index')->with('success', 'Buku berhasil diupdate.');
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();
        return redirect()->route('bukus.index')->with('success', 'Buku berhasil dihapus.');
    }
}