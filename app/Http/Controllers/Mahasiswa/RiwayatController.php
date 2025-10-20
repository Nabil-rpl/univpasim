<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Mahasiswa as MahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:mahasiswa']);
    }

    /**
     * Tampilkan halaman riwayat peminjaman
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = MahasiswaModel::where('email', $user->email)->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Query peminjaman
        $query = Peminjaman::where('mahasiswa_id', $mahasiswa->id)
            ->with(['buku', 'petugas']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_sampai);
        }

        // Ambil data dan urutkan dari terbaru
        $peminjaman = $query->orderBy('created_at', 'desc')->get();

        return view('mahasiswa.peminjaman.riwayat', compact('peminjaman', 'mahasiswa'));
    }
}