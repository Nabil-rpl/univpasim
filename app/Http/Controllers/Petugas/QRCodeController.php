<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QRCode;
use App\Models\Buku;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;
use Illuminate\Support\Facades\Storage;

class QRCodeController extends Controller
{
    /**
     * Tampilkan semua QR milik petugas yang login
     */
    public function index()
    {
        $qrcodes = QRCode::where('user_id', Auth::id())->get();
        return view('petugas.qrcode.index', compact('qrcodes'));
    }

    /**
     * Generate QR code untuk buku / data tertentu
     */
    public function generate($type, $id)
    {
        // Contoh hanya untuk buku, tapi bisa ditambah untuk mahasiswa, dsb.
        if ($type === 'buku') {
            $item = Buku::findOrFail($id);
            $kode_unik = 'BUKU-' . strtoupper(uniqid());

            // Buat isi QR
            $dataQR = [
                'id' => $item->id,
                'judul' => $item->judul,
                'kode_unik' => $kode_unik,
            ];

            // Generate gambar QR
            $qrImage = QrCodeGenerator::format('png')->size(300)->generate(json_encode($dataQR));

            // Simpan ke storage
            $filename = 'qr_codes/' . $kode_unik . '.png';
            Storage::put('public/' . $filename, $qrImage);

            // Simpan ke database
            QRCode::create([
                'kode_unik' => $kode_unik,
                'gambar_qr' => $filename,
                'user_id' => Auth::id(),
                'buku_id' => $item->id,
            ]);

            return back()->with('success', 'QR Code berhasil dibuat untuk buku "' . $item->judul . '".');
        }

        return back()->with('error', 'Tipe QR tidak dikenal.');
    }

    /**
     * Hapus QR Code
     */
    public function destroy($id)
    {
        $qr = QRCode::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Hapus file QR dari storage
        if ($qr->gambar_qr && Storage::exists('public/' . $qr->gambar_qr)) {
            Storage::delete('public/' . $qr->gambar_qr);
        }

        $qr->delete();

        return back()->with('success', 'QR Code berhasil dihapus.');
    }
}
