@extends('layouts.app')

@section('content')
<div class="container mt-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-book-fill text-primary"></i> Daftar Buku
        </h2>
        <span class="badge bg-primary rounded-pill px-3 py-2">
            Total: {{ $buku->total() }} Buku
        </span>
    </div>

    {{-- Alert Success --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card Table --}}
    <div class="card shadow-lg border-0">
        <div class="card-header bg-gradient text-white py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h5 class="mb-0">
                <i class="bi bi-table"></i> Data Buku Perpustakaan
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th width="50">No</th>
                            <th width="100">Foto</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th width="100">Tahun</th>
                            <th width="100">Kategori</th>
                            <th width="80">Stok</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($buku as $item)
                            <tr>
                                <td class="text-center fw-bold">
                                    {{ $loop->iteration + ($buku->currentPage() - 1) * $buku->perPage() }}
                                </td>

                                {{-- Foto Buku --}}
                                <td class="text-center">
                                    @if ($item->foto && file_exists(public_path('storage/' . $item->foto)))
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#fotoModal{{ $item->id }}">
                                            <img src="{{ asset('storage/' . $item->foto) }}" 
                                                 alt="Foto Buku"
                                                 width="70" 
                                                 height="70" 
                                                 class="rounded shadow-sm object-fit-cover hover-zoom"
                                                 style="cursor: pointer; transition: transform 0.3s;">
                                        </a>

                                        {{-- Modal Preview Foto --}}
                                        <div class="modal fade" id="fotoModal{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="fotoModalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title" id="fotoModalLabel{{ $item->id }}">
                                                            <i class="bi bi-book"></i> {{ $item->judul }}
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-center p-4 bg-light">
                                                        <img src="{{ asset('storage/' . $item->foto) }}" 
                                                             alt="Foto Buku"
                                                             class="img-fluid rounded shadow-lg"
                                                             style="max-height: 500px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                            <br>
                                            <small class="text-muted fst-italic">No Image</small>
                                        </div>
                                    @endif
                                </td>

                                {{-- Data Buku --}}
                                <td class="text-start">
                                    <strong>{{ $item->judul }}</strong>
                                </td>
                                <td>{{ $item->penulis }}</td>
                                <td>{{ $item->penerbit }}</td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $item->tahun_terbit }}</span>
                                </td>
                                <td class="text-center">
                                    @if($item->kategori)
                                        <span class="badge bg-primary" style="font-size: 0.75rem;">
                                            {{ Str::limit($item->kategori, 15) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $item->stok > 0 ? 'bg-success' : 'bg-danger' }} px-3">
                                        {{ $item->stok }}
                                    </span>
                                </td>

                                {{-- Tombol Aksi --}}
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.buku.show', $item->id) }}" 
                                           class="btn btn-info text-white"
                                           data-bs-toggle="tooltip"
                                           title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <button type="button" 
                                                class="btn btn-danger"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $item->id }}"
                                                title="Hapus Buku">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    {{-- Modal Konfirmasi Hapus --}}
                                    <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-exclamation-triangle"></i> Konfirmasi Hapus
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-1">Yakin ingin menghapus buku ini?</p>
                                                    <strong>"{{ $item->judul }}"</strong>
                                                    <p class="text-muted mt-2 mb-0">
                                                        <small>Data yang dihapus tidak dapat dikembalikan.</small>
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="bi bi-x"></i> Batal
                                                    </button>
                                                    <form action="{{ route('admin.buku.destroy', $item->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2 mb-0">Belum ada data buku tersedia.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Card Footer with Pagination --}}
        @if($buku->hasPages())
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Menampilkan {{ $buku->firstItem() }} - {{ $buku->lastItem() }} dari {{ $buku->total() }} data
                </div>
                <div>
                    {{ $buku->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.hover-zoom:hover {
    transform: scale(1.1);
}

.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.table-hover tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.05);
    transition: background-color 0.3s;
}

.btn-group .btn {
    transition: all 0.3s;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.badge {
    font-weight: 500;
    letter-spacing: 0.3px;
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

.alert {
    border-radius: 10px;
    border: none;
}
</style>

<script>
// Initialize Bootstrap tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection