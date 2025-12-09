@extends('layouts.app')

@section('title', 'Detail Notifikasi')
@section('page-title', 'Detail Notifikasi')

@push('styles')
<style>
    .detail-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .detail-header {
        padding: 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .detail-icon-box {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
    }

    .detail-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .detail-meta {
        display: flex;
        gap: 25px;
        font-size: 0.95rem;
        opacity: 0.9;
    }

    .detail-meta i {
        margin-right: 8px;
    }

    .detail-body {
        padding: 35px;
    }

    .detail-section {
        margin-bottom: 30px;
    }

    .detail-section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 3px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .detail-section-title i {
        color: #667eea;
        font-size: 1.3rem;
    }

    .detail-content {
        font-size: 1.05rem;
        color: #4a5568;
        line-height: 1.8;
        text-align: justify;
    }

    .info-box {
        background: #f7fafc;
        border-left: 4px solid #667eea;
        padding: 20px;
        border-radius: 10px;
        margin: 20px 0;
    }

    .info-box-title {
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-box-content {
        color: #718096;
        font-size: 0.95rem;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(17, 153, 142, 0.3);
    }

    .badge-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);
    }

    .badge-warning {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(250, 112, 154, 0.3);
    }

    .badge-danger {
        background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(235, 51, 73, 0.3);
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 12px 25px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .related-info {
        background: linear-gradient(135deg, #f0f4ff 0%, #ffffff 100%);
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
    }

    .related-info-title {
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 15px;
        font-size: 1rem;
    }

    .related-info-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .related-info-item:last-child {
        border-bottom: none;
    }

    .related-info-label {
        color: #718096;
        font-weight: 600;
    }

    .related-info-value {
        color: #2d3748;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('admin.notifikasi.index') }}" class="btn btn-secondary btn-action me-2">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-action">
            <i class="bi bi-house"></i> Dashboard
        </a>
    </div>

    <!-- Detail Card -->
    <div class="detail-card">
        <div class="detail-header" style="background: {{ getNotificationColor($notifikasi->tipe) }}">
            <div class="detail-icon-box">
                <i class="bi bi-{{ getNotificationIcon($notifikasi->tipe) }}"></i>
            </div>
            <h1 class="detail-title">{{ $notifikasi->judul }}</h1>
            <div class="detail-meta">
                <span><i class="bi bi-clock"></i> {{ $notifikasi->created_at->format('d F Y, H:i') }}</span>
                <span><i class="bi bi-tag"></i> {{ ucwords(str_replace('_', ' ', $notifikasi->tipe)) }}</span>
                <span>
                    <i class="bi bi-{{ $notifikasi->dibaca ? 'check-circle' : 'circle' }}"></i> 
                    {{ $notifikasi->dibaca ? 'Sudah Dibaca' : 'Belum Dibaca' }}
                </span>
            </div>
        </div>

        <div class="detail-body">
            <!-- Status Badge -->
            <div class="mb-4">
                <span class="status-badge {{ getStatusBadgeClass($notifikasi->tipe) }}">
                    <i class="bi bi-{{ getNotificationIcon($notifikasi->tipe) }}"></i>
                    {{ ucwords(str_replace('_', ' ', $notifikasi->tipe)) }}
                </span>
            </div>

            <!-- Main Content -->
            <div class="detail-section">
                <h5 class="detail-section-title">
                    <i class="bi bi-file-text"></i>
                    Isi Notifikasi
                </h5>
                <div class="detail-content">
                    {{ $notifikasi->isi }}
                </div>
            </div>

            <!-- Additional Info -->
            @if($notifikasi->related_id)
            <div class="info-box">
                <div class="info-box-title">
                    <i class="bi bi-info-circle"></i>
                    Informasi Terkait
                </div>
                <div class="info-box-content">
                    <strong>ID Referensi:</strong> #{{ $notifikasi->related_id }}
                    <br>
                    <strong>Tipe:</strong> {{ ucwords(str_replace('_', ' ', $notifikasi->related_type ?? 'Tidak ada')) }}
                </div>
            </div>
            @endif

            <!-- Related Information (if available) -->
            @if($notifikasi->data)
            <div class="related-info">
                <div class="related-info-title">
                    <i class="bi bi-list-ul"></i> Detail Tambahan
                </div>
                @php
                    $data = is_string($notifikasi->data) ? json_decode($notifikasi->data, true) : $notifikasi->data;
                @endphp
                @if(is_array($data))
                    @foreach($data as $key => $value)
                    <div class="related-info-item">
                        <span class="related-info-label">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                        <span class="related-info-value">{{ is_array($value) ? json_encode($value) : $value }}</span>
                    </div>
                    @endforeach
                @endif
            </div>
            @endif

            <!-- Metadata -->
            <div class="detail-section mt-4">
                <h5 class="detail-section-title">
                    <i class="bi bi-info-square"></i>
                    Metadata
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="related-info-item">
                            <span class="related-info-label">Dibuat Pada</span>
                            <span class="related-info-value">{{ $notifikasi->created_at->format('d F Y, H:i:s') }}</span>
                        </div>
                        <div class="related-info-item">
                            <span class="related-info-label">Diperbarui Pada</span>
                            <span class="related-info-value">{{ $notifikasi->updated_at->format('d F Y, H:i:s') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="related-info-item">
                            <span class="related-info-label">Status Baca</span>
                            <span class="related-info-value">
                                @if($notifikasi->dibaca)
                                    <span class="badge bg-success">Sudah Dibaca</span>
                                @else
                                    <span class="badge bg-warning">Belum Dibaca</span>
                                @endif
                            </span>
                        </div>
                        <div class="related-info-item">
                            <span class="related-info-label">ID Notifikasi</span>
                            <span class="related-info-value">#{{ $notifikasi->id }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="detail-section mt-4">
                <h5 class="detail-section-title">
                    <i class="bi bi-gear"></i>
                    Aksi
                </h5>
                <div class="action-buttons">
                    @if(!$notifikasi->dibaca)
                    <button type="button" class="btn btn-success btn-action" onclick="markAsRead({{ $notifikasi->id }})">
                        <i class="bi bi-check2"></i> Tandai Dibaca
                    </button>
                    @endif
                    
                    <a href="{{ route('admin.notifikasi.index') }}" class="btn btn-primary btn-action">
                        <i class="bi bi-list"></i> Lihat Semua Notifikasi
                    </a>

                    <form action="{{ route('admin.notifikasi.destroy', $notifikasi->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus notifikasi ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-action">
                            <i class="bi bi-trash"></i> Hapus Notifikasi
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
function markAsRead(id) {
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
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endpush