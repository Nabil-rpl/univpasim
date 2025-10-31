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

        // Filter pencarian (support untuk mahasiswa dan pengguna luar)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('mahasiswa', function($mhs) use ($search) {
                    $mhs->where('name', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%")
                        ->orWhere('no_hp', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('buku', function($buku) use ($search) {
                    $buku->where('judul', 'like', "%{$search}%")
                         ->orWhere('penulis', 'like', "%{$search}%");
                });
            });
        }

        // Filter berdasarkan role peminjam
        if ($request->filled('role')) {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('role', $request->role);
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

        $peminjaman = $query->paginate(15)->withQueryString();

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
            $denda = $peminjaman->hitungDenda();

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

            $message = 'Pengembalian berhasil diproses';
            if ($denda > 0) {
                $message .= ' dengan denda Rp ' . number_format($denda, 0, ',', '.');
            }

            return redirect()->route('petugas.pengembalian.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan riwayat pengembalian
     */
    public function riwayat(Request $request)
    {
        $query = Pengembalian::with(['peminjaman.mahasiswa', 'peminjaman.buku', 'petugas'])
            ->orderBy('created_at', 'desc');

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('peminjaman.mahasiswa', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            })->orWhereHas('peminjaman.buku', function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->whereHas('peminjaman.mahasiswa', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pengembalian', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pengembalian', '<=', $request->tanggal_sampai);
        }

        $pengembalian = $query->paginate(15)->withQueryString();

        // Statistik
        $stats = [
            'total' => Pengembalian::count(),
            'total_denda' => Pengembalian::sum('denda'),
            'bulan_ini' => Pengembalian::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return view('petugas.pengembalian.riwayat', compact('pengembalian', 'stats'));
    }

    /**
     * Cari peminjaman berdasarkan NIM, Nama, atau No HP
     */
    public function search(Request $request)
    {
        $keyword = $request->get('keyword');

        $peminjaman = Peminjaman::with(['mahasiswa', 'buku'])
            ->where('status', 'dipinjam')
            ->whereHas('mahasiswa', function($query) use ($keyword) {
                $query->where('nim', 'like', "%{$keyword}%")
                      ->orWhere('name', 'like', "%{$keyword}%")
                      ->orWhere('no_hp', 'like', "%{$keyword}%");
            })
            ->orderBy('tanggal_deadline', 'asc')
            ->paginate(10);

        return view('petugas.pengembalian.index', compact('peminjaman', 'keyword'));
    }
}