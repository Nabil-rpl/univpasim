<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DendaController extends Controller
{
    /**
     * Display a listing of fines/denda.
     */
    public function index(Request $request)
    {
        // Hitung statistik denda
        $stats = [
            'terlambat' => Peminjaman::where('status', 'dipinjam')
                ->whereNotNull('tanggal_deadline')
                ->where('tanggal_deadline', '<', now())
                ->count(),
            
            'belum_bayar' => Pengembalian::where('denda_dibayar', false)
                ->where('denda', '>', 0)
                ->sum('denda'),
            
            'sudah_bayar' => Pengembalian::where('denda_dibayar', true)
                ->where('denda', '>', 0)
                ->sum('denda'),
        ];

        // Query untuk mendapatkan daftar denda
        $query = Peminjaman::with(['mahasiswa', 'buku', 'pengembalian'])
            ->where(function($q) {
                // Peminjaman yang sedang terlambat (masih dipinjam)
                $q->where('status', 'dipinjam')
                  ->whereNotNull('tanggal_deadline')
                  ->where('tanggal_deadline', '<', now());
            })
            ->orWhereHas('pengembalian', function($q) {
                // Peminjaman yang sudah dikembalikan tapi ada denda
                $q->where('denda', '>', 0);
            });

        // Filter pencarian (nama peminjam atau judul buku)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('mahasiswa', function($mhs) use ($search) {
                    $mhs->where('name', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('buku', function($bk) use ($search) {
                    $bk->where('judul', 'like', "%{$search}%")
                       ->orWhere('penulis', 'like', "%{$search}%");
                });
            });
        }

        // Filter status pembayaran
        if ($request->filled('status_bayar')) {
            if ($request->status_bayar == 'belum_bayar') {
                $query->where(function($q) {
                    // Masih dipinjam dan terlambat (otomatis belum bayar)
                    $q->where('status', 'dipinjam')
                      ->whereNotNull('tanggal_deadline')
                      ->where('tanggal_deadline', '<', now())
                      // Atau sudah dikembalikan tapi belum bayar denda
                      ->orWhereHas('pengembalian', function($pen) {
                          $pen->where('denda_dibayar', false)
                              ->where('denda', '>', 0);
                      });
                });
            } elseif ($request->status_bayar == 'sudah_bayar') {
                $query->whereHas('pengembalian', function($q) {
                    $q->where('denda_dibayar', true)
                      ->where('denda', '>', 0);
                });
            }
        }

        // Filter role peminjam
        if ($request->filled('role')) {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        // Filter berdasarkan range tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_sampai);
        }

        // Urutkan berdasarkan yang terbaru
        $dendaList = $query->orderBy('created_at', 'desc')->paginate(15);

        // Append query string untuk pagination
        $dendaList->appends($request->all());

        return view('petugas.denda.index', compact('stats', 'dendaList'));
    }

    /**
     * Export denda ke PDF
     */
    public function exportPdf(Request $request)
    {
        // Query untuk mendapatkan peminjaman terlambat (sama seperti di index)
        $query = Peminjaman::with(['mahasiswa', 'buku', 'pengembalian'])
            ->where(function($q) {
                // Peminjaman yang sedang terlambat
                $q->where('status', 'dipinjam')
                  ->whereNotNull('tanggal_deadline')
                  ->where('tanggal_deadline', '<', now());
            })
            ->orWhereHas('pengembalian', function($q) {
                // Peminjaman yang sudah dikembalikan tapi ada denda
                $q->where('denda', '>', 0);
            });

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('mahasiswa', function($mhs) use ($search) {
                    $mhs->where('name', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('buku', function($bk) use ($search) {
                    $bk->where('judul', 'like', "%{$search}%")
                       ->orWhere('penulis', 'like', "%{$search}%");
                });
            });
        }

        // Filter status pembayaran
        if ($request->filled('status_bayar')) {
            if ($request->status_bayar == 'belum_bayar') {
                $query->where(function($q) {
                    $q->where('status', 'dipinjam')
                      ->whereNotNull('tanggal_deadline')
                      ->where('tanggal_deadline', '<', now())
                      ->orWhereHas('pengembalian', function($pen) {
                          $pen->where('denda_dibayar', false)
                              ->where('denda', '>', 0);
                      });
                });
            } elseif ($request->status_bayar == 'sudah_bayar') {
                $query->whereHas('pengembalian', function($q) {
                    $q->where('denda_dibayar', true)
                      ->where('denda', '>', 0);
                });
            }
        }

        // Filter role
        if ($request->filled('role')) {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        // Filter berdasarkan range tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_sampai);
        }

        $dendaList = $query->orderBy('created_at', 'desc')->get();

        // Hitung statistik
        $totalPeminjamTerlambat = Peminjaman::where('status', 'dipinjam')
            ->whereNotNull('tanggal_deadline')
            ->where('tanggal_deadline', '<', now())
            ->count();
        
        $totalDendaBelumBayar = Pengembalian::where('denda_dibayar', false)
            ->where('denda', '>', 0)
            ->sum('denda');
        
        $totalDendaSudahBayar = Pengembalian::where('denda_dibayar', true)
            ->where('denda', '>', 0)
            ->sum('denda');

        // Data untuk PDF
        $data = [
            'dendaList' => $dendaList,
            'totalPeminjamTerlambat' => $totalPeminjamTerlambat,
            'totalDendaBelumBayar' => $totalDendaBelumBayar,
            'totalDendaSudahBayar' => $totalDendaSudahBayar,
            'tanggal_cetak' => now()->format('d F Y H:i:s'),
            'filters' => [
                'search' => $request->search,
                'status_bayar' => $request->status_bayar,
                'role' => $request->role,
                'tanggal_dari' => $request->tanggal_dari,
                'tanggal_sampai' => $request->tanggal_sampai,
            ]
        ];

        // Generate PDF
        $pdf = Pdf::loadView('petugas.denda.pdf', $data)
                  ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-denda-' . now()->format('Y-m-d-His') . '.pdf');
    }

    /**
     * Tandai denda sebagai sudah dibayar
     */
    public function bayar(Request $request, $id)
    {
        $pengembalian = Pengembalian::findOrFail($id);

        // Validasi
        if ($pengembalian->denda_dibayar) {
            return redirect()->back()->with('error', 'Denda sudah dibayar sebelumnya.');
        }

        if ($pengembalian->denda <= 0) {
            return redirect()->back()->with('error', 'Tidak ada denda yang harus dibayar.');
        }

        // Update status pembayaran
        $pengembalian->update([
            'denda_dibayar' => true,
            'denda_dibayar_pada' => now(),
            'catatan_pembayaran' => $request->catatan ?? 'Pembayaran denda oleh ' . auth()->user()->name,
        ]);

        return redirect()->back()->with('success', 'Denda berhasil ditandai sebagai lunas!');
    }

    /**
     * Batalkan status pembayaran denda
     */
    public function batalBayar($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);

        // Validasi
        if (!$pengembalian->denda_dibayar) {
            return redirect()->back()->with('error', 'Denda belum dibayar.');
        }

        // Reset status pembayaran
        $pengembalian->update([
            'denda_dibayar' => false,
            'denda_dibayar_pada' => null,
            'catatan_pembayaran' => null,
        ]);

        return redirect()->back()->with('success', 'Status pembayaran denda berhasil dibatalkan!');
    }

    /**
     * Export denda ke Excel/PDF (opsional)
     */
    public function export(Request $request)
    {
        // Redirect ke exportPdf
        return $this->exportPdf($request);
    }

    /**
     * Tampilkan detail denda per peminjam
     */
    public function show($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        
        // Ambil semua denda dari user ini
        $dendaList = Pengembalian::whereHas('peminjaman', function($q) use ($userId) {
            $q->where('mahasiswa_id', $userId);
        })
        ->where('denda', '>', 0)
        ->with(['peminjaman.buku'])
        ->orderBy('created_at', 'desc')
        ->get();

        // Hitung total
        $totalDenda = $dendaList->sum('denda');
        $totalBelumBayar = $dendaList->where('denda_dibayar', false)->sum('denda');
        $totalSudahBayar = $dendaList->where('denda_dibayar', true)->sum('denda');

        return view('petugas.denda.show', compact('user', 'dendaList', 'totalDenda', 'totalBelumBayar', 'totalSudahBayar'));
    }

    /**
     * Kirim notifikasi/reminder denda (opsional)
     */
    public function kirimReminder($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // TODO: Implementasi kirim email/SMS reminder
        // Bisa menggunakan Laravel Mail atau service SMS

        return redirect()->back()->with('info', 'Fitur kirim reminder sedang dalam pengembangan.');
    }
}