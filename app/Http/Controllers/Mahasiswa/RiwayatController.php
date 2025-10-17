<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Mahasiswa as MahasiswaModel;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    /**
     * Display riwayat peminjaman mahasiswa
     */
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = MahasiswaModel::where('email', $user->email)->first();

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $peminjamans = Peminjaman::where('mahasiswa_id', $mahasiswa->id)
            ->with(['buku', 'petugas'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('mahasiswa.riwayat.index', compact('peminjamans', 'mahasiswa'));
    }
}