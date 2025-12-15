<?php

namespace App\Http\Controllers\PenggunaLuar;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotifikasiController extends Controller
{
    /**
     * Display a listing of notifications
     */
    public function index(Request $request)
    {
        try {
            $query = Notifikasi::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc');

            // Filter berdasarkan status
            if ($request->filled('status')) {
                if ($request->status === 'unread') {
                    $query->where('dibaca', false);
                } elseif ($request->status === 'read') {
                    $query->where('dibaca', true);
                }
            }

            // Filter berdasarkan tipe
            if ($request->filled('tipe')) {
                $query->where('tipe', $request->tipe);
            }

            // Filter pencarian
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('isi', 'like', "%{$search}%");
                });
            }

            $notifikasi = $query->paginate(20)->withQueryString();

            $unreadCount = Notifikasi::where('user_id', auth()->id())
                ->where('dibaca', false)
                ->count();

            // Statistik notifikasi
            $stats = [
                'terlambat' => Notifikasi::where('user_id', auth()->id())
                    ->where('tipe', 'terlambat')
                    ->where('dibaca', false)
                    ->count(),
                'reminder_deadline' => Notifikasi::where('user_id', auth()->id())
                    ->where('tipe', 'reminder_deadline')
                    ->where('dibaca', false)
                    ->count(),
                'denda_belum_dibayar' => Notifikasi::where('user_id', auth()->id())
                    ->where('tipe', 'denda_belum_dibayar')
                    ->where('dibaca', false)
                    ->count(),
            ];

            return view('pengguna_luar.notifikasi.index', compact('notifikasi', 'unreadCount', 'stats'));

        } catch (\Exception $e) {
            Log::error('Error pada index notifikasi pengguna luar', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('pengguna-luar.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat notifikasi');
        }
    }

    /**
     * Display the specified notification (redirect to index with highlight)
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

                Log::info('Notifikasi pengguna luar ditandai sebagai dibaca', [
                    'notifikasi_id' => $id,
                    'user_id' => auth()->id()
                ]);
            }

            // Redirect ke index dengan highlight
            return redirect()->route('pengguna-luar.notifikasi.index', ['highlight' => $id])
                ->with('notif_opened', $id);

        } catch (\Exception $e) {
            Log::error('Error menampilkan detail notifikasi pengguna luar', [
                'notifikasi_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('pengguna-luar.notifikasi.index')
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
            }

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notifikasi ditandai sebagai dibaca'
                ]);
            }

            return redirect()->back()->with('success', 'Notifikasi ditandai sebagai dibaca');

        } catch (\Exception $e) {
            Log::error('Error mark as read pengguna luar', [
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

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Berhasil menandai {$updated} notifikasi sebagai dibaca",
                    'count' => $updated
                ]);
            }

            return redirect()->back()
                ->with('success', "Berhasil menandai {$updated} notifikasi sebagai dibaca");

        } catch (\Exception $e) {
            Log::error('Error mark all as read pengguna luar', [
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
     * Remove the specified notification
     */
    public function destroy(string $id)
    {
        try {
            $notifikasi = Notifikasi::where('user_id', auth()->id())
                ->findOrFail($id);

            $judul = $notifikasi->judul;
            $notifikasi->delete();

            Log::info('Notifikasi pengguna luar dihapus', [
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

            return redirect()->route('pengguna-luar.notifikasi.index')
                ->with('success', 'Notifikasi berhasil dihapus');

        } catch (\Exception $e) {
            Log::error('Error menghapus notifikasi pengguna luar', [
                'notifikasi_id' => $id,
                'error' => $e->getMessage()
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus notifikasi');
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
            Log::error('Error get unread count pengguna luar', [
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
                        'link' => route('pengguna-luar.notifikasi.show', $n->id),
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
            Log::error('Error get latest notifications pengguna luar', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'notifikasi' => [],
                'unread_count' => 0
            ], 500);
        }
    }
}