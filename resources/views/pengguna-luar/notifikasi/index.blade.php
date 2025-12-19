@extends('layouts.pengguna-luar')

@section('title', 'Notifikasi')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-bell-fill text-primary me-2"></i>Notifikasi
            </h1>
            <p class="text-muted mb-0">Kelola dan lihat semua notifikasi Anda</p>
        </div>
        
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary" onclick="tandaiSemuaDibaca()">
                <i class="bi bi-check2-all me-1"></i> Tandai Semua Dibaca
            </button>
            <button type="button" class="btn btn-outline-danger" onclick="hapusSudahDibaca()">
                <i class="bi bi-trash me-1"></i> Hapus yang Sudah Dibaca
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Statistik -->
        <div class="col-12 mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                        <i class="bi bi-bell-fill text-primary fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="text-muted mb-1">Total Notifikasi</h6>
                                    <h3 class="mb-0">{{ $stats['total'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                        <i class="bi bi-envelope-fill text-warning fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="text-muted mb-1">Belum Dibaca</h6>
                                    <h3 class="mb-0">{{ $stats['belum_dibaca'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                        <i class="bi bi-envelope-check-fill text-success fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="text-muted mb-1">Sudah Dibaca</h6>
                                    <h3 class="mb-0">{{ $stats['sudah_dibaca'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                        <i class="bi bi-calendar-check text-info fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="text-muted mb-1">Hari Ini</h6>
                                    <h3 class="mb-0">{{ $stats['hari_ini'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Daftar Notifikasi -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-1">Filter Status</label>
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="belum_dibaca" {{ request('status') == 'belum_dibaca' ? 'selected' : '' }}>
                                    Belum Dibaca
                                </option>
                                <option value="dibaca" {{ request('status') == 'dibaca' ? 'selected' : '' }}>
                                    Sudah Dibaca
                                </option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-1">Filter Tipe</label>
                            <select name="tipe" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Tipe</option>
                                @foreach($tipeNotifikasi as $key => $label)
                                    <option value="{{ $key }}" {{ request('tipe') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-1">Per Halaman</label>
                            <select name="per_page" class="form-select" onchange="this.form.submit()">
                                <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                                <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    @forelse($notifikasi as $notif)
                        <div class="notification-item border-bottom {{ !$notif->dibaca ? 'bg-light bg-opacity-50' : '' }}">
                            <div class="d-flex align-items-start p-3">
                                <!-- Icon -->
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-{{ $notif->getBadgeColor() }} bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-{{ $notif->getIcon() }} text-{{ $notif->getBadgeColor() }} fs-5"></i>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1 fw-semibold">
                                                {{ $notif->judul }}
                                                @if(!$notif->dibaca)
                                                    <span class="badge bg-primary rounded-pill ms-2">Baru</span>
                                                @endif
                                                @if($notif->isBaru())
                                                    <span class="badge bg-danger rounded-pill ms-1">
                                                        <i class="bi bi-circle-fill small"></i>
                                                    </span>
                                                @endif
                                            </h6>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>{{ $notif->getWaktuRelatif() }}
                                                @if($notif->prioritas !== 'normal')
                                                    <span class="badge bg-{{ $notif->getPrioritasColor() }} ms-2">
                                                        {{ ucfirst($notif->prioritas) }}
                                                    </span>
                                                @endif
                                            </small>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="btn-group btn-group-sm">
                                            <!-- Tombol Show/Detail -->
                                            <a href="{{ route('pengguna-luar.notifikasi.show', $notif->id) }}" 
                                               class="btn btn-outline-info btn-sm"
                                               title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            <!-- Tombol Link (jika ada URL) -->
                                            @if($notif->url)
                                                <a href="{{ $notif->url }}" 
                                                   class="btn btn-outline-primary btn-sm"
                                                   target="_blank"
                                                   title="Buka Link"
                                                   onclick="tandaiDibaca({{ $notif->id }})">
                                                    <i class="bi bi-box-arrow-up-right"></i>
                                                </a>
                                            @endif
                                            
                                            <!-- Tombol Tandai Belum Dibaca (jika sudah dibaca) -->
                                            @if($notif->dibaca)
                                                <button type="button" 
                                                        class="btn btn-outline-secondary btn-sm"
                                                        onclick="tandaiTidakDibaca({{ $notif->id }})"
                                                        title="Tandai belum dibaca">
                                                    <i class="bi bi-envelope"></i>
                                                </button>
                                            @endif
                                            
                                            <!-- Tombol Hapus -->
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-sm"
                                                    onclick="hapusNotifikasi({{ $notif->id }})"
                                                    title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <p class="mb-0 text-muted">{{ $notif->isi }}</p>

                                    @if($notif->data)
                                        <div class="mt-2">
                                            @foreach($notif->data as $key => $value)
                                                <span class="badge bg-secondary me-1">{{ $key }}: {{ $value }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-bell-slash text-muted" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">Tidak ada notifikasi</h5>
                            <p class="text-muted">Notifikasi Anda akan muncul di sini</p>
                        </div>
                    @endforelse
                </div>

                @if($notifikasi->hasPages())
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Menampilkan {{ $notifikasi->firstItem() }} - {{ $notifikasi->lastItem() }} 
                                dari {{ $notifikasi->total() }} notifikasi
                            </div>
                            {{ $notifikasi->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Form untuk Tandai Dibaca -->
<form id="form-tandai-dibaca" method="POST" class="d-none">
    @csrf
</form>

<!-- Form untuk Tandai Tidak Dibaca -->
<form id="form-tandai-tidak-dibaca" method="POST" class="d-none">
    @csrf
</form>

<!-- Form untuk Hapus Notifikasi -->
<form id="form-hapus-notifikasi" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

<!-- Form untuk Tandai Semua Dibaca -->
<form id="form-tandai-semua-dibaca" 
      action="{{ route('pengguna-luar.notifikasi.index') }}" 
      method="POST" class="d-none">
    @csrf
</form>

<!-- Form untuk Hapus Sudah Dibaca -->
<form id="form-hapus-sudah-dibaca" 
      action="{{ route('pengguna-luar.notifikasi.index') }}" 
      method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
function tandaiDibaca(id) {
    const form = document.getElementById('form-tandai-dibaca');
    form.action = '{{ url("pengguna-luar/notifikasi") }}/' + id + '/tandai-dibaca';
    form.submit();
}

function tandaiTidakDibaca(id) {
    if (confirm('Tandai notifikasi ini sebagai belum dibaca?')) {
        const form = document.getElementById('form-tandai-tidak-dibaca');
        form.action = '{{ url("pengguna-luar/notifikasi") }}/' + id + '/tandai-tidak-dibaca';
        form.submit();
    }
}

function hapusNotifikasi(id) {
    if (confirm('Yakin ingin menghapus notifikasi ini?')) {
        const form = document.getElementById('form-hapus-notifikasi');
        form.action = '{{ url("pengguna-luar/notifikasi") }}/' + id;
        form.submit();
    }
}

function tandaiSemuaDibaca() {
    if (confirm('Tandai semua notifikasi sebagai dibaca?')) {
        document.getElementById('form-tandai-semua-dibaca').submit();
    }
}

function hapusSudahDibaca() {
    if (confirm('Yakin ingin menghapus semua notifikasi yang sudah dibaca?')) {
        document.getElementById('form-hapus-sudah-dibaca').submit();
    }
}

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

@push('styles')
<style>
.notification-item {
    transition: all 0.3s ease;
}

.notification-item:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.notification-item:last-child {
    border-bottom: none !important;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endpush
@endsection