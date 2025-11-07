<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Buku;
use App\Models\QRCode as QRCodeModel; // ✅ Ubah alias untuk Model
use SimpleSoftwareIO\QrCode\Facades\QrCode; // ✅ Import package QR Code

class BukuController extends Controller
{
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
        // Daftar semua kategori jurusan kuliah
        $categories = [
            'Aktuaria',
            'Astronomi',
            'Biologi',
            'Bioteknologi',
            'Fisika',
            'Geofisika',
            'Geologi',
            'Ilmu Lingkungan',
            'Kimia',
            'Matematika',
            'Matematika Bisnis',
            'Meteorologi',
            'Statistika',
            'Apoteker',
            'Farmasi',
            'Fisioterapi',
            'Ilmu Gizi',
            'Ilmu Kedokteran',
            'Ilmu Keperawatan',
            'Kebidanan',
            'Kedokteran Gigi',
            'Kedokteran Hewan',
            'Kesehatan Lingkungan',
            'Kesehatan Masyarakat',
            'Keselamatan dan Kesehatan Kerja',
            'Pendidikan Dokter',
            'Psikologi',
            'Rekam Medis',
            'Ilmu Teknik',
            'Instrumentasi',
            'Pendidikan Teknologi Agroindustri',
            'Rekayasa Keselamatan Kebakaran',
            'Teknik Biomedis',
            'Teknik Dirgantara/Penerbangan',
            'Teknik Elektro',
            'Teknik Geodesi',
            'Teknik Geomatika',
            'Teknik Geologi',
            'Teknik Industri',
            'Teknik Kimia',
            'Teknik Listrik',
            'Teknik Material',
            'Teknik Mesin',
            'Teknik Metalurgi',
            'Teknik Nuklir',
            'Teknik Otomotif',
            'Teknik Penerbangan',
            'Teknik Perkapalan',
            'Teknik Perminyakan',
            'Teknik Pertambangan',
            'Teknik Robotika',
            'Teknologi Bioproses',
            'Cyber Security',
            'Ilmu Komputer',
            'Manajemen Informatika',
            'Sains Data',
            'Sistem Informasi',
            'Sistem Komputer',
            'Teknik Komputer',
            'Teknik Telekomunikasi',
            'Teknologi Informasi',
            'Teknologi Rekayasa Multimedia',
            'Arsitektur Interior',
            'Arsitektur Lanskap',
            'Konstruksi',
            'Perencanaan Wilayah dan Kota',
            'Teknik Arsitektur',
            'Teknik Bangunan',
            'Teknik Lingkungan',
            'Teknik Pengairan',
            'Teknik Sipil',
            'Agribisnis',
            'Agriekoteknologi',
            'Agrobisnis Perikanan',
            'Agronomi dan Hortikultura',
            'Akuakultur',
            'Bioteknologi',
            'Ilmu Kelautan',
            'Ilmu Perikanan',
            'Kehutanan',
            'Kelautan',
            'Manajemen Sumber Daya Perairan',
            'Manajemen Sumberdaya Lahan',
            'Nautika',
            'Oseanografi',
            'Pertanian dan Agribisnis',
            'Peternakan',
            'Proteksi Tanaman',
            'Teknik Kelautan',
            'Teknik Pertanian',
            'Teknologi Industri Pertanian',
            'Teknologi Pangan',
            'Ilmu Olahraga',
            'Administrasi Bisnis/Tata Niaga',
            'Administrasi Pemerintahan',
            'Administrasi Perkantoran',
            'Administrasi Publik',
            'Antropologi Sosial/Budaya',
            'Arkeologi',
            'Hubungan Internasional',
            'Ilmu Administrasi Fiskal',
            'Ilmu Administrasi Negara',
            'Ilmu Administrasi Niaga',
            'Ilmu Pemerintahan',
            'Ilmu Politik',
            'Kesejahteraan Sosial',
            'Kriminologi',
            'Sejarah',
            'Sosiologi',
            'Hubungan Masyarakat',
            'Ilmu Komunikasi',
            'Ilmu Perpustakaan',
            'Jurnalistik',
            'Kearsipan Digital',
            'Komunikasi dan Pengembangan Masyarakat',
            'Manajemen Komunikasi',
            'Televisi dan Film',
            'Bahasa dan Budaya Tiongkok',
            'Bahasa dan Kebudayaan Korea',
            'Ilmu Filsafat',
            'Linguistik',
            'Sastra Arab',
            'Sastra Belanda',
            'Sastra Cina',
            'Sastra Indonesia',
            'Sastra Inggris',
            'Sastra Jawa',
            'Sastra Sunda',
            'Sastra Daerah',
            'Sastra Jepang',
            'Sastra Jerman',
            'Sastra Prancis',
            'Sastra Rusia/Slavia',
            'Sejarah dan Kebudayaan Islam',
            'Akuntansi',
            'Bisnis Digital',
            'Bisnis Internasional',
            'Bisnis Islam',
            'Ekonomi Pembangunan',
            'Ekonomi Syariah',
            'Hubungan Masyarakat',
            'Ilmu Ekonomi',
            'Ilmu Ekonomi Islam',
            'Kewirausahaan',
            'Manajemen',
            'Manajemen Bisnis',
            'Manajemen Keuangan',
            'Perbankan',
            'Geografi',
            'Ilmu Hukum',
            'Ilmu Keluarga dan Konsumen',
            'Ilmu Kesejahteraan Sosial',
            'Manajemen Perhotelan',
            'Pariwisata',
            'Peradilan Agama',
            'Perhotelan',
            'Politik Islam',
            'Psikologi',
            'Studi Agama',
            'Teologi',
            'Travel',
            'Administrasi Pendidikan',
            'Bimbingan dan Konseling',
            'Manajemen Pemasaran Pariwisata',
            'Manajemen Pendidikan',
            'Pendidikan Agama Islam',
            'Pendidikan Bahasa Arab',
            'Pendidikan Bahasa Daerah',
            'Pendidikan Bahasa Indonesia',
            'Pendidikan Bahasa Inggris',
            'Pendidikan Bahasa Jepang',
            'Pendidikan Bahasa Jerman',
            'Pendidikan Bahasa Korea',
            'Pendidikan Bahasa Prancis',
            'Pendidikan Biologi',
            'Pendidikan Fisika',
            'Pendidikan Geografi',
            'Pendidikan Guru Anak Usia Dini (PAUD)',
            'Pendidikan Guru Sekolah Dasar (PGSD)',
            'Pendidikan Ilmu Komputer',
            'Pendidikan IPA',
            'Pendidikan IPS',
            'Pendidikan Jasmani, Kesehatan, dan Rekreasi',
            'Pendidikan Kepelatihan Olahraga',
            'Pendidikan Khusus',
            'Pendidikan Kimia',
            'Pendidikan Luar Biasa',
            'Pendidikan Luar Sekolah (PLS)',
            'Pendidikan Masyarakat',
            'Pendidikan Matematika',
            'Pendidikan Pancasila dan Kewarganegaraan',
            'Pendidikan Sejarah',
            'Pendidikan Seni Musik',
            'Pendidikan Seni Rupa',
            'Pendidikan Seni Tari',
            'Pendidikan Sosiologi',
            'Pendidikan Teknik Otomotif',
            'Perpustakaan & Sains Informasi',
            'Psikologi Pendidikan dan Bimbingan',
            'Teknologi Pendidikan',
            'Desain dan Komunikasi Visual',
            'Desain Interior',
            'Desain Produk',
            'Film dan Animasi',
            'Film dan Televisi',
            'Musik',
            'Seni Kriya',
            'Seni Musik',
            'Seni Rupa Murni',
            'Seni Tari',
            'Tata Boga',
            'Tata Busana',
            'Tata Kelola Seni',
            'Tata Rias',
            'Administrasi Asuransi Dan Aktuaria',
            'Administrasi Bisnis',
            'Administrasi Keuangan Dan Perbankan',
            'Administrasi Perkantoran Dan Sekretaris',
            'Administrasi Perpajakan',
            'Administrasi Perumahsakitan',
            'Akuntansi',
            'Akuntansi Perpajakan',
            'Akuntansi Sektor Publik',
            'Analisis Kimia',
            'Bisnis Internasional',
            'Desain Batik Dan Fashion',
            'Desain Grafis',
            'Ekowisata',
            'Fisioterapi',
            'Hubungan Masyarakat',
            'Informasi Dan Humas',
            'Instrumentasi Dan Elektronika',
            'Kearsipan',
            'Manajemen Informasi Dan Dokumentasi',
            'Manajemen Perhotelan',
            'Okupasi Terapi',
            'Paramedik Veteriner',
            'Pemasaran Digital',
            'Penyiaran Multimedia',
            'Periklanan Kreatif',
            'Sekretaris',
            'Teknologi Produksi',
            'Vokasional Pariwisata'
        ];

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

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto_buku', 'public');
            $validated['foto'] = $path;
        }

        // Simpan buku
        $buku = Buku::create($validated);

        // Generate QR Code otomatis
        $this->generateQRCode($buku);

        return redirect()->route('petugas.buku.index')->with('success', 'Buku dan QR Code berhasil ditambahkan!');
    }

    public function edit(Buku $buku)
    {
        // Ambil kategori yang sama dengan create
        $categories = [
            'Aktuaria', 'Astronomi', 'Biologi', 'Bioteknologi', 'Fisika',
            // ... (copy semua kategori dari method create)
        ];
        
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

        // ✅ Generate QR Code sebagai SVG menggunakan package QrCode
        $qrCodeImage = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($kodeUnik);

        // Simpan QR Code ke storage
        $fileName = 'qr_codes/qr-' . $buku->id . '-' . time() . '.svg';
        Storage::disk('public')->put($fileName, $qrCodeImage);

        // ✅ Simpan ke database menggunakan Model QRCodeModel
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
}