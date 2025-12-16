@extends('layouts.app')

@section('title', 'Detail Notifikasi')
@section('page-title', 'Detail Notifikasi')

@push('styles')
<style>
    .detail-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .detail-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px 35px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .detail-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .detail-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
    }

    .detail-header .content {
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
        font-size: 2.5rem;
        margin-bottom: 20px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .detail-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 15px;
        color: white;
        line-height: 1.3;
    }

    .detail-meta {
        display: flex;
        gap: 25px;
        flex-wrap: wrap;
        font-size: 0.95rem;
        opacity: 0.95;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.15);
        padding: 8px 16px;
        border-radius: 10px;
        backdrop-filter: blur(5px);
    }

    .meta-item i {
        font-size: 1.1rem;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 18px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 15px;
    }

    .badge-unread {
        background: linear-gradient(135deg, #f093fb, #f5576c);
        color: white;
        box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
        animation: pulse-badge 2s ease-in-out infinite;
    }

    @keyframes pulse-badge {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .badge-read {
        background: #10b981;
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .detail-body {
        padding: 40px 35px;
    }

    .content-section {
        margin-bottom: 35px;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-title i {
        color: #667eea;
        font-size: 1.5rem;
    }

    .content-text {
        color: #475569;
        line-height: 1.9;
        font-size: 1.05rem;
        background: #f8fafc;
        padding: 25px;
        border-radius: 16px;
        border-left: 4px solid #667eea;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 25px;
    }

    .info-item {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 20px;
        border-radius: 16px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s;
    }

    .info-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border-color: #667eea;
    }

    .info-label {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .info-value {
        font-size: 1.1rem;
        color: #1e293b;
        font-weight: 700;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-top: 30px;
        padding-top: 30px;
        border-top: 2px solid #f1f5f9;
    }

    .btn-action {
        padding: 14px 28px;
        border-radius: 14px;
        border: none;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
    }

    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
    }

    .btn-danger:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(239, 68, 68, 0.4);
        color: white;
    }

    .btn-secondary {
        background: white;
        color: #64748b;
        border: 2px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .btn-secondary:hover {
        border-color: #667eea;
        color: #667eea;
        background: #f8fafc;
        transform: translateY(-2px);
    }

    .related-section {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        padding: 30px;
        border-radius: 16px;
        margin-top: 30px;
        border: 2px solid #bfdbfe;
    }

    .related-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .related-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: white;
        color: #667eea;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.15);
    }

    .related-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
        color: #667eea;
    }

    /* Gradient backgrounds for icons */
    .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .bg-gradient-danger { background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); }
    .bg-gradient-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
    .bg-gradient-secondary { background: linear-gradient(135deg, #a8caba 0%, #5d4e6d 100%); }

    @media (max-width: 768px) {
        .detail-header {
            padding: 30px 20px;
        }

        .detail-title {
            font-size: 1.5rem;
        }

        .detail-meta {
            flex-direction: column;
            gap: 10px;
        }

        .detail-body {
            padding: 25px 20px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Detail Card -->
    <div class="detail-card">
        <!-- Header -->
        <div class="detail-header">
            <div class="content">
                <div class="detail-icon bg-gradient-{{ $notifikasi->getBadgeColor() }}">
                    <i class="bi bi-{{ $notifikasi->getIcon() }}"></i>
                </div>

                <h1 class="detail-title">{{ $notifikasi->judul }}</h1>

                <div class="detail-meta">
                    <span class="meta-item">
                        <i class="bi bi-clock-history"></i>
                        {{ $notifikasi->getWaktuRelatif() }}
                    </span>
                    <span class="meta-item">
                        <i class="bi bi-calendar3"></i>
                        {{ $notifikasi->created_at->format('d M Y, H:i') }} WIB
                    </span>
                    <span class="meta-item">
                        <i class="bi bi-tag-fill"></i>
                        {{ ucwords(str_replace('_', ' ', $notifikasi->tipe)) }}
                    </span>
                </div>

                <div>
                    <span class="status-badge {{ !$notifikasi->dibaca ? 'badge-unread' : 'badge-read' }}">
                        <i class="bi bi-{{ !$notifikasi->dibaca ? 'circle-fill' : 'check-circle-fill' }}"></i>
                        {{ !$notifikasi->dibaca ? 'Belum Dibaca' : 'Sudah Dibaca' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="detail-body">
            <!-- Content Section -->
            <div class="content-section">
                <h2 class="section-title">
                    <i class="bi bi-file-text-fill"></i>
                    Isi Notifikasi
                </h2>
                <div class="content-text">
                    {{ $notifikasi->isi }}
                </div>
            </div>

            <!-- Information Grid -->
            <div class="content-section">
                <h2 class="section-title">
                    <i class="bi bi-info-circle-fill"></i>
                    Informasi Detail
                </h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-hash me-1"></i> ID Notifikasi
                        </div>
                        <div class="info-value">#{{ $notifikasi->id }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-bookmark-fill me-1"></i> Kategori
                        </div>
                        <div class="info-value">{{ ucwords(str_replace('_', ' ', $notifikasi->tipe)) }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-clock-fill me-1"></i> Diterima
                        </div>
                        <div class="info-value">{{ $notifikasi->created_at->format('d M Y, H:i') }}</div>
                    </div>

                    @if($notifikasi->dibaca && $notifikasi->dibaca_pada)
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-check2-circle me-1"></i> Dibaca Pada
                        </div>
                        <div class="info-value">{{ $notifikasi->dibaca_pada->format('d M Y, H:i') }}</div>
                    </div>
                    @endif

                    @if($notifikasi->prioritas && $notifikasi->prioritas !== 'normal')
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Prioritas
                        </div>
                        <div class="info-value text-{{ $notifikasi->getPrioritasColor() }}">
                            {{ ucfirst($notifikasi->prioritas) }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Related Link Section -->
            @if($notifikasi->url)
            <div class="related-section">
                <h3 class="related-title">
                    <i class="bi bi-link-45deg"></i>
                    Tindakan Terkait
                </h3>
                <p class="mb-3" style="color: #1e40af;">
                    Klik tombol di bawah untuk melihat detail atau melakukan tindakan terkait notifikasi ini:
                </p>
                <a href="{{ $notifikasi->url }}" class="related-link">
                    <i class="bi bi-box-arrow-up-right"></i>
                    Lihat Detail Lengkap
                </a>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('admin.notifikasi.index') }}" class="btn-action btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Daftar
                </a>

                @if(!$notifikasi->dibaca)
                <button type="button" class="btn-action btn-success" onclick="markAsReadInline({{ $notifikasi->id }})">
                    <i class="bi bi-check-circle-fill"></i>
                    Tandai Sudah Dibaca
                </button>
                @endif

                @if($notifikasi->url)
                <a href="{{ $notifikasi->url }}" class="btn-action btn-primary">
                    <i class="bi bi-arrow-right-circle-fill"></i>
                    Buka Link Terkait
                </a>
                @endif

                <form action="{{ route('admin.notifikasi.destroy', $notifikasi->id) }}" 
                      method="POST" 
                      style="display: inline;" 
                      onsubmit="return confirm('Yakin ingin menghapus notifikasi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-danger">
                        <i class="bi bi-trash3-fill"></i>
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
// Mark notification as read WITHOUT reload
function markAsReadInline(id) {
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
    
    fetch(`/admin/notifikasi/${id}/baca`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update badge
            const statusBadge = document.querySelector('.status-badge');
            statusBadge.classList.remove('badge-unread');
            statusBadge.classList.add('badge-read');
            statusBadge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Sudah Dibaca';
            
            // Remove button
            btn.remove();
            
            // Show success message
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show';
            alert.innerHTML = `
                <i class="bi bi-check-circle-fill me-2"></i>Notifikasi berhasil ditandai sebagai dibaca
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector('.container-fluid').insertBefore(alert, document.querySelector('.detail-card'));
            
            // Auto hide alert
            setTimeout(() => {
                new bootstrap.Alert(alert).close();
            }, 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Tandai Sudah Dibaca';
        alert('Terjadi kesalahan. Silakan coba lagi.');
    });
}

// Auto dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endpush