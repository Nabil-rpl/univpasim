<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Laporan;

class LaporanController extends Controller
{
    public function index()
    {
        $laporans = Laporan::where('dibuat_oleh', Auth::id())->get();
        return view('petugas.laporan.index', compact('laporans'));
    }

    public function create()
    {
        return view('petugas.laporan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        Laporan::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'status' => 'Belum Diproses',
            'dibuat_oleh' => Auth::id(),
        ]);

        return redirect()->route('petugas.laporan.index')->with('success', 'Laporan berhasil dibuat.');
    }

    public function edit($id)
    {
        $laporan = Laporan::where('id', $id)->where('dibuat_oleh', Auth::id())->firstOrFail();
        return view('petugas.laporan.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $laporan = Laporan::where('id', $id)->where('dibuat_oleh', Auth::id())->firstOrFail();

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        $laporan->update($request->only('judul', 'isi'));

        return redirect()->route('petugas.laporan.index')->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $laporan = Laporan::where('id', $id)->where('dibuat_oleh', Auth::id())->firstOrFail();
        $laporan->delete();

        return redirect()->route('petugas.laporan.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
