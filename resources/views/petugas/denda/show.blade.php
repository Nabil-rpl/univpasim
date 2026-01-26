@extends('layouts.petugas')

@section('title', 'Detail Denda - ' . $user->name)

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-money-bill-wave text-danger me-2"></i>Detail Denda
            </h1>
            <p class="text-muted mb-0">Riwayat denda untuk {{ $user->name }}</p>
        </div>
        <a href="{{ route('petugas.denda.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Info User -->
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-user me-2"></i>Informasi User
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Nama Lengkap</label>
                        <p class="mb-0 fw-bold">{{ $user->name }}</p>
                    </div>

                    @if($user->role == 'mahasiswa')
                        <div class="mb-3">
                            <label class="text-muted small">NIM</label>
                            <p class="mb-0">{{ $user->nim ?? '-' }}</p>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="text-muted small">Email</label>
                        <p class="mb-0">{{ $user->email }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">No. HP</label>
                        <p class="mb-0">{{ $user->no_hp ?? '-' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Role</label>
                        <p class="mb-0">
                            @if($user->role == 'mahasiswa')
                                <span class="badge bg-primary">Mahasiswa</span>
                            @else
                                <span class="badge bg-info">Pengguna Luar</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Denda -->
            <div class="card shadow mt-4">
                <div class="card-header bg-danger text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-calculator me-2"></i>Ringkasan Denda
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Total Denda Keseluruhan</label>
                        <h4 class="mb-0 text-danger fw-bold">
                            Rp {{ number_format($summary['total_denda'], 0, ',', '.') }}
                        </h4>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="text-muted small">Belum Dibayar</label>
                        <h5 class="mb-0 text-warning">
                            Rp {{ number_format($summary['belum_dibayar'], 0, ',', '.') }}
                        </h5>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Sudah Lunas</label>
                        <h5 class="mb-0 text-success">
                            Rp {{ number_format($summary['lunas'], 0, ',', '.') }}
                        </h5>
                    </div>

                    <hr>

                    <div>
                        <label class="text-muted small">Total Peminjaman dengan Denda</label>
                        <p class="mb-0 fw-bold">{{ $summary['jumlah_peminjaman'] }} Peminjaman</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Peminjaman dengan Denda -->
        <div class="col-md-8 mb-4">
            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-list me-2"></i>Riwayat Peminjaman dengan Denda
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($peminjamans as $peminjaman)
                        <div class="card mb-3 border-start border-4 {{ $peminjaman->denda_lunas ? 'border-success' : 'border-danger' }}">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="card-title mb-2">
                                            {{ $peminjaman->buku->judul }}
                                        </h5>
                                        <p class="text-muted small mb-2">
                                            <i class="fas fa-user-edit me-1"></i>
                                            {{ $peminjaman->buku->penulis }}
                                        </p>

                                        <div class="row g-2 small">
                                            <div class="col-md-6">
                                                <i class="fas fa-calendar-alt text-primary me-1"></i>
                                                <strong>Pinjam:</strong> 
                                                {{ $peminjaman->tanggal_pinjam->format('d M Y') }}
                                            </div>
                                            <div class="col-md-6">
                                                <i class="fas fa-calendar-check text-success me-1"></i>
                                                <strong>Kembali:</strong> 
                                                {{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d M Y') : '-' }}
                                            </div>
                                            <div class="col-md-6">
                                                <i class="fas fa-clock text-warning me-1"></i>
                                                <strong>Deadline:</strong> 
                                                {{ $peminjaman->tanggal_deadline->format('d M Y') }}
                                            </div>
                                            <div class="col-md-6">
                                                <i class="fas fa-hourglass-half text-danger me-1"></i>
                                                <strong>Terlambat:</strong> 
                                                {{ $peminjaman->hari_terlambat }} hari
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 text-end">
                                        <div class="mb-2">
                                            <label class="text-muted small d-block">Denda</label>
                                            <h4 class="text-danger mb-0">
                                                Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}
                                            </h4>
                                        </div>

                                        @if($peminjaman->denda_lunas)
                                            <span class="badge bg-success mb-2">
                                                <i class="fas fa-check-circle me-1"></i>Lunas
                                            </span>
                                            @if($peminjaman->tanggal_bayar_denda)
                                                <p class="text-muted small mb-2">
                                                    Dibayar: {{ $peminjaman->tanggal_bayar_denda->format('d M Y') }}
                                                </p>
                                            @endif
                                        @else
                                            <span class="badge bg-danger mb-2">
                                                <i class="fas fa-exclamation-circle me-1"></i>Belum Dibayar
                                            </span>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-success w-100" 
                                                        onclick="bayarDenda({{ $peminjaman->id }}, {{ $peminjaman->denda }})">
                                                    <i class="fas fa-money-bill me-1"></i>Bayar Denda
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada riwayat denda</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Bayar Denda -->
<div class="modal fade" id="modalBayarDenda" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-money-bill-wave me-2"></i>Konfirmasi Pembayaran Denda
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formBayarDenda" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Pastikan user telah membayar denda sebelum konfirmasi
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Denda</label>
                        <input type="text" id="jumlahDenda" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Pembayaran</label>
                        <input type="date" name="tanggal_bayar" class="form-control" 
                               value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea name="catatan" class="form-control" rows="2" 
                                  placeholder="Catatan pembayaran..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>Konfirmasi Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function bayarDenda(peminjamanId, jumlah) {
    // Set action form
    document.getElementById('formBayarDenda').action = `/petugas/denda/${peminjamanId}/bayar`;
    
    // Set jumlah denda
    document.getElementById('jumlahDenda').value = 'Rp ' + jumlah.toLocaleString('id-ID');
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('modalBayarDenda'));
    modal.show();
}
</script>
@endpush
@endsection