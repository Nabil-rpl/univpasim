<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku; 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:petugas']);
    }

    /**
     * Tampilkan semua data peminjaman
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['mahasiswa', 'buku', 'petugas']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan role peminjam (mahasiswa/pengguna_luar)
        if ($request->filled('role')) {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_sampai);
        }

        // Pencarian (support nama, nim, no_hp, dan judul buku)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('mahasiswa', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('nim', 'like', "%{$search}%")
                      ->orWhere('no_hp', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('buku', function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('penulis', 'like', "%{$search}%");
                });
            });
        }

        $peminjamans = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Statistik
        $stats = [
            'total' => Peminjaman::count(),
            'dipinjam' => Peminjaman::where('status', 'dipinjam')->count(),
            'dikembalikan' => Peminjaman::where('status', 'dikembalikan')->count(),
            'terlambat' => Peminjaman::where('status', 'dipinjam')
                ->whereNotNull('tanggal_deadline')
                ->whereDate('tanggal_deadline', '<', now())
                ->count()
        ];

        return view('petugas.peminjaman.index', compact('peminjamans', 'stats'));
    }

    /**
     * Form tambah peminjaman
     */
    public function create()
    {
        // Ambil semua user yang role-nya mahasiswa atau pengguna_luar
        $peminjams = User::whereIn('role', ['mahasiswa', 'pengguna_luar'])
            ->orderBy('name')
            ->get();
        
        $bukus = Buku::where('stok', '>', 0)->orderBy('judul')->get();

        return view('petugas.peminjaman.create', compact('peminjams', 'bukus'));
    }

    /**
     * Simpan data peminjaman baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:buku,id',
            'durasi_hari' => 'required|integer|min:1|max:30',
        ]);

        DB::beginTransaction();
        try {
            $buku = Buku::findOrFail($request->buku_id);

            // Cek stok
            if ($buku->stok <= 0) {
                return redirect()->back()->with('error', 'Stok buku tidak tersedia.');
            }

            // Cek apakah sudah meminjam buku yang sama
            $sudahPinjam = Peminjaman::where('mahasiswa_id', $request->mahasiswa_id)
                ->where('buku_id', $request->buku_id)
                ->where('status', 'dipinjam')
                ->exists();

            if ($sudahPinjam) {
                return redirect()->back()->with('error', 'Peminjam sudah meminjam buku ini.');
            }

            // Hitung tanggal deadline
            $tanggalPinjam = now();
            $tanggalDeadline = now()->addDays($request->durasi_hari);

            // Buat peminjaman
            Peminjaman::create([
                'mahasiswa_id' => $request->mahasiswa_id,
                'buku_id' => $request->buku_id,
                'petugas_id' => Auth::id(),
                'tanggal_pinjam' => $tanggalPinjam,
                'durasi_hari' => $request->durasi_hari,
                'tanggal_deadline' => $tanggalDeadline,
                'status' => 'dipinjam',
            ]);

            // Kurangi stok
            $buku->decrement('stok');

            DB::commit();
            return redirect()->route('petugas.peminjaman.index')
                ->with('success', 'Peminjaman berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Detail peminjaman
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with(['mahasiswa', 'buku', 'petugas'])->findOrFail($id);
        return view('petugas.peminjaman.show', compact('peminjaman'));
    }

    /**
     * Proses pengembalian buku
     */
    public function kembalikan($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            if ($peminjaman->status == 'dikembalikan') {
                return redirect()->back()->with('error', 'Buku sudah dikembalikan.');
            }

            // Update status peminjaman
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => now(),
                'petugas_id' => Auth::id(),
            ]);

            // Tambah stok buku
            $peminjaman->buku->increment('stok');

            DB::commit();
            return redirect()->route('petugas.peminjaman.index')
                ->with('success', 'Buku berhasil dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus data peminjaman
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            // Jika masih dipinjam, kembalikan stok
            if ($peminjaman->status == 'dipinjam') {
                $peminjaman->buku->increment('stok');
            }

            $peminjaman->delete();

            DB::commit();
            return redirect()->route('petugas.peminjaman.index')
                ->with('success', 'Data peminjaman berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}