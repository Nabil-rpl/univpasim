<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Laporan;
use App\Models\QRCode;

class DashboardController extends Controller
{
    /**
     * Dashboard Admin dengan data user management
     */
    public function admin()
    {
        // Ambil semua data user
        $users = User::orderBy('created_at', 'desc')->get();
        
        // Kirim ke view admin.dashboard
        return view('admin.dashboard', compact('users'));
    }

    /**
     * Dashboard Petugas
     */
    public function petugas()
    {
        $user = Auth::user();

        // Ambil semua laporan milik petugas yang login
        $laporans = Laporan::where('dibuat_oleh', $user->id)->get();

        // Ambil semua QR code milik petugas
        $qrcodes = QRCode::where('user_id', $user->id)->get();

        // Kirim ke view petugas.dashboard
        return view('petugas.dashboard', compact('laporans', 'qrcodes'));
    }

    /**
     * Dashboard Mahasiswa
     */
    public function mahasiswa()
    {
        return view('mahasiswa.dashboard');
    }
}
