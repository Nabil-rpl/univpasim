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

        // Filter berdasarkan role peminjam
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

        // Pencarian
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
            $mahasiswa = User::findOrFail($request->mahasiswa_id);
            $petugas = Auth::user();

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
            $peminjaman = Peminjaman::create([
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

            // Kirim notifikasi peminjaman disetujui
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
                    'buku_id' => $buku->id,
                    'deadline' => $tanggalDeadline->format('Y-m-d H:i:s'),
                    'durasi' => $request->durasi_hari
                ],
                route('mahasiswa.peminjaman.show', $peminjaman->id),
                'normal',
                Auth::id()
            );

            Log::info('Peminjaman baru dibuat', [
                'peminjaman_id' => $peminjaman->id,
                'mahasiswa' => $mahasiswa->name,
                'buku' => $buku->judul,
                'deadline' => $tanggalDeadline->format('Y-m-d H:i:s')
            ]);

            DB::commit();
            
            return redirect()->route('petugas.peminjaman.index')
                ->with('success', "Peminjaman berhasil ditambahkan. Notifikasi telah dikirim ke {$mahasiswa->name}.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error create peminjaman', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Detail peminjaman
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with(['mahasiswa', 'buku', 'petugas'])->findOrFail($id);
        
        // Hitung status keterlambatan jika masih dipinjam
        $hariTerlambat = 0;
        $denda = 0;
        
        if ($peminjaman->status === 'dipinjam' && $peminjaman->tanggal_deadline < now()) {
            $hariTerlambat = now()->diffInDays($peminjaman->tanggal_deadline);
            $denda = $hariTerlambat * 5000;
        }
        
        return view('petugas.peminjaman.show', compact('peminjaman', 'hariTerlambat', 'denda'));
    }

    /**
     * Proses pengembalian buku
     */
    public function kembalikan($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::with(['mahasiswa', 'buku'])->findOrFail($id);

            if ($peminjaman->status == 'dikembalikan') {
                return redirect()->back()->with('error', 'Buku sudah dikembalikan.');
            }

            // Hitung keterlambatan dan denda
            $hariTerlambat = 0;
            $denda = 0;
            $statusKeterlambatan = 'tepat_waktu';

            if ($peminjaman->tanggal_deadline < now()) {
                $hariTerlambat = now()->diffInDays($peminjaman->tanggal_deadline);
                $denda = $hariTerlambat * 5000;
                $statusKeterlambatan = 'terlambat';
            }

            // Update status peminjaman
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => now(),
                'petugas_id' => Auth::id(),
            ]);

            // Tambah stok buku
            $peminjaman->buku->increment('stok');

            // Kirim notifikasi pengembalian
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
                    'peminjaman_id' => $peminjaman->id,
                    'buku_id' => $peminjaman->buku_id,
                    'status_keterlambatan' => $statusKeterlambatan,
                    'hari_terlambat' => $hariTerlambat,
                    'denda' => $denda
                ],
                route('mahasiswa.peminjaman.show', $peminjaman->id),
                $statusKeterlambatan === 'terlambat' ? 'urgent' : 'normal',
                Auth::id()
            );

            Log::info('Buku dikembalikan', [
                'peminjaman_id' => $peminjaman->id,
                'mahasiswa' => $peminjaman->mahasiswa->name,
                'buku' => $peminjaman->buku->judul,
                'status_keterlambatan' => $statusKeterlambatan,
                'hari_terlambat' => $hariTerlambat,
                'denda' => $denda
            ]);

            DB::commit();
            
            $message = 'Buku berhasil dikembalikan.';
            if ($statusKeterlambatan === 'terlambat') {
                $message .= " Terlambat {$hariTerlambat} hari. Denda: Rp " . number_format($denda, 0, ',', '.');
            }
            
            return redirect()->route('petugas.peminjaman.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error kembalikan buku', [
                'peminjaman_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * ✅ Kirim notifikasi reminder manual ke peminjaman terlambat
     */
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
                    'buku_id' => $peminjaman->buku_id,
                    'hari_terlambat' => $hariTerlambat,
                    'denda' => $denda
                ],
                route('mahasiswa.peminjaman.show', $peminjaman->id),
                'mendesak',
                Auth::id()
            );

            Log::info('Reminder manual dikirim', [
                'peminjaman_id' => $peminjaman->id,
                'mahasiswa' => $peminjaman->mahasiswa->name,
                'dikirim_oleh' => Auth::user()->name
            ]);

            return redirect()->back()->with('success', "Notifikasi reminder berhasil dikirim ke {$peminjaman->mahasiswa->name}.");

        } catch (\Exception $e) {
            Log::error('Error kirim reminder manual', [
                'peminjaman_id' => $id,
                'error' => $e->getMessage()
            ]);
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