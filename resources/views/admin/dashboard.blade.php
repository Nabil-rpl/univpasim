@extends('layouts.app')

@section('page-title', 'Dashboard Admin')

@section('content')
<div class="dashboard-wrapper">
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="dashboard-header">
                    <div>
                        <h2 class="dashboard-title">Dashboard Admin</h2>
                        <p class="dashboard-subtitle">Selamat datang kembali, Admin</p>
                    </div>
                </div>

                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show alert-custom" role="alert">
                        <div class="alert-content">
                            <i class="bi bi-check-circle-fill"></i>
                            <div>
                                <h5 class="alert-heading">Sukses!</h5>
                                <p class="mb-0">{{ session('success') }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show alert-custom" role="alert">
                        <div class="alert-content">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <div>
                                <h5 class="alert-heading">Error!</h5>
                                <p class="mb-0">{{ session('error') }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Stats Cards Row -->
        <div class="row mb-4 g-3">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card stat-card-primary">
                    <div class="stat-card-inner">
                        <div class="stat-icon-box">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stat-content">
                            <p class="stat-label">Total User</p>
                            <h3 class="stat-number">{{ $users->count() }}</h3>
                            <span class="stat-change"><i class="bi bi-arrow-up"></i> 5% dari bulan lalu</span>
                        </div>
                    </div>
                    <div class="stat-card-bg"></div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card stat-card-success">
                    <div class="stat-card-inner">
                        <div class="stat-icon-box">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="stat-content">
                            <p class="stat-label">Admin</p>
                            <h3 class="stat-number">{{ $users->where('role', 'admin')->count() }}</h3>
                            <span class="stat-change"><i class="bi bi-dash"></i> Stabil</span>
                        </div>
                    </div>
                    <div class="stat-card-bg"></div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card stat-card-info">
                    <div class="stat-card-inner">
                        <div class="stat-icon-box">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div class="stat-content">
                            <p class="stat-label">Petugas</p>
                            <h3 class="stat-number">{{ $users->where('role', 'petugas')->count() }}</h3>
                            <span class="stat-change"><i class="bi bi-arrow-up"></i> 3% dari bulan lalu</span>
                        </div>
                    </div>
                    <div class="stat-card-bg"></div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card stat-card-warning">
                    <div class="stat-card-inner">
                        <div class="stat-icon-box">
                            <i class="bi bi-mortarboard"></i>
                        </div>
                        <div class="stat-content">
                            <p class="stat-label">Mahasiswa</p>
                            <h3 class="stat-number">{{ $users->where('role', 'mahasiswa')->count() }}</h3>
                            <span class="stat-change"><i class="bi bi-arrow-up"></i> 8% dari bulan lalu</span>
                        </div>
                    </div>
                    <div class="stat-card-bg"></div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-3 mb-4">
            <!-- Doughnut Chart -->
            <div class="col-12 col-lg-6">
                <div class="chart-card">
                    <div class="chart-header">
                        <div>
                            <h5 class="chart-title">Distribusi Pengguna</h5>
                            <p class="chart-subtitle">Perbandingan jumlah pengguna berdasarkan role</p>
                        </div>
                        <div class="chart-menu">
                            <button class="btn-icon" data-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                        </div>
                    </div>
                    <div class="chart-body">
                        <canvas id="doughnutChart" height="280"></canvas>
                    </div>
                </div>
            </div>

            <!-- Bar Chart -->
            <div class="col-12 col-lg-6">
                <div class="chart-card">
                    <div class="chart-header">
                        <div>
                            <h5 class="chart-title">Statistik Pengguna</h5>
                            <p class="chart-subtitle">Jumlah pengguna per kategori</p>
                        </div>
                        <div class="chart-menu">
                            <button class="btn-icon" data-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                        </div>
                    </div>
                    <div class="chart-body">
                        <canvas id="barChart" height="280"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Charts Row -->
        <div class="row g-3 mb-4">
            <!-- Line Chart -->
            <div class="col-12 col-lg-8">
                <div class="chart-card">
                    <div class="chart-header">
                        <div>
                            <h5 class="chart-title">Tren Aktivitas User</h5>
                            <p class="chart-subtitle">Data 7 hari terakhir</p>
                        </div>
                        <div class="chart-filters">
                            <select class="form-select form-select-sm">
                                <option>7 Hari</option>
                                <option>30 Hari</option>
                                <option>90 Hari</option>
                            </select>
                        </div>
                    </div>
                    <div class="chart-body">
                        <canvas id="lineChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            <!-- Status Card -->
            <div class="col-12 col-lg-4">
                <div class="status-card">
                    <div class="status-header">
                        <h5 class="status-title">Status Sistem</h5>
                        <span class="status-badge">Online</span>
                    </div>
                    <div class="status-body">
                        <div class="status-item">
                            <div class="status-indicator online"></div>
                            <div class="status-info">
                                <p class="status-label">Server</p>
                                <p class="status-value">Aktif</p>
                            </div>
                        </div>
                        <div class="status-item">
                            <div class="status-indicator">
                                <span class="status-number">{{ $users->count() }}</span>
                            </div>
                            <div class="status-info">
                                <p class="status-label">Total Pengguna</p>
                                <p class="status-value">Terdaftar</p>
                            </div>
                        </div>
                        <div class="status-item">
                            <div class="status-indicator">
                                <span class="status-number">98%</span>
                            </div>
                            <div class="status-info">
                                <p class="status-label">Uptime</p>
                                <p class="status-value">Reliability</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Section -->
        <div class="row g-3">
            <div class="col-12">
                <div class="quick-stats">
                    <h5 class="quick-stats-title">Ringkasan Sistem</h5>
                    
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="quick-stat-item">
                                <div class="quick-stat-label">Total Pengguna</div>
                                <div class="quick-stat-bar">
                                    <div class="quick-stat-fill" style="width: 75%"></div>
                                </div>
                                <div class="quick-stat-value">{{ $users->count() }} / 200</div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="quick-stat-item">
                                <div class="quick-stat-label">Admin</div>
                                <div class="quick-stat-bar">
                                    <div class="quick-stat-fill" style="width: 60%; background: linear-gradient(90deg, #48bb78, #38a169)"></div>
                                </div>
                                <div class="quick-stat-value">{{ $users->where('role', 'admin')->count() }}</div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="quick-stat-item">
                                <div class="quick-stat-label">Petugas</div>
                                <div class="quick-stat-bar">
                                    <div class="quick-stat-fill" style="width: 45%; background: linear-gradient(90deg, #4299e1, #3182ce)"></div>
                                </div>
                                <div class="quick-stat-value">{{ $users->where('role', 'petugas')->count() }}</div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="quick-stat-item">
                                <div class="quick-stat-label">Mahasiswa</div>
                                <div class="quick-stat-bar">
                                    <div class="quick-stat-fill" style="width: 90%; background: linear-gradient(90deg, #f6ad55, #ed8936)"></div>
                                </div>
                                <div class="quick-stat-value">{{ $users->where('role', 'mahasiswa')->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --primary: #667eea;
    --primary-dark: #764ba2;
    --success: #48bb78;
    --success-dark: #38a169;
    --info: #4299e1;
    --info-dark: #3182ce;
    --warning: #f6ad55;
    --warning-dark: #ed8936;
    --danger: #f56565;
    --light: #f7fafc;
    --light-2: #edf2f7;
    --dark: #2d3748;
    --gray: #718096;
    --border: #e2e8f0;
}

.dashboard-wrapper {
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.container-fluid {
    max-width: 1400px;
}

/* Header */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.dashboard-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.dashboard-subtitle {
    font-size: 1rem;
    color: var(--gray);
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.btn-gradient-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border: none;
    color: white;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-gradient-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    color: white;
}

/* Alerts */
.alert-custom {
    border: none;
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
}

.alert-content {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.alert-success {
    background: rgba(72, 187, 120, 0.1);
}

.alert-success .alert-content i {
    color: var(--success);
    font-size: 1.25rem;
}

.alert-danger {
    background: rgba(245, 101, 101, 0.1);
}

.alert-danger .alert-content i {
    color: var(--danger);
    font-size: 1.25rem;
}

.alert-heading {
    font-size: 0.95rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

/* Stat Cards */
.stat-card {
    position: relative;
    border: none;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
}

.stat-card-inner {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
    padding: 1.5rem;
}

.stat-card-bg {
    position: absolute;
    top: -50%;
    right: -10%;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    opacity: 0.1;
    z-index: 1;
}

.stat-icon-box {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 1.75rem;
    background: rgba(255, 255, 255, 0.15);
}

.stat-card-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
}

.stat-card-success {
    background: linear-gradient(135deg, var(--success), var(--success-dark));
    color: white;
}

.stat-card-info {
    background: linear-gradient(135deg, var(--info), var(--info-dark));
    color: white;
}

.stat-card-warning {
    background: linear-gradient(135deg, var(--warning), var(--warning-dark));
    color: white;
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
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-change {
    font-size: 0.8rem;
    opacity: 0.8;
}

.stat-change i {
    margin-right: 0.25rem;
}

/* Chart Cards */
.chart-card {
    background: white;
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.chart-card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border);
}

.chart-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.25rem;
}

.chart-subtitle {
    font-size: 0.85rem;
    color: var(--gray);
    margin: 0;
}

.chart-filters {
    min-width: 120px;
}

.form-select-sm {
    padding: 0.4rem 0.75rem;
    border-radius: 8px;
    border: 1px solid var(--border);
    font-size: 0.9rem;
}

.chart-menu {
    display: flex;
    gap: 0.5rem;
}

.btn-icon {
    width: 32px;
    height: 32px;
    border: none;
    background: transparent;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    color: var(--gray);
}

.btn-icon:hover {
    background: var(--light-2);
    color: var(--dark);
}

.chart-body {
    padding: 1.5rem;
    position: relative;
}

/* Status Card */
.status-card {
    background: white;
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.status-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border);
}

.status-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
}

.status-badge {
    background: linear-gradient(135deg, var(--success), var(--success-dark));
    color: white;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-body {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.status-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.status-indicator {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: var(--light-2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.25rem;
}

.status-indicator.online {
    background: linear-gradient(135deg, var(--success), var(--success-dark));
    color: white;
    box-shadow: 0 0 20px rgba(72, 187, 120, 0.3);
}

.status-number {
    color: var(--dark);
}

.status-info {
    flex: 1;
}

.status-label {
    font-size: 0.85rem;
    color: var(--gray);
    margin: 0;
    font-weight: 500;
}

.status-value {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
}

.activity-card {
    background: white;
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.activity-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border);
}

.activity-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
}

.link-primary {
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
}

.activity-table {
    overflow-x: auto;
}

.table {
    margin: 0;
    font-size: 0.95rem;
}

.table thead th {
    background: var(--light-2);
    border: none;
    font-weight: 600;
    color: var(--dark);
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    padding: 1rem 1.5rem;
}

.table tbody tr {
    border: none;
    border-bottom: 1px solid var(--border);
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background: var(--light);
}

.table tbody td {
    padding: 1.25rem 1.5rem;
    vertical-align: middle;
}

.user-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    font-weight: 600;
}

.badge {
    padding: 0.35rem 0.8rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.badge-success {
    background: rgba(72, 187, 120, 0.2);
    color: var(--success-dark);
}

.badge-info {
    background: rgba(66, 153, 225, 0.2);
    color: var(--info-dark);
}

.badge-warning {
    background: rgba(246, 173, 85, 0.2);
    color: var(--warning-dark);
}

.status-online-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.35rem 0.75rem;
    background: rgba(72, 187, 120, 0.1);
    color: var(--success-dark);
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-online-badge::before {
    content: '';
    width: 6px;
    height: 6px;
    background: var(--success);
    border-radius: 50%;
    display: inline-block;
}

.btn-action {
    display: inline-flex;
    width: 32px;
    height: 32px;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    background: var(--light-2);
    color: var(--gray);
    text-decoration: none;
    transition: all 0.3s ease;
    margin-right: 0.5rem;
}

.btn-action:hover {
    background: var(--border);
    color: var(--dark);
}

/* Quick Stats */
.quick-stats {
    background: white;
    border: none;
    border-radius: 15px;
    overflow: hidden;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.quick-stats-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 1.5rem;
}

.quick-stat-item {
    margin-bottom: 0;
}

.quick-stat-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.quick-stat-bar {
    height: 6px;
    background: var(--light-2);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.quick-stat-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary), var(--primary-dark));
    border-radius: 10px;
    transition: width 0.3s ease;
}

.quick-stat-value {
    font-size: 0.85rem;
    color: var(--gray);
    font-weight: 500;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .dashboard-title {
        font-size: 1.75rem;
    }

    .stat-card-inner {
        flex-direction: column;
        gap: 1rem;
    }

    .stat-number {
        font-size: 1.75rem;
    }

    .chart-header {
        flex-direction: column;
        gap: 1rem;
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

// Chart Colors
const chartColors = {
    admin: 'rgba(72, 187, 120, 0.8)',
    petugas: 'rgba(66, 153, 225, 0.8)',
    mahasiswa: 'rgba(246, 173, 85, 0.8)'
};

// Doughnut Chart
const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
new Chart(doughnutCtx, {
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
            borderWidth: 2,
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
                    usePointStyle: true,
                    pointStyle: 'circle'
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
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            y: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Line Chart
const lineCtx = document.getElementById('lineChart').getContext('2d');
new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
        datasets: [
            {
                label: 'Admin',
                data: [5, 6, 5, 7, 8, 6, 5],
                borderColor: '#48bb78',
                backgroundColor: 'rgba(72, 187, 120, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#48bb78',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            },
            {
                label: 'Petugas',
                data: [12, 15, 13, 18, 16, 14, 12],
                borderColor: '#4299e1',
                backgroundColor: 'rgba(66, 153, 225, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#4299e1',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            },
            {
                label: 'Mahasiswa',
                data: [28, 32, 30, 35, 38, 36, 32],
                borderColor: '#f6ad55',
                backgroundColor: 'rgba(246, 173, 85, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#f6ad55',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        interaction: {
            mode: 'index',
            intersect: false
        },
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    font: { size: 12, weight: '600' },
                    padding: 15,
                    usePointStyle: true
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 10
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Auto hide alerts
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert-custom');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>

@endsection