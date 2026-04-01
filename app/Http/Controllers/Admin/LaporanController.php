<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Admin hanya bisa melihat daftar laporan (READ ONLY)
     */
    public function index()
    {
        $laporan = Laporan::with('pembuat')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(10);

        return view('admin.laporan.index', compact('laporan'));
    }

    /**
     * Admin hanya bisa melihat detail laporan (READ ONLY)
     */
    public function show(Laporan $laporan)
    {
        $laporan->load('pembuat');
        return view('admin.laporan.show', compact('laporan'));
    }

    /**
     * Export semua laporan ke PDF
     */
    public function exportPdf()
    {
        $laporan = Laporan::with('pembuat')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        $pdf = Pdf::loadView('admin.laporan.laporan-pdf', compact('laporan'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('rekap-laporan-' . date('d-m-Y') . '.pdf');
    }
}