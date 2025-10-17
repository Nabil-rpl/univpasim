@extends('layouts.petugas')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="row g-0">
            
            {{-- Gambar Buku --}}
            <div class="col-md-4 text-center p-3 bg-light">
                @if ($buku->foto && file_exists(public_path('storage/' . $buku->foto)))
                    <img src="{{ asset('storage/' . $buku->foto) }}" 
                         alt="{{ $buku->judul }}" 
                         class="img-fluid rounded shadow-sm"
                         style="max-height: 350px; object-fit: cover;">
                @else
                    <p class="text-muted fst-italic mt-5">Tidak ada foto buku</p>
                @endif
            </div>

            {{-- Detail Buku --}}
            <div class="col-md-8">
                <div class="card-body">
                    <h3 class="card-title fw-bold mb-3">📖 {{ $buku->judul }}</h3>

                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Penulis</th>
                            <td>: {{ $buku->penulis }}</td>
                        </tr>
                        <tr>
                            <th>Penerbit</th>
                            <td>: {{ $buku->penerbit }}</td>
                        </tr>
                        <tr>
                            <th>Tahun Terbit</th>
                            <td>: {{ $buku->tahun_terbit }}</td>
                        </tr>
                        <tr>
                            <th>Stok</th>
                            <td>: {{ $buku->stok }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat</th>
                            <td>: {{ $buku->created_at->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Terakhir Diperbarui</th>
                            <td>: {{ $buku->updated_at->diffForHumans() }}</td>
                        </tr>
                    </table>

                    {{-- 🔳 Bagian QR Code --}}
                    <hr>
                    <h5 class="fw-bold mb-3">🔳 QR Code Buku</h5>

                    @if ($buku->qrCode)
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $buku->qrCode->gambar_qr) }}" 
                                 alt="QR Code {{ $buku->judul }}" 
                                 class="img-fluid border rounded p-2 shadow-sm"
                                 style="max-width: 200px;">
                            <p class="mt-2 text-muted">Kode Unik: {{ $buku->qrCode->kode_unik }}</p>

                            <form action="{{ route('petugas.qrcode.destroy', $buku->qrCode->id) }}" method="POST" onsubmit="return confirm('Hapus QR Code ini?')" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">🗑 Hapus QR</button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-warning text-center">
                            Belum ada QR Code untuk buku ini.
                        </div>
                        <div class="text-center">
                            <a href="{{ route('petugas.qrcode.generate', ['type' => 'buku', 'id' => $buku->id]) }}" 
                               class="btn btn-success">
                                ⚙️ Buat QR Code
                            </a>
                        </div>
                    @endif

                    <hr>
                    <div class="mt-4">
                        <a href="{{ route('petugas.buku.index') }}" class="btn btn-secondary">
                            ← Kembali
                        </a>
                        <a href="{{ route('petugas.buku.edit', $buku->id) }}" class="btn btn-warning">
                            ✏ Edit Buku
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
