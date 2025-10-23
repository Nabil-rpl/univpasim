<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    // Menampilkan daftar laporan
    public function index()
    {
        $laporan = Laporan::with('pembuat')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('petugas.laporan.index', compact('laporan'));
    }

    // Form untuk membuat laporan baru
    public function create()
    {
        return view('petugas.laporan.create');
    }

    // Menyimpan laporan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        Laporan::create([
            'judul' => $validated['judul'],
            'isi' => $validated['isi'],
            'dibuat_oleh' => Auth::id(),
        ]);

        return redirect()->route('petugas.laporan.index')
            ->with('success', 'Laporan berhasil dibuat!');
    }

    // Menampilkan detail laporan
    public function show(Laporan $laporan)
    {
        $laporan->load('pembuat');
        return view('petugas.laporan.show', compact('laporan'));
    }

    // Form untuk edit laporan
    public function edit(Laporan $laporan)
    {
        // Hanya pembuat atau admin yang bisa edit
        if ($laporan->dibuat_oleh !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit laporan ini.');
        }

        return view('petugas.laporan.edit', compact('laporan'));
    }

    // Update laporan
    public function update(Request $request, Laporan $laporan)
    {
        // Hanya pembuat atau admin yang bisa update
        if ($laporan->dibuat_oleh !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit laporan ini.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        $laporan->update($validated);

        return redirect()->route('petugas.laporan.show', $laporan)
            ->with('success', 'Laporan berhasil diperbarui!');
    }

    // Hapus laporan
    public function destroy(Laporan $laporan)
    {
        // Hanya pembuat atau admin yang bisa hapus
        if ($laporan->dibuat_oleh !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses untuk menghapus laporan ini.');
        }

        $laporan->delete();

        return redirect()->route('petugas.laporan.index')
            ->with('success', 'Laporan berhasil dihapus!');
    }
}