<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Laporan;
use App\Models\QrCode;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Perpanjangan;
use Carbon\Carbon;

class PetugasController extends Controller
{
    public function __construct()
    {
        // Middleware agar hanya role petugas yang bisa masuk
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $user = Auth::user();

            // ========== DATA DASAR ==========
            $laporans = Laporan::where('dibuat_oleh', $user->id)->get();
            $qrcodes = QrCode::where('dibuat_oleh', $user->id)->get();
            $buku = Buku::all();

            // ========== STATISTIK PEMINJAMAN ==========
            $totalPeminjaman = Peminjaman::count();
            $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
            $peminjamanSelesai = Peminjaman::where('status', 'dikembalikan')->count();
            $peminjamanTerlambat = Peminjaman::where('status', 'dipinjam')
                ->whereDate('tanggal_deadline', '<', now())
                ->count();

            // ========== STATISTIK PENGEMBALIAN ==========
            $totalPengembalian = Pengembalian::count();
            $totalDenda = Pengembalian::sum('denda') ?? 0;
            $dendaLunas = Pengembalian::where('denda_dibayar', true)->sum('denda') ?? 0;
            $dendaBelumLunas = Pengembalian::where('denda', '>', 0)
                ->where('denda_dibayar', false)
                ->sum('denda') ?? 0;

            // ========== STATISTIK PERPANJANGAN ==========
            $perpanjanganMenunggu = Perpanjangan::where('status', 'menunggu')->count();
            $perpanjanganDisetujui = Perpanjangan::where('status', 'disetujui')->count();

            // ========== DATA GRAFIK PEMINJAMAN & PENGEMBALIAN (6 BULAN TERAKHIR) ==========
            $bulanLabels = [];
            $peminjamanPerBulan = [];
            $pengembalianPerBulan = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $tanggal = Carbon::now()->subMonths($i);
                $bulanLabels[] = $tanggal->translatedFormat('M Y');
                
                $peminjamanPerBulan[] = Peminjaman::whereYear('tanggal_pinjam', $tanggal->year)
                    ->whereMonth('tanggal_pinjam', $tanggal->month)
                    ->count();
                    
                $pengembalianPerBulan[] = Pengembalian::whereYear('tanggal_pengembalian', $tanggal->year)
                    ->whereMonth('tanggal_pengembalian', $tanggal->month)
                    ->count();
            }

            // ========== DATA GRAFIK TOP 5 BUKU TERPOPULER ==========
            $topBuku = Peminjaman::select('buku_id', DB::raw('COUNT(*) as total_pinjam'))
                ->with('buku')
                ->groupBy('buku_id')
                ->orderByDesc('total_pinjam')
                ->limit(5)
                ->get();

            $topBukuLabels = $topBuku->map(function($item) {
                if (!$item->buku) return 'Unknown';
                $judul = $item->buku->judul;
                return strlen($judul) > 30 ? substr($judul, 0, 30) . '...' : $judul;
            })->toArray();

            $topBukuData = $topBuku->pluck('total_pinjam')->toArray();

            // ========== DATA GRAFIK DENDA PER BULAN (6 BULAN TERAKHIR) ==========
            $dendaPerBulan = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $tanggal = Carbon::now()->subMonths($i);
                
                $dendaPerBulan[] = Pengembalian::whereYear('tanggal_pengembalian', $tanggal->year)
                    ->whereMonth('tanggal_pengembalian', $tanggal->month)
                    ->sum('denda') ?? 0;
            }

            // ========== PEMINJAMAN TERBARU (5 DATA) ==========
            $peminjamanTerbaru = Peminjaman::with(['mahasiswa', 'buku'])
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

            // ========== PERPANJANGAN MENUNGGU (5 DATA) ==========
            $perpanjanganMenungguList = Perpanjangan::with(['peminjaman.mahasiswa', 'peminjaman.buku'])
                ->where('status', 'menunggu')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

            // ========== KIRIM SEMUA DATA KE VIEW ==========
            return view('petugas.dashboard', compact(
                'laporans',
                'qrcodes',
                'buku',
                'totalPeminjaman',
                'peminjamanAktif',
                'peminjamanSelesai',
                'peminjamanTerlambat',
                'totalPengembalian',
                'dendaLunas',
                'dendaBelumLunas',
                'perpanjanganMenunggu',
                'perpanjanganDisetujui',
                'bulanLabels',
                'peminjamanPerBulan',
                'pengembalianPerBulan',
                'topBukuLabels',
                'topBukuData',
                'dendaPerBulan',
                'peminjamanTerbaru',
                'perpanjanganMenungguList'
            ));

        } catch (\Exception $e) {
            \Log::error('Dashboard Petugas Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}