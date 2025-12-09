<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // ✅ PERBAIKAN: Tambahkan filter user_id untuk admin yang login
        $query = Notifikasi::where('user_id', auth()->id());

        // Filter Status
        if ($request->status == 'unread') {
            $query->where('dibaca', false);
        } elseif ($request->status == 'read') {
            $query->where('dibaca', true);
        }

        // Filter Tipe
        if ($request->tipe) {
            $query->where('tipe', $request->tipe);
        }

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('isi', 'like', '%' . $request->search . '%');
            });
        }

        // Ambil data notifikasi dengan pagination
        $notifikasi = $query->orderBy('created_at', 'desc')->paginate(10);

        // Hitung notifikasi belum dibaca untuk user yang login
        $unreadCount = Notifikasi::where('user_id', auth()->id())
                                 ->where('dibaca', false)
                                 ->count();

        return view('admin.notifikasi.index', compact('notifikasi', 'unreadCount'));
    }

    /**
     * Show the form for creating a new resource (Broadcast Notifikasi).
     */
    public function create()
    {
        return view('admin.notifikasi.create');
    }

    /**
     * Store a newly created resource in storage (Broadcast Notifikasi).
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'target_role' => 'nullable|in:admin,petugas,mahasiswa,pengguna_luar,all',
            'prioritas' => 'required|in:rendah,normal,tinggi,mendesak',
        ]);

        $targetRole = $request->target_role === 'all' ? null : $request->target_role;

        if ($targetRole) {
            $userIds = User::where('role', $targetRole)->pluck('id');
            Notifikasi::kirimKeMultipleUsers(
                $userIds,
                'sistem',
                $request->judul,
                $request->isi,
                null,
                null,
                $request->prioritas,
                auth()->id()
            );
        } else {
            Notifikasi::kirimKeSemuaUser(
                'sistem',
                $request->judul,
                $request->isi,
                null,
                null,
                $request->prioritas,
                auth()->id()
            );
        }

        return redirect()->route('admin.notifikasi.index')
            ->with('success', 'Notifikasi berhasil dikirim!');
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
            $notifikasi->update([
                'dibaca' => true,
                'dibaca_pada' => now()
            ]);
        }

        return view('admin.notifikasi.show', compact('notifikasi'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notifikasi = Notifikasi::where('user_id', auth()->id())
            ->findOrFail($id);

        $notifikasi->update([
            'dibaca' => true,
            'dibaca_pada' => now()
        ]);

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
            ->where('dibaca', false)
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
     * View all system notifications sent by admin
     */
    public function systemNotifications()
    {
        $notifikasi = Notifikasi::where('tipe', 'sistem')
            ->where('dibuat_oleh', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notifikasi.system', compact('notifikasi'));
    }
}