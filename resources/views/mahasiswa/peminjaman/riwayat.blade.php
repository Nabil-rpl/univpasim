@extends('layouts.mahasiswa')

@section('page-title', 'Riwayat Peminjaman')

@section('content')
<style>
    .history-container {
        background: linear-gradient(135deg, #EFF6FF 0%, #F8FAFC 100%);
        min-height: 100vh;
        padding: 3rem 0;
    }

    /* Breadcrumb */
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
        transition: color 0.3s ease;
    }

    .breadcrumb-custom a:hover {
        color: #3B82F6;
    }

    /* Header Section */
    .page-header-custom {
        background: #FFFFFF;
        border-radius: 20px;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(96, 165, 250, 0.12);
        border: 1px solid #E0E7FF;
        animation: fadeInUp 0.6s ease;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .header-text h3 {
        margin: 0 0 0.5rem 0;
        font-size: 1.75rem;
        font-weight: 800;
        color: #1E293B;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-text p {
        margin: 0;
        color: #64748B;
        font-weight: 500;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    /* Alerts */
    .alert-custom {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        border-radius: 16px;
        border: none;
        margin-bottom: 2rem;
        animation: slideInDown 0.4s ease;
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

    .alert-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .alert-content {
        flex: 1;
        font-weight: 600;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stats-card-custom {
        background: #FFFFFF;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(96, 165, 250, 0.12);
        border: 1px solid #E0E7FF;
        transition: all 0.3s ease;
        animation: fadeInUp 0.6s ease;
        animation-fill-mode: both;
        position: relative;
        overflow: hidden;
    }

    .stats-card-custom:nth-child(1) { animation-delay: 0.1s; }
    .stats-card-custom:nth-child(2) { animation-delay: 0.2s; }
    .stats-card-custom:nth-child(3) { animation-delay: 0.3s; }

    .stats-card-custom:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(96, 165, 250, 0.2);
    }

    .stats-decoration {
        position: absolute;
        bottom: -20px;
        right: -20px;
        font-size: 6rem;
        opacity: 0.1;
        color: currentColor;
    }

    .stats-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .stats-info h6 {
        margin: 0 0 0.5rem 0;
        color: #64748B;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stats-info h2 {
        margin: 0;
        font-size: 2.5rem;
        font-weight: 800;
        color: #1E293B;
    }

    .stats-icon-custom {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .stats-card-custom.primary .stats-icon-custom {
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
    }

    .stats-card-custom.warning .stats-icon-custom {
        background: linear-gradient(135deg, #FBBF24, #F59E0B);
    }

    .stats-card-custom.success .stats-icon-custom {
        background: linear-gradient(135deg, #34D399, #10B981);
    }

    /* Filter Card */
    .filter-card-custom {
        background: #FFFFFF;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(96, 165, 250, 0.12);
        border: 1px solid #E0E7FF;
        margin-bottom: 2rem;
        animation: fadeInUp 0.7s ease;
    }

    .filter-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .filter-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
    }

    .filter-header h5 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
        color: #1E293B;
    }

    .form-label-custom {
        font-weight: 700;
        color: #1E293B;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-control-custom,
    .form-select-custom {
        border: 2px solid #E0E7FF;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #F8FAFC;
        color: #1E293B;
        font-weight: 500;
    }

    .form-control-custom:focus,
    .form-select-custom:focus {
        border-color: #60A5FA;
        box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.1);
        outline: none;
        background: #FFFFFF;
    }

    /* Buttons */
    .btn-custom {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 700;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
        border: none;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        color: white;
        box-shadow: 0 4px 15px rgba(96, 165, 250, 0.3);
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(96, 165, 250, 0.4);
        color: white;
    }

    .btn-secondary-custom {
        background: #F1F5F9;
        color: #475569;
        border: 2px solid #E2E8F0;
    }

    .btn-secondary-custom:hover {
        background: #E2E8F0;
        transform: translateY(-2px);
        color: #475569;
    }

    .btn-info-custom {
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        color: white;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .btn-info-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(96, 165, 250, 0.3);
        color: white;
    }

    .btn-warning-custom {
        background: linear-gradient(135deg, #FBBF24, #F59E0B);
        color: white;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .btn-warning-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
        color: white;
    }

    /* Table Card */
    .table-card-custom {
        background: #FFFFFF;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(96, 165, 250, 0.12);
        border: 1px solid #E0E7FF;
        animation: fadeInUp 0.8s ease;
    }

    .table-responsive {
        border-radius: 20px;
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

    .table-custom tbody td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        color: #475569;
        font-weight: 500;
    }

    .book-icon-custom {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 12px rgba(96, 165, 250, 0.3);
        overflow: hidden;
    }

    .book-icon-custom img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .book-info strong {
        color: #1E293B;
        font-weight: 700;
        display: block;
        margin-bottom: 0.25rem;
    }

    .book-info small {
        color: #64748B;
        font-size: 0.85rem;
    }

    /* Badge */
    .badge-custom {
        padding: 0.5rem 0.875rem;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .badge-warning-custom {
        background: #FEF3C7;
        color: #92400E;
    }

    .badge-danger-custom {
        background: #FEE2E2;
        color: #991B1B;
    }

    .badge-success-custom {
        background: #D1FAE5;
        color: #065F46;
    }

    /* Empty State */
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

    .empty-state-custom h4 {
        color: #1E293B;
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 0.75rem;
    }

    .empty-state-custom p {
        color: #64748B;
        margin-bottom: 2rem;
        font-size: 1.05rem;
    }

    /* Pagination */
    .pagination {
        margin-top: 2rem;
        justify-content: center;
        gap: 0.5rem;
    }

    .pagination .page-link {
        border-radius: 10px;
        border: 2px solid #E0E7FF;
        color: #3B82F6;
        font-weight: 700;
        padding: 0.625rem 1rem;
        background: #FFFFFF;
        box-shadow: 0 2px 8px rgba(96, 165, 250, 0.1);
        transition: all 0.3s ease;
        margin: 0;
    }

    .pagination .page-link:hover {
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        color: white;
        transform: translateY(-2px);
        border-color: #60A5FA;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #60A5FA, #3B82F6);
        color: white;
        border-color: #60A5FA;
    }

    .pagination .page-item.disabled .page-link {
        background: #F1F5F9;
        color: #CBD5E1;
        border-color: #E2E8F0;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
        }

        .btn-custom {
            flex: 1;
            justify-content: center;
        }

        .stats-info h2 {
            font-size: 2rem;
        }

        .stats-icon-custom {
            width: 60px;
            height: 60px;
            font-size: 1.75rem;
        }
    }
</style>

<div class="history-container">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
                <li class="breadcrumb-item">
                    <a href="{{ route('mahasiswa.dashboard') }}">
                        <i class="bi bi-house-door-fill"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active">Riwayat Peminjaman</li>
            </ol>
        </nav>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert-custom alert-success-custom">
                <i class="bi bi-check-circle-fill alert-icon"></i>
                <div class="alert-content">{{ session('success') }}</div>
            </div>
        @endif
        @if (session('error'))
            <div class="alert-custom alert-danger-custom">
                <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
                <div class="alert-content">{{ session('error') }}</div>
            </div>
        @endif

        <!-- Header -->
        <div class="page-header-custom">
            <div class="header-content">
                <div class="header-text">
                    <h3>
                        <i class="bi bi-clock-history"></i>
                        Riwayat Peminjaman
                    </h3>
                    <p>Semua riwayat peminjaman buku Anda</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('mahasiswa.buku.index') }}" class="btn-custom btn-primary-custom">
                        <i class="bi bi-book"></i>
                        Lihat Buku
                    </a>
                    <a href="{{ route('mahasiswa.dashboard') }}" class="btn-custom btn-secondary-custom">
                        <i class="bi bi-arrow-left"></i>
                        Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistik Cards -->
        @php
            $totalPeminjaman = $peminjaman->count();
            $sedangDipinjam = $peminjaman->where('status', 'dipinjam')->count();
            $sudahDikembalikan = $peminjaman->where('status', 'dikembalikan')->count();
        @endphp

        <div class="stats-grid">
            <div class="stats-card-custom primary">
                <div class="stats-content">
                    <div class="stats-info">
                        <h6>Total Peminjaman</h6>
                        <h2>{{ $totalPeminjaman }}</h2>
                    </div>
                    <div class="stats-icon-custom">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                </div>
                <i class="bi bi-journal-bookmark-fill stats-decoration"></i>
            </div>
            <div class="stats-card-custom warning">
                <div class="stats-content">
                    <div class="stats-info">
                        <h6>Sedang Dipinjam</h6>
                        <h2>{{ $sedangDipinjam }}</h2>
                    </div>
                    <div class="stats-icon-custom">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <i class="bi bi-hourglass-split stats-decoration"></i>
            </div>
            <div class="stats-card-custom success">
                <div class="stats-content">
                    <div class="stats-info">
                        <h6>Sudah Dikembalikan</h6>
                        <h2>{{ $sudahDikembalikan }}</h2>
                    </div>
                    <div class="stats-icon-custom">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
                <i class="bi bi-check-circle-fill stats-decoration"></i>
            </div>
        </div>

        <!-- Filter -->
        <div class="filter-card-custom">
            <div class="filter-header">
                <div class="filter-icon">
                    <i class="bi bi-funnel-fill"></i>
                </div>
                <h5>Filter Riwayat</h5>
            </div>
            <form method="GET" action="{{ route('mahasiswa.peminjaman.riwayat') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label-custom">Status</label>
                        <select name="status" class="form-select form-select-custom">
                            <option value="">Semua Status</option>
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                            <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-custom">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari" class="form-control form-control-custom" value="{{ request('tanggal_dari') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-custom">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" class="form-control form-control-custom" value="{{ request('tanggal_sampai') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn-custom btn-primary-custom w-100">
                            <i class="bi bi-search"></i>
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="table-card-custom">
            @if ($peminjaman->count() > 0)
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">Buku</th>
                                <th width="12%">Tanggal Pinjam</th>
                                <th width="12%">Tanggal Deadline</th>
                                <th width="12%">Tanggal Kembali</th>
                                <th width="10%">Status</th>
                                <th width="10%">Denda</th>
                                <th width="14%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($peminjaman as $index => $p)
                                <tr>
                                    <td>{{ ($peminjaman->currentPage() - 1) * $peminjaman->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="book-icon-custom">
                                                @if ($p->buku->foto)
                                                    <img src="{{ asset('storage/' . $p->buku->foto) }}"
                                                        alt="{{ $p->buku->judul }}">
                                                @else
                                                    <i class="bi bi-book-fill"></i>
                                                @endif
                                            </div>
                                            <div class="book-info">
                                                <strong>{{ $p->buku->judul }}</strong>
                                                <small>{{ $p->buku->penulis }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="bi bi-calendar-event" style="color: #60A5FA;"></i>
                                        {{ $p->tanggal_pinjam->format('d M Y') }}
                                    </td>
                                    <td>
                                        <i class="bi bi-calendar-date" style="color: #F59E0B;"></i>
                                        {{ $p->tanggal_deadline->format('d M Y') }}
                                        
                                        {{-- Tampilkan badge jika ada perpanjangan --}}
                                        @if($p->perpanjangan()->where('status', 'disetujui')->count() > 0)
                                            <br>
                                            <small class="badge bg-info mt-1">
                                                <i class="bi bi-arrow-clockwise"></i> Diperpanjang
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($p->tanggal_kembali)
                                            <i class="bi bi-calendar-check" style="color: #10B981;"></i>
                                            {{ $p->tanggal_kembali->format('d M Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($p->status == 'dipinjam')
                                            @php
                                                $isOverdue = $p->tanggal_deadline->isPast();
                                                $badgeClass = $isOverdue ? 'badge-danger-custom' : 'badge-warning-custom';
                                            @endphp
                                            <span class="badge-custom {{ $badgeClass }}">
                                                <i class="bi bi-hourglass-split"></i>Dipinjam
                                            </span>
                                            
                                            {{-- Tampilkan status perpanjangan menunggu --}}
                                            @if($p->hasPerpanjanganMenunggu())
                                                <br>
                                                <small class="badge bg-warning mt-1">
                                                    <i class="bi bi-clock"></i> Menunggu Persetujuan
                                                </small>
                                            @endif
                                        @else
                                            <span class="badge-custom badge-success-custom">
                                                <i class="bi bi-check-circle"></i>Dikembalikan
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($p->status == 'dipinjam' && $p->tanggal_deadline->isPast())
                                            @php
                                                $hariTerlambat = $p->tanggal_deadline->diffInDays(now());
                                                $denda = $hariTerlambat * 5000;
                                            @endphp
                                            <span style="color: #DC2626; font-weight: 700;">Rp {{ number_format($denda, 0, ',', '.') }}</span>
                                        @elseif($p->pengembalian && $p->pengembalian->denda > 0)
                                            <span style="color: #DC2626; font-weight: 700;">Rp {{ number_format($p->pengembalian->denda, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                                            <a href="{{ route('mahasiswa.peminjaman.show', $p->id) }}" 
                                               class="btn-custom btn-info-custom" 
                                               title="Detail"
                                               style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            
                                            @if($p->status == 'dipinjam' && $p->bisakahDiperpanjang())
                                                <a href="{{ route('mahasiswa.perpanjangan.create', $p->id) }}" 
                                                   class="btn-custom btn-warning-custom" 
                                                   title="Perpanjang"
                                                   style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">
                                                    <i class="bi bi-arrow-clockwise"></i> Perpanjang
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($peminjaman instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div style="padding: 1.5rem 2rem;">
                        {{ $peminjaman->appends(request()->except('page'))->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state-custom">
                    <div class="empty-icon-custom">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h4>Belum Ada Riwayat Peminjaman</h4>
                    <p>Anda belum pernah meminjam buku</p>
                    <a href="{{ route('mahasiswa.buku.index') }}" class="btn-custom btn-primary-custom">
                        <i class="bi bi-book"></i>
                        Lihat Koleksi Buku
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Auto hide alerts
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert-custom');
        alerts.forEach(function(alert) {
            alert.style.animation = 'slideOutUp 0.4s ease';
            setTimeout(() => alert.remove(), 400);
        });
    }, 5000);
</script>

<style>
@keyframes slideOutUp {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-20px);
    }
}
</style>
@endsection