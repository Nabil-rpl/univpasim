<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Perpanjangan;
use App\Models\Peminjaman;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PerpanjanganController extends Controller
{
    /**
     * Tampilkan daftar perpanjangan yang menunggu persetujuan
     */
    public function index(Request $request)
    {
        $query = Perpanjangan::with(['peminjaman.buku.qrCode', 'peminjaman.mahasiswa', 'petugas']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default tampilkan yang menunggu
            $query->where('status', 'menunggu');
        }

        // Filter berdasarkan role peminjam
        if ($request->filled('role')) {
            $query->whereHas('peminjaman.mahasiswa', function($q) use ($request) {
                $q->where('role', $request->role);
            });
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
            'total' => Perpanjangan::count(),
        ];

        return view('petugas.perpanjangan.index', compact('perpanjangan', 'stats'));
    }

    /**
     * Setujui perpanjangan
     */
    public function approve($id)
    {
        $perpanjangan = Perpanjangan::with('peminjaman.buku.qrCode', 'peminjaman.mahasiswa')
            ->findOrFail($id);

        // Validasi status
        if ($perpanjangan->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Perpanjangan sudah diproses sebelumnya');
        }

        // Validasi peminjaman masih aktif
        if ($perpanjangan->peminjaman->status !== 'dipinjam') {
            return redirect()->back()->with('error', 'Peminjaman sudah tidak aktif');
        }

        // Validasi: Cek apakah peminjaman sudah terlambat
        if ($perpanjangan->peminjaman->isTerlambat()) {
            return redirect()->back()->with('error', 
                'Tidak dapat menyetujui perpanjangan. Peminjaman sudah melewati deadline dan dikenakan denda. Harap proses pengembalian terlebih dahulu.');
        }

        DB::beginTransaction();
        try {
            Log::info('Memproses persetujuan perpanjangan', [
                'perpanjangan_id' => $perpanjangan->id,
                'peminjaman_id' => $perpanjangan->peminjaman->id,
                'petugas_id' => Auth::id()
            ]);

            $petugasId = Auth::id();
            $petugas = Auth::user();
            $tanggalDeadlineBaru = Carbon::parse($perpanjangan->tanggal_deadline_baru);
            $durasiTambahanHari = $perpanjangan->durasi_tambahan;
            $peminjaman = $perpanjangan->peminjaman;
            $mahasiswa = $peminjaman->mahasiswa;
            $buku = $peminjaman->buku;

            // 1. Update status perpanjangan
            $perpanjangan->update([
                'status' => 'disetujui',
                'diproses_oleh' => $petugasId,
            ]);

            Log::info('Status perpanjangan berhasil diupdate');

            // 2. Update peminjaman (deadline & durasi)
            $durasiTotal = $peminjaman->durasi_hari + $durasiTambahanHari;

            $peminjaman->update([
                'tanggal_deadline' => $tanggalDeadlineBaru,
                'durasi_hari' => $durasiTotal,
                'petugas_id' => $petugasId,
            ]);

            Log::info('Peminjaman berhasil diupdate', [
                'deadline_lama' => $perpanjangan->tanggal_deadline_lama,
                'deadline_baru' => $tanggalDeadlineBaru,
                'durasi_lama' => $peminjaman->durasi_hari - $durasiTambahanHari,
                'durasi_baru' => $durasiTotal
            ]);

            // ✅ 3. KIRIM NOTIFIKASI KE MAHASISWA
            Notifikasi::kirim(
                $mahasiswa->id,
                'perpanjangan_disetujui',
                "Perpanjangan Disetujui: {$buku->judul}",
                "Selamat! Pengajuan perpanjangan Anda telah disetujui.\n\n" .
                "📚 Buku: {$buku->judul}\n" .
                "🔖 Kode: {$buku->kode_buku}\n" .
                "✅ Status: DISETUJUI\n" .
                "📅 Deadline Lama: " . Carbon::parse($perpanjangan->tanggal_deadline_lama)->translatedFormat('d F Y') . "\n" .
                "📅 Deadline Baru: " . $tanggalDeadlineBaru->translatedFormat('d F Y') . "\n" .
                "⏱️ Durasi Tambahan: {$durasiTambahanHari} hari\n" .
                "👤 Disetujui oleh: {$petugas->name}\n\n" .
                "⚠️ Harap kembalikan buku sebelum deadline baru untuk menghindari denda.",
                [
                    'perpanjangan_id' => $perpanjangan->id,
                    'peminjaman_id' => $peminjaman->id,
                    'deadline_baru' => $tanggalDeadlineBaru->format('Y-m-d'),
                    'durasi_tambahan' => $durasiTambahanHari,
                    'petugas_nama' => $petugas->name
                ],
                route('mahasiswa.peminjaman.show', $peminjaman->id),
                'normal',
                $petugasId
            );

            Log::info('Notifikasi perpanjangan disetujui dikirim ke mahasiswa');

            // ✅ 4. KIRIM NOTIFIKASI KE SEMUA ADMIN
            $adminIds = User::where('role', 'admin')->pluck('id');
            
            foreach ($adminIds as $adminId) {
                Notifikasi::kirim(
                    $adminId,
                    'perpanjangan_disetujui',
                    "Perpanjangan Disetujui oleh {$petugas->name}",
                    "Petugas {$petugas->name} telah menyetujui perpanjangan peminjaman.\n\n" .
                    "👤 Peminjam: {$mahasiswa->name}\n" .
                    "🆔 NIM/NIK: " . ($mahasiswa->nim ?? $mahasiswa->nik ?? '-') . "\n" .
                    "📚 Buku: {$buku->judul}\n" .
                    "🔖 Kode Buku: {$buku->kode_buku}\n" .
                    "📅 Deadline Lama: " . Carbon::parse($perpanjangan->tanggal_deadline_lama)->translatedFormat('d F Y') . "\n" .
                    "📅 Deadline Baru: " . $tanggalDeadlineBaru->translatedFormat('d F Y') . "\n" .
                    "⏱️ Durasi Tambahan: {$durasiTambahanHari} hari\n" .
                    "✅ Disetujui oleh: {$petugas->name}\n" .
                    "🕐 Waktu: " . now()->translatedFormat('d F Y H:i'),
                    [
                        'perpanjangan_id' => $perpanjangan->id,
                        'peminjaman_id' => $peminjaman->id,
                        'mahasiswa_id' => $mahasiswa->id,
                        'mahasiswa_nama' => $mahasiswa->name,
                        'buku_judul' => $buku->judul,
                        'kode_buku' => $buku->kode_buku,
                        'petugas_id' => $petugasId,
                        'petugas_nama' => $petugas->name,
                        'deadline_baru' => $tanggalDeadlineBaru->format('Y-m-d')
                    ],
                    route('admin.perpanjangan.show', $perpanjangan->id),
                    'normal',
                    $petugasId
                );
            }

            Log::info('Notifikasi perpanjangan disetujui dikirim ke semua admin', [
                'jumlah_admin' => $adminIds->count()
            ]);

            DB::commit();

            $namaPeminjam = $mahasiswa->name;
            $judulBuku = $buku->judul;

            return redirect()->back()->with('success', 
                "Perpanjangan disetujui! Peminjaman '{$judulBuku}' oleh {$namaPeminjam} diperpanjang hingga " . 
                $tanggalDeadlineBaru->translatedFormat('d F Y'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error saat menyetujui perpanjangan', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Tolak perpanjangan
     */
    public function reject(Request $request, $id)
    {
        $perpanjangan = Perpanjangan::with('peminjaman.mahasiswa', 'peminjaman.buku.qrCode')->findOrFail($id);

        if ($perpanjangan->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Perpanjangan sudah diproses sebelumnya');
        }

        $request->validate([
            'catatan_petugas' => 'required|string|max:500',
        ], [
            'catatan_petugas.required' => 'Catatan penolakan wajib diisi',
            'catatan_petugas.max' => 'Catatan maksimal 500 karakter',
        ]);

        DB::beginTransaction();
        try {
            $petugas = Auth::user();
            $mahasiswa = $perpanjangan->peminjaman->mahasiswa;
            $buku = $perpanjangan->peminjaman->buku;
            
            $perpanjangan->update([
                'status' => 'ditolak',
                'catatan_petugas' => $request->catatan_petugas,
                'diproses_oleh' => Auth::id(),
            ]);

            // ✅ 1. KIRIM NOTIFIKASI KE MAHASISWA
            Notifikasi::kirim(
                $mahasiswa->id,
                'perpanjangan_ditolak',
                "Perpanjangan Ditolak: {$buku->judul}",
                "Maaf, pengajuan perpanjangan Anda ditolak.\n\n" .
                "📚 Buku: {$buku->judul}\n" .
                "🔖 Kode: {$buku->kode_buku}\n" .
                "❌ Status: DITOLAK\n" .
                "📝 Alasan Penolakan:\n{$request->catatan_petugas}\n\n" .
                "👤 Ditolak oleh: {$petugas->name}\n" .
                "📅 Deadline Tetap: " . Carbon::parse($perpanjangan->tanggal_deadline_lama)->translatedFormat('d F Y') . "\n\n" .
                "⚠️ Harap kembalikan buku sesuai deadline awal untuk menghindari denda.",
                [
                    'perpanjangan_id' => $perpanjangan->id,
                    'peminjaman_id' => $perpanjangan->peminjaman_id,
                    'alasan_ditolak' => $request->catatan_petugas,
                    'petugas_nama' => $petugas->name
                ],
                route('mahasiswa.peminjaman.show', $perpanjangan->peminjaman_id),
                'tinggi',
                Auth::id()
            );

            Log::info('Notifikasi perpanjangan ditolak dikirim ke mahasiswa');

            // ✅ 2. KIRIM NOTIFIKASI KE SEMUA ADMIN
            $adminIds = User::where('role', 'admin')->pluck('id');
            
            foreach ($adminIds as $adminId) {
                Notifikasi::kirim(
                    $adminId,
                    'perpanjangan_ditolak',
                    "Perpanjangan Ditolak oleh {$petugas->name}",
                    "Petugas {$petugas->name} telah menolak perpanjangan peminjaman.\n\n" .
                    "👤 Peminjam: {$mahasiswa->name}\n" .
                    "🆔 NIM/NIK: " . ($mahasiswa->nim ?? $mahasiswa->nik ?? '-') . "\n" .
                    "📚 Buku: {$buku->judul}\n" .
                    "🔖 Kode Buku: {$buku->kode_buku}\n" .
                    "❌ Status: DITOLAK\n" .
                    "📝 Alasan Penolakan:\n{$request->catatan_petugas}\n\n" .
                    "👤 Ditolak oleh: {$petugas->name}\n" .
                    "🕐 Waktu: " . now()->translatedFormat('d F Y H:i'),
                    [
                        'perpanjangan_id' => $perpanjangan->id,
                        'peminjaman_id' => $perpanjangan->peminjaman_id,
                        'mahasiswa_id' => $mahasiswa->id,
                        'mahasiswa_nama' => $mahasiswa->name,
                        'buku_judul' => $buku->judul,
                        'kode_buku' => $buku->kode_buku,
                        'petugas_id' => Auth::id(),
                        'petugas_nama' => $petugas->name,
                        'alasan_ditolak' => $request->catatan_petugas
                    ],
                    route('admin.perpanjangan.show', $perpanjangan->id),
                    'normal',
                    Auth::id()
                );
            }

            Log::info('Notifikasi perpanjangan ditolak dikirim ke semua admin', [
                'jumlah_admin' => $adminIds->count()
            ]);

            DB::commit();

            $namaPeminjam = $mahasiswa->name;

            return redirect()->back()->with('success', 
                "Perpanjangan dari {$namaPeminjam} berhasil ditolak");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Detail perpanjangan
     */
    public function show($id)
    {
        $perpanjangan = Perpanjangan::with([
            'peminjaman.buku.qrCode',
            'peminjaman.mahasiswa',
            'peminjaman.petugas',
            'petugas'
        ])->findOrFail($id);

        return view('petugas.perpanjangan.show', compact('perpanjangan'));
    }

    /**
     * Riwayat perpanjangan (semua status)
     */
    public function riwayat(Request $request)
    {
        $query = Perpanjangan::with(['peminjaman.buku.qrCode', 'peminjaman.mahasiswa', 'petugas'])
            ->whereIn('status', ['disetujui', 'ditolak']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
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
                        ->orWhere('nim', 'like', "%{$search}%");
                })
                ->orWhereHas('peminjaman.buku', function($buku) use ($search) {
                    $buku->where('judul', 'like', "%{$search}%");
                });
            });
        }

        $perpanjangan = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends(request()->query());

        return view('petugas.perpanjangan.riwayat', compact('perpanjangan'));
    }

    /**
     * Batalkan perpanjangan yang sudah disetujui (emergency only)
     */
    public function cancel(Request $request, $id)
    {
        $perpanjangan = Perpanjangan::with('peminjaman')->findOrFail($id);

        if ($perpanjangan->status !== 'disetujui') {
            return redirect()->back()->with('error', 'Hanya perpanjangan yang sudah disetujui yang bisa dibatalkan');
        }

        $request->validate([
            'alasan_pembatalan' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Kembalikan deadline ke sebelum perpanjangan
            $perpanjangan->peminjaman->update([
                'tanggal_deadline' => $perpanjangan->tanggal_deadline_lama,
                'durasi_hari' => $perpanjangan->peminjaman->durasi_hari - $perpanjangan->durasi_tambahan,
            ]);

            // Update status perpanjangan
            $perpanjangan->update([
                'status' => 'dibatalkan',
                'catatan_petugas' => $request->alasan_pembatalan,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Perpanjangan berhasil dibatalkan dan deadline dikembalikan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}