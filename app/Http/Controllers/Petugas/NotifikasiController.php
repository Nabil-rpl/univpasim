<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotifikasiController extends Controller
{
    /**
     * Display a listing of the resource with filters
     */
    public function index(Request $request)
    {
        try {
            $query = Notifikasi::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc');

            // Filter berdasarkan status (dibaca/belum dibaca)
            if ($request->filled('status')) {
                if ($request->status === 'unread') {
                    $query->where('dibaca', false);
                } elseif ($request->status === 'read') {
                    $query->where('dibaca', true);
                }
            }

            // Filter berdasarkan tipe notifikasi
            if ($request->filled('tipe')) {
                $query->where('tipe', $request->tipe);
            }

            // Filter berdasarkan pencarian (judul atau isi)
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('isi', 'like', "%{$search}%");
                });
            }

            // Pagination
            $notifikasi = $query->paginate(20)->withQueryString();

            // Hitung notifikasi belum dibaca
            $belumDibaca = Notifikasi::where('user_id', auth()->id())
                ->where('dibaca', false)
                ->count();

            // Statistik notifikasi untuk petugas (hanya yang relevan)
            $stats = [
                'peminjaman_baru' => Notifikasi::where('user_id', auth()->id())
                    ->where('tipe', 'peminjaman_baru')
                    ->where('dibaca', false)
                    ->count(),
                'perpanjangan_baru' => Notifikasi::where('user_id', auth()->id())
                    ->where('tipe', 'perpanjangan_baru')
                    ->where('dibaca', false)
                    ->count(),
                'pengembalian_baru' => Notifikasi::where('user_id', auth()->id())
                    ->where('tipe', 'pengembalian_baru')
                    ->where('dibaca', false)
                    ->count(),
                'terlambat' => Notifikasi::where('user_id', auth()->id())
                    ->where('tipe', 'terlambat')
                    ->where('dibaca', false)
                    ->count(),
            ];

            Log::info('Index notifikasi petugas', [
                'user_id' => auth()->id(),
                'total_notifikasi' => $notifikasi->total(),
                'belum_dibaca' => $belumDibaca,
                'filters' => $request->only(['status', 'tipe', 'search'])
            ]);

            return view('petugas.notifikasi.index', compact('notifikasi', 'belumDibaca', 'stats'));

        } catch (\Exception $e) {
            Log::error('Error pada index notifikasi', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('petugas.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat notifikasi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $notifikasi = Notifikasi::where('user_id', auth()->id())
                ->findOrFail($id);

            // Tandai sebagai dibaca jika belum
            if (!$notifikasi->dibaca) {
                $notifikasi->update([
                    'dibaca' => true,
                    'dibaca_pada' => now()
                ]);

                Log::info('Notifikasi ditandai sebagai dibaca', [
                    'notifikasi_id' => $id,
                    'user_id' => auth()->id()
                ]);
            }

            return view('petugas.notifikasi.show', compact('notifikasi'));

        } catch (\Exception $e) {
            Log::error('Error menampilkan detail notifikasi', [
                'notifikasi_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('petugas.notifikasi.index')
                ->with('error', 'Notifikasi tidak ditemukan');
        }
    }

    /**
     * Mark notification as read (AJAX)
     */
    public function markAsRead($id)
    {
        try {
            $notifikasi = Notifikasi::where('user_id', auth()->id())
                ->findOrFail($id);

            if (!$notifikasi->dibaca) {
                $notifikasi->update([
                    'dibaca' => true,
                    'dibaca_pada' => now()
                ]);

                Log::info('Notifikasi ditandai sebagai dibaca via AJAX', [
                    'notifikasi_id' => $id,
                    'user_id' => auth()->id()
                ]);
            }

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notifikasi ditandai sebagai dibaca'
                ]);
            }

            return redirect()->back()->with('success', 'Notifikasi ditandai sebagai dibaca');

        } catch (\Exception $e) {
            Log::error('Error mark as read', [
                'notifikasi_id' => $id,
                'error' => $e->getMessage()
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan'
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        try {
            $updated = Notifikasi::where('user_id', auth()->id())
                ->where('dibaca', false)
                ->update([
                    'dibaca' => true,
                    'dibaca_pada' => now(),
                ]);

            Log::info('Semua notifikasi ditandai sebagai dibaca', [
                'user_id' => auth()->id(),
                'jumlah' => $updated
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Berhasil menandai {$updated} notifikasi sebagai dibaca",
                    'count' => $updated
                ]);
            }

            return redirect()->back()->with('success', "Berhasil menandai {$updated} notifikasi sebagai dibaca");

        } catch (\Exception $e) {
            Log::error('Error mark all as read', [
                'error' => $e->getMessage()
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan'
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $notifikasi = Notifikasi::where('user_id', auth()->id())
                ->findOrFail($id);

            $judul = $notifikasi->judul;
            $notifikasi->delete();

            Log::info('Notifikasi dihapus', [
                'notifikasi_id' => $id,
                'judul' => $judul,
                'user_id' => auth()->id()
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notifikasi berhasil dihapus'
                ]);
            }

            return redirect()->route('petugas.notifikasi.index')
                ->with('success', 'Notifikasi berhasil dihapus');

        } catch (\Exception $e) {
            Log::error('Error menghapus notifikasi', [
                'notifikasi_id' => $id,
                'error' => $e->getMessage()
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan'
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus notifikasi');
        }
    }

    /**
     * Delete all read notifications
     */
    public function deleteRead()
    {
        try {
            $deleted = Notifikasi::where('user_id', auth()->id())
                ->where('dibaca', true)
                ->delete();

            Log::info('Notifikasi yang sudah dibaca dihapus', [
                'user_id' => auth()->id(),
                'jumlah' => $deleted
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Berhasil menghapus {$deleted} notifikasi yang sudah dibaca",
                    'count' => $deleted
                ]);
            }

            return redirect()->back()->with('success', "Berhasil menghapus {$deleted} notifikasi yang sudah dibaca");

        } catch (\Exception $e) {
            Log::error('Error delete read notifications', [
                'error' => $e->getMessage()
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan'
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }

    /**
     * Get unread notifications count (for AJAX)
     */
    public function getUnreadCount()
    {
        try {
            $count = Notifikasi::where('user_id', auth()->id())
                ->where('dibaca', false)
                ->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);

        } catch (\Exception $e) {
            Log::error('Error get unread count', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'count' => 0
            ], 500);
        }
    }

    /**
     * Get latest notifications (for AJAX dropdown)
     */
    public function getLatest()
    {
        try {
            $notifikasi = Notifikasi::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($n) {
                    return [
                        'id' => $n->id,
                        'judul' => $n->judul,
                        'isi' => \Str::limit($n->isi, 100),
                        'tipe' => $n->tipe,
                        'dibaca' => $n->dibaca,
                        'waktu' => $n->getWaktuRelatif(),
                        'icon' => $n->getIcon(),
                        'badge_color' => $n->getBadgeColor(),
                        'link' => route('petugas.notifikasi.show', $n->id),
                        'created_at' => $n->created_at->format('Y-m-d H:i:s')
                    ];
                });

            $unreadCount = Notifikasi::where('user_id', auth()->id())
                ->where('dibaca', false)
                ->count();

            return response()->json([
                'success' => true,
                'notifikasi' => $notifikasi,
                'unread_count' => $unreadCount
            ]);

        } catch (\Exception $e) {
            Log::error('Error get latest notifications', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'notifikasi' => [],
                'unread_count' => 0
            ], 500);
        }
    }

    /**
     * Filter by notification type
     */
    public function filterByType($type)
    {
        return redirect()->route('petugas.notifikasi.index', ['tipe' => $type]);
    }

    /**
     * Show unread notifications only
     */
    public function unread()
    {
        return redirect()->route('petugas.notifikasi.index', ['status' => 'unread']);
    }
}