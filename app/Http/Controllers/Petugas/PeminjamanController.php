<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku; 
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class PeminjamanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:petugas']);
    }

    public function index(Request $request)
    {
        $query = Peminjaman::with(['mahasiswa', 'buku', 'petugas']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('role')) {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_sampai);
        }

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

        $peminjamans = $query
            ->orderByDesc('created_at')
            ->simplePaginate(15)
            ->appends(request()->query());

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

    public function create()
    {
        $peminjams = User::whereIn('role', ['mahasiswa', 'pengguna_luar'])
            ->orderBy('name')
            ->get();
        
        $bukus = Buku::where('stok', '>', 0)->orderBy('judul')->get();

        return view('petugas.peminjaman.create', compact('peminjams', 'bukus'));
    }

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
            $mahasiswa = User::findOrFail($request->mahasiswa_id);
            $petugas = Auth::user();

            if ($buku->stok <= 0) {
                return redirect()->back()->with('error', 'Stok buku tidak tersedia.');
            }

            $sudahPinjam = Peminjaman::where('mahasiswa_id', $request->mahasiswa_id)
                ->where('buku_id', $request->buku_id)
                ->where('status', 'dipinjam')
                ->exists();

            if ($sudahPinjam) {
                return redirect()->back()->with('error', 'Peminjam sudah meminjam buku ini.');
            }

            $tanggalPinjam   = now();
            $tanggalDeadline = now()->addDays($request->durasi_hari);

            $peminjaman = Peminjaman::create([
                'mahasiswa_id'    => $request->mahasiswa_id,
                'buku_id'         => $request->buku_id,
                'petugas_id'      => Auth::id(),
                'tanggal_pinjam'  => $tanggalPinjam,
                'durasi_hari'     => $request->durasi_hari,
                'tanggal_deadline'=> $tanggalDeadline,
                'status'          => 'dipinjam',
            ]);

            $buku->decrement('stok');

            Notifikasi::kirim(
                $mahasiswa->id,
                'peminjaman_disetujui',
                "✅ Peminjaman Disetujui: {$buku->judul}",
                "Peminjaman buku Anda telah disetujui oleh petugas.\n\n" .
                "📚 Buku: {$buku->judul}\n" .
                "✍️ Penulis: {$buku->penulis}\n" .
                "🏢 Penerbit: {$buku->penerbit}\n" .
                "📅 Tanggal Pinjam: " . $tanggalPinjam->translatedFormat('d F Y, H:i') . " WIB\n" .
                "⏰ Deadline: " . $tanggalDeadline->translatedFormat('d F Y, H:i') . " WIB\n" .
                "⏱️ Durasi: {$request->durasi_hari} hari\n" .
                "👤 Diproses oleh: {$petugas->name} (Petugas)\n\n" .
                "⚠️ PENTING:\n" .
                "• Harap kembalikan buku sebelum deadline\n" .
                "• Denda Rp 5.000/hari untuk keterlambatan\n" .
                "• Jaga kondisi buku dengan baik",
                [
                    'peminjaman_id' => $peminjaman->id,
                    'buku_id'       => $buku->id,
                    'deadline'      => $tanggalDeadline->format('Y-m-d H:i:s'),
                    'durasi'        => $request->durasi_hari
                ],
                route('mahasiswa.peminjaman.show', $peminjaman->id),
                'normal',
                Auth::id()
            );

            Log::info('Peminjaman baru dibuat', [
                'peminjaman_id' => $peminjaman->id,
                'mahasiswa'     => $mahasiswa->name,
                'buku'          => $buku->judul,
                'deadline'      => $tanggalDeadline->format('Y-m-d H:i:s')
            ]);

            DB::commit();
            
            return redirect()->route('petugas.peminjaman.index')
                ->with('success', "Peminjaman berhasil ditambahkan. Notifikasi telah dikirim ke {$mahasiswa->name}.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error create peminjaman', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['mahasiswa', 'buku', 'petugas'])->findOrFail($id);
        
        $hariTerlambat = 0;
        $denda = 0;
        
        if ($peminjaman->status === 'dipinjam' && $peminjaman->tanggal_deadline < now()) {
            $hariTerlambat = now()->diffInDays($peminjaman->tanggal_deadline);
            $denda = $hariTerlambat * 5000;
        }
        
        return view('petugas.peminjaman.show', compact('peminjaman', 'hariTerlambat', 'denda'));
    }

    public function kembalikan($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::with(['mahasiswa', 'buku'])->findOrFail($id);

            if ($peminjaman->status == 'dikembalikan') {
                return redirect()->back()->with('error', 'Buku sudah dikembalikan.');
            }

            $hariTerlambat      = 0;
            $denda              = 0;
            $statusKeterlambatan = 'tepat_waktu';

            if ($peminjaman->tanggal_deadline < now()) {
                $hariTerlambat      = now()->diffInDays($peminjaman->tanggal_deadline);
                $denda              = $hariTerlambat * 5000;
                $statusKeterlambatan = 'terlambat';
            }

            $peminjaman->update([
                'status'         => 'dikembalikan',
                'tanggal_kembali'=> now(),
                'petugas_id'     => Auth::id(),
            ]);

            $peminjaman->buku->increment('stok');

            $pesanNotifikasi = "Buku yang Anda pinjam telah berhasil dikembalikan.\n\n" .
                "📚 Buku: {$peminjaman->buku->judul}\n" .
                "📅 Tanggal Pinjam: " . $peminjaman->tanggal_pinjam->translatedFormat('d F Y') . "\n" .
                "📅 Tanggal Kembali: " . now()->translatedFormat('d F Y, H:i') . " WIB\n" .
                "⏰ Deadline: " . $peminjaman->tanggal_deadline->translatedFormat('d F Y, H:i') . " WIB\n";

            if ($statusKeterlambatan === 'terlambat') {
                $pesanNotifikasi .= "\n⚠️ KETERLAMBATAN:\n" .
                    "• Terlambat: {$hariTerlambat} hari\n" .
                    "• Denda: Rp " . number_format($denda, 0, ',', '.') . "\n" .
                    "• Harap lunasi denda ke petugas perpustakaan";
            } else {
                $pesanNotifikasi .= "\n✅ Dikembalikan tepat waktu\n" .
                    "• Tidak ada denda\n" .
                    "• Terima kasih telah mengembalikan tepat waktu!";
            }

            Notifikasi::kirim(
                $peminjaman->mahasiswa_id,
                'peminjaman_dikembalikan',
                "📖 Buku Dikembalikan: {$peminjaman->buku->judul}",
                $pesanNotifikasi,
                [
                    'peminjaman_id'       => $peminjaman->id,
                    'buku_id'             => $peminjaman->buku_id,
                    'status_keterlambatan'=> $statusKeterlambatan,
                    'hari_terlambat'      => $hariTerlambat,
                    'denda'               => $denda
                ],
                route('mahasiswa.peminjaman.show', $peminjaman->id),
                $statusKeterlambatan === 'terlambat' ? 'urgent' : 'normal',
                Auth::id()
            );

            DB::commit();
            
            $message = 'Buku berhasil dikembalikan.';
            if ($statusKeterlambatan === 'terlambat') {
                $message .= " Terlambat {$hariTerlambat} hari. Denda: Rp " . number_format($denda, 0, ',', '.');
            }
            
            return redirect()->route('petugas.peminjaman.index')->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function kirimReminderTerlambat($id)
    {
        try {
            $peminjaman = Peminjaman::with(['mahasiswa', 'buku'])->findOrFail($id);

            if ($peminjaman->status !== 'dipinjam') {
                return redirect()->back()->with('error', 'Buku sudah dikembalikan.');
            }

            if ($peminjaman->tanggal_deadline >= now()) {
                return redirect()->back()->with('error', 'Peminjaman belum melewati deadline.');
            }

            $hariTerlambat = now()->diffInDays($peminjaman->tanggal_deadline);
            $denda = $hariTerlambat * 5000;

            Notifikasi::kirim(
                $peminjaman->mahasiswa_id,
                'terlambat',
                "⚠️ Reminder: Buku Terlambat - {$peminjaman->buku->judul}",
                "PERINGATAN! Buku yang Anda pinjam telah melewati batas waktu pengembalian.\n\n" .
                "📚 Buku: {$peminjaman->buku->judul}\n" .
                "⏰ Deadline: " . $peminjaman->tanggal_deadline->translatedFormat('d F Y, H:i') . " WIB\n" .
                "⏱️ Terlambat: {$hariTerlambat} hari\n" .
                "💰 Denda Sementara: Rp " . number_format($denda, 0, ',', '.') . "\n\n" .
                "⚠️ SEGERA KEMBALIKAN BUKU!\n" .
                "• Denda bertambah Rp 5.000 per hari\n" .
                "• Hubungi petugas jika ada kendala",
                [
                    'peminjaman_id' => $peminjaman->id,
                    'buku_id'       => $peminjaman->buku_id,
                    'hari_terlambat'=> $hariTerlambat,
                    'denda'         => $denda
                ],
                route('mahasiswa.peminjaman.show', $peminjaman->id),
                'mendesak',
                Auth::id()
            );

            return redirect()->back()->with('success', "Notifikasi reminder berhasil dikirim ke {$peminjaman->mahasiswa->name}.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);

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

    /**
     * Export semua peminjaman ke PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Peminjaman::with(['mahasiswa', 'buku', 'petugas']);

        // Terapkan filter yang sama dengan index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('role')) {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_sampai);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('mahasiswa', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('nim', 'like', "%{$search}%");
                })->orWhereHas('buku', function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%");
                });
            });
        }

        $peminjamans = $query->orderByDesc('tanggal_pinjam')->get();

        // Buat info filter untuk ditampilkan di PDF
        $filters = [];
        if ($request->filled('status'))       $filters[] = 'Status: ' . ucfirst($request->status);
        if ($request->filled('role'))         $filters[] = 'Tipe: ' . ucfirst(str_replace('_', ' ', $request->role));
        if ($request->filled('tanggal_dari')) $filters[] = 'Dari: ' . $request->tanggal_dari;
        if ($request->filled('tanggal_sampai')) $filters[] = 'Sampai: ' . $request->tanggal_sampai;
        if ($request->filled('search'))       $filters[] = 'Pencarian: ' . $request->search;
        $filterInfo = count($filters) ? implode(' | ', $filters) : null;

        $pdf = Pdf::loadView('petugas.peminjaman.peminjaman-pdf', compact('peminjamans', 'filterInfo'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('rekap-peminjaman-' . date('d-m-Y') . '.pdf');
    }
}