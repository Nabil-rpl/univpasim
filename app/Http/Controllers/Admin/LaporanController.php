<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     * Admin hanya bisa melihat daftar laporan (READ ONLY)
     */
    public function index()
    {
        $laporan = Laporan::with('pembuat')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.laporan.index', compact('laporan'));
    }

    /**
     * Display the specified resource.
     * Admin hanya bisa melihat detail laporan (READ ONLY)
     */
    public function show(Laporan $laporan)
    {
        $laporan->load('pembuat');
        return view('admin.laporan.show', compact('laporan'));
    }

    // Admin TIDAK BISA:
    // - create() - tidak ada
    // - store() - tidak ada
    // - edit() - tidak ada
    // - update() - tidak ada
    // - destroy() - tidak ada (bisa ditambahkan jika admin boleh hapus)
}