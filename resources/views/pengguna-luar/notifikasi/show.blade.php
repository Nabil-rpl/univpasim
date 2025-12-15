@extends('layouts.pengguna_luar')

@section('page-title', 'Detail Notifikasi')

@section('content')
<div class="container-fluid">
    {{-- Back Button --}}
    <div class="mb-4">
        <a href="{{ route('pengguna-luar.notifikasi.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Notifikasi
        </a>
    </div>

    {{-- Alert Messages --}}
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

    {{-- Notification Detail Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-{{ $notifikasi->getBadgeColor() }} text-white d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="notification-header-icon">
                    <i class="bi bi-{{ $notifikasi->getIcon() }}"></i>
                </div>
                <div>
                    <h5 class="mb-0">Detail Notifikasi</h5>
                    <small class="opacity-75">{{ $notifikasi->created_at->format('d F Y, H:i') }}</small>
                </div>
            </div>
            <div class="d-flex gap-2">
                @if($notifikasi->isBaru())
                <span class="badge bg-light text-dark">BARU</span>
                @endif
                <span class="badge bg-white text-dark">{{ ucwords(str_replace('_', ' ', $notifikasi->tipe)) }}</span>
            </div>
        </div>
        <div class="card-body">
            {{-- Status & Priority Badge --}}
            <div class="mb-4 d-flex gap-2">
                @if($notifikasi->dibaca)
                <span class="badge badge-success-soft">
                    <i class="bi bi-check-circle-fill"></i> Sudah Dibaca
                </span>
                @else
                <span class="badge badge-warning-soft">
                    <i class="bi bi-envelope-fill"></i> Belum Dibaca
                </span>
                @endif

                @if($notifikasi->prioritas)
                <span class="badge badge-{{ $notifikasi->getPrioritasColor() }}-soft">
                    <i class="bi bi-flag-fill"></i> Prioritas: {{ strtoupper($notifikasi->prioritas) }}
                </span>
                @endif
            </div>

            {{-- Title --}}
            <h3 class="notification-detail-title mb-3">{{ $notifikasi->judul }}</h3>

            {{-- Content --}}
            <div class="notification-detail-content mb-4">
                <p class="lead">{{ $notifikasi->isi }}</p>
            </div>

            {{-- Additional Data --}}
            @if($notifikasi->data && is_array($notifikasi->data))
            <div class="alert alert-info border-0 mb-4">
                <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Informasi Tambahan</h6>
                <ul class="mb-0">
                    @foreach($notifikasi->data as $key => $value)
                    <li><strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Metadata --}}
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="info-box">
                        <i class="bi bi-clock-history text-primary"></i>
                        <div>
                            <small class="text-muted">Diterima</small>
                            <p class="mb-0 fw-semibold">{{ $notifikasi->created_at->format('d F Y, H:i') }}</p>
                            <small class="text-muted">{{ $notifikasi->getWaktuRelatif() }}</small>
                        </div>
                    </div>
                </div>
                @if($notifikasi->dibaca)
                <div class="col-md-6">
                    <div class="info-box">
                        <i class="bi bi-check-circle text-success"></i>
                        <div>
                            <small class="text-muted">Dibaca</small>
                            <p class="mb-0 fw-semibold">{{ $notifikasi->dibaca_pada->format('d F Y, H:i') }}</p>
                            <small class="text-muted">{{ $notifikasi->dibaca_pada->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Type Info --}}
            <div class="alert alert-{{ $notifikasi->getBadgeColor() }}-soft border-0">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-{{ $notifikasi->getIcon() }} fs-4"></i>
                    <div>
                        <strong>Kategori: {{ ucwords(str_replace('_', ' ', $notifikasi->tipe)) }}</strong>
                        <p class="mb-0 small mt-1">
                            @switch($notifikasi->tipe)
                                @case('terlambat')
                                    Notifikasi ini mengingatkan Anda tentang keterlambatan pengembalian buku.
                                    @break
                                @case('reminder_deadline')
                                    Notifikasi pengingat deadline pengembalian buku Anda.
                                    @break
                                @case('denda_belum_dibayar')
                                    Anda memiliki denda yang belum dibayarkan.
                                    @break
                                @case('peminjaman_disetujui')
                                    Peminjaman buku Anda telah disetujui.
                                    @break
                                @case('peminjaman_ditolak')
                                    Peminjaman buku Anda ditolak.
                                    @break
                                @case('buku_tersedia')
                                    Buku yang Anda tunggu kini telah tersedia.
                                    @break
                                @case('perpanjangan_disetujui')
                                    Perpanjangan peminjaman Anda telah disetujui.
                                    @break
                                @case('perpanjangan_ditolak')
                                    Perpanjangan peminjaman Anda ditolak.
                                    @break
                                @default
                                    Notifikasi informasi umum dari sistem perpustakaan.
                            @endswitch
                        </p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="d-flex gap-2 justify-content-end mt-4">
                @if($notifikasi->url)
                <a href="{{ $notifikasi->url }}" class="btn btn-info">
                    <i class="bi bi-arrow-right-circle"></i> Lihat Detail
                </a>
                @endif
                @if(!$notifikasi->dibaca)
                <button class="btn btn-primary" id="markReadBtn">
                    <i class="bi bi-check"></i> Tandai Sebagai Dibaca
                </button>
                @endif
                <button class="btn btn-danger" id="deleteBtn">
                    <i class="bi bi-trash"></i> Hapus Notifikasi
                </button>
            </div>
        </div>
    </div>

    {{-- Related Actions Card --}}
    @if(in_array($notifikasi->tipe, ['terlambat', 'reminder_deadline', 'denda_belum_dibayar']))
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="bi bi-lightning-fill text-warning"></i> Tindakan yang Disarankan</h6>
        </div>
        <div class="card-body">
            @if($notifikasi->tipe == 'terlambat')
            <p class="mb-3"><i class="bi bi-exclamation-triangle text-danger"></i> Segera kembalikan buku Anda untuk menghindari denda lebih lanjut.</p>
            <a href="{{ route('pengguna-luar.peminjaman.index') }}" class="btn btn-primary">
                <i class="bi bi-book"></i> Lihat Peminjaman Aktif
            </a>
            @elseif($notifikasi->tipe == 'reminder_deadline')
            <p class="mb-3"><i class="bi bi-clock text-warning"></i> Pastikan Anda mengembalikan buku sebelum deadline.</p>
            <a href="{{ route('pengguna-luar.peminjaman.index') }}" class="btn btn-primary">
                <i class="bi bi-calendar-check"></i> Cek Deadline
            </a>
            @elseif($notifikasi->tipe == 'denda_belum_dibayar')
            <p class="mb-3"><i class="bi bi-cash-coin text-danger"></i> Lakukan pembayaran denda untuk dapat meminjam buku kembali.</p>
            <a href="{{ route('pengguna-luar.peminjaman.riwayat') }}" class="btn btn-primary">
                <i class="bi bi-cash-coin"></i> Lihat Riwayat & Denda
            </a>
            @endif
        </div>
    </div>
    @endif

    {{-- Pembuat Info (jika ada) --}}
    @if($notifikasi->pembuat)
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <small class="text-muted">
                <i class="bi bi-person-circle"></i> Dikirim oleh: 
                <strong>{{ $notifikasi->pembuat->name }}</strong> 
                ({{ ucfirst($notifikasi->pembuat->role) }})
            </small>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    .notification-header-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .badge-success-soft {
        background: #dcfce7;
        color: #16a34a;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
    }

    .badge-warning-soft {
        background: #fef3c7;
        color: #f59e0b;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
    }

    .badge-danger-soft {
        background: #fee2e2;
        color: #dc2626;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
    }

    .badge-primary-soft {
        background: #dbeafe;
        color: #3b82f6;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
    }

    .badge-secondary-soft {
        background: #f1f5f9;
        color: #64748b;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
    }

    .notification-detail-title {
        color: #1e293b;
        font-weight: 700;
        font-size: 28px;
    }

    .notification-detail-content {
        font-size: 16px;
        line-height: 1.8;
        color: #475569;
        padding: 20px;
        background: #f8fafc;
        border-radius: 12px;
        border-left: 4px solid #3b82f6;
    }

    .info-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        gap: 16px;
        align-items: center;
        border: 1px solid #e2e8f0;
    }

    .info-box i {
        font-size: 32px;
    }

    .alert-danger-soft {
        background: #fee2e2;
        color: #991b1b;
    }

    .alert-warning-soft {
        background: #fef3c7;
        color: #92400e;
    }

    .alert-info-soft {
        background: #e0e7ff;
        color: #3730a3;
    }

    .alert-success-soft {
        background: #dcfce7;
        color: #166534;
    }

    .alert-primary-soft {
        background: #dbeafe;
        color: #1e40af;
    }

    .alert-secondary-soft {
        background: #f1f5f9;
        color: #475569;
    }

    .bg-danger { background-color: #dc2626 !important; }
    .bg-warning { background-color: #f59e0b !important; }
    .bg-success { background-color: #16a34a !important; }
    .bg-info { background-color: #6366f1 !important; }
    .bg-primary { background-color: #3b82f6 !important; }
    .bg-secondary { background-color: #64748b !important; }
</style>
@endpush

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const notifId = {{ $notifikasi->id }};

    // Mark as read
    @if(!$notifikasi->dibaca)
    document.getElementById('markReadBtn')?.addEventListener('click', function() {
        markAsRead(notifId);
    });
    @endif

    // Delete notification
    document.getElementById('deleteBtn').addEventListener('click', function() {
        if (confirm('Hapus notifikasi ini?')) {
            deleteNotification(notifId);
        }
    });

    function markAsRead(id) {
        fetch(`/pengguna-luar/notifikasi/${id}/mark-as-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
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

    function deleteNotification(id) {
        fetch(`/pengguna-luar/notifikasi/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("pengguna-luar.notifikasi.index") }}';
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endpush
@endsection