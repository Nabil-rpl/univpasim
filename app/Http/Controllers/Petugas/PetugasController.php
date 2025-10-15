<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Laporan;
use App\Models\QrCode;

class PetugasController extends Controller
{
    public function __construct()
    {
        // Middleware agar hanya role petugas yang bisa masuk
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Hanya tampilkan laporan dan QR milik petugas login
        $laporans = Laporan::where('dibuat_oleh', $user->id)->get();
        $qrcodes = QrCode::where('dibuat_oleh', $user->id)->get();

        return view('petugas.dashboard', compact('laporans', 'qrcodes'));
    }
}
