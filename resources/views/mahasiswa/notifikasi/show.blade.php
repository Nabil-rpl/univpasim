@extends('layouts.mahasiswa')

@section('title', 'Detail Notifikasi')
@section('page-title', 'Detail Notifikasi')

@push('styles')
<style>
    :root {
        --primary: #0d6efd;
        --primary-dark: #0a58ca;
        --secondary: #6c757d;
        --success: #28a745;
        --danger: #dc3545;
        --warning: #ffc107;
        --info: #17a2b8;
    }

    body {
        background-color: #f5f6fa;
        min-height: 100vh;
    }

    .detail-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #6c757d;
        text-decoration: none;
        font-size: 14px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        padding: 10px 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .back-button:hover {
        color: #0d6efd;
        transform: translateX(-4px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
    }

    .detail-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .detail-header {
        background: linear-gradient(135deg, #0d6efd, #0a58ca);
        color: white;
        padding: 35px 30px;
    }

    .detail-icon {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 20px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }

    .detail-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 16px;
        text-align: center;
        line-height: 1.3;
    }

    .detail-badges {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .detail-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }

    .detail-body {
        padding: 35px 30px;
    }

    .content-section {
        margin-bottom: 30px;
    }

    .content-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i {
        color: #0d6efd;
        font-size: 1.1rem;
    }

    .content-box {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e9ecef;
        line-height: 1.7;
        color: #333;
        font-size: 0.95rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }

    .info-item {
        padding: 18px;
        background: #f8f9fa;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        transition: all 0.3s;
    }

    .info-item:hover {
        border-color: #0d6efd;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.1);
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .info-label i {
        color: #0d6efd;
        font-size: 0.95rem;
    }

    .info-value {
        font-weight: 600;
        color: #343a40;
        font-size: 0.95rem;
    }

    .priority-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .priority-mendesak {
        background: #fed7d7;
        color: #c53030;
    }

    .priority-tinggi {
        background: #feebc8;
        color: #c05621;
    }

    .priority-normal {
        background: #d1ecf1;
        color: #0c5460;
    }

    .priority-rendah {
        background: #e2e8f0;
        color: #718096;
    }

    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .type-badge i {
        font-size: 0.95rem;
    }

    .additional-data {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e9ecef;
    }

    .additional-data-item {
        padding: 14px 0;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .additional-data-item:last-child {
        border-bottom: none;
    }

    .additional-data-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.85rem;
    }

    .additional-data-value {
        font-weight: 700;
        color: #343a40;
        font-size: 0.9rem;
        text-align: right;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
        padding-top: 25px;
        border-top: 2px solid #f1f3f5;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-action.btn-primary {
        background: #0d6efd;
        color: white;
    }

    .btn-action.btn-primary:hover {
        background: #0a58ca;
        color: white;
    }

    .btn-action.btn-danger {
        background: #f5f5f5;
        border: 1px solid #dc3545;
        color: #dc3545;
    }

    .btn-action.btn-danger:hover {
        background: #dc3545;
        color: white;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-unread {
        background: #fff3cd;
        color: #856404;
    }

    .status-read {
        background: #d4edda;
        color: #155724;
    }

    /* Gradient backgrounds */
    .bg-gradient-primary { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
    .bg-gradient-danger { background: linear-gradient(135deg, #dc3545 0%, #e55353 100%); }
    .bg-gradient-info { background: linear-gradient(135deg, #17a2b8 0%, #3ab0c3 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #ffc107 0%, #ffcd39 100%); }
    .bg-gradient-secondary { background: linear-gradient(135deg, #6c757d 0%, #8a9199 100%); }

    /* Responsive */
    @media (max-width: 768px) {
        .detail-header {
            padding: 25px 20px;
        }

        .detail-body {
            padding: 25px 20px;
        }

        .detail-title {
            font-size: 1.4rem;
        }

        .detail-icon {
            width: 60px;
            height: 60px;
            font-size: 1.8rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }

        .additional-data-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }

        .additional-data-value {
            text-align: left;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="detail-container">
        {{-- Back Button --}}
        <a href="{{ route('mahasiswa.notifikasi.index') }}" class="back-button">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Daftar Notifikasi</span>
        </a>

        {{-- Alert Messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Detail Card --}}
        <div class="detail-card">
            {{-- Header --}}
            <div class="detail-header">
                <div class="detail-icon">
                    <i class="bi bi-{{ $notifikasi->getIcon() }}"></i>
                </div>
                <h2 class="detail-title">{{ $notifikasi->judul }}</h2>
                <div class="detail-badges">
                    <span class="detail-badge status-{{ $notifikasi->dibaca ? 'read' : 'unread' }}">
                        <i class="bi bi-{{ $notifikasi->dibaca ? 'check-circle-fill' : 'envelope-fill' }}"></i>
                        {{ $notifikasi->dibaca ? 'Sudah Dibaca' : 'Belum Dibaca' }}
                    </span>
                    <span class="detail-badge">
                        <i class="bi bi-clock"></i>
                        {{ $notifikasi->getWaktuRelatif() }}
                    </span>
                </div>
            </div>

            {{-- Body --}}
            <div class="detail-body">
                {{-- Content Section --}}
                <div class="content-section">
                    <div class="section-title">
                        <i class="bi bi-file-text"></i>
                        <span>Isi Notifikasi</span>
                    </div>
                    <div class="content-box">
                        {{ $notifikasi->isi }}
                    </div>
                </div>

                {{-- Info Section --}}
                <div class="content-section">
                    <div class="section-title">
                        <i class="bi bi-info-circle"></i>
                        <span>Informasi Detail</span>
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-tag"></i>
                                <span>Tipe Notifikasi</span>
                            </div>
                            <div class="info-value">
                                <span class="type-badge bg-gradient-{{ $notifikasi->getBadgeColor() }}" style="color: white;">
                                    <i class="bi bi-{{ $notifikasi->getIcon() }}"></i>
                                    {{ ucwords(str_replace('_', ' ', $notifikasi->tipe)) }}
                                </span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-flag"></i>
                                <span>Prioritas</span>
                            </div>
                            <div class="info-value">
                                <span class="priority-badge priority-{{ $notifikasi->prioritas ?? 'normal' }}">
                                    <i class="bi bi-{{ ($notifikasi->prioritas === 'mendesak' || $notifikasi->prioritas === 'tinggi') ? 'exclamation-triangle-fill' : 'flag-fill' }}"></i>
                                    {{ ucfirst($notifikasi->prioritas ?? 'Normal') }}
                                </span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-calendar-event"></i>
                                <span>Tanggal Diterima</span>
                            </div>
                            <div class="info-value">
                                {{ $notifikasi->created_at->format('d M Y, H:i') }}
                            </div>
                        </div>

                        @if($notifikasi->dibaca && $notifikasi->dibaca_pada)
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-check-circle"></i>
                                <span>Dibaca Pada</span>
                            </div>
                            <div class="info-value">
                                {{ $notifikasi->dibaca_pada->format('d M Y, H:i') }}
                            </div>
                        </div>
                        @endif

                        @if($notifikasi->pembuat)
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-person"></i>
                                <span>Dikirim Oleh</span>
                            </div>
                            <div class="info-value">
                                {{ $notifikasi->pembuat->name }}
                            </div>
                        </div>
                        @endif

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-hash"></i>
                                <span>ID Notifikasi</span>
                            </div>
                            <div class="info-value">
                                #{{ $notifikasi->id }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Additional Data Section --}}
                @if($notifikasi->data && is_array($notifikasi->data) && count($notifikasi->data) > 0)
                <div class="content-section">
                    <div class="section-title">
                        <i class="bi bi-info-square"></i>
                        <span>Informasi Tambahan</span>
                    </div>
                    <div class="additional-data">
                        @foreach($notifikasi->data as $key => $value)
                        <div class="additional-data-item">
                            <span class="additional-data-label">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                            <span class="additional-data-value">{{ $value }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="action-buttons">
                    <a href="{{ route('mahasiswa.notifikasi.index') }}" class="btn btn-action btn-primary">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar
                    </a>

                    @if($notifikasi->url)
                    <a href="{{ $notifikasi->url }}" class="btn btn-action btn-primary">
                        <i class="bi bi-box-arrow-up-right"></i>
                        Lihat Detail Terkait
                    </a>
                    @endif

                    <form action="{{ route('mahasiswa.notifikasi.destroy', $notifikasi->id) }}" method="POST" style="display: inline;" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-action btn-danger delete-btn">
                            <i class="bi bi-trash"></i>
                            Hapus Notifikasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Delete confirmation
    document.querySelector('.delete-btn')?.addEventListener('click', function(e) {
        e.preventDefault();
        
        if (confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
            document.querySelector('.delete-form').submit();
        }
    });

    // Auto dismiss alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endpush