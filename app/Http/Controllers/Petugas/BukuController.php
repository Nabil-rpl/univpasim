<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // ✅ TAMBAHAN
use Illuminate\Support\Str;
use App\Models\Buku;
use App\Models\QRCode as QRCodeModel;
use App\Models\Notifikasi; // ✅ TAMBAHAN: Import Model Notifikasi
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BukuController extends Controller
{
    /**
     * Mendapatkan daftar semua kategori jurusan kuliah
     */
    private function getCategories()
    {
        return [
            'Teknik Informatika',
            'Sistem Informasi',
            'Manajemen',
            'Akuntansi',
            'Hukum',
            'Kedokteran',
            'Teknik Sipil',
            'Arsitektur',
            'Psikologi',
            'Sastra',
            'Matematika',
            'Fisika',
            'Kimia',
            'Biologi',
            'Statistika',
            'Teknik Elektro',
            'Teknik Mesin',
            'Teknik Industri',
            'Ekonomi Pembangunan',
            'Bisnis Digital',
            'Ilmu Komunikasi',
            'Sosiologi',
            'Pendidikan Matematika',
            'Pendidikan Bahasa Inggris',
            'PGSD',
            'Keperawatan',
            'Farmasi',
            'Kesehatan Masyarakat',
            'Desain Grafis',
            'Desain Interior',
            'Seni Rupa',
            'Film'
        ];
    }

    public function index(Request $request)
    {
        $query = Buku::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('judul', 'like', "%{$search}%")
                ->orWhere('penulis', 'like', "%{$search}%");
        }

        $buku = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('petugas.buku.index', compact('buku'));
    }

    public function create()
    {
        $categories = $this->getCategories();
        return view('petugas.buku.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
            'kategori' => 'required|string|max:255',
            'stok' => 'required|integer|min:1',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // ✅ GUNAKAN TRANSACTION untuk memastikan semua proses berhasil
        DB::beginTransaction();
        try {
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('foto_buku', 'public');
                $validated['foto'] = $path;
            }

            // Simpan buku
            $buku = Buku::create($validated);

            // Generate QR Code otomatis
            $this->generateQRCode($buku);

            // ✅ KIRIM NOTIFIKASI BUKU BARU
            $this->kirimNotifikasiBukuBaru($buku);

            DB::commit();

            return redirect()->route('petugas.buku.index')
                ->with('success', 'Buku dan QR Code berhasil ditambahkan! Notifikasi telah dikirim ke Admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error
            \Log::error('Error saat menambah buku: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan buku. Silakan coba lagi.');
        }
    }

    public function edit(Buku $buku)
    {
        $categories = $this->getCategories();
        return view('petugas.buku.edit', compact('buku', 'categories'));
    }

    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
            'kategori' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($buku->foto && Storage::exists('public/' . $buku->foto)) {
                Storage::delete('public/' . $buku->foto);
            }
            $path = $request->file('foto')->store('foto_buku', 'public');
            $validated['foto'] = $path;
        }

        $buku->update($validated);

        return redirect()->route('petugas.buku.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    public function show(Buku $buku)
    {
        // Load relasi qrCode dan petugas
        $buku->load(['qrCode.petugas']);

        return view('petugas.buku.show', compact('buku'));
    }

    public function destroy(Buku $buku)
    {
        // Hapus foto jika ada
        if ($buku->foto && Storage::exists('public/' . $buku->foto)) {
            Storage::delete('public/' . $buku->foto);
        }

        // Hapus QR Code jika ada
        if ($buku->qrCode && $buku->qrCode->gambar_qr) {
            Storage::delete('public/' . $buku->qrCode->gambar_qr);
        }

        $buku->delete();
        return redirect()->route('petugas.buku.index')->with('success', 'Buku berhasil dihapus!');
    }

    /**
     * Generate QR Code untuk buku (SVG Format)
     */
    private function generateQRCode($buku)
    {
        // Generate kode unik
        $kodeUnik = 'BOOK-' . $buku->id . '-' . Str::random(8);

        /**
         * @var \SimpleSoftwareIO\QrCode\Generator $qr
         */
        $qr = QrCode::format('svg');

        // Generate QR Code sebagai SVG menggunakan package QrCode
        $qrCodeImage = $qr
            ->size(300)
            ->errorCorrection('H')
            ->generate($kodeUnik);

        // Simpan QR Code ke storage
        $fileName = 'qr_codes/qr-' . $buku->id . '-' . time() . '.svg';
        Storage::disk('public')->put($fileName, $qrCodeImage);

        // Simpan ke database menggunakan Model QRCodeModel
        QRCodeModel::create([
            'kode_unik' => $kodeUnik,
            'gambar_qr' => $fileName,
            'dibuat_oleh' => Auth::id(),
            'buku_id' => $buku->id,
        ]);
    }

    /**
     * Regenerate QR Code untuk buku tertentu
     */
    public function regenerateQR(Buku $buku)
    {
        // Hapus QR lama jika ada
        if ($buku->qrCode) {
            if ($buku->qrCode->gambar_qr) {
                Storage::delete('public/' . $buku->qrCode->gambar_qr);
            }
            $buku->qrCode->delete();
        }

        // Generate QR baru
        $this->generateQRCode($buku);

        return redirect()->back()->with('success', 'QR Code berhasil di-generate ulang!');
    }

    /**
     * ✅ PRIVATE METHOD: Kirim notifikasi buku baru ke Admin & Petugas lain
     */
    private function kirimNotifikasiBukuBaru(Buku $buku)
    {
        $petugas = Auth::user();
        
        // Buat detail informasi
        $detailInfo = "Buku baru telah ditambahkan ke perpustakaan.\n\n";
        $detailInfo .= "Judul: {$buku->judul}\n";
        $detailInfo .= "Penulis: {$buku->penulis}\n";
        $detailInfo .= "Penerbit: {$buku->penerbit}\n";
        $detailInfo .= "Tahun Terbit: {$buku->tahun_terbit}\n";
        $detailInfo .= "Kategori: {$buku->kategori}\n";
        $detailInfo .= "Stok: {$buku->stok} eksemplar\n\n";
        $detailInfo .= "Ditambahkan oleh: {$petugas->name}";
        if ($petugas->role) {
            $detailInfo .= " (" . ucfirst($petugas->role) . ")";
        }
        $detailInfo .= "\n";
        $detailInfo .= "Waktu: " . now()->format('d F Y, H:i:s');

        // Kirim notifikasi ke semua admin dan petugas (kecuali yang menambahkan)
        $adminPetugasIds = \App\Models\User::whereIn('role', ['admin', 'petugas'])
            ->where('id', '!=', Auth::id()) // Kecuali petugas yang menambahkan
            ->pluck('id');

        if ($adminPetugasIds->isNotEmpty()) {
            Notifikasi::kirimKeMultipleUsers(
                $adminPetugasIds,
                'buku_baru',
                "Buku Baru: {$buku->judul}",
                $detailInfo,
                [
                    'buku_id' => $buku->id,
                    'judul' => $buku->judul,
                    'penulis' => $buku->penulis,
                    'penerbit' => $buku->penerbit,
                    'kategori' => $buku->kategori,
                    'stok' => $buku->stok,
                    'ditambahkan_oleh' => $petugas->name,
                    'role_penambah' => $petugas->role
                ],
                route('admin.buku.show', $buku->id), // URL ke detail buku (admin)
                'normal',
                Auth::id()
            );
        }

        // ✅ TAMBAHAN: Kirim notifikasi ke Mahasiswa yang sesuai dengan kategori buku
        $this->kirimNotifikasiKeMahasiswaTerkait($buku, $petugas);
    }

    /**
     * ✅ PRIVATE METHOD: Kirim notifikasi ke mahasiswa yang jurusannya sesuai dengan kategori buku
     */
    private function kirimNotifikasiKeMahasiswaTerkait(Buku $buku, $petugas)
    {
        // Ambil mahasiswa yang jurusannya sama dengan kategori buku
        $mahasiswaIds = \App\Models\Mahasiswa::where('jurusan', $buku->kategori)
            ->join('users', 'mahasiswa.email', '=', 'users.email')
            ->pluck('users.id');

        if ($mahasiswaIds->isEmpty()) {
            return; // Tidak ada mahasiswa yang sesuai
        }

        // Pesan untuk mahasiswa
        $pesanMahasiswa = "Buku baru tersedia untuk jurusan Anda!\n\n";
        $pesanMahasiswa .= "📚 Judul: {$buku->judul}\n";
        $pesanMahasiswa .= "✍️ Penulis: {$buku->penulis}\n";
        $pesanMahasiswa .= "🏢 Penerbit: {$buku->penerbit}\n";
        $pesanMahasiswa .= "📅 Tahun Terbit: {$buku->tahun_terbit}\n";
        $pesanMahasiswa .= "📦 Stok Tersedia: {$buku->stok} eksemplar\n\n";
        $pesanMahasiswa .= "Kategori buku ini sesuai dengan jurusan Anda ({$buku->kategori}). ";
        $pesanMahasiswa .= "Segera kunjungi perpustakaan untuk meminjam!\n\n";
        $pesanMahasiswa .= "Ditambahkan oleh: {$petugas->name}";

        Notifikasi::kirimKeMultipleUsers(
            $mahasiswaIds,
            'buku_tersedia',
            "Buku Baru Tersedia: {$buku->judul}",
            $pesanMahasiswa,
            [
                'buku_id' => $buku->id,
                'judul' => $buku->judul,
                'kategori' => $buku->kategori,
                'stok' => $buku->stok,
                'untuk_jurusan' => $buku->kategori
            ],
            null, // Mahasiswa mungkin tidak punya akses ke route admin.buku.show
            'normal',
            Auth::id()
        );
    }
}