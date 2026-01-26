@extends('layouts.pengguna-luar')

@section('title', 'Detail Notifikasi')

@push('styles')
<style>
    :root {
        --primary: #4A90E2;
        --primary-dark: #357ABD;
        --secondary: #5DADE2;
        --success: #52C41A;
        --danger: #FF4D4F;
        --warning: #FAAD14;
        --info: #1890FF;
    }

    body {
        background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
        min-height: 100vh;
    }

    .breadcrumb {
        background: white;
        padding: 12px 20px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .breadcrumb-item a {
        color: #4A90E2;
        text-decoration: none;
        transition: color 0.2s;
    }

    .breadcrumb-item a:hover {
        color: #357ABD;
    }

    .breadcrumb-item.active {
        color: #666;
    }

    .detail-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .detail-header {
        background: linear-gradient(135deg, #4A90E2 0%, #5DADE2 100%);
        padding: 25px;
        color: white;
    }

    .detail-icon-box {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .detail-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        color: white;
    }

    .detail-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        margin-bottom: 8px;
    }

    .detail-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 8px 16px;
        border-radius: 8px;
        border: none;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .btn-action:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
    }

    .detail-body {
        padding: 25px;
    }

    .meta-section {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .meta-item-detail {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f8f9fa;
        padding: 8px 15px;
        border-radius: 8px;
        font-size: 0.85rem;
        color: #333;
    }

    .meta-item-detail i {
        color: #4A90E2;
        font-size: 1rem;
    }

    .content-section {
        margin-bottom: 25px;
    }

    .section-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #666;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i {
        color: #4A90E2;
    }

    .content-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 20px;
        color: #333;
        line-height: 1.7;
    }

    .data-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }

    .data-item {
        background: white;
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .data-label {
        font-size: 0.8rem;
        color: #666;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .data-value {
        font-size: 0.95rem;
        color: #333;
        font-weight: 600;
    }

    .creator-info {
        background: #f8f9fa;
        padding: 12px 15px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.85rem;
        color: #666;
        margin-top: 20px;
    }

    .creator-info i {
        color: #4A90E2;
        font-size: 1.2rem;
    }

    .detail-footer {
        background: #f8f9fa;
        padding: 20px 25px;
        border-top: 1px solid #e9ecef;
    }

    .btn-back {
        padding: 10px 20px;
        border-radius: 8px;
        background: white;
        color: #666;
        border: 1px solid #e0e0e0;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-back:hover {
        background: #4A90E2;
        color: white;
        border-color: #4A90E2;
    }

    .related-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .related-header {
        background: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #e9ecef;
    }

    .related-header h6 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #333;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .related-header i {
        color: #4A90E2;
    }

    .related-item {
        display: flex;
        align-items: start;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s;
    }

    .related-item:last-child {
        border-bottom: none;
    }

    .related-item:hover {
        background: #f8f9fa;
        transform: translateX(5px);
    }

    .related-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: white;
        flex-shrink: 0;
        margin-right: 15px;
    }

    .related-content {
        flex: 1;
    }

    .related-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .related-text {
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 5px;
    }

    .related-time {
        font-size: 0.75rem;
        color: #999;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .badge-new {
        background: #4A90E2;
        color: white;
        padding: 3px 8px;
        border-radius: 5px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-left: 8px;
    }

    /* Priority Badges */
    .badge-priority {
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-mendesak {
        background: #fed7d7;
        color: #c53030;
    }

    .badge-tinggi {
        background: #feebc8;
        color: #c05621;
    }

    .badge-normal {
        background: #bee3f8;
        color: #2c5282;
    }

    .badge-rendah {
        background: #e2e8f0;
        color: #718096;
    }

    .badge-read {
        background: #d4edda;
        color: #155724;
    }

    .badge-unread {
        background: #fff3cd;
        color: #856404;
    }

    /* Gradient backgrounds for icons */
    .bg-gradient-primary { background: linear-gradient(135deg, #4A90E2 0%, #5DADE2 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #52C41A 0%, #73D13D 100%); }
    .bg-gradient-danger { background: linear-gradient(135deg, #FF4D4F 0%, #FF7875 100%); }
    .bg-gradient-info { background: linear-gradient(135deg, #1890FF 0%, #40A9FF 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #FAAD14 0%, #FFC53D 100%); }
    .bg-gradient-secondary { background: linear-gradient(135deg, #8C8C8C 0%, #BFBFBF 100%); }

    /* Responsive */
    @media (max-width: 768px) {
        .detail-header {
            padding: 20px;
        }

        .detail-icon-box {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }

        .detail-title {
            font-size: 1.2rem;
        }

        .meta-section {
            flex-direction: column;
            gap: 10px;
        }

        .detail-actions {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }

        .data-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('pengguna-luar.dashboard') }}">
                    <i class="bi bi-house-door"></i> Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('pengguna-luar.notifikasi.index') }}">
                    <i class="bi bi-bell"></i> Notifikasi
                </a>
            </li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Main Detail Card -->
            <div class="detail-card">
                <!-- Header -->
                <div class="detail-header">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="detail-icon-box">
                                <i class="bi bi-{{ $notifikasi->getIcon() }}"></i>
                            </div>
                            <div>
                                <span class="detail-badge">
                                    {{ str_replace('_', ' ', ucwords($notifikasi->tipe)) }}
                                </span>
                                <h1 class="detail-title">{{ $notifikasi->judul }}</h1>
                            </div>
                        </div>
                        
                        <div class="detail-actions">
                            @if(!$notifikasi->dibaca)
                                <button type="button" 
                                        class="btn-action"
                                        onclick="tandaiDibaca({{ $notifikasi->id }})">
                                    <i class="bi bi-check-circle"></i> Tandai Dibaca
                                </button>
                            @else
                                <button type="button" 
                                        class="btn-action"
                                        onclick="tandaiTidakDibaca({{ $notifikasi->id }})">
                                    <i class="bi bi-envelope"></i> Tandai Belum Dibaca
                                </button>
                            @endif
                            
                            <button type="button" 
                                    class="btn-action"
                                    onclick="hapusNotifikasi({{ $notifikasi->id }})">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="detail-body">
                    <!-- Meta Information -->
                    <div class="meta-section">
                        <div class="meta-item-detail">
                            <i class="bi bi-clock"></i>
                            <span>{{ $notifikasi->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        
                        <div class="meta-item-detail">
                            <i class="bi bi-calendar-check"></i>
                            <span>{{ $notifikasi->getWaktuRelatif() }}</span>
                        </div>
                        
                        @if($notifikasi->prioritas !== 'normal')
                            <div class="meta-item-detail">
                                <i class="bi bi-flag-fill"></i>
                                <span class="badge-priority badge-{{ $notifikasi->prioritas }}">
                                    Prioritas {{ ucfirst($notifikasi->prioritas) }}
                                </span>
                            </div>
                        @endif

                        @if($notifikasi->dibaca)
                            <div class="meta-item-detail">
                                <i class="bi bi-check-circle-fill" style="color: #52C41A;"></i>
                                <span>Dibaca: {{ $notifikasi->dibaca_pada->format('d M Y, H:i') }}</span>
                            </div>
                        @else
                            <div class="meta-item-detail">
                                <span class="badge-unread">
                                    <i class="bi bi-envelope-fill"></i> Belum Dibaca
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="content-section">
                        <h6 class="section-title">
                            <i class="bi bi-chat-text"></i>
                            Isi Pesan
                        </h6>
                        <div class="content-box">
                            <p class="mb-0" style="white-space: pre-line;">{{ $notifikasi->isi }}</p>
                        </div>
                    </div>

                    <!-- Additional Data -->
                    @if($notifikasi->data && count($notifikasi->data) > 0)
                        <div class="content-section">
                            <h6 class="section-title">
                                <i class="bi bi-info-circle"></i>
                                Informasi Tambahan
                            </h6>
                            <div class="data-grid">
                                @foreach($notifikasi->data as $key => $value)
                                    <div class="data-item">
                                        <div class="data-label">{{ ucfirst(str_replace('_', ' ', $key)) }}</div>
                                        <div class="data-value">{{ is_array($value) ? json_encode($value) : $value }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Creator Info -->
                    @if($notifikasi->pembuat)
                        <div class="creator-info">
                            <i class="bi bi-person-circle"></i>
                            <span>
                                Dibuat oleh: 
                                <strong>{{ $notifikasi->pembuat->nama ?? $notifikasi->pembuat->name }}</strong>
                                ({{ $notifikasi->pembuat->role }})
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="detail-footer">
                    <a href="{{ route('pengguna-luar.notifikasi.index') }}" class="btn-back">
                        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Notifikasi
                    </a>
                </div>
            </div>

            <!-- Related Notifications -->
            @php
                $relatedNotifications = \App\Models\Notifikasi::where('user_id', auth()->id())
                    ->where('tipe', $notifikasi->tipe)
                    ->where('id', '!=', $notifikasi->id)
                    ->latest()
                    ->limit(3)
                    ->get();
            @endphp

            @if($relatedNotifications->count() > 0)
                <div class="related-card">
                    <div class="related-header">
                        <h6>
                            <i class="bi bi-list-ul"></i>
                            Notifikasi Terkait ({{ str_replace('_', ' ', ucwords($notifikasi->tipe)) }})
                        </h6>
                    </div>
                    <div>
                        @foreach($relatedNotifications as $related)
                            <a href="{{ route('pengguna-luar.notifikasi.show', $related->id) }}" 
                               class="related-item">
                                <div class="related-icon bg-gradient-{{ $related->getBadgeColor() }}">
                                    <i class="bi bi-{{ $related->getIcon() }}"></i>
                                </div>
                                <div class="related-content">
                                    <div class="related-title">
                                        {{ $related->judul }}
                                        @if(!$related->dibaca)
                                            <span class="badge-new">Baru</span>
                                        @endif
                                    </div>
                                    <p class="related-text">{{ Str::limit($related->isi, 80) }}</p>
                                    <div class="related-time">
                                        <i class="bi bi-clock"></i>
                                        {{ $related->getWaktuRelatif() }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Form untuk Tandai Dibaca -->
<form id="form-tandai-dibaca" 
      action="{{ route('pengguna-luar.notifikasi.show', $notifikasi->id) }}" 
      method="POST" class="d-none">
    @csrf
</form>

<!-- Form untuk Tandai Tidak Dibaca -->
<form id="form-tandai-tidak-dibaca" 
      action="{{ route('pengguna-luar.notifikasi.show', $notifikasi->id) }}" 
      method="POST" class="d-none">
    @csrf
</form>

<!-- Form untuk Hapus Notifikasi -->
<form id="form-hapus-notifikasi" 
      action="{{ route('pengguna-luar.notifikasi.destroy', $notifikasi->id) }}" 
      method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function tandaiDibaca(id) {
    document.getElementById('form-tandai-dibaca').submit();
}

function tandaiTidakDibaca(id) {
    if (confirm('Tandai notifikasi ini sebagai belum dibaca?')) {
        document.getElementById('form-tandai-tidak-dibaca').submit();
    }
}

function hapusNotifikasi(id) {
    if (confirm('Yakin ingin menghapus notifikasi ini?')) {
        document.getElementById('form-hapus-notifikasi').submit();
    }
}
</script>
@endpush