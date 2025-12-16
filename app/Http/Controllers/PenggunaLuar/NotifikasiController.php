<?php

namespace App\Http\Controllers\PenggunaLuar;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotifikasiController extends Controller
{
    /**
     * Display a listing of notifications
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $perPage = $request->input('per_page', 15);
            $tipe = $request->input('tipe');
            $status = $request->input('status');

            $query = Notifikasi::where('user_id', $user->id)
                ->orderBy('prioritas', 'desc')
                ->orderBy('created_at', 'desc');

            if ($tipe) {
                $query->where('tipe', $tipe);
            }

            if ($status === 'dibaca') {
                $query->sudahDibaca();
            } elseif ($status === 'belum_dibaca') {
                $query->belumDibaca();
            }

            $notifikasi = $query->paginate($perPage);

            $stats = [
                'total' => Notifikasi::where('user_id', $user->id)->count(),
                'belum_dibaca' => Notifikasi::where('user_id', $user->id)->belumDibaca()->count(),
                'sudah_dibaca' => Notifikasi::where('user_id', $user->id)->sudahDibaca()->count(),
                'hari_ini' => Notifikasi::where('user_id', $user->id)->hariIni()->count(),
            ];

            $tipeNotifikasi = [
                'peminjaman_baru' => 'Peminjaman Baru',
                'peminjaman_disetujui' => 'Peminjaman Disetujui',
                'peminjaman_ditolak' => 'Peminjaman Ditolak',
                'perpanjangan_baru' => 'Perpanjangan Baru',
                'perpanjangan_disetujui' => 'Perpanjangan Disetujui',
                'perpanjangan_ditolak' => 'Perpanjangan Ditolak',
                'pengembalian_sukses' => 'Pengembalian Sukses',
                'reminder_deadline' => 'Pengingat Deadline',
                'terlambat' => 'Keterlambatan',
                'buku_tersedia' => 'Buku Tersedia',
                'denda_belum_dibayar' => 'Denda Belum Dibayar',
                'sistem' => 'Notifikasi Sistem',
            ];

            return view('pengguna-luar.notifikasi.index', compact(
                'notifikasi',
                'stats',
                'tipeNotifikasi'
            ));

        } catch (\Exception $e) {
            Log::error('Error menampilkan notifikasi', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Gagal memuat notifikasi');
        }
    }

    /**
     * ✅ Get notifikasi terbaru untuk dropdown (AJAX)
     * Method name: latest (sesuai route)
     */
    public function latest(Request $request)
    {
        try {
            Log::info('=== NOTIFIKASI LATEST CALLED ===', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name
            ]);

            $limit = $request->input('limit', 10);

            $notifikasi = Notifikasi::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($notif) {
                    return [
                        'id' => $notif->id,
                        'tipe' => $notif->tipe,
                        'judul' => $notif->judul,
                        'isi' => mb_substr($notif->isi, 0, 80),
                        'link' => $notif->url ?? route('pengguna-luar.notifikasi.show', $notif->id),
                        'dibaca' => (bool) $notif->dibaca,
                        'waktu' => $notif->created_at->locale('id')->diffForHumans(),
                        'created_at' => $notif->created_at->format('Y-m-d H:i:s')
                    ];
                });

            $unreadCount = Notifikasi::where('user_id', Auth::id())
                ->where('dibaca', false)
                ->count();

            Log::info('=== NOTIFIKASI LATEST SUCCESS ===', [
                'count' => $notifikasi->count(),
                'unread' => $unreadCount
            ]);

            return response()->json([
                'success' => true,
                'notifikasi' => $notifikasi,
                'unread_count' => $unreadCount
            ]);

        } catch (\Exception $e) {
            Log::error('=== ERROR NOTIFIKASI LATEST ===', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat notifikasi'
            ], 500);
        }
    }

    /**
     * ✅ Tandai notifikasi sebagai dibaca
     * Method name: markAsRead (sesuai route mark-as-read)
     */
    public function markAsRead($id)
    {
        try {
            Log::info('Mark as read called', ['id' => $id, 'user' => Auth::id()]);

            $notifikasi = Notifikasi::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $notifikasi->tandaiDibaca();

            // Jika request AJAX
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notifikasi ditandai sebagai dibaca'
                ]);
            }

            // Redirect ke URL yang ada di notifikasi (jika ada)
            if ($notifikasi->url) {
                return redirect($notifikasi->url);
            }

            return redirect()->back()->with('success', 'Notifikasi ditandai sebagai dibaca');

        } catch (\Exception $e) {
            Log::error('Error tandai notifikasi sebagai dibaca', [
                'notifikasi_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menandai notifikasi'
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal menandai notifikasi');
        }
    }

    /**
     * ✅ Tandai notifikasi sebagai belum dibaca
     * Method name: markAsUnread (sesuai route mark-as-unread)
     */
    public function markAsUnread($id)
    {
        try {
            $notifikasi = Notifikasi::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $notifikasi->tandaiTidakDibaca();

            return redirect()->back()->with('success', 'Notifikasi ditandai sebagai belum dibaca');

        } catch (\Exception $e) {
            Log::error('Error tandai notifikasi sebagai belum dibaca', [
                'notifikasi_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Gagal menandai notifikasi');
        }
    }

    /**
     * ✅ Tandai semua notifikasi sebagai dibaca
     * Method name: markAllRead (sesuai route mark-all-read)
     */
    public function markAllRead()
    {
        try {
            $updated = Notifikasi::where('user_id', Auth::id())
                ->belumDibaca()
                ->update([
                    'dibaca' => true,
                    'dibaca_pada' => now()
                ]);

            Log::info('Semua notifikasi ditandai sebagai dibaca', [
                'user_id' => Auth::id(),
                'jumlah' => $updated
            ]);

            // Jika request AJAX
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Berhasil menandai $updated notifikasi sebagai dibaca"
                ]);
            }

            return redirect()->back()->with('success', "Berhasil menandai $updated notifikasi sebagai dibaca");

        } catch (\Exception $e) {
            Log::error('Error tandai semua notifikasi sebagai dibaca', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menandai semua notifikasi'
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal menandai semua notifikasi');
        }
    }

    /**
     * ✅ Hapus notifikasi
     */
    public function destroy($id)
    {
        try {
            $notifikasi = Notifikasi::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $notifikasi->delete();

            Log::info('Notifikasi dihapus', [
                'notifikasi_id' => $id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'Notifikasi berhasil dihapus');

        } catch (\Exception $e) {
            Log::error('Error hapus notifikasi', [
                'notifikasi_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Gagal menghapus notifikasi');
        }
    }

    /**
     * ✅ Hapus notifikasi yang sudah dibaca
     * Method name: deleteRead (sesuai route delete-read)
     */
    public function deleteRead()
    {
        try {
            $deleted = Notifikasi::where('user_id', Auth::id())
                ->sudahDibaca()
                ->delete();

            Log::info('Notifikasi yang sudah dibaca dihapus', [
                'user_id' => Auth::id(),
                'jumlah' => $deleted
            ]);

            return redirect()->back()->with('success', "Berhasil menghapus $deleted notifikasi yang sudah dibaca");

        } catch (\Exception $e) {
            Log::error('Error hapus notifikasi yang sudah dibaca', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Gagal menghapus notifikasi');
        }
    }

    /**
     * Show detail notifikasi
     */
    public function show($id)
    {
        try {
            $notifikasi = Notifikasi::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Tandai sebagai dibaca jika belum dibaca
            if (!$notifikasi->dibaca) {
                $notifikasi->tandaiDibaca();
            }

            return view('pengguna-luar.notifikasi.show', compact('notifikasi'));

        } catch (\Exception $e) {
            Log::error('Error menampilkan detail notifikasi', [
                'notifikasi_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->route('pengguna-luar.notifikasi.index')
                ->with('error', 'Notifikasi tidak ditemukan');
        }
    }
}