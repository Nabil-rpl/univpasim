<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PengembalianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:petugas']);
    }

    public function index(Request $request)
    {
        $query = Peminjaman::with(['mahasiswa', 'buku'])
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_deadline', 'asc');

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

        if ($request->filled('role')) {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        if ($request->filter == 'terlambat') {
            $query->whereDate('tanggal_deadline', '<', now());
        } elseif ($request->filter == 'tepat_waktu') {
            $query->whereDate('tanggal_deadline', '>=', now());
        } elseif ($request->filter == 'hari_ini') {
            $query->whereDate('tanggal_deadline', '=', now());
        }

        if ($request->sort == 'deadline_desc') {
            $query->orderBy('tanggal_deadline', 'desc');
        } elseif ($request->sort == 'denda_desc') {
            $query->whereDate('tanggal_deadline', '<', now())
                  ->orderByRaw('DATEDIFF(NOW(), tanggal_deadline) DESC');
        }

        $peminjaman = $query->paginate(15)->appends(request()->query());

        $stats = [
            'aktif' => Peminjaman::where('status', 'dipinjam')->count(),
            'terlambat' => Peminjaman::where('status', 'dipinjam')
                ->whereDate('tanggal_deadline', '<', now())
                ->count(),
            'total_denda' => 0
        ];

        $peminjamanTerlambat = Peminjaman::where('status', 'dipinjam')
            ->whereDate('tanggal_deadline', '<', now())
            ->get();
        
        foreach ($peminjamanTerlambat as $p) {
            $stats['total_denda'] += $p->hitungDenda();
        }

        return view('petugas.pengembalian.index', compact('peminjaman', 'stats'));
    }

    public function exportPdf(Request $request)
    {
        $query = Peminjaman::with(['mahasiswa', 'buku'])
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_deadline', 'asc');

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

        if ($request->filled('role')) {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        if ($request->filter == 'terlambat') {
            $query->whereDate('tanggal_deadline', '<', now());
        } elseif ($request->filter == 'tepat_waktu') {
            $query->whereDate('tanggal_deadline', '>=', now());
        } elseif ($request->filter == 'hari_ini') {
            $query->whereDate('tanggal_deadline', '=', now());
        }

        if ($request->sort == 'deadline_desc') {
            $query->orderBy('tanggal_deadline', 'desc');
        } elseif ($request->sort == 'denda_desc') {
            $query->whereDate('tanggal_deadline', '<', now())
                  ->orderByRaw('DATEDIFF(NOW(), tanggal_deadline) DESC');
        }

        $peminjamanList = $query->get();
        $totalAktif = Peminjaman::where('status', 'dipinjam')->count();
        $totalTerlambat = Peminjaman::where('status', 'dipinjam')
            ->whereDate('tanggal_deadline', '<', now())
            ->count();
        $totalTepatWaktu = $totalAktif - $totalTerlambat;
        
        $totalDenda = 0;
        $peminjamanTerlambat = Peminjaman::where('status', 'dipinjam')
            ->whereDate('tanggal_deadline', '<', now())
            ->get();
        
        foreach ($peminjamanTerlambat as $p) {
            $totalDenda += $p->hitungDenda();
        }

        $data = [
            'peminjamanList' => $peminjamanList,
            'totalAktif' => $totalAktif,
            'totalTerlambat' => $totalTerlambat,
            'totalTepatWaktu' => $totalTepatWaktu,
            'totalDenda' => $totalDenda,
            'tanggal_cetak' => now()->format('d F Y H:i:s'),
            'filters' => [
                'search' => $request->search,
                'role' => $request->role,
                'filter' => $request->filter,
                'sort' => $request->sort,
            ]
        ];

        $pdf = Pdf::loadView('petugas.pengembalian.pdf', $data)
                  ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pengembalian-aktif-' . now()->format('Y-m-d-His') . '.pdf');
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['mahasiswa', 'buku', 'petugas'])
            ->findOrFail($id);

        if ($peminjaman->status === 'dikembalikan') {
            return redirect()->route('petugas.pengembalian.index')
                ->with('error', 'Buku ini sudah dikembalikan');
        }

        $denda = $peminjaman->hitungDenda();
        $hariTerlambat = $peminjaman->getHariTerlambat();

        return view('petugas.pengembalian.show', compact('peminjaman', 'denda', 'hariTerlambat'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'denda_dibayar' => 'sometimes|boolean',
            'catatan_pembayaran' => 'nullable|string|max:500',
        ]);

        $peminjaman = Peminjaman::with(['mahasiswa', 'buku'])->findOrFail($id);
        
        if ($peminjaman->status === 'dikembalikan') {
            return redirect()->back()->with('error', 'Buku ini sudah dikembalikan');
        }

        DB::beginTransaction();
        
        try {
            $tanggalPengembalian = Carbon::now();
            $petugasId = Auth::id();
            $petugas = Auth::user();
            $denda = $peminjaman->hitungDenda();
            $hariTerlambat = $peminjaman->getHariTerlambat();

            Log::info('=== MULAI PROSES PENGEMBALIAN ===', [
                'peminjaman_id' => $peminjaman->id,
                'mahasiswa_id' => $peminjaman->mahasiswa_id,
                'denda' => $denda,
            ]);

            $dendaDibayar = false;
            $dendaDibayarPada = null;
            $catatanPembayaran = null;

            if ($denda > 0 && $request->has('denda_dibayar') && $request->denda_dibayar) {
                $dendaDibayar = true;
                $dendaDibayarPada = now();
                $catatanPembayaran = $request->catatan_pembayaran ?? 'Denda dibayar saat pengembalian';
            }

            $pengembalian = Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'petugas_id' => $petugasId,
                'tanggal_pengembalian' => $tanggalPengembalian,
                'denda' => $denda,
                'denda_dibayar' => $dendaDibayar,
                'denda_dibayar_pada' => $dendaDibayarPada,
                'catatan_pembayaran' => $catatanPembayaran,
            ]);

            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => $tanggalPengembalian,
                'petugas_id' => $petugasId,
            ]);

            $peminjaman->buku->increment('stok');

            $statusKeterlambatan = $denda > 0 ? 'terlambat' : 'tepat waktu';
            $pesanNotif = "Pengembalian buku Anda telah diproses oleh petugas.\n\n" .
                         "📚 Buku: {$peminjaman->buku->judul}\n" .
                         "📅 Tanggal Pengembalian: " . $tanggalPengembalian->translatedFormat('d F Y, H:i') . " WIB\n" .
                         "📅 Deadline: " . $peminjaman->tanggal_deadline->translatedFormat('d F Y') . "\n" .
                         "✅ Status: " . strtoupper($statusKeterlambatan) . "\n" .
                         "👤 Diproses oleh: {$petugas->name}\n";
            
            $tipeNotif = 'pengembalian_sukses';
            $tipePrioritas = 'normal';
            $judulNotif = "Pengembalian Berhasil: {$peminjaman->buku->judul}";

            if ($denda > 0) {
                $pesanNotif .= "\n💰 Denda: Rp " . number_format($denda, 0, ',', '.') . "\n" .
                              "📊 Hari Terlambat: {$hariTerlambat} hari\n" .
                              "💵 Tarif Denda: Rp 5.000/hari\n\n";
                
                if ($dendaDibayar) {
                    $pesanNotif .= "✅ STATUS PEMBAYARAN: LUNAS\n" .
                                  "Denda telah dibayar pada " . $dendaDibayarPada->translatedFormat('d F Y, H:i') . " WIB\n" .
                                  "Terima kasih atas pembayaran Anda.\n\n" .
                                  "🎉 Anda dapat meminjam buku lain kapan saja.";
                    $tipeNotif = 'pengembalian_sukses'; // ✅ FIXED: gunakan tipe yang ada
                    $judulNotif = "Pengembalian & Denda Lunas: {$peminjaman->buku->judul}";
                } else {
                    $pesanNotif .= "⚠️ STATUS PEMBAYARAN: BELUM LUNAS\n\n" .
                                  "PENTING: Harap segera melunasi denda kepada petugas {$petugas->name} di perpustakaan.";
                    $tipeNotif = 'denda_belum_dibayar';
                    $tipePrioritas = 'mendesak';
                    $judulNotif = "Pengembalian dengan Denda: {$peminjaman->buku->judul}";
                }
            } else {
                $pesanNotif .= "\n✅ Tidak ada denda.\n\n🎉 Terima kasih telah mengembalikan buku tepat waktu!";
            }

            // ✅ FIXED: Route checking yang aman
            $userRole = $peminjaman->mahasiswa->role;
            $notifUrl = '#'; // Default fallback
            
            try {
                if ($userRole === 'mahasiswa') {
                    if (\Route::has('mahasiswa.peminjaman.show')) {
                        $notifUrl = route('mahasiswa.peminjaman.show', $peminjaman->id);
                    }
                } elseif (in_array($userRole, ['pengguna_luar', 'pengguna-luar'])) {
                    if (\Route::has('pengguna-luar.peminjaman.show')) {
                        $notifUrl = route('pengguna-luar.peminjaman.show', $peminjaman->id);
                    } elseif (\Route::has('mahasiswa.peminjaman.show')) {
                        $notifUrl = route('mahasiswa.peminjaman.show', $peminjaman->id);
                    }
                } else {
                    if (\Route::has('mahasiswa.peminjaman.show')) {
                        $notifUrl = route('mahasiswa.peminjaman.show', $peminjaman->id);
                    }
                }
            } catch (\Exception $routeError) {
                Log::warning('⚠️ Error generating route', [
                    'error' => $routeError->getMessage(),
                    'user_role' => $userRole
                ]);
            }
            
            Notifikasi::kirim(
                $peminjaman->mahasiswa_id,
                $tipeNotif,
                $judulNotif,
                $pesanNotif,
                [
                    'peminjaman_id' => $peminjaman->id,
                    'pengembalian_id' => $pengembalian->id,
                    'buku_id' => $peminjaman->buku_id,
                    'denda' => $denda,
                    'denda_dibayar' => $dendaDibayar,
                    'hari_terlambat' => $hariTerlambat,
                    'status' => $statusKeterlambatan,
                ],
                $notifUrl,
                $tipePrioritas,
                $petugasId
            );

            DB::commit();

            $message = 'Pengembalian berhasil diproses';
            if ($denda > 0) {
                $message .= $dendaDibayar 
                    ? ' dan denda Rp ' . number_format($denda, 0, ',', '.') . ' telah LUNAS.'
                    : ' dengan denda Rp ' . number_format($denda, 0, ',', '.') . ' (BELUM DIBAYAR).';
            }

            return redirect()->route('petugas.pengembalian.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ERROR PENGEMBALIAN: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function editDenda($id)
    {
        $pengembalian = Pengembalian::with(['peminjaman.mahasiswa', 'peminjaman.buku', 'petugas'])
            ->findOrFail($id);

        if ($pengembalian->denda <= 0) {
            return redirect()->route('petugas.pengembalian.riwayat')
                ->with('error', 'Tidak ada denda pada pengembalian ini');
        }

        if ($pengembalian->denda_dibayar) {
            return redirect()->route('petugas.pengembalian.riwayat')
                ->with('info', 'Denda sudah dibayar sebelumnya pada ' . $pengembalian->denda_dibayar_pada->format('d M Y H:i'));
        }

        return view('petugas.pengembalian.edit-denda', compact('pengembalian'));
    }

    public function updatePembayaranDenda(Request $request, $id)
    {
        $request->validate([
            'catatan_pembayaran' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // ✅ FIXED: Pastikan relasi ter-load dengan benar
            $pengembalian = Pengembalian::with(['peminjaman.mahasiswa', 'peminjaman.buku'])
                ->findOrFail($id);

            // ✅ FIXED: Validasi data penting
            if (!$pengembalian->peminjaman) {
                Log::error('❌ Data peminjaman tidak ditemukan', ['pengembalian_id' => $id]);
                return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan');
            }

            if (!$pengembalian->peminjaman->mahasiswa) {
                Log::error('❌ Data mahasiswa tidak ditemukan', ['peminjaman_id' => $pengembalian->peminjaman_id]);
                return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan');
            }

            if ($pengembalian->denda <= 0) {
                return redirect()->back()->with('error', 'Tidak ada denda yang perlu dibayar');
            }

            if ($pengembalian->denda_dibayar) {
                return redirect()->back()->with('info', 'Denda sudah dibayar sebelumnya');
            }

            $petugasNama = Auth::user()->name;
            $petugasId = Auth::id();

            Log::info('========== MULAI UPDATE PEMBAYARAN DENDA ==========', [
                'pengembalian_id' => $pengembalian->id,
                'mahasiswa_id' => $pengembalian->peminjaman->mahasiswa_id,
                'denda' => $pengembalian->denda,
            ]);

            $pengembalian->update([
                'denda_dibayar' => true,
                'denda_dibayar_pada' => now(),
                'catatan_pembayaran' => $request->catatan_pembayaran ?? 'Denda dilunasi oleh petugas',
            ]);

            Log::info('✅ Status pembayaran denda berhasil diupdate');

            // ✅ FIXED: Tentukan URL notifikasi dengan pengecekan yang aman
            $userRole = $pengembalian->peminjaman->mahasiswa->role;
            $notifUrl = '#'; // Default fallback
            
            try {
                if ($userRole === 'mahasiswa') {
                    if (\Route::has('mahasiswa.peminjaman.show')) {
                        $notifUrl = route('mahasiswa.peminjaman.show', $pengembalian->peminjaman_id);
                    }
                } elseif (in_array($userRole, ['pengguna_luar', 'pengguna-luar'])) {
                    if (\Route::has('pengguna-luar.peminjaman.show')) {
                        $notifUrl = route('pengguna-luar.peminjaman.show', $pengembalian->peminjaman_id);
                    } elseif (\Route::has('mahasiswa.peminjaman.show')) {
                        $notifUrl = route('mahasiswa.peminjaman.show', $pengembalian->peminjaman_id);
                    }
                } else {
                    if (\Route::has('mahasiswa.peminjaman.show')) {
                        $notifUrl = route('mahasiswa.peminjaman.show', $pengembalian->peminjaman_id);
                    }
                }
            } catch (\Exception $routeError) {
                Log::warning('⚠️ Error generating route, using fallback', [
                    'error' => $routeError->getMessage(),
                    'user_role' => $userRole
                ]);
            }

            $pesanNotif = "Pembayaran denda Anda telah dikonfirmasi!\n\n" .
                         "📚 Buku: {$pengembalian->peminjaman->buku->judul}\n" .
                         "💰 Jumlah Denda: Rp " . number_format($pengembalian->denda, 0, ',', '.') . "\n" .
                         "📅 Tanggal Pembayaran: " . now()->translatedFormat('d F Y, H:i') . " WIB\n" .
                         "👤 Dikonfirmasi oleh: {$petugasNama}\n\n" .
                         "✅ STATUS: LUNAS\n\n" .
                         "🎉 Terima kasih atas pembayaran Anda!";

            if ($request->filled('catatan_pembayaran')) {
                $pesanNotif .= "\n\n📝 Catatan: {$request->catatan_pembayaran}";
            }

            $notifikasiBerhasil = null;
            
            try {
                // ✅ FIXED: Gunakan 'pengembalian_sukses' bukan 'denda_lunas'
                $notifikasiBerhasil = Notifikasi::kirim(
                    $pengembalian->peminjaman->mahasiswa_id,
                    'pengembalian_sukses', // ✅ FIXED: gunakan tipe yang sudah ada di enum
                    "Pembayaran Denda Lunas: {$pengembalian->peminjaman->buku->judul}",
                    $pesanNotif,
                    [
                        'peminjaman_id' => $pengembalian->peminjaman_id,
                        'pengembalian_id' => $pengembalian->id,
                        'buku_id' => $pengembalian->peminjaman->buku_id,
                        'denda' => $pengembalian->denda,
                        'denda_dibayar' => true,
                        'status_pembayaran' => 'lunas',
                    ],
                    $notifUrl,
                    'normal',
                    $petugasId
                );

                if ($notifikasiBerhasil) {
                    Log::info('✅ Notifikasi pembayaran denda berhasil dikirim', [
                        'notifikasi_id' => $notifikasiBerhasil->id ?? 'unknown',
                        'mahasiswa_id' => $pengembalian->peminjaman->mahasiswa_id
                    ]);
                } else {
                    Log::error('❌ Notifikasi::kirim mengembalikan false');
                }
            } catch (\Exception $notifError) {
                Log::error('❌ Exception saat mengirim notifikasi', [
                    'error' => $notifError->getMessage(),
                    'trace' => $notifError->getTraceAsString(),
                    'mahasiswa_id' => $pengembalian->peminjaman->mahasiswa_id
                ]);
                // Jangan rollback transaction hanya karena notifikasi gagal
            }

            DB::commit();

            Log::info('========== UPDATE PEMBAYARAN DENDA SELESAI ==========');

            $successMessage = 'Status pembayaran denda berhasil diupdate.';
            if ($notifikasiBerhasil) {
                $successMessage .= ' Notifikasi telah dikirim ke ' . $pengembalian->peminjaman->mahasiswa->name . '.';
            } else {
                $successMessage .= ' Namun notifikasi gagal dikirim. Silakan informasikan mahasiswa secara manual.';
            }

            return redirect()->route('petugas.pengembalian.riwayat')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ ERROR UPDATE PEMBAYARAN DENDA', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'pengembalian_id' => $id
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function riwayat(Request $request)
    {
        $query = Pengembalian::with(['peminjaman.mahasiswa', 'peminjaman.buku', 'petugas'])
            ->orderBy('created_at', 'desc');

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

        if ($request->filled('role')) {
            $query->whereHas('peminjaman.mahasiswa', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pengembalian', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pengembalian', '<=', $request->tanggal_sampai);
        }

        if ($request->filled('status_denda')) {
            if ($request->status_denda === 'lunas') {
                $query->where('denda_dibayar', true);
            } elseif ($request->status_denda === 'belum_lunas') {
                $query->where('denda', '>', 0)->where('denda_dibayar', false);
            }
        }

        $pengembalian = $query->paginate(15)->appends(request()->query());

        $stats = [
            'total' => Pengembalian::count(),
            'total_denda' => Pengembalian::sum('denda'),
            'denda_lunas' => Pengembalian::where('denda_dibayar', true)->sum('denda'),
            'denda_belum_lunas' => Pengembalian::where('denda', '>', 0)->where('denda_dibayar', false)->sum('denda'),
            'bulan_ini' => Pengembalian::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return view('petugas.pengembalian.riwayat', compact('pengembalian', 'stats'));
    }

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