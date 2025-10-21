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
                    <div class="d-flex align-items-center justify-content-center" style="min-height: 300px;">
                        <div class="text-center">
                            <i class="bi bi-book-fill text-muted" style="font-size: 5rem;"></i>
                            <p class="text-muted fst-italic mt-3">Tidak ada foto buku</p>
                        </div>
                    </div>
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
                            <td>: <span class="badge {{ $buku->stok > 0 ? 'bg-success' : 'bg-danger' }}">{{ $buku->stok }}</span></td>
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
                        <div class="text-center bg-light p-4 rounded">
                            <img src="{{ asset('storage/' . $buku->qrCode->gambar_qr) }}" 
                                 alt="QR Code {{ $buku->judul }}" 
                                 class="img-fluid border rounded p-2 shadow-sm bg-white"
                                 style="max-width: 250px;">
                            
                            <div class="mt-3">
                                <p class="mb-1"><strong>Kode Unik:</strong></p>
                                <code class="bg-dark text-white px-3 py-2 rounded d-inline-block">
                                    {{ $buku->qrCode->kode_unik }}
                                </code>
                            </div>

                            <div class="mt-3">
                                <p class="text-muted mb-2">
                                    <small>
                                        <i class="bi bi-person-badge me-1"></i>
                                        Dibuat oleh: {{ $buku->qrCode->user->name ?? 'System' }}
                                    </small>
                                </p>
                                <p class="text-muted mb-0">
                                    <small>
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $buku->qrCode->created_at->diffForHumans() }}
                                    </small>
                                </p>
                            </div>

                            <div class="mt-3">
                                {{-- Tombol Download QR --}}
                                <a href="{{ asset('storage/' . $buku->qrCode->gambar_qr) }}" 
                                   download="QR-{{ $buku->judul }}.png"
                                   class="btn btn-primary btn-sm">
                                    <i class="bi bi-download me-1"></i> Download QR
                                </a>

                                {{-- Tombol Regenerate QR --}}
                                <form action="{{ route('petugas.buku.regenerateQR', $buku->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Regenerate QR Code? QR lama akan dihapus.')">
                                    @csrf
                                    <button class="btn btn-warning btn-sm">
                                        <i class="bi bi-arrow-repeat me-1"></i> Regenerate QR
                                    </button>
                                </form>

                                {{-- Tombol Hapus QR --}}
                                <form action="{{ route('petugas.qrcode.destroy', $buku->qrCode->id) }}" 
                                      method="POST" 
                                      class="d-inline" 
                                      onsubmit="return confirm('Hapus QR Code ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash me-1"></i> Hapus QR
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning text-center">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Belum ada QR Code untuk buku ini.
                        </div>
                        <div class="text-center">
                            <a href="{{ route('petugas.qrcode.generate', ['type' => 'buku', 'id' => $buku->id]) }}" 
                               class="btn btn-success">
                                <i class="bi bi-qr-code me-1"></i> Buat QR Code
                            </a>
                        </div>
                    @endif

                    <hr>
                    <div class="mt-4">
                        <a href="{{ route('petugas.buku.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <a href="{{ route('petugas.buku.edit', $buku->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i> Edit Buku
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Alert Messages --}}
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        alert('{{ session("success") }}');
    });
</script>
@endif
@endsection