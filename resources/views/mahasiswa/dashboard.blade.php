@extends('layouts.mahasiswa')

@section('page-title', 'Dashboard Mahasiswa')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
                        <p class="text-muted mb-0">
                            @if($mahasiswa)
                                NIM: {{ $mahasiswa->nim }} | Jurusan: {{ $mahasiswa->jurusan ?? '-' }}
                            @else
                                <span class="text-danger">Data mahasiswa tidak ditemukan. Hubungi admin.</span>
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('mahasiswa.peminjaman.index') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-book"></i> Pinjam Buku
                    </a>
                </div>
            </div>
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

    <!-- Stats Cards -->
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="stat-card stat-primary">
                <div class="stat-icon">
                    <i class="bi bi-book-half"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $totalBuku }}</h3>
                    <p class="stat-label">Total Buku</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card stat-success">
                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $bukuTersedia }}</h3>
                    <p class="stat-label">Buku Tersedia</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card stat-warning">
                <div class="stat-icon">
                    <i class="bi bi-bookmark-star"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $peminjamanAktif }}</h3>
                    <p class="stat-label">Sedang Dipinjam</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="row g-3">
        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card action-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning-charge me-2"></i>Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('mahasiswa.peminjaman.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-book"></i> Pinjam Buku Baru
                        </a>
                        <a href="{{ route('mahasiswa.peminjaman.riwayat') }}" class="btn btn-outline-info">
                            <i class="bi bi-clock-history"></i> Lihat Riwayat
                        </a>
                    </div>
                    
                    @if($peminjamanAktif > 0)
                        <div class="alert alert-info mt-3 mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            <small>Anda memiliki <strong>{{ $peminjamanAktif }}</strong> buku yang sedang dipinjam.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-8">
            <div class="card activity-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman Terakhir</h5>
                </div>
                <div class="card-body">
                    @if($riwayatPeminjaman->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Buku</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riwayatPeminjaman as $p)
                                        <tr>
                                            <td>
                                                <strong>{{ $p->buku->judul }}</strong><br>
                                                <small class="text-muted">{{ $p->buku->penulis }}</small>
                                            </td>
                                            <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                                            <td>
                                                @if($p->status == 'dipinjam')
                                                    <span class="badge bg-warning">Dipinjam</span>
                                                @else
                                                    <span class="badge bg-success">Dikembalikan</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('mahasiswa.peminjaman.show', $p->id) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('mahasiswa.peminjaman.riwayat') }}" class="btn btn-sm btn-outline-primary">
                                Lihat Semua Riwayat <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-2">Belum ada riwayat peminjaman</p>
                            <a href="{{ route('mahasiswa.peminjaman.index') }}" class="btn btn-primary btn-sm">
                                Pinjam Buku Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #667eea;
    --success: #48bb78;
    --warning: #f6ad55;
    --info: #4299e1;
}

.welcome-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.welcome-card h2 {
    font-weight: 700;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    display: flex;
    align-items: center;
    gap: 1.5rem;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

.stat-primary .stat-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stat-success .stat-icon {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
}

.stat-warning .stat-icon {
    background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
    color: white;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    color: #2d3748;
}

.stat-label {
    margin: 0;
    color: #718096;
    font-weight: 600;
}

.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.card-header {
    background: transparent;
    border-bottom: 1px solid #e2e8f0;
    padding: 1.25rem;
}

.card-header h5 {
    font-weight: 600;
    color: #2d3748;
}

.table {
    font-size: 0.95rem;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), #764ba2);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.alert {
    border: none;
    border-radius: 12px;
}
</style>

<script>
// Auto hide alerts
setTimeout(function() {
    var alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        var bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>
@endsection