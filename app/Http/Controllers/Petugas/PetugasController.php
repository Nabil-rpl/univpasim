<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Laporan;
use App\Models\QrCode;
use App\Models\Buku;

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

        // Ambil data berdasarkan petugas login
        $laporans = Laporan::where('dibuat_oleh', $user->id)->get();
        $qrcodes  = QrCode::where('dibuat_oleh', $user->id)->get();
        $buku     = Buku::all(); // ✅ ini variabel yang benar

        // Kirim ke view
        return view('petugas.dashboard', compact('laporans', 'qrcodes', 'buku'));
    }
}
