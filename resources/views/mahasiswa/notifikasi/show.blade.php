@extends('layouts.pengguna-luar')

@section('page-title', 'Detail Notifikasi')

@push('styles')
<style>
    .detail-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #64748b;
        text-decoration: none;
        font-size: 14px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .back-button:hover {
        color: #3B82F6;
        transform: translateX(-4px);
    }

    .detail-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .detail-header {
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        color: white;
        padding: 40px 30px;
        position: relative;
        overflow: hidden;
    }

    .detail-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 15s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.1); opacity: 0.5; }
    }

    .detail-header-content {
        position: relative;
        z-index: 1;
    }

    .detail-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        margin: 0 auto 20px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }

    .detail-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 16px;
        text-align: center;
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
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
    }

    .detail-body {
        padding: 40px 30px;
    }

    .content-section {
        margin-bottom: 40px;
    }

    .content-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 14px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i {
        color: #3B82F6;
    }

    .content-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #e2e8f0;
        line-height: 1.8;
        color: #334155;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .info-item {
        padding: 16px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #64748b;
        margin-bottom: 8px;
    }

    .info-label i {
        color: #3B82F6;
        font-size: 16px;
    }

    .info-value {
        font-weight: 600;
        color: #1e293b;
        font-size: 15px;
    }

    .priority-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
    }

    .priority-mendesak {
        background: #fee2e2;
        color: #dc2626;
    }

    .priority-tinggi {
        background: #fef3c7;
        color: #f59e0b;
    }

    .priority-normal {
        background: #dbeafe;
        color: #3B82F6;
    }

    .priority-rendah {
        background: #f1f5f9;
        color: #64748b;
    }

    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
    }

    .type-terlambat {
        background: linear-gradient(135deg, #fee2e2, #fca5a5);
        color: #dc2626;
    }

    .type-reminder_deadline {
        background: linear-gradient(135deg, #fef3c7, #fcd34d);
        color: #f59e0b;
    }

    .type-denda_belum_dibayar {
        background: linear-gradient(135deg, #fce7f3, #f9a8d4);
        color: #ec4899;
    }

    .type-peminjaman_disetujui {
        background: linear-gradient(135deg, #dcfce7, #86efac);
        color: #16a34a;
    }

    .type-peminjaman_ditolak {
        background: linear-gradient(135deg, #fee2e2, #fca5a5);
        color: #dc2626;
    }

    .type-default {
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: #6366f1;
    }

    .additional-data {
        background: #eff6ff;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #bfdbfe;
    }

    .additional-data-item {
        padding: 12px 0;
        border-bottom: 1px solid #dbeafe;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .additional-data-item:last-child {
        border-bottom: none;
    }

    .additional-data-label {
        font-weight: 500;
        color: #475569;
    }

    .additional-data-value {
        font-weight: 600;
        color: #1e293b;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
        padding-top: 30px;
        border-top: 2px solid #f1f5f9;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .btn-action.btn-primary {
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        border: none;
        color: white;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }

    .btn-action.btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
    }

    .btn-action.btn-success {
        background: white;
        border: 2px solid #10b981;
        color: #10b981;
    }

    .btn-action.btn-success:hover {
        background: #10b981;
        color: white;
    }

    .btn-action.btn-danger {
        background: white;
        border: 2px solid #ef4444;
        color: #ef4444;
    }

    .btn-action.btn-danger:hover {
        background: #ef4444;
        color: white;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
    }

    .status-unread {
        background: linear-gradient(135deg, #f093fb, #f5576c);
        color: white;
        box-shadow: 0 2px 8px rgba(245, 87, 108, 0.3);
    }

    .status-read {
        background: #dcfce7;
        color: #16a34a;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .detail-header {
            padding: 30px 20px;
        }

        .detail-body {
            padding: 30px 20px;
        }

        .detail-title {
            font-size: 20px;
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
    }
</style>
@endpush

@section('content')
<div class="detail-container">
    {{-- Back Button --}}
    <a href="{{ route('pengguna-luar.notifikasi.index') }}" class="back-button">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali ke Daftar Notifikasi</span>
    </a>

    {{-- Detail Card --}}
    <div class="detail-card">
        {{-- Header --}}
        <div class="detail-header">
            <div class="detail-header-content">
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
                            <span class="type-badge type-{{ $notifikasi->tipe ?: 'default' }}">
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
                            <span class="priority-badge priority-{{ $notifikasi->prioritas }}">
                                <i class="bi bi-{{ $notifikasi->prioritas === 'mendesak' || $notifikasi->prioritas === 'tinggi' ? 'exclamation-triangle-fill' : 'flag-fill' }}"></i>
                                {{ ucfirst($notifikasi->prioritas) }}
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
                <a href="{{ route('pengguna-luar.notifikasi.index') }}" class="btn btn-action btn-primary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Daftar
                </a>

                @if(!$notifikasi->dibaca)
                <form action="{{ route('pengguna-luar.notifikasi.mark-as-read', $notifikasi->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-action btn-success">
                        <i class="bi bi-check-circle"></i>
                        Tandai Dibaca
                    </button>
                </form>
                @endif

                @if($notifikasi->url)
                <a href="{{ $notifikasi->url }}" class="btn btn-action btn-primary">
                    <i class="bi bi-box-arrow-up-right"></i>
                    Lihat Detail
                </a>
                @endif

                <form action="{{ route('pengguna-luar.notifikasi.destroy', $notifikasi->id) }}" method="POST" style="display: inline;" class="delete-form">
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