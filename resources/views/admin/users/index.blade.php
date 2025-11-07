@extends('layouts.app')

@section('page-title', 'Dashboard User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1 text-dark fw-bold">Kelola Data User</h2>
                    <p class="text-muted mb-0 small">Kelola pengguna sistem dengan mudah</p>
                </div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-add-user">
                    <i class="bi bi-plus-circle me-2"></i>Tambah User
                </a>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="alert-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="flex-grow-1">{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="alert-icon">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div class="flex-grow-1">{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="row mb-4 g-4">
                <div class="col-xl col-lg-4 col-md-6 col-sm-6">
                    <div class="stat-card stat-card-blue">
                        <div class="stat-card-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stat-card-content">
                            <p class="stat-label">Total User</p>
                            <h3 class="stat-value">{{ $totalUsers }}</h3>
                        </div>
                        <div class="stat-card-wave">
                            <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;">
                                <path d="M0.00,49.98 C150.00,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: rgba(255,255,255,0.1);"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="col-xl col-lg-4 col-md-6 col-sm-6">
                    <div class="stat-card stat-card-green">
                        <div class="stat-card-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="stat-card-content">
                            <p class="stat-label">Admin</p>
                            <h3 class="stat-value">{{ $totalAdmin }}</h3>
                        </div>
                        <div class="stat-card-wave">
                            <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;">
                                <path d="M0.00,49.98 C150.00,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: rgba(255,255,255,0.1);"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="col-xl col-lg-4 col-md-6 col-sm-6">
                    <div class="stat-card stat-card-purple">
                        <div class="stat-card-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div class="stat-card-content">
                            <p class="stat-label">Petugas</p>
                            <h3 class="stat-value">{{ $totalPetugas }}</h3>
                        </div>
                        <div class="stat-card-wave">
                            <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;">
                                <path d="M0.00,49.98 C150.00,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: rgba(255,255,255,0.1);"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="col-xl col-lg-4 col-md-6 col-sm-6">
                    <div class="stat-card stat-card-orange">
                        <div class="stat-card-icon">
                            <i class="bi bi-mortarboard"></i>
                        </div>
                        <div class="stat-card-content">
                            <p class="stat-label">Mahasiswa</p>
                            <h3 class="stat-value">{{ $totalMahasiswa }}</h3>
                        </div>
                        <div class="stat-card-wave">
                            <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;">
                                <path d="M0.00,49.98 C150.00,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: rgba(255,255,255,0.1);"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="col-xl col-lg-4 col-md-6 col-sm-6">
                    <div class="stat-card stat-card-cyan">
                        <div class="stat-card-icon">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="stat-card-content">
                            <p class="stat-label">Pengguna Luar</p>
                            <h3 class="stat-value">{{ $totalPenggunaLuar ?? 0 }}</h3>
                        </div>
                        <div class="stat-card-wave">
                            <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;">
                                <path d="M0.00,49.98 C150.00,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: rgba(255,255,255,0.1);"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="card search-card mb-4">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-search me-1"></i>Cari User
                            </label>
                            <input type="text" 
                                   name="search" 
                                   class="form-control form-control-modern" 
                                   placeholder="Cari nama, email, atau NIM..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-funnel me-1"></i>Filter Role
                            </label>
                            <select name="role" class="form-select form-control-modern">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                                <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                <option value="pengguna_luar" {{ request('role') == 'pengguna_luar' ? 'selected' : '' }}>Pengguna Luar</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary btn-search flex-grow-1">
                                <i class="bi bi-search me-2"></i>Cari
                            </button>
                            @if(request('search') || request('role'))
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-reset">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- User Table -->
            <div class="card table-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-table me-2"></i>Daftar User
                            </h5>
                        </div>
                        <div class="badge-total">{{ $users->total() }} Total User</div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 modern-table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="25%">Nama</th>
                                    <th width="20%">Email</th>
                                    <th width="15%">Role</th>
                                    <th width="15%">Info Tambahan</th>
                                    <th width="12%">Terdaftar</th>
                                    <th width="8%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $index => $user)
                                <tr class="table-row">
                                    <td>
                                        <div class="row-number">{{ $users->firstItem() + $index }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="ms-3">
                                                <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->role == 'admin')
                                            <span class="badge badge-modern badge-admin">
                                                <i class="bi bi-shield-check me-1"></i>Admin
                                            </span>
                                        @elseif($user->role == 'petugas')
                                            <span class="badge badge-modern badge-petugas">
                                                <i class="bi bi-person-badge me-1"></i>Petugas
                                            </span>
                                        @elseif($user->role == 'pengguna_luar')
                                            <span class="badge badge-modern badge-pengguna-luar">
                                                <i class="bi bi-person-circle me-1"></i>Pengguna Luar
                                            </span>
                                        @else
                                            <span class="badge badge-modern badge-mahasiswa">
                                                <i class="bi bi-mortarboard me-1"></i>Mahasiswa
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->role === 'mahasiswa')
                                            @if($user->mahasiswa)
                                                <small class="text-muted d-block">
                                                    <i class="bi bi-hash"></i>{{ $user->mahasiswa->nim }}
                                                </small>
                                            @elseif($user->nim)
                                                <small class="text-muted d-block">
                                                    <i class="bi bi-hash"></i>{{ $user->nim }}
                                                </small>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        @elseif($user->role === 'pengguna_luar')
                                            @if($user->no_hp)
                                                <small class="text-muted d-block">
                                                    <i class="bi bi-telephone me-1"></i>{{ $user->no_hp }}
                                                </small>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted small">
                                            <i class="bi bi-calendar3 me-1"></i>{{ $user->created_at->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-modern" role="group">
                                            <a href="{{ route('admin.users.show', $user->id) }}" 
                                               class="btn btn-sm btn-action btn-action-info" 
                                               title="Detail"
                                               data-bs-toggle="tooltip">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                                               class="btn btn-sm btn-action btn-action-warning" 
                                               title="Edit"
                                               data-bs-toggle="tooltip">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-action btn-action-danger btn-delete" 
                                                    onclick="confirmDelete({{ $user->id }})"
                                                    title="Hapus"
                                                    data-bs-toggle="tooltip"
                                                    @if($user->id === auth()->id()) disabled @endif>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Form Delete (Hidden) -->
                                        <form id="delete-form-{{ $user->id }}" 
                                              action="{{ route('admin.users.destroy', $user->id) }}" 
                                              method="POST" 
                                              class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <p class="mb-0 mt-3">Belum ada data user</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($users->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-muted small">
                            <i class="bi bi-info-circle me-1"></i>
                            Menampilkan <strong>{{ $users->firstItem() }}</strong> - <strong>{{ $users->lastItem() }}</strong> dari <strong>{{ $users->total() }}</strong> user
                        </div>
                        <div>
                            {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

/* ========== GLOBAL STYLES ========== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
    color: #1e293b;
}

/* ========== CONTAINER ========== */
.container-fluid {
    padding: 30px;
    max-width: 1400px;
    margin: 0 auto;
}

/* ========== HEADER ========== */
h2 {
    font-size: 32px;
    font-weight: 800;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: slideInDown 0.6s ease-out;
}

.btn-add-user {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 12px 28px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.25);
    transition: all 0.3s ease;
}

.btn-add-user:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 28px rgba(102, 126, 234, 0.4);
}

/* ========== ALERTS ========== */
.custom-alert {
    border: none;
    border-radius: 16px;
    padding: 16px 20px;
    margin-bottom: 24px;
    backdrop-filter: blur(10px);
    animation: slideInRight 0.5s ease-out;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
}

.alert-success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    border-left: 4px solid #10b981;
    color: #065f46;
}

.alert-danger {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border-left: 4px solid #ef4444;
    color: #991b1b;
}

.alert-icon {
    font-size: 24px;
    margin-right: 16px;
}

/* ========== STAT CARDS ========== */
.stat-card {
    position: relative;
    border-radius: 20px;
    padding: 28px;
    color: white;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    animation: fadeInUp 0.6s ease-out;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
}

.stat-card:hover::before {
    opacity: 1;
}

.stat-card-blue {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-card-green {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.stat-card-purple {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
}

.stat-card-orange {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.stat-card-cyan {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
}

.stat-card-icon {
    position: absolute;
    top: 20px;
    right: 20px;
    font-size: 48px;
    opacity: 0.2;
    transition: all 0.3s ease;
}

.stat-card:hover .stat-card-icon {
    transform: scale(1.2) rotate(10deg);
    opacity: 0.3;
}

.stat-card-content {
    position: relative;
    z-index: 2;
}

.stat-label {
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    opacity: 0.9;
    margin-bottom: 8px;
}

.stat-value {
    font-size: 36px;
    font-weight: 800;
    margin: 0;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.stat-card-wave {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 60px;
    z-index: 1;
}

/* ========== SEARCH CARD ========== */
.search-card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    animation: fadeInUp 0.6s ease-out 0.1s both;
    background: white;
}

.form-label {
    color: #475569;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
}

.form-control-modern,
.form-select {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.form-control-modern:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    background: white;
}

.btn-search {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
}

.btn-search:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

/* ========== TABLE CARD ========== */
.table-card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    animation: fadeInUp 0.6s ease-out 0.2s both;
    background: white;
}

.table-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 24px 28px;
    border: none;
}

.badge-total {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    padding: 8px 20px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 13px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

/* ========== TABLE ========== */
.modern-table {
    font-size: 14px;
}

.modern-table thead {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.modern-table thead th {
    padding: 18px 20px;
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #64748b;
    border: none;
}

.modern-table tbody td {
    padding: 20px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}

.table-row {
    transition: all 0.3s ease;
}

.table-row:hover {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    transform: scale(1.01);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.row-number {
    display: inline-block;
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    text-align: center;
    line-height: 32px;
    color: white;
    font-weight: 700;
    font-size: 13px;
}

/* ========== AVATAR ========== */
.avatar-circle {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 16px;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    flex-shrink: 0;
}

/* ========== BADGES ========== */
.badge-modern {
    padding: 8px 16px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.badge-admin {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.badge-petugas {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: white;
}

.badge-mahasiswa {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.badge-pengguna-luar {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    color: white;
}

/* ========== ACTION BUTTONS ========== */
.btn-group-modern {
    gap: 6px;
    display: inline-flex;
}

.btn-action {
    padding: 10px 14px;
    border-radius: 10px;
    border: none;
    transition: all 0.3s ease;
    font-size: 14px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.btn-action-info {
    background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
    color: white;
}

.btn-action-info:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(14, 165, 233, 0.4);
}

.btn-action-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.btn-action-warning:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
}

.btn-action-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.btn-action-danger:hover:not(:disabled) {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
}

.btn-action-danger:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.btn-delete.loading {
    pointer-events: none;
    opacity: 0.7;
    position: relative;
}

.btn-delete.loading::after {
    content: '';
    position: absolute;
    width: 14px;
    height: 14px;
    top: 50%;
    left: 50%;
    margin-left: -7px;
    margin-top: -7px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 0.6s linear infinite;
}

/* ========== EMPTY STATE ========== */
.empty-state i {
    font-size: 64px;
    color: #cbd5e1;
}

.empty-state p {
    color: #94a3b8;
    font-size: 16px;
    font-weight: 500;
}

/* ========== PAGINATION ========== */
.card-footer {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: none;
    padding: 20px 28px;
}

.pagination {
    margin: 0;
    gap: 6px;
}

.pagination .page-link {
    border: none;
    border-radius: 10px;
    padding: 10px 16px;
    font-size: 14px;
    font-weight: 600;
    color: #64748b;
    background: white;
    transition: all 0.3s ease;
    margin: 0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.pagination .page-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.3);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.3);
}

.pagination .page-item.disabled .page-link {
    background: #e2e8f0;
    color: #cbd5e1;
    cursor: not-allowed;
    box-shadow: none;
}

/* ========== ANIMATIONS ========== */
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
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes spin {
    to { 
        transform: rotate(360deg); 
    }
}

/* ========== RESPONSIVE ========== */
@media (max-width: 1199px) {
    .stat-card {
        margin-bottom: 16px;
    }
}

@media (max-width: 992px) {
    .container-fluid {
        padding: 20px;
    }

    h2 {
        font-size: 26px;
    }

    .stat-value {
        font-size: 28px;
    }

    .stat-card {
        padding: 20px;
    }
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 16px;
    }

    h2 {
        font-size: 22px;
    }

    .btn-add-user {
        padding: 10px 20px;
        font-size: 13px;
    }

    .stat-card {
        margin-bottom: 16px;
    }

    .stat-value {
        font-size: 24px;
    }

    .stat-card-icon {
        font-size: 36px;
    }

    .modern-table thead th,
    .modern-table tbody td {
        padding: 12px;
        font-size: 13px;
    }

    .avatar-circle {
        width: 36px;
        height: 36px;
        font-size: 14px;
    }

    .btn-action {
        padding: 8px 10px;
        font-size: 12px;
    }

    .badge-modern {
        padding: 6px 12px;
        font-size: 11px;
    }

    .card-footer {
        flex-direction: column;
        gap: 16px;
    }

    .card-footer > div {
        width: 100%;
        text-align: center;
    }

    .pagination .page-link {
        padding: 8px 12px;
        font-size: 13px;
    }
}

@media (max-width: 576px) {
    h2 {
        font-size: 20px;
    }

    .btn-add-user {
        padding: 8px 16px;
        font-size: 12px;
    }

    .stat-label {
        font-size: 11px;
    }

    .stat-value {
        font-size: 22px;
    }

    .stat-card-icon {
        font-size: 32px;
    }

    .avatar-circle {
        width: 32px;
        height: 32px;
        font-size: 13px;
        border-radius: 10px;
    }

    .modern-table thead th,
    .modern-table tbody td {
        padding: 10px;
        font-size: 12px;
    }

    .row-number {
        width: 28px;
        height: 28px;
        line-height: 28px;
        font-size: 12px;
    }

    .btn-action {
        padding: 7px 9px;
        font-size: 11px;
    }

    .badge-modern {
        padding: 5px 10px;
        font-size: 10px;
    }

    .pagination .page-link {
        padding: 6px 10px;
        font-size: 12px;
    }

    .form-control-modern,
    .form-select {
        padding: 10px 14px;
        font-size: 13px;
    }

    .btn-search {
        padding: 10px 16px;
        font-size: 13px;
    }
}

/* ========== SMOOTH SCROLLING ========== */
html {
    scroll-behavior: smooth;
}

/* ========== CUSTOM SCROLLBAR ========== */
::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}
</style>

<script>
function confirmDelete(userId) {
    if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
        const deleteForm = document.getElementById('delete-form-' + userId);
        const deleteBtn = deleteForm.closest('td').querySelector('.btn-delete');
        deleteBtn.classList.add('loading');
        deleteForm.submit();
    }
}

// Auto hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto hide alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endsection