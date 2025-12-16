@extends('layouts.pengguna-luar')

@section('title', 'Detail Notifikasi')

@section('content')
<div class="container-fluid px-4">
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
            <div class="card border-0 shadow-sm">
                <!-- Header -->
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <div class="bg-{{ $notifikasi->getBadgeColor() }} bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="bi bi-{{ $notifikasi->getIcon() }} text-{{ $notifikasi->getBadgeColor() }} fs-4"></i>
                            </div>
                            <div>
                                <span class="badge bg-{{ $notifikasi->getBadgeColor() }} mb-2">
                                    {{ str_replace('_', ' ', ucwords($notifikasi->tipe)) }}
                                </span>
                                <h5 class="mb-0">{{ $notifikasi->judul }}</h5>
                            </div>
                        </div>
                        
                        <div class="btn-group btn-group-sm">
                            @if(!$notifikasi->dibaca)
                                <button type="button" 
                                        class="btn btn-outline-success"
                                        onclick="tandaiDibaca({{ $notifikasi->id }})">
                                    <i class="bi bi-check-circle me-1"></i> Tandai Dibaca
                                </button>
                            @else
                                <button type="button" 
                                        class="btn btn-outline-secondary"
                                        onclick="tandaiTidakDibaca({{ $notifikasi->id }})">
                                    <i class="bi bi-envelope me-1"></i> Tandai Belum Dibaca
                                </button>
                            @endif
                            
                            <button type="button" 
                                    class="btn btn-outline-danger"
                                    onclick="hapusNotifikasi({{ $notifikasi->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="card-body p-4">
                    <!-- Meta Information -->
                    <div class="d-flex flex-wrap gap-3 mb-4 pb-3 border-bottom">
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-clock me-2"></i>
                            <small>{{ $notifikasi->created_at->format('d M Y, H:i') }}</small>
                        </div>
                        
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-calendar-check me-2"></i>
                            <small>{{ $notifikasi->getWaktuRelatif() }}</small>
                        </div>
                        
                        @if($notifikasi->prioritas !== 'normal')
                            <div class="d-flex align-items-center">
                                <i class="bi bi-flag-fill me-2 text-{{ $notifikasi->getPrioritasColor() }}"></i>
                                <span class="badge bg-{{ $notifikasi->getPrioritasColor() }}">
                                    Prioritas {{ ucfirst($notifikasi->prioritas) }}
                                </span>
                            </div>
                        @endif

                        @if($notifikasi->dibaca)
                            <div class="d-flex align-items-center text-success">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <small>Dibaca: {{ $notifikasi->dibaca_pada->format('d M Y, H:i') }}</small>
                            </div>
                        @else
                            <div class="d-flex align-items-center">
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-envelope-fill me-1"></i> Belum Dibaca
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Isi Pesan:</h6>
                        <div class="alert alert-light border">
                            <p class="mb-0" style="white-space: pre-line;">{{ $notifikasi->isi }}</p>
                        </div>
                    </div>

                    <!-- Additional Data -->
                    @if($notifikasi->data && count($notifikasi->data) > 0)
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Informasi Tambahan:</h6>
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <div class="row g-3">
                                        @foreach($notifikasi->data as $key => $value)
                                            <div class="col-md-6">
                                                <div class="d-flex">
                                                    <strong class="text-muted me-2">{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                    <span>{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- URL Action -->
                    @if($notifikasi->url)
                        <div class="alert alert-info border-0">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    <strong>Ada tindakan yang perlu dilakukan</strong>
                                </div>
                                <a href="{{ $notifikasi->url }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka Link
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Pembuat Notifikasi -->
                    @if($notifikasi->pembuat)
                        <div class="mt-4 pt-3 border-top">
                            <small class="text-muted">
                                <i class="bi bi-person-circle me-1"></i>
                                Dibuat oleh: 
                                <strong>{{ $notifikasi->pembuat->nama ?? $notifikasi->pembuat->name }}</strong>
                                ({{ $notifikasi->pembuat->role }})
                            </small>
                        </div>
                    @endif
                </div>

                <!-- Footer Actions -->
                <div class="card-footer bg-white border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('pengguna-luar.notifikasi.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Notifikasi
                        </a>
                        
                        @if($notifikasi->url)
                            <a href="{{ $notifikasi->url }}" class="btn btn-primary">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Detail
                            </a>
                        @endif
                    </div>
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
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">
                            <i class="bi bi-list-ul me-2"></i>
                            Notifikasi Terkait ({{ str_replace('_', ' ', ucwords($notifikasi->tipe)) }})
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @foreach($relatedNotifications as $related)
                            <a href="{{ route('pengguna-luar.notifikasi.show', $related->id) }}" 
                               class="d-block text-decoration-none text-dark border-bottom p-3 hover-bg-light">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-{{ $related->getBadgeColor() }} bg-opacity-10 rounded p-2">
                                            <i class="bi bi-{{ $related->getIcon() }} text-{{ $related->getBadgeColor() }}"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            {{ $related->judul }}
                                            @if(!$related->dibaca)
                                                <span class="badge bg-primary rounded-pill">Baru</span>
                                            @endif
                                        </h6>
                                        <p class="text-muted small mb-1">{{ Str::limit($related->isi, 80) }}</p>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>{{ $related->getWaktuRelatif() }}
                                        </small>
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
      action="{{ route('pengguna-luar.notifikasi.tandai-dibaca', $notifikasi->id) }}" 
      method="POST" class="d-none">
    @csrf
</form>

<!-- Form untuk Tandai Tidak Dibaca -->
<form id="form-tandai-tidak-dibaca" 
      action="{{ route('pengguna-luar.notifikasi.tandai-tidak-dibaca', $notifikasi->id) }}" 
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

@push('styles')
<style>
.hover-bg-light:hover {
    background-color: rgba(0, 0, 0, 0.02);
    transition: background-color 0.3s ease;
}

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
@endpush
@endsection