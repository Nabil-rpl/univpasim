@extends('layouts.petugas')
@section('page-title', 'Detail Perpanjangan Peminjaman')
@section('content')
<style>
    /* Styling dari index.blade.php dipertahankan */
    .perpanjangan-container {
        background: linear-gradient(135deg, #EFF6FF 0%, #F8FAFC 100%);
        min-height: 100vh;
        padding: 3rem 0;
    }

    .breadcrumb-custom {
        background: transparent;
        padding: 0;
        margin-bottom: 2rem;
    }

    .breadcrumb-custom .breadcrumb-item {
        color: #64748B;
        font-weight: 600;
    }

    .breadcrumb-custom .breadcrumb-item.active {
        color: #3B82F6;
    }

    .breadcrumb-custom a {
        color: #60A5FA;
        text-decoration: none;
    }

    .page-header-custom {
        background: #FFFFFF;
        border-radius: 20px;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(96, 165, 250, 0.12);
        border: 1px solid #E0E7FF;
    }

    .card-detail-custom {
        background: #FFFFFF;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(96, 165, 250, 0.12);
        border: 1px solid #E0E7FF;
        margin-bottom: 2rem;
    }

    .detail-item {
        margin-bottom: 1.5rem;
        border-bottom: 1px dashed #E2E8F0;
        padding-bottom: 1rem;
    }

    .detail-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .detail-label {
        font-weight: 700;
        color: #475569;
        font-size: 0.95rem;
        display: block;
        margin-bottom: 0.25rem;
    }

    .detail-value {
        font-size: 1.1rem;
        color: #1E293B;
        font-weight: 600;
    }

    .status-badge {
        font-size: 1rem;
        padding: 8px 16px;
        border-radius: 12px;
        font-weight: 700;
    }

    .btn-custom {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 700;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        border: none;
    }

    .btn-success-custom {
        background: linear-gradient(135deg, #34D399, #10B981);
        color: white;
    }

    .btn-danger-custom {
        background: linear-gradient(135deg, #F87171, #EF4444);
        color: white;
    }

    .alert-custom {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        border-radius: 16px;
        border: none;
        margin-bottom: 2rem;
    }

    .alert-info-custom {
        background: linear-gradient(135deg, #EEF2FF 0%, #DBEAFE 100%);
        color: #1E40AF;
        border-left: 4px solid #3B82F6;
    }
</style>
<div class="perpanjangan-container">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
                <li class="breadcrumb-item">
                    <a href="{{ route('petugas.dashboard') }}"><i class="bi bi-house-door-fill"></i> Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('petugas.perpanjangan.index') }}">Kelola Perpanjangan</a>
                </li>
                <li class="breadcrumb-item active">Detail Permintaan</li>
            </ol>
        </nav>

        <div class="page-header-custom">
            <h3 style="margin: 0 0 0.5rem 0; font-size: 1.75rem; font-weight: 800; color: #1E293B;">
                <i class="bi bi-file-text-fill"></i> Detail Perpanjangan #{{ $perpanjangan->id }}
            </h3>
            <p style="margin: 0; color: #64748B; font-weight: 500;">Informasi lengkap permintaan perpanjangan peminjaman.</p>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card-detail-custom mb-3">
                    <h5 class="fw-bold mb-3" style="color: #1E293B;">
                        <i class="bi bi-info-circle-fill me-2 text-primary"></i>Status dan Aksi
                    </h5>
                    <div class="d-flex align-items-center mb-3">
                        <span class="detail-label me-3 mb-0">Status Saat Ini:</span>
                        @if($perpanjangan->status == 'menunggu')
                            <span class="status-badge bg-warning text-dark">
                                <i class="bi bi-hourglass-split"></i> Menunggu Persetujuan
                            </span>
                        @elseif($perpanjangan->status == 'disetujui')
                            <span class="status-badge bg-success text-white">
                                <i class="bi bi-check-circle-fill"></i> Disetujui
                            </span>
                        @else
                            <span class="status-badge bg-danger text-white">
                                <i class="bi bi-x-circle-fill"></i> Ditolak
                            </span>
                        @endif
                    </div>

                    @if($perpanjangan->status == 'menunggu')
                        <hr>
                        <p class="fw-bold text-muted mb-3">Tindakan Petugas:</p>
                        <div class="d-flex gap-3">
                            {{-- Form Setujui --}}
                            <form action="{{ route('petugas.perpanjangan.approve', $perpanjangan->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn-custom btn-success-custom"
                                    onclick="return confirm('Yakin menyetujui perpanjangan ini? Tanggal deadline peminjaman akan diperbarui.')">
                                    <i class="bi bi-check-circle"></i> Setujui
                                </button>
                            </form>
                            {{-- Tombol Tolak (Membuka Modal) --}}
                            <button type="button" class="btn-custom btn-danger-custom"
                                data-bs-toggle="modal"
                                data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle"></i> Tolak
                            </button>
                        </div>
                    @else
                        <div class="alert-custom alert-info-custom mt-4">
                            <i class="bi bi-check-circle-fill" style="font-size: 1.5rem;"></i>
                            <div>Permintaan ini sudah selesai diproses.</div>
                        </div>
                    @endif
                </div>

                <div class="card-detail-custom">
                    <h5 class="fw-bold mb-4" style="color: #1E293B;">
                        <i class="bi bi-book-half me-2 text-info"></i>Detail Peminjaman
                    </h5>
                    <div class="detail-item">
                        <span class="detail-label">Judul Buku</span>
                        <div class="detail-value">{{ $perpanjangan->peminjaman->buku->judul }}</div>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">ID Peminjaman</span>
                        <div class="detail-value">{{ $perpanjangan->peminjaman->id }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <span class="detail-label">Tanggal Pinjam</span>
                                <div class="detail-value">{{ $perpanjangan->peminjaman->tanggal_pinjam->format('d M Y') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <span class="detail-label">Tanggal Kembali (Awal)</span>
                                <div class="detail-value">{{ $perpanjangan->tanggal_deadline_lama->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <span class="detail-label">Tanggal Perpanjangan Baru</span>
                                <div class="detail-value text-success">{{ $perpanjangan->tanggal_deadline_baru->format('d M Y') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <span class="detail-label">Durasi Tambahan</span>
                                <div class="detail-value text-info">{{ $perpanjangan->durasi_tambahan }} Hari</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card-detail-custom">
                    <h5 class="fw-bold mb-4" style="color: #1E293B;">
                        <i class="bi bi-person-fill me-2 text-warning"></i>Detail Peminjam
                    </h5>
                    <div class="detail-item">
                        <span class="detail-label">Nama Peminjam</span>
                        <div class="detail-value">{{ $perpanjangan->peminjaman->mahasiswa->name }}</div>
                    </div>
                    @if($perpanjangan->peminjaman->mahasiswa->role == 'mahasiswa')
                    <div class="detail-item">
                        <span class="detail-label">NIM</span>
                        <div class="detail-value">{{ $perpanjangan->peminjaman->mahasiswa->nim }}</div>
                    </div>
                    @else
                    <div class="detail-item">
                        <span class="detail-label">Nomor HP</span>
                        <div class="detail-value">{{ $perpanjangan->peminjaman->mahasiswa->no_hp }}</div>
                    </div>
                    @endif
                    <div class="detail-item">
                        <span class="detail-label">Role Pengguna</span>
                        <div class="detail-value">
                            <span class="badge {{ $perpanjangan->peminjaman->mahasiswa->role == 'mahasiswa' ? 'bg-primary' : 'bg-info' }}">
                                {{ ucfirst($perpanjangan->peminjaman->mahasiswa->role) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-detail-custom">
                    <h5 class="fw-bold mb-4" style="color: #1E293B;">
                        <i class="bi bi-chat-left-text-fill me-2 text-secondary"></i>Alasan & Catatan
                    </h5>
                    <div class="detail-item">
                        <span class="detail-label">Alasan Perpanjangan</span>
                        <p class="text-muted fst-italic mt-2">{{ $perpanjangan->alasan }}</p>
                    </div>

                    @if($perpanjangan->status != 'menunggu')
                    <div class="detail-item">
                        <span class="detail-label">Catatan Petugas</span>
                        <p class="text-muted fst-italic mt-2">
                            {{ $perpanjangan->catatan_petugas ?? 'Tidak ada catatan.' }}
                        </p>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Diproses Oleh</span>
                        <div class="detail-value">
                            {{ $perpanjangan->petugas->name ?? 'N/A' }}
                        </div>
                    </div>
                    @endif
                    <div class="detail-item">
                        <span class="detail-label">Diajukan Pada</span>
                        <div class="detail-value">
                            {{ $perpanjangan->created_at->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-x-circle me-2"></i>Tolak Perpanjangan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('petugas.perpanjangan.reject', $perpanjangan->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Perhatian!</strong> Anda akan menolak perpanjangan untuk buku **{{ $perpanjangan->peminjaman->buku->judul }}** oleh **{{ $perpanjangan->peminjaman->mahasiswa->name }}**.
                    </div>
                    <div class="mb-3">
                        <label for="catatan_petugas" class="form-label fw-bold">Catatan Penolakan (Wajib)</label>
                        <textarea name="catatan_petugas" id="catatan_petugas" rows="4" class="form-control"
                            placeholder="Sebutkan alasan penolakan secara jelas. Contoh: Buku tersebut sudah dipesan peminjam lain."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-custom btn-danger-custom">
                        <i class="bi bi-x-circle-fill"></i> Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection