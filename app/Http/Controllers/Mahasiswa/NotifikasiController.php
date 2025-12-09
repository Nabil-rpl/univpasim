<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifikasi = Notifikasi::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $belumDibaca = Notifikasi::where('user_id', auth()->id())
            ->belumDibaca()
            ->count();

        // Statistik notifikasi untuk mahasiswa
        $stats = [
            'terlambat' => Notifikasi::where('user_id', auth()->id())
                ->where('tipe', 'terlambat')
                ->belumDibaca()
                ->count(),
            'reminder_deadline' => Notifikasi::where('user_id', auth()->id())
                ->where('tipe', 'reminder_deadline')
                ->belumDibaca()
                ->count(),
            'denda_belum_dibayar' => Notifikasi::where('user_id', auth()->id())
                ->where('tipe', 'denda_belum_dibayar')
                ->belumDibaca()
                ->count(),
            'peminjaman' => Notifikasi::where('user_id', auth()->id())
                ->whereIn('tipe', ['peminjaman_disetujui', 'peminjaman_ditolak'])
                ->belumDibaca()
                ->count(),
            'perpanjangan' => Notifikasi::where('user_id', auth()->id())
                ->whereIn('tipe', ['perpanjangan_disetujui', 'perpanjangan_ditolak'])
                ->belumDibaca()
                ->count(),
        ];

        return view('mahasiswa.notifikasi.index', compact('notifikasi', 'belumDibaca', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $notifikasi = Notifikasi::where('user_id', auth()->id())
            ->findOrFail($id);

        // Tandai sebagai dibaca
        if (!$notifikasi->dibaca) {
            $notifikasi->tandaiDibaca();
        }

        return view('mahasiswa.notifikasi.show', compact('notifikasi'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notifikasi = Notifikasi::where('user_id', auth()->id())
            ->findOrFail($id);

        $notifikasi->tandaiDibaca();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notifikasi ditandai sebagai dibaca');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notifikasi::where('user_id', auth()->id())
            ->where('dibaca', false)
            ->update([
                'dibaca' => true,
                'dibaca_pada' => now(),
            ]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Semua notifikasi ditandai sebagai dibaca');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $notifikasi = Notifikasi::where('user_id', auth()->id())
            ->findOrFail($id);

        $notifikasi->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus');
    }

    /**
     * Delete all read notifications
     */
    public function deleteRead()
    {
        Notifikasi::where('user_id', auth()->id())
            ->where('dibaca', true)
            ->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notifikasi yang sudah dibaca berhasil dihapus');
    }

    /**
     * Get unread notifications count (for AJAX)
     */
    public function getUnreadCount()
    {
        $count = Notifikasi::where('user_id', auth()->id())
            ->belumDibaca()
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get latest notifications (for AJAX dropdown)
     */
    public function getLatest()
    {
        $notifikasi = Notifikasi::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'notifikasi' => $notifikasi,
            'count' => $notifikasi->where('dibaca', false)->count(),
        ]);
    }

    /**
     * Filter by notification type
     */
    public function filterByType($type)
    {
        $notifikasi = Notifikasi::where('user_id', auth()->id())
            ->where('tipe', $type)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $belumDibaca = Notifikasi::where('user_id', auth()->id())
            ->belumDibaca()
            ->count();

        $stats = [
            'terlambat' => Notifikasi::where('user_id', auth()->id())
                ->where('tipe', 'terlambat')
                ->belumDibaca()
                ->count(),
            'reminder_deadline' => Notifikasi::where('user_id', auth()->id())
                ->where('tipe', 'reminder_deadline')
                ->belumDibaca()
                ->count(),
            'denda_belum_dibayar' => Notifikasi::where('user_id', auth()->id())
                ->where('tipe', 'denda_belum_dibayar')
                ->belumDibaca()
                ->count(),
            'peminjaman' => Notifikasi::where('user_id', auth()->id())
                ->whereIn('tipe', ['peminjaman_disetujui', 'peminjaman_ditolak'])
                ->belumDibaca()
                ->count(),
            'perpanjangan' => Notifikasi::where('user_id', auth()->id())
                ->whereIn('tipe', ['perpanjangan_disetujui', 'perpanjangan_ditolak'])
                ->belumDibaca()
                ->count(),
        ];

        return view('mahasiswa.notifikasi.index', compact('notifikasi', 'belumDibaca', 'stats'));
    }

    /**
     * Show unread notifications only
     */
    public function unread()
    {
        $notifikasi = Notifikasi::where('user_id', auth()->id())
            ->belumDibaca()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $belumDibaca = $notifikasi->total();

        $stats = [
            'terlambat' => Notifikasi::where('user_id', auth()->id())
                ->where('tipe', 'terlambat')
                ->belumDibaca()
                ->count(),
            'reminder_deadline' => Notifikasi::where('user_id', auth()->id())
                ->where('tipe', 'reminder_deadline')
                ->belumDibaca()
                ->count(),
            'denda_belum_dibayar' => Notifikasi::where('user_id', auth()->id())
                ->where('tipe', 'denda_belum_dibayar')
                ->belumDibaca()
                ->count(),
            'peminjaman' => Notifikasi::where('user_id', auth()->id())
                ->whereIn('tipe', ['peminjaman_disetujui', 'peminjaman_ditolak'])
                ->belumDibaca()
                ->count(),
            'perpanjangan' => Notifikasi::where('user_id', auth()->id())
                ->whereIn('tipe', ['perpanjangan_disetujui', 'perpanjangan_ditolak'])
                ->belumDibaca()
                ->count(),
        ];

        return view('mahasiswa.notifikasi.index', compact('notifikasi', 'belumDibaca', 'stats'));
    }

    /**
     * Show important notifications (terlambat, denda, reminder)
     */
    public function important()
    {
        $notifikasi = Notifikasi::where('user_id', auth()->id())
            ->whereIn('tipe', ['terlambat', 'denda_belum_dibayar', 'reminder_deadline'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $belumDibaca = Notifikasi::where('user_id', auth()->id())
            ->belumDibaca()
            ->count();

        $stats = [
            'terlambat' => Notifikasi::where('user_id', auth()->id())
                ->where('tipe', 'terlambat')
                ->belumDibaca()
                ->count(),
            'reminder_deadline' => Notifikasi::where('user_id', auth()->id())
                ->where('tipe', 'reminder_deadline')
                ->belumDibaca()
                ->count(),
            'denda_belum_dibayar' => Notifikasi::where('user_id', auth()->id())
                ->where('tipe', 'denda_belum_dibayar')
                ->belumDibaca()
                ->count(),
            'peminjaman' => Notifikasi::where('user_id', auth()->id())
                ->whereIn('tipe', ['peminjaman_disetujui', 'peminjaman_ditolak'])
                ->belumDibaca()
                ->count(),
            'perpanjangan' => Notifikasi::where('user_id', auth()->id())
                ->whereIn('tipe', ['perpanjangan_disetujui', 'perpanjangan_ditolak'])
                ->belumDibaca()
                ->count(),
        ];

        return view('mahasiswa.notifikasi.index', compact('notifikasi', 'belumDibaca', 'stats'));
    }

    /**
     * Show peminjaman related notifications
     */
    public function peminjaman()
    {
        $notifikasi = Notifikasi::where('user_id', auth()->id())
            ->whereIn('tipe', [
                'peminjaman_disetujui', 
                'peminjaman_ditolak',
                'pengembalian_sukses',
                'buku_tersedia'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $belumDibaca = Notifikasi::where('user_id', auth()->id())
            ->belumDibaca()
            ->count();

        $stats = [
            'terlambat' => Notifikasi::where('user_id', auth()->id())
                ->where('tipe', 'terlambat')
                ->belumDibaca()
                ->count(),
            'reminder_deadline' => Notifikasi::where('user_id', auth()->id())
                ->where('tipe', 'reminder_deadline')
                ->belumDibaca()
                ->count(),
            'denda_belum_dibayar' => Notifikasi::where('user_id', auth()->id())
                ->where('tipe', 'denda_belum_dibayar')
                ->belumDibaca()
                ->count(),
            'peminjaman' => Notifikasi::where('user_id', auth()->id())
                ->whereIn('tipe', ['peminjaman_disetujui', 'peminjaman_ditolak'])
                ->belumDibaca()
                ->count(),
            'perpanjangan' => Notifikasi::where('user_id', auth()->id())
                ->whereIn('tipe', ['perpanjangan_disetujui', 'perpanjangan_ditolak'])
                ->belumDibaca()
                ->count(),
        ];

        return view('mahasiswa.notifikasi.index', compact('notifikasi', 'belumDibaca', 'stats'));
    }

    /**
     * Show perpanjangan related notifications
     */
    public function perpanjangan()
    {
        $notifikasi = Notifikasi::where('user_id', auth()->id())
            ->whereIn('tipe', ['perpanjangan_disetujui', 'perpanjangan_ditolak'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $belumDibaca = Notifikasi::where('user_id', auth()->id())
            ->belumDibaca()
            ->count();

        $stats = [
            'terlambat' => Notifikasi::where('user_id', auth()->id())
                ->where('tipe', 'terlambat')
                ->belumDibaca()
                ->count(),
            'reminder_deadline' => Notifikasi::where('user_id', auth()->id())
                ->where('tipe', 'reminder_deadline')
                ->belumDibaca()
                ->count(),
            'denda_belum_dibayar' => Notifikasi::where('user_id', auth()->id())
                ->where('tipe', 'denda_belum_dibayar')
                ->belumDibaca()
                ->count(),
            'peminjaman' => Notifikasi::where('user_id', auth()->id())
                ->whereIn('tipe', ['peminjaman_disetujui', 'peminjaman_ditolak'])
                ->belumDibaca()
                ->count(),
            'perpanjangan' => Notifikasi::where('user_id', auth()->id())
                ->whereIn('tipe', ['perpanjangan_disetujui', 'perpanjangan_ditolak'])
                ->belumDibaca()
                ->count(),
        ];

        return view('mahasiswa.notifikasi.index', compact('notifikasi', 'belumDibaca', 'stats'));
    }
}