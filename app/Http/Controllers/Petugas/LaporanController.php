<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = Laporan::with('pembuat')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(10);

        return view('petugas.laporan.index', compact('laporan'));
    }

    public function create()
    {
        return view('petugas.laporan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'isi'   => 'required|string',
        ]);

        Laporan::create([
            'bulan'       => $validated['bulan'],
            'tahun'       => $validated['tahun'],
            'isi'         => $validated['isi'],
            'dibuat_oleh' => Auth::id(),
        ]);

        return redirect()->route('petugas.laporan.index')
            ->with('success', 'Laporan berhasil dibuat!');
    }

    public function show(Laporan $laporan)
    {
        $laporan->load('pembuat');
        return view('petugas.laporan.show', compact('laporan'));
    }

    public function edit(Laporan $laporan)
    {
        if ($laporan->dibuat_oleh !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        return view('petugas.laporan.edit', compact('laporan'));
    }

    public function update(Request $request, Laporan $laporan)
    {
        if ($laporan->dibuat_oleh !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'isi'   => 'required|string',
        ]);

        $laporan->update($validated);

        return redirect()->route('petugas.laporan.show', $laporan)
            ->with('success', 'Laporan berhasil diperbarui!');
    }

    public function destroy(Laporan $laporan)
    {
        if ($laporan->dibuat_oleh !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $laporan->delete();

        return redirect()->route('petugas.laporan.index')
            ->with('success', 'Laporan berhasil dihapus!');
    }

    public function exportPdf()
    {
        $laporan = Laporan::with('pembuat')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        $pdf = Pdf::loadView('petugas.laporan.laporan-pdf', compact('laporan'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('rekap-laporan-' . date('d-m-Y') . '.pdf');
    }
}