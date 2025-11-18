<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perpanjangan;
use Illuminate\Http\Request;

class PerpanjanganController extends Controller
{
    /**
     * Tampilkan daftar semua perpanjangan (Admin Read-Only)
     */
    public function index(Request $request)
    {
        $query = Perpanjangan::with(['peminjaman.buku', 'peminjaman.mahasiswa', 'petugas']);

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
                    $buku->where('judul', 'like', "%{$search}%")
                        ->orWhere('kode_buku', 'like', "%{$search}%");
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
        $perpanjangan = Perpanjangan::with([
            'peminjaman.buku',
            'peminjaman.mahasiswa',
            'peminjaman.petugas',
            'petugas'
        ])->findOrFail($id);

        return view('admin.perpanjangan.show', compact('perpanjangan'));
    }

    /**
     * Export data perpanjangan (opsional)
     */
    public function export(Request $request)
    {
        $query = Perpanjangan::with(['peminjaman.buku', 'peminjaman.mahasiswa', 'petugas']);

        // Apply filters sama seperti di index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $perpanjangan = $query->orderBy('created_at', 'desc')->get();

        // Return sebagai CSV atau Excel
        $filename = 'perpanjangan_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($perpanjangan) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'ID',
                'Tanggal Perpanjangan',
                'Nama Peminjam',
                'NIM/NIK',
                'Judul Buku',
                'Kode Buku',
                'Deadline Lama',
                'Deadline Baru',
                'Durasi Tambahan (Hari)',
                'Status',
                'Diproses Oleh',
                'Alasan',
                'Catatan Petugas'
            ]);

            // Data
            foreach ($perpanjangan as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->tanggal_perpanjangan->format('d/m/Y'),
                    $item->peminjaman->mahasiswa->name ?? '-',
                    $item->peminjaman->mahasiswa->nim ?? $item->peminjaman->mahasiswa->nik ?? '-',
                    $item->peminjaman->buku->judul ?? '-',
                    $item->peminjaman->buku->kode_buku ?? '-',
                    $item->tanggal_deadline_lama->format('d/m/Y'),
                    $item->tanggal_deadline_baru->format('d/m/Y'),
                    $item->durasi_tambahan,
                    $item->getStatusLabel(),
                    $item->petugas->name ?? '-',
                    $item->alasan ?? '-',
                    $item->catatan_petugas ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}