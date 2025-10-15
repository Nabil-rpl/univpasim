<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QRCode;
use App\Models\User;
use Illuminate\Http\Request;

class QRCodeController extends Controller
{
    public function index()
    {
        $qrcodes = QRCode::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.qrcodes.index', compact('qrcodes'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.qrcodes.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_unik' => 'required|string|max:255|unique:qr_codes',
            'gambar_qr' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
        ]);

        QRCode::create($request->all());
        return redirect()->route('admin.qrcodes.index')->with('success', 'QR Code berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $qrcode = QRCode::findOrFail($id);
        $users = User::all();
        return view('admin.qrcodes.edit', compact('qrcode', 'users'));
    }

    public function update(Request $request, $id)
    {
        $qrcode = QRCode::findOrFail($id);
        $request->validate([
            'kode_unik' => 'required|string|max:255|unique:qr_codes,kode_unik,' . $id,
            'gambar_qr' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $qrcode->update($request->all());
        return redirect()->route('admin.qrcodes.index')->with('success', 'QR Code berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $qrcode = QRCode::findOrFail($id);
        $qrcode->delete();
        return redirect()->route('admin.qrcodes.index')->with('success', 'QR Code berhasil dihapus!');
    }
}
