<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    /**
     * Tampilkan daftar peminjaman yang belum dikembalikan
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['mahasiswa', 'buku'])
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_deadline', 'asc');

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('mahasiswa', function($mhs) use ($search) {
                    $mhs->where('name', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                })
                ->orWhereHas('buku', function($buku) use ($search) {
                    $buku->where('judul', 'like', "%{$search}%");
                });
            });
        }

        // Filter status
        if ($request->filter == 'terlambat') {
            $query->whereDate('tanggal_deadline', '<', now());
        } elseif ($request->filter == 'tepat_waktu') {
            $query->whereDate('tanggal_deadline', '>=', now());
        } elseif ($request->filter == 'hari_ini') {
            $query->whereDate('tanggal_deadline', '=', now());
        }

        // Sorting
        if ($request->sort == 'deadline_desc') {
            $query->orderBy('tanggal_deadline', 'desc');
        } elseif ($request->sort == 'denda_desc') {
            $query->whereDate('tanggal_deadline', '<', now())
                  ->orderByRaw('DATEDIFF(NOW(), tanggal_deadline) DESC');
        }

        $peminjaman = $query->paginate(15);

        // Hitung statistik
        $stats = [
            'aktif' => Peminjaman::where('status', 'dipinjam')->count(),
            'terlambat' => Peminjaman::where('status', 'dipinjam')
                ->whereDate('tanggal_deadline', '<', now())
                ->count(),
            'total_denda' => 0
        ];

        // Hitung total denda
        $peminjamanTerlambat = Peminjaman::where('status', 'dipinjam')
            ->whereDate('tanggal_deadline', '<', now())
            ->get();
        
        foreach ($peminjamanTerlambat as $p) {
            $stats['total_denda'] += $p->hitungDenda();
        }

        return view('petugas.pengembalian.index', compact('peminjaman', 'stats'));
    }

    /**
     * Tampilkan detail peminjaman sebelum proses pengembalian
     */
    public function show($peminjaman_id)
    {
        $peminjaman = Peminjaman::with(['mahasiswa', 'buku'])
            ->findOrFail($peminjaman_id);

        // Cek apakah sudah dikembalikan
        if ($peminjaman->status === 'dikembalikan') {
            return redirect()->route('petugas.pengembalian.index')
                ->with('error', 'Buku ini sudah dikembalikan');
        }

        // Hitung denda jika terlambat
        $denda = $peminjaman->hitungDenda();
        $hariTerlambat = $peminjaman->getHariTerlambat();

        return view('petugas.pengembalian.show', compact('peminjaman', 'denda', 'hariTerlambat'));
    }

    /**
     * Proses pengembalian buku
     */
    public function store(Request $request, $peminjaman_id)
    {
        $peminjaman = Peminjaman::findOrFail($peminjaman_id);
        
        // Cek apakah sudah dikembalikan
        if ($peminjaman->status === 'dikembalikan') {
            return redirect()->back()->with('error', 'Buku ini sudah dikembalikan');
        }

        DB::beginTransaction();
        
        try {
            $tanggalPengembalian = Carbon::now();
            
            // Hitung denda jika terlambat
            $denda = 0;
            if ($tanggalPengembalian->isAfter($peminjaman->tanggal_deadline)) {
                $hariTerlambat = $tanggalPengembalian->diffInDays($peminjaman->tanggal_deadline);
                $dendaPerHari = 5000;
                $denda = $hariTerlambat * $dendaPerHari;
            }

            // Buat record pengembalian
            Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'petugas_id' => Auth::id(),
                'tanggal_pengembalian' => $tanggalPengembalian,
                'denda' => $denda,
            ]);

            // Update status peminjaman
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => $tanggalPengembalian,
            ]);

            // Tambah stok buku
            $peminjaman->buku->increment('stok');

            DB::commit();

            return redirect()->route('petugas.pengembalian.index')
                ->with('success', 'Pengembalian berhasil diproses' . ($denda > 0 ? ' dengan denda Rp ' . number_format($denda, 0, ',', '.') : ''));

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan riwayat pengembalian
     */
    public function riwayat()
    {
        $pengembalian = Pengembalian::with(['peminjaman.mahasiswa', 'peminjaman.buku', 'petugas'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('petugas.pengembalian.riwayat', compact('pengembalian'));
    }

    /**
     * Cari peminjaman berdasarkan NIM atau Nama Mahasiswa
     */
    public function search(Request $request)
    {
        $keyword = $request->get('keyword');

        $peminjaman = Peminjaman::with(['mahasiswa', 'buku'])
            ->where('status', 'dipinjam')
            ->whereHas('mahasiswa', function($query) use ($keyword) {
                $query->where('nim', 'like', "%{$keyword}%")
                      ->orWhere('name', 'like', "%{$keyword}%");
            })
            ->orderBy('tanggal_deadline', 'asc')
            ->paginate(10);

        return view('petugas.pengembalian.index', compact('peminjaman', 'keyword'));
    }
}