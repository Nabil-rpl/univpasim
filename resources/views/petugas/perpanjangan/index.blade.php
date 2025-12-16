@extends('layouts.petugas')

@section('page-title', 'Kelola Perpanjangan Peminjaman')

@section('content')
<style>
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

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stats-card {
        background: white;
        border-radius: 16px;
        padding: 1.75rem;
        border-left: 4px solid;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
    }

    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .stats-card.warning { border-left-color: #ed8936; }
    .stats-card.success { border-left-color: #10b981; }
    .stats-card.danger { border-left-color: #ef4444; }
    .stats-card.info { border-left-color: #3b82f6; }

    .alert-custom {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        border-radius: 16px;
        border: none;
        margin-bottom: 2rem;
    }

    .alert-success-custom {
        background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
        color: #065F46;
        border-left: 4px solid #10B981;
    }

    .alert-danger-custom {
        background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
        color: #991B1B;
        border-left: 4px solid #EF4444;
    }

    .filter-card-custom {
        background: #FFFFFF;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(96, 165, 250, 0.12);
        border: 1px solid #E0E7FF;
        margin-bottom: 2rem;
    }

    .table-card-custom {
        background: #FFFFFF;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(96, 165, 250, 0.12);
        border: 1px solid #E0E7FF;
    }

    .table-custom {
        margin: 0;
    }

    .table-custom thead {
        background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
    }

    .table-custom thead th {
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border: none;
        padding: 1.25rem 1rem;
        color: #3B82F6;
    }

    .table-custom tbody tr {
        border-bottom: 1px solid #F1F5F9;
        transition: all 0.3s ease;
    }

    .table-custom tbody tr:hover {
        background: #F8FAFC;
    }

    .table-custom tbody tr.urgent {
        background: #fffbeb;
        border-left: 4px solid #f59e0b;
    }

    .table-custom tbody td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        color: #475569;
        font-weight: 500;
    }

    .btn-custom {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 700;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        border: none;
    }

    .btn-success-custom {
        background: linear-gradient(135deg, #34D399, #10B981);
        color: white;
    }

    .btn-success-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .btn-danger-custom {
        background: linear-gradient(135deg, #F87171, #EF4444);
        color: white;
    }

    .btn-danger-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        color: white;
    }

    .empty-state-custom {
        text-align: center;
        padding: 5rem 2rem;
    }

    .empty-icon-custom {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        margin: 0 auto 2rem;
        color: #60A5FA;
    }

    .badge-role {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .extension-info {
        background: #f8fafc;
        border-radius: 10px;
        padding: 0.75rem;
        margin-top: 0.5rem;
    }
</style>

<div class="perpanjangan-container">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
                <li class="breadcrumb-item">
                    <a href="{{ route('petugas.dashboard') }}">
                        <i class="bi bi-house-door-fill"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active">Kelola Perpanjangan</li>
            </ol>
        </nav>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert-custom alert-success-custom">
                <i class="bi bi-check-circle-fill" style="font-size: 1.5rem;"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif
        @if (session('error'))
            <div class="alert-custom alert-danger-custom">
                <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.5rem;"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <!-- Header -->
        <div class="page-header-custom">
            <div class="header-content">
                <div>
                    <h3 style="margin: 0 0 0.5rem 0; font-size: 1.75rem; font-weight: 800; color: #1E293B;">
                        <i class="bi bi-arrow-clockwise"></i> Kelola Perpanjangan Peminjaman
                    </h3>
                    <p style="margin: 0; color: #64748B; font-weight: 500;">Setujui atau tolak perpanjangan peminjaman buku</p>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stats-card warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2" style="font-size: 0.875rem;">Menunggu Persetujuan</h6>
                        <h2 class="mb-0 fw-bold" style="color: #ed8936;">{{ $stats['menunggu'] ?? 0 }}</h2>
                    </div>
                    <div style="font-size: 2.5rem; opacity: 0.3;">⏳</div>
                </div>
            </div>

            <div class="stats-card success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2" style="font-size: 0.875rem;">Disetujui</h6>
                        <h2 class="mb-0 fw-bold" style="color: #10b981;">{{ $stats['disetujui'] ?? 0 }}</h2>
                    </div>
                    <div style="font-size: 2.5rem; opacity: 0.3;">✅</div>
                </div>
            </div>

            <div class="stats-card danger">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2" style="font-size: 0.875rem;">Ditolak</h6>
                        <h2 class="mb-0 fw-bold" style="color: #ef4444;">{{ $stats['ditolak'] ?? 0 }}</h2>
                    </div>
                    <div style="font-size: 2.5rem; opacity: 0.3;">❌</div>
                </div>
            </div>

            <div class="stats-card info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2" style="font-size: 0.875rem;">Total Perpanjangan</h6>
                        <h2 class="mb-0 fw-bold" style="color: #3b82f6;">{{ $stats['total'] ?? 0 }}</h2>
                    </div>
                    <div style="font-size: 2.5rem; opacity: 0.3;">📊</div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="filter-card-custom">
            <form method="GET" action="{{ route('petugas.perpanjangan.index') }}">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label style="font-weight: 700; color: #1E293B; margin-bottom: 0.5rem;">
                            <i class="bi bi-search me-1"></i>Pencarian
                        </label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Nama peminjam atau judul buku..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label style="font-weight: 700; color: #1E293B; margin-bottom: 0.5rem;">
                            <i class="bi bi-funnel me-1"></i>Status
                        </label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label style="font-weight: 700; color: #1E293B; margin-bottom: 0.5rem;">
                            <i class="bi bi-person me-1"></i>Tipe
                        </label>
                        <select name="role" class="form-select">
                            <option value="">Semua</option>
                            <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            <option value="pengguna_luar" {{ request('role') == 'pengguna_luar' ? 'selected' : '' }}>Pengguna Luar</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('petugas.perpanjangan.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="table-card-custom">
            @if ($perpanjangan->count() > 0)
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="22%">Peminjam & Buku</th>
                                <th width="10%">Deadline Lama</th>
                                <th width="10%">Deadline Baru</th>
                                <th width="8%">Durasi</th>
                                <th width="15%">Alasan</th>
                                <th width="10%">Status</th>
                                <th width="10%">Diajukan</th>
                                <th width="10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($perpanjangan as $index => $p)
                                @php
                                    $isUrgent = $p->peminjaman && 
                                                $p->peminjaman->tanggal_deadline && 
                                                $p->status == 'menunggu' &&
                                                now()->diffInDays($p->peminjaman->tanggal_deadline, false) <= 1;
                                @endphp
                                <tr class="{{ $isUrgent ? 'urgent' : '' }}">
                                    <td><strong>{{ ($perpanjangan->currentPage() - 1) * $perpanjangan->perPage() + $loop->iteration }}</strong></td>
                                    <td>
                                        <div class="mb-2">
                                            <strong class="d-block" style="color: #1e293b;">{{ $p->peminjaman->mahasiswa->name }}</strong>
                                            @if($p->peminjaman->mahasiswa->role == 'mahasiswa')
                                                <span class="badge badge-role bg-primary">
                                                    <i class="bi bi-mortarboard-fill me-1"></i>Mahasiswa
                                                </span>
                                                <small class="text-muted d-block mt-1">NIM: {{ $p->peminjaman->mahasiswa->nim ?? '-' }}</small>
                                            @else
                                                <span class="badge badge-role bg-info">
                                                    <i class="bi bi-person-fill me-1"></i>Pengguna Luar
                                                </span>
                                                <small class="text-muted d-block mt-1">HP: {{ $p->peminjaman->mahasiswa->no_hp ?? '-' }}</small>
                                            @endif
                                        </div>
                                        <div class="extension-info">
                                            <i class="bi bi-book text-primary me-1"></i>
                                            <strong style="font-size: 0.875rem;">{{ $p->peminjaman->buku->judul }}</strong>
                                            @if($isUrgent)
                                                <span class="badge bg-warning text-dark ms-1">
                                                    <i class="bi bi-exclamation-triangle-fill"></i> Urgent
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $p->tanggal_deadline_lama->format('d M Y') }}</strong>
                                        <br><small class="text-muted">{{ $p->tanggal_deadline_lama->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ $p->tanggal_deadline_baru->format('d M Y') }}</strong>
                                        <br><small class="text-muted">{{ $p->tanggal_deadline_baru->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info" style="font-size: 0.875rem;">
                                            <i class="bi bi-plus-circle me-1"></i>{{ $p->durasi_tambahan }} hari
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($p->alasan, 40) }}</small>
                                        @if(strlen($p->alasan) > 40)
                                            <br>
                                            <button class="btn btn-sm btn-link p-0 text-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#alasanModal{{ $p->id }}">
                                                Lihat semua
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        @if($p->status == 'menunggu')
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-hourglass-split"></i> Menunggu
                                            </span>
                                        @elseif($p->status == 'disetujui')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle-fill"></i> Disetujui
                                            </span>
                                            @if($p->petugas)
                                                <br><small class="text-muted">{{ $p->petugas->name }}</small>
                                            @endif
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle-fill"></i> Ditolak
                                            </span>
                                            @if($p->catatan_petugas)
                                                <br>
                                                <button class="btn btn-sm btn-link p-0 text-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#catatanModal{{ $p->id }}">
                                                    Lihat catatan
                                                </button>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $p->created_at->format('d M Y') }}</strong>
                                        <br><small class="text-muted">{{ $p->created_at->format('H:i') }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($p->status == 'menunggu')
                                            <div class="d-flex flex-column gap-2">
                                                <form action="{{ route('petugas.perpanjangan.approve', $p->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn-custom btn-success-custom w-100" 
                                                            onclick="return confirm('Yakin menyetujui perpanjangan ini?')"
                                                            title="Setujui">
                                                        <i class="bi bi-check-circle"></i> Setujui
                                                    </button>
                                                </form>
                                                
                                                <button type="button" class="btn-custom btn-danger-custom w-100" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectModal{{ $p->id }}"
                                                        title="Tolak">
                                                    <i class="bi bi-x-circle"></i> Tolak
                                                </button>
                                            </div>
                                        @else
                                            <span class="badge bg-secondary">Sudah Diproses</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Modal Alasan -->
                                <div class="modal fade" id="alasanModal{{ $p->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Alasan Perpanjangan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <strong>Peminjam:</strong> {{ $p->peminjaman->mahasiswa->name }}
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Buku:</strong> {{ $p->peminjaman->buku->judul }}
                                                </div>
                                                <div>
                                                    <strong>Alasan:</strong>
                                                    <p class="mt-2">{{ $p->alasan }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Catatan Petugas -->
                                @if($p->catatan_petugas)
                                <div class="modal fade" id="catatanModal{{ $p->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Catatan Penolakan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ $p->catatan_petugas }}</p>
                                                @if($p->petugas)
                                                    <hr>
                                                    <small class="text-muted">Ditolak oleh: <strong>{{ $p->petugas->name }}</strong></small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Modal Reject -->
                                <div class="modal fade" id="rejectModal{{ $p->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-x-circle me-2"></i>Tolak Perpanjangan
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('petugas.perpanjangan.reject', $p->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="alert alert-warning">
                                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                                        <strong>Perhatian!</strong> Anda akan menolak perpanjangan dari 
                                                        <strong>{{ $p->peminjaman->mahasiswa->name }}</strong>
                                                    </div>

                                                    <div class="mb-3">
                                                        <strong>Alasan Mahasiswa:</strong>
                                                        <p class="text-muted mt-2 p-3" style="background: #f8fafc; border-radius: 8px;">
                                                            {{ $p->alasan }}
                                                        </p>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">
                                                            Catatan Penolakan <span class="text-danger">*</span>
                                                        </label>
                                                        <textarea name="catatan_petugas" class="form-control" rows="4" 
                                                                  placeholder="Jelaskan alasan penolakan dengan jelas..." required></textarea>
                                                        <small class="text-muted">Catatan ini akan dilihat oleh mahasiswa</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="bi bi-x-lg me-1"></i>Batal
                                                    </button>
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="bi bi-x-circle me-1"></i>Tolak Perpanjangan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="padding: 1.5rem 2rem; border-top: 1px solid #e2e8f0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Menampilkan {{ $perpanjangan->firstItem() ?? 0 }} - {{ $perpanjangan->lastItem() ?? 0 }} 
                            dari {{ $perpanjangan->total() }} data
                        </div>
                        <div>
                            {{ $perpanjangan->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-state-custom">
                    <div class="empty-icon-custom">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h4 style="color: #1e293b; font-weight: 700;">Tidak Ada Data Perpanjangan</h4>
                    <p style="color: #64748b;">
                        @if(request('status'))
                            Tidak ada perpanjangan dengan status <strong>{{ request('status') }}</strong>
                        @else
                            Belum ada pengajuan perpanjangan peminjaman
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Auto hide alerts
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert-custom');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.3s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    });

    // Confirm before submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.classList.contains('btn-secondary')) {
                const originalContent = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
                submitBtn.disabled = true;
                
                // Reset badge perpanjangan setelah approve/reject
                if (typeof resetPerpanjanganBadge === 'function') {
                    resetPerpanjanganBadge();
                }
                
                // Reset if form submission fails
                setTimeout(() => {
                    submitBtn.innerHTML = originalContent;
                    submitBtn.disabled = false;
                }, 10000);
            }
        });
    });
</script>
@endsection