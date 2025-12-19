@extends('layouts.app')

@section('title', 'Detail Perpanjangan')

@section('content')
<style>
    /* Page Header */
    .page-header-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 28px 32px;
        color: white;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.25);
        margin-bottom: 28px;
    }

    .btn-back {
        background: white;
        color: #667eea;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        color: #667eea;
    }

    /* Info Cards */
    .info-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.06);
        margin-bottom: 24px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .info-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .card-header-custom {
        padding: 20px 24px;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-header-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .card-header-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .card-body-custom {
        padding: 24px;
    }

    /* Info Table */
    .info-table {
        margin: 0;
    }

    .info-table tr {
        border-bottom: 1px solid #f1f5f9;
    }

    .info-table tr:last-child {
        border-bottom: none;
    }

    .info-table th {
        padding: 16px 0;
        font-weight: 600;
        color: #64748b;
        font-size: 0.875rem;
        width: 40%;
        vertical-align: top;
    }

    .info-table td {
        padding: 16px 0;
        color: #1e293b;
        font-weight: 500;
        font-size: 0.9rem;
    }

    /* ID Badge */
    .id-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 20px;
        font-family: 'Courier New', monospace;
        font-weight: bold;
        font-size: 1rem;
        display: inline-block;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* Status Badge */
    .status-badge-custom {
        padding: 8px 18px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Date Badge */
    .date-badge {
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 700;
        display: inline-block;
    }

    /* Alert Box */
    .alert-box {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border-left: 4px solid #3b82f6;
        padding: 14px 18px;
        border-radius: 10px;
        color: #1e40af;
        font-size: 0.9rem;
        margin: 0;
    }

    /* Timeline */
    .timeline-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .timeline-container {
        padding: 32px;
    }

    .timeline-item {
        position: relative;
        padding-left: 60px;
        padding-bottom: 32px;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 50px;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, #667eea 0%, #e2e8f0 100%);
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-icon {
        position: absolute;
        left: 0;
        top: 0;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border: 3px solid white;
    }

    .timeline-content h6 {
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 6px;
        color: #1e293b;
    }

    .timeline-content p {
        font-size: 0.875rem;
        margin: 0;
        color: #64748b;
    }

    .timeline-content .timeline-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: #94a3b8;
        margin-top: 6px;
    }

    /* Link Styling */
    .link-primary {
        color: #667eea;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .link-primary:hover {
        color: #5568d3;
        text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .info-table th,
        .info-table td {
            display: block;
            width: 100%;
        }

        .info-table th {
            padding-bottom: 4px;
        }

        .info-table td {
            padding-top: 4px;
            padding-bottom: 16px;
        }
    }
</style>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-2 fw-bold">
                    <i class="bi bi-file-text-fill me-2"></i>Detail Perpanjangan
                </h3>
                <p class="mb-0 opacity-90" style="font-size: 0.95rem;">
                    Informasi lengkap pengajuan perpanjangan peminjaman buku
                </p>
            </div>
            <a href="{{ route('admin.perpanjangan.index') }}" class="btn btn-back">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-6">
            <!-- Informasi Perpanjangan -->
            <div class="info-card">
                <div class="card-header-custom">
                    <div class="card-header-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                    <h6 class="card-header-title">Informasi Perpanjangan</h6>
                </div>
                <div class="card-body-custom">
                    <table class="info-table table table-borderless">
                        <tbody>
                            <tr>
                                <th>
                                    <i class="bi bi-hash me-2 text-primary"></i>
                                    ID Perpanjangan
                                </th>
                                <td>
                                    <span class="id-badge">#{{ str_pad($perpanjangan->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <i class="bi bi-calendar-plus me-2 text-primary"></i>
                                    Tanggal Pengajuan
                                </th>
                                <td>
                                    <strong>{{ $perpanjangan->tanggal_perpanjangan->format('d M Y') }}</strong>
                                    <span class="text-muted ms-2">{{ $perpanjangan->tanggal_perpanjangan->format('H:i') }} WIB</span>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <i class="bi bi-flag-fill me-2 text-primary"></i>
                                    Status
                                </th>
                                <td>
                                    <span class="status-badge-custom bg-{{ $perpanjangan->getStatusBadgeClass() }}">
                                        <i class="bi bi-{{ $perpanjangan->getStatusIcon() }}"></i>
                                        {{ $perpanjangan->getStatusLabel() }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <i class="bi bi-calendar-x me-2 text-primary"></i>
                                    Deadline Lama
                                </th>
                                <td>
                                    <span class="date-badge bg-danger bg-opacity-10 text-danger">
                                        <i class="bi bi-calendar-x me-1"></i>
                                        {{ $perpanjangan->tanggal_deadline_lama->format('d M Y') }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <i class="bi bi-calendar-check me-2 text-primary"></i>
                                    Deadline Baru
                                </th>
                                <td>
                                    <span class="date-badge bg-success bg-opacity-10 text-success">
                                        <i class="bi bi-calendar-check me-1"></i>
                                        {{ $perpanjangan->tanggal_deadline_baru->format('d M Y') }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <i class="bi bi-clock-history me-2 text-primary"></i>
                                    Durasi Tambahan
                                </th>
                                <td>
                                    <span class="date-badge bg-info text-white">
                                        <i class="bi bi-plus-lg me-1"></i>
                                        {{ $perpanjangan->durasi_tambahan }} Hari
                                    </span>
                                </td>
                            </tr>
                            @if($perpanjangan->alasan)
                            <tr>
                                <th>
                                    <i class="bi bi-chat-left-text me-2 text-primary"></i>
                                    Alasan Perpanjangan
                                </th>
                                <td>{{ $perpanjangan->alasan }}</td>
                            </tr>
                            @endif
                            @if($perpanjangan->catatan_petugas)
                            <tr>
                                <th>
                                    <i class="bi bi-sticky me-2 text-primary"></i>
                                    Catatan Petugas
                                </th>
                                <td>
                                    <div class="alert-box">
                                        <i class="bi bi-info-circle me-2"></i>
                                        {{ $perpanjangan->catatan_petugas }}
                                    </div>
                                </td>
                            </tr>
                            @endif
                            @if($perpanjangan->petugas)
                            <tr>
                                <th>
                                    <i class="bi bi-person-badge me-2 text-primary"></i>
                                    Diproses Oleh
                                </th>
                                <td>
                                    <strong>{{ $perpanjangan->petugas->name }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $perpanjangan->updated_at->format('d M Y H:i') }} WIB
                                    </small>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Informasi Peminjaman -->
            <div class="info-card">
                <div class="card-header-custom">
                    <div class="card-header-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-book"></i>
                    </div>
                    <h6 class="card-header-title">Informasi Peminjaman</h6>
                </div>
                <div class="card-body-custom">
                    <table class="info-table table table-borderless">
                        <tbody>
                            <tr>
                                <th>
                                    <i class="bi bi-hash me-2 text-info"></i>
                                    ID Peminjaman
                                </th>
                                <td>
                                    <a href="{{ route('admin.peminjaman.show', $perpanjangan->peminjaman->id) }}" 
                                       class="link-primary">
                                        <i class="bi bi-box-arrow-up-right me-1"></i>
                                        #{{ str_pad($perpanjangan->peminjaman->id, 5, '0', STR_PAD_LEFT) }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <i class="bi bi-calendar-event me-2 text-info"></i>
                                    Tanggal Pinjam
                                </th>
                                <td>
                                    <strong>{{ $perpanjangan->peminjaman->tanggal_pinjam->format('d M Y') }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <i class="bi bi-flag me-2 text-info"></i>
                                    Status Peminjaman
                                </th>
                                <td>
                                    @php
                                        $statusClass = match($perpanjangan->peminjaman->status) {
                                            'dipinjam' => 'warning',
                                            'dikembalikan' => 'success',
                                            'terlambat' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="status-badge-custom bg-{{ $statusClass }}">
                                        {{ ucfirst($perpanjangan->peminjaman->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <i class="bi bi-hourglass-split me-2 text-info"></i>
                                    Durasi Total
                                </th>
                                <td>
                                    <span class="badge bg-secondary px-3 py-2">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $perpanjangan->peminjaman->durasi_hari }} Hari
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-6">
            <!-- Informasi Peminjam -->
            <div class="info-card">
                <div class="card-header-custom">
                    <div class="card-header-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <h6 class="card-header-title">Informasi Peminjam</h6>
                </div>
                <div class="card-body-custom">
                    <table class="info-table table table-borderless">
                        <tbody>
                            <tr>
                                <th>
                                    <i class="bi bi-person-circle me-2 text-success"></i>
                                    Nama Lengkap
                                </th>
                                <td>
                                    <strong>{{ $perpanjangan->peminjaman->mahasiswa->name ?? '-' }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <i class="bi bi-card-text me-2 text-success"></i>
                                    NIM/NIK
                                </th>
                                <td>
                                    {{ $perpanjangan->peminjaman->mahasiswa->nim ?? $perpanjangan->peminjaman->mahasiswa->nik ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <i class="bi bi-envelope me-2 text-success"></i>
                                    Email
                                </th>
                                <td>
                                    {{ $perpanjangan->peminjaman->mahasiswa->email ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <i class="bi bi-telephone me-2 text-success"></i>
                                    No. HP
                                </th>
                                <td>
                                    <strong>{{ $perpanjangan->peminjaman->mahasiswa->no_hp ?? '-' }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <i class="bi bi-tag me-2 text-success"></i>
                                    Role
                                </th>
                                <td>
                                    <span class="badge {{ $perpanjangan->peminjaman->mahasiswa->role == 'mahasiswa' ? 'bg-info' : 'bg-secondary' }} px-3 py-2">
                                        {{ ucfirst(str_replace('_', ' ', $perpanjangan->peminjaman->mahasiswa->role ?? 'N/A')) }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Informasi Buku -->
            <div class="info-card">
                <div class="card-header-custom">
                    <div class="card-header-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-book-fill"></i>
                    </div>
                    <h6 class="card-header-title">Informasi Buku</h6>
                </div>
                <div class="card-body-custom">
                    <table class="info-table table table-borderless">
                        <tbody>
                            <tr>
                                <th>
                                    <i class="bi bi-journal-text me-2 text-warning"></i>
                                    Judul Buku
                                </th>
                                <td>
                                    <strong>{{ $perpanjangan->peminjaman->buku->judul ?? '-' }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <i class="bi bi-upc-scan me-2 text-warning"></i>
                                    Kode Buku
                                </th>
                                <td>
                                    <span class="badge bg-dark px-3 py-2">
                                        {{ $perpanjangan->peminjaman->buku->kode_buku ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <i class="bi bi-pen me-2 text-warning"></i>
                                    Pengarang
                                </th>
                                <td>
                                    {{ $perpanjangan->peminjaman->buku->penulis ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <i class="bi bi-building me-2 text-warning"></i>
                                    Penerbit
                                </th>
                                <td>
                                    {{ $perpanjangan->peminjaman->buku->penerbit ?? '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="timeline-card">
                <div class="card-header-custom">
                    <div class="card-header-icon bg-purple bg-opacity-10" style="color: #a855f7;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h6 class="card-header-title">Riwayat Timeline</h6>
                </div>
                <div class="timeline-container">
                    <!-- Pengajuan -->
                    <div class="timeline-item">
                        <div class="timeline-icon bg-info">
                            <i class="bi bi-calendar-plus"></i>
                        </div>
                        <div class="timeline-content">
                            <h6>📝 Pengajuan Perpanjangan</h6>
                            <p>{{ $perpanjangan->tanggal_perpanjangan->format('d M Y, H:i') }} WIB</p>
                            <div class="timeline-meta">
                                <i class="bi bi-person-circle"></i>
                                <span>Diajukan oleh {{ $perpanjangan->peminjaman->mahasiswa->name ?? 'User' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Update -->
                    @if($perpanjangan->status !== 'menunggu')
                    <div class="timeline-item">
                        <div class="timeline-icon bg-{{ $perpanjangan->getStatusBadgeClass() }}">
                            <i class="bi bi-{{ $perpanjangan->getStatusIcon() }}"></i>
                        </div>
                        <div class="timeline-content">
                            <h6>
                                @if($perpanjangan->status == 'disetujui')
                                    ✅ Perpanjangan Disetujui
                                @elseif($perpanjangan->status == 'ditolak')
                                    ❌ Perpanjangan Ditolak
                                @else
                                    🚫 Perpanjangan Dibatalkan
                                @endif
                            </h6>
                            <p>{{ $perpanjangan->updated_at->format('d M Y, H:i') }} WIB</p>
                            @if($perpanjangan->petugas)
                            <div class="timeline-meta">
                                <i class="bi bi-person-badge"></i>
                                <span>Diproses oleh {{ $perpanjangan->petugas->name }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection