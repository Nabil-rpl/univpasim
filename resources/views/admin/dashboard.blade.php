@extends('layouts.app')

@section('page-title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Dashboard Admin</h2>
                    <p class="text-muted mb-0">Selamat datang kembali</p>
                </div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle"></i> Tambah User Baru
                </a>
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
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row mb-4 g-3">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card stat-card stat-card-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="stat-content">
                            <h6 class="stat-label">Total User</h6>
                            <h3 class="stat-number">{{ $users->count() }}</h3>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card stat-card stat-card-success">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="stat-content">
                            <h6 class="stat-label">Admin</h6>
                            <h3 class="stat-number">{{ $users->where('role', 'admin')->count() }}</h3>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card stat-card stat-card-info">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="stat-content">
                            <h6 class="stat-label">Petugas</h6>
                            <h3 class="stat-number">{{ $users->where('role', 'petugas')->count() }}</h3>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card stat-card stat-card-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="stat-content">
                            <h6 class="stat-label">Mahasiswa</h6>
                            <h3 class="stat-number">{{ $users->where('role', 'mahasiswa')->count() }}</h3>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-mortarboard"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-3 mb-4">
        <!-- Pie Chart -->
        <div class="col-12 col-lg-6">
            <div class="card chart-card">
                <div class="card-header border-0 pb-3">
                    <h5 class="mb-0">Distribusi User</h5>
                </div>
                <div class="card-body">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Bar Chart -->
        <div class="col-12 col-lg-6">
            <div class="card chart-card">
                <div class="card-header border-0 pb-3">
                    <h5 class="mb-0">Statistik Pengguna</h5>
                </div>
                <div class="card-body">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0 pb-3">
                    <h5 class="mb-0">Status Sistem</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="status-item">
                                <div class="status-circle status-online"></div>
                                <h6 class="mt-2">Sistem Aktif</h6>
                                <p class="text-muted mb-0">Online</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="status-item">
                                <div class="status-data">{{ $users->count() }}</div>
                                <h6 class="mt-2">Total Data</h6>
                                <p class="text-muted mb-0">Pengguna Terdaftar</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="status-item">
                                <div class="status-data">{{ round(($users->count() / 100) * 85) }}%</div>
                                <h6 class="mt-2">Aktivitas</h6>
                                <p class="text-muted mb-0">Pengguna Aktif</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #667eea;
    --success: #48bb78;
    --info: #4299e1;
    --warning: #f6ad55;
    --danger: #f56565;
    --light: #f7fafc;
    --dark: #2d3748;
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

.container-fluid {
    padding: 2rem;
}

/* Stats Cards */
.stat-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    position: relative;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5));
}

.stat-card-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stat-card-success {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
}

.stat-card-info {
    background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
    color: white;
}

.stat-card-warning {
    background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
    color: white;
}

.stat-content {
    flex: 1;
}

.stat-label {
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.85;
    margin-bottom: 0.5rem;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
}

.stat-icon {
    font-size: 3rem;
    opacity: 0.2;
    display: flex;
    align-items: center;
}

/* Chart Cards */
.chart-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.chart-card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.chart-card .card-header {
    background: transparent;
    border-bottom: 1px solid #e2e8f0 !important;
    padding: 1.5rem;
}

.chart-card .card-header h5 {
    font-weight: 600;
    color: var(--dark);
}

.chart-card .card-body {
    padding: 1.5rem;
    position: relative;
}

/* Status Section */
.status-item {
    padding: 2rem;
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.status-item:hover {
    background: linear-gradient(135deg, #edf2f7 0%, #e2e8f0 100%);
}

.status-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    margin: 0 auto;
    animation: pulse 2s infinite;
}

.status-online {
    background: linear-gradient(135deg, var(--success), #38a169);
    box-shadow: 0 0 20px rgba(72, 187, 120, 0.4);
}

.status-data {
    width: 60px;
    height: 60px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--info), #3182ce);
    color: white;
    border-radius: 50%;
    font-weight: 700;
    font-size: 1.5rem;
}

.status-item h6 {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.25rem;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(72, 187, 120, 0.7);
    }
    70% {
        box-shadow: 0 0 0 15px rgba(72, 187, 120, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(72, 187, 120, 0);
    }
}

/* General Cards */
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.card-body {
    padding: 1.5rem;
}

/* Alerts */
.alert {
    border: none;
    border-radius: 12px;
    border-left: 4px solid;
}

.alert-success {
    border-left-color: var(--success);
    background: rgba(72, 187, 120, 0.1);
}

.alert-danger {
    border-left-color: var(--danger);
    background: rgba(245, 101, 101, 0.1);
}

/* Button */
.btn-primary {
    background: linear-gradient(135deg, var(--primary), #764ba2);
    border: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

/* Responsive */
@media (max-width: 768px) {
    .stat-number {
        font-size: 2rem;
    }

    .stat-icon {
        font-size: 2.5rem;
    }

    .container-fluid {
        padding: 1rem;
    }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
// Data dari backend
const users = @json($users);
const roleData = {
    admin: users.filter(u => u.role === 'admin').length,
    petugas: users.filter(u => u.role === 'petugas').length,
    mahasiswa: users.filter(u => u.role === 'mahasiswa').length
};

// Pie Chart
const pieCtx = document.getElementById('pieChart').getContext('2d');
new Chart(pieCtx, {
    type: 'doughnut',
    data: {
        labels: ['Admin', 'Petugas', 'Mahasiswa'],
        datasets: [{
            data: [roleData.admin, roleData.petugas, roleData.mahasiswa],
            backgroundColor: [
                'rgba(72, 187, 120, 0.8)',
                'rgba(66, 153, 225, 0.8)',
                'rgba(246, 173, 85, 0.8)'
            ],
            borderColor: [
                '#48bb78',
                '#4299e1',
                '#f6ad55'
            ],
            borderWidth: 3,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: { size: 13, weight: '600' },
                    padding: 20,
                    usePointStyle: true
                }
            }
        }
    }
});

// Bar Chart
const barCtx = document.getElementById('barChart').getContext('2d');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: ['Admin', 'Petugas', 'Mahasiswa'],
        datasets: [{
            label: 'Jumlah Pengguna',
            data: [roleData.admin, roleData.petugas, roleData.mahasiswa],
            backgroundColor: [
                'rgba(72, 187, 120, 0.8)',
                'rgba(66, 153, 225, 0.8)',
                'rgba(246, 173, 85, 0.8)'
            ],
            borderColor: [
                '#48bb78',
                '#4299e1',
                '#f6ad55'
            ],
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        indexAxis: 'y',
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Auto hide alerts
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>

@endsection