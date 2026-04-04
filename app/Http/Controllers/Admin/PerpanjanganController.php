<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perpanjangan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PerpanjanganController extends Controller
{
    /**
     * Tampilkan daftar semua perpanjangan (Admin Read-Only)
     */
    public function index(Request $request)
    {
        // ✅ Tambahkan qrCode di eager loading
        $query = Perpanjangan::with([
            'peminjaman.buku.qrCode',  // Load relasi qrCode
            'peminjaman.mahasiswa', 
            'petugas'
        ]);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan role peminjam
        if ($request->filled('role')) {
            $query->whereHas('peminjaman.mahasiswa', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('peminjaman.mahasiswa', function($mhs) use ($search) {
                    $mhs->where('name', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%")
                        ->orWhere('no_hp', 'like', "%{$search}%");
                })
                ->orWhereHas('peminjaman.buku', function($buku) use ($search) {
                    $buku->where('judul', 'like', "%{$search}%");
                })
                // ✅ Tambahkan pencarian di qrCode
                ->orWhereHas('peminjaman.buku.qrCode', function($qr) use ($search) {
                    $qr->where('kode_buku', 'like', "%{$search}%");
                });
            });
        }

        $perpanjangan = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends(request()->query());

        // Statistik
        $stats = [
            'menunggu' => Perpanjangan::where('status', 'menunggu')->count(),
            'disetujui' => Perpanjangan::where('status', 'disetujui')->count(),
            'ditolak' => Perpanjangan::where('status', 'ditolak')->count(),
            'dibatalkan' => Perpanjangan::where('status', 'dibatalkan')->count(),
            'total' => Perpanjangan::count(),
        ];

        return view('admin.perpanjangan.index', compact('perpanjangan', 'stats'));
    }

    /**
     * Detail perpanjangan
     */
    public function show($id)
    {
        // ✅ Tambahkan qrCode di eager loading
        $perpanjangan = Perpanjangan::with([
            'peminjaman.buku.qrCode',  // Load relasi qrCode
            'peminjaman.mahasiswa',
            'peminjaman.petugas',
            'petugas'
        ])->findOrFail($id);

        return view('admin.perpanjangan.show', compact('perpanjangan'));
    }

    /**
     * Export data perpanjangan to PDF
     */
    public function export(Request $request)
    {
        $query = Perpanjangan::with([
            'peminjaman.buku.qrCode',
            'peminjaman.mahasiswa',
            'petugas'
        ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('peminjaman.mahasiswa', function($mhs) use ($search) {
                    $mhs->where('name', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                })
                ->orWhereHas('peminjaman.buku', function($buku) use ($search) {
                    $buku->where('judul', 'like', "%{$search}%");
                });
            });
        }

        $perpanjangan = $query->orderBy('created_at', 'desc')->get();
        $status       = $request->status ? ucfirst($request->status) : 'Semua Status';
        $tanggal      = now()->format('d/m/Y H:i');

        $pdf = Pdf::loadView('admin.perpanjangan.pdf', compact('perpanjangan', 'status', 'tanggal'))
                  ->setPaper('a4', 'landscape');

        $filename = 'perpanjangan_' . date('YmdHis') . '.pdf';

        return $pdf->download($filename);
    }
}