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
        $qrcodes = QRCode::where('dibuat_oleh', Auth::id())->get();
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
            
            // Cek apakah sudah ada QR code untuk buku ini
            $existingQR = QRCode::where('buku_id', $item->id)->first();
            if ($existingQR) {
                return back()->with('info', 'QR Code untuk buku ini sudah ada.');
            }

            $kode_unik = 'BOOK-' . $item->id . '-' . strtoupper(uniqid());

            // ✅ SOLUSI 1: Gunakan SVG format (tidak perlu imagick)
            $qrSvg = QrCodeGenerator::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate($kode_unik);

            // Simpan sebagai SVG
            $filename = 'qr_codes/qr-' . $item->id . '-' . time() . '.svg';
            Storage::disk('public')->put($filename, $qrSvg);

            // Simpan ke database
            QRCode::create([
                'kode_unik' => $kode_unik,
                'gambar_qr' => $filename,
                'dibuat_oleh' => Auth::id(),
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
            ->where('dibuat_oleh', Auth::id())
            ->firstOrFail();

        // Hapus file QR dari storage
        if ($qr->gambar_qr && Storage::disk('public')->exists($qr->gambar_qr)) {
            Storage::disk('public')->delete($qr->gambar_qr);
        }

        $qr->delete();

        return back()->with('success', 'QR Code berhasil dihapus.');
    }
}