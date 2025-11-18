<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;

class PeminjamanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Tampilkan daftar peminjaman (Read Only for Admin)
     * Admin hanya bisa melihat data, tidak bisa create/update/delete
     */
    public function index(Request $request)
    {
        // Query dasar dengan eager loading + perpanjangan
        $query = Peminjaman::with([
            'mahasiswa.mahasiswa',
            'buku',
            'petugas',
            'perpanjangan' => function($q) {
                $q->orderBy('created_at', 'desc');
            }
        ]);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tipe peminjam (mahasiswa atau pengguna_luar)
        if ($request->filled('tipe_peminjam')) {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('role', $request->tipe_peminjam);
            });
        }

        // Filter peminjaman yang diperpanjang
        if ($request->filled('diperpanjang')) {
            if ($request->diperpanjang == 'ya') {
                $query->has('perpanjangan');
            } elseif ($request->diperpanjang == 'tidak') {
                $query->doesntHave('perpanjangan');
            }
        }

        // Filter berdasarkan bulan/tahun
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal_pinjam', $request->bulan)
                  ->whereYear('tanggal_pinjam', $request->tahun);
        }

        // Search berdasarkan nama peminjam atau judul buku
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('mahasiswa', function($mq) use ($search) {
                    $mq->where('name', 'like', "%{$search}%")
                       ->orWhere('nim', 'like', "%{$search}%")
                       ->orWhere('no_hp', 'like', "%{$search}%");
                })
                ->orWhereHas('buku', function($bq) use ($search) {
                    $bq->where('judul', 'like', "%{$search}%")
                       ->orWhere('penulis', 'like', "%{$search}%");
                });
            });
        }

        // Urutkan berdasarkan yang terbaru
        $peminjamans = $query->orderBy('created_at', 'desc')->paginate(15)->appends(request()->query());

        // Hitung statistik
        $allPeminjamans = Peminjaman::with('perpanjangan')->get();
        $statistics = [
            'total_peminjaman' => $allPeminjamans->count(),
            'total_mahasiswa' => $allPeminjamans->filter(fn($p) => $p->mahasiswa->role === 'mahasiswa')->count(),
            'total_pengguna_luar' => $allPeminjamans->filter(fn($p) => $p->mahasiswa->role === 'pengguna_luar')->count(),
            'sedang_dipinjam' => $allPeminjamans->where('status', 'dipinjam')->count(),
            'sudah_dikembalikan' => $allPeminjamans->where('status', 'dikembalikan')->count(),
            'terlambat' => $allPeminjamans->filter(fn($p) => $p->status === 'dipinjam' && $p->isTerlambat())->count(),
            'diperpanjang' => $allPeminjamans->filter(fn($p) => $p->perpanjangan->isNotEmpty())->count(),
            'total_denda' => $allPeminjamans->filter(fn($p) => $p->status === 'dipinjam' && $p->isTerlambat())
                                        ->sum(fn($p) => $p->hitungDenda())
        ];

        return view('admin.peminjaman.index', compact('peminjamans', 'statistics'));
    }

    /**
     * Tampilkan detail peminjaman
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with([
            'mahasiswa.mahasiswa',
            'buku',
            'petugas',
            'perpanjangan' => function($q) {
                $q->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id);

        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    /**
     * Export data peminjaman ke CSV
     */
    public function export(Request $request)
    {
        $query = Peminjaman::with([
            'mahasiswa.mahasiswa',
            'buku',
            'petugas',
            'perpanjangan'
        ]);

        // Terapkan filter yang sama dengan index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tipe_peminjam')) {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('role', $request->tipe_peminjam);
            });
        }

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal_pinjam', $request->bulan)
                  ->whereYear('tanggal_pinjam', $request->tahun);
        }

        $peminjamans = $query->orderBy('created_at', 'desc')->get();

        $filename = 'peminjaman_' . date('YmdHis') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($peminjamans) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'No',
                'Nama Peminjam',
                'Tipe Peminjam',
                'NIM/No HP',
                'Judul Buku',
                'Penulis',
                'Tanggal Pinjam',
                'Durasi (Hari)',
                'Tanggal Deadline',
                'Tanggal Kembali',
                'Status',
                'Terlambat (Hari)',
                'Denda (Rp)',
                'Diperpanjang',
                'Jumlah Perpanjangan',
                'Petugas',
                'Tanggal Transaksi'
            ]);
            
            // Data
            foreach ($peminjamans as $index => $item) {
                $tipePeminjam = $item->mahasiswa->role === 'mahasiswa' ? 'Mahasiswa' : 'Pengguna Luar';
                
                if ($item->mahasiswa->role === 'mahasiswa') {
                    $identitas = $item->mahasiswa->mahasiswa->nim ?? $item->mahasiswa->nim ?? '-';
                } else {
                    $identitas = $item->mahasiswa->no_hp ?? '-';
                }

                $hariTerlambat = 0;
                $denda = 0;
                if ($item->status === 'dipinjam' && $item->isTerlambat()) {
                    $hariTerlambat = $item->getHariTerlambat();
                    $denda = $item->hitungDenda();
                }

                $jumlahPerpanjangan = $item->perpanjangan->count();
                $diperpanjang = $jumlahPerpanjangan > 0 ? 'Ya' : 'Tidak';

                fputcsv($file, [
                    $index + 1,
                    $item->mahasiswa->name,
                    $tipePeminjam,
                    $identitas,
                    $item->buku->judul,
                    $item->buku->penulis,
                    $item->tanggal_pinjam->format('d/m/Y H:i'),
                    $item->durasi_hari,
                    $item->tanggal_deadline ? $item->tanggal_deadline->format('d/m/Y') : '-',
                    $item->tanggal_kembali ? $item->tanggal_kembali->format('d/m/Y H:i') : '-',
                    ucfirst($item->status),
                    $hariTerlambat,
                    $denda,
                    $diperpanjang,
                    $jumlahPerpanjangan,
                    $item->petugas ? $item->petugas->name : 'Sistem',
                    $item->created_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Statistik peminjaman (untuk dashboard atau API)
     */
    public function statistics()
    {
        $peminjamans = Peminjaman::with(['mahasiswa', 'buku', 'perpanjangan'])->get();

        $stats = [
            'overview' => [
                'total_peminjaman' => $peminjamans->count(),
                'sedang_dipinjam' => $peminjamans->where('status', 'dipinjam')->count(),
                'sudah_dikembalikan' => $peminjamans->where('status', 'dikembalikan')->count(),
                'terlambat' => $peminjamans->filter(fn($p) => $p->status === 'dipinjam' && $p->isTerlambat())->count(),
                'diperpanjang' => $peminjamans->filter(fn($p) => $p->perpanjangan->isNotEmpty())->count(),
            ],
            'by_user_type' => [
                'mahasiswa' => $peminjamans->filter(fn($p) => $p->mahasiswa->role === 'mahasiswa')->count(),
                'pengguna_luar' => $peminjamans->filter(fn($p) => $p->mahasiswa->role === 'pengguna_luar')->count(),
            ],
            'financial' => [
                'total_denda_aktif' => $peminjamans->filter(fn($p) => $p->status === 'dipinjam' && $p->isTerlambat())
                                                    ->sum(fn($p) => $p->hitungDenda()),
            ],
            'top_borrowers' => $peminjamans->groupBy('mahasiswa_id')
                                          ->map(fn($group) => [
                                              'nama' => $group->first()->mahasiswa->name,
                                              'tipe' => $group->first()->mahasiswa->role,
                                              'total_pinjam' => $group->count()
                                          ])
                                          ->sortByDesc('total_pinjam')
                                          ->take(5)
                                          ->values(),
            'top_books' => $peminjamans->groupBy('buku_id')
                                       ->map(fn($group) => [
                                           'judul' => $group->first()->buku->judul,
                                           'total_dipinjam' => $group->count()
                                       ])
                                       ->sortByDesc('total_dipinjam')
                                       ->take(5)
                                       ->values(),
        ];

        return response()->json($stats);
    }
}