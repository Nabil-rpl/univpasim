<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Buku;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'buku'])->get();
        return view('admin.peminjamans.index', compact('peminjamans'));
    }

    public function create()
    {
        $users = User::where('role', 'mahasiswa')->get();
        $bukus = Buku::all();
        return view('admin.peminjamans.create', compact('users', 'bukus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_pinjam' => 'required|date',
        ]);

        Peminjaman::create([
            'user_id' => $request->user_id,
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'status' => 'dipinjam'
        ]);

        return redirect()->route('peminjamans.index')->with('success', 'Peminjaman berhasil dibuat.');
    }

    public function show(Peminjaman $peminjaman)
    {
        return view('admin.peminjamans.show', compact('peminjaman'));
    }

    public function edit(Peminjaman $peminjaman)
    {
        $users = User::where('role', 'mahasiswa')->get();
        $bukus = Buku::all();
        return view('admin.peminjamans.edit', compact('peminjaman', 'users', 'bukus'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date',
            'status' => 'required|in:dipinjam,dikembalikan'
        ]);

        $peminjaman->update($request->all());

        return redirect()->route('peminjamans.index')->with('success', 'Data peminjaman berhasil diupdate.');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();
        return redirect()->route('peminjamans.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }
}