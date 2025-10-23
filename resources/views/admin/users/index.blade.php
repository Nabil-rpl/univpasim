@extends('layouts.app')

@section('page-title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0 text-dark fw-bold">Dashboard Admin - Manajemen User</h2>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-add-user btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Tambah User Baru
                </a>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="row mb-4 g-3">
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card bg-primary text-white shadow-sm border-0 rounded-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-1">Total User</h6>
                                    <h3 class="mb-0">{{ $totalUsers }}</h3>
                                </div>
                                <i class="bi bi-people fs-2 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card bg-success text-white shadow-sm border-0 rounded-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-1">Admin</h6>
                                    <h3 class="mb-0">{{ $totalAdmin }}</h3>
                                </div>
                                <i class="bi bi-shield-check fs-2 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card bg-info text-white shadow-sm border-0 rounded-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-1">Petugas</h6>
                                    <h3 class="mb-0">{{ $totalPetugas }}</h3>
                                </div>
                                <i class="bi bi-person-badge fs-2 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stat-card bg-warning text-white shadow-sm border-0 rounded-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-1">Mahasiswa</h6>
                                    <h3 class="mb-0">{{ $totalMahasiswa }}</h3>
                                </div>
                                <i class="bi bi-mortarboard fs-2 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="card shadow-sm border-0 rounded-3 mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Cari User</label>
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Cari nama, email, atau NIM..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Filter Role</label>
                            <select name="role" class="form-select">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                                <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-1"></i>Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- User Table -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-gradient-primary text-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Daftar User</h5>
                        <span class="badge bg-white text-primary">{{ $users->total() }} Total</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="25%">Nama</th>
                                    <th width="25%">Email</th>
                                    <th width="15%">Role</th>
                                    <th width="15%">Terdaftar</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $index => $user)
                                <tr class="table-row">
                                    <td>{{ $users->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2">
                                                {{ strtoupper(substr($user->display_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $user->display_name }}</strong>
                                                @if($user->role === 'mahasiswa' && $user->mahasiswa)
                                                    <br><small class="text-muted">{{ $user->mahasiswa->nim }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role == 'admin')
                                            <span class="badge bg-success">Admin</span>
                                        @elseif($user->role == 'petugas')
                                            <span class="badge bg-info">Petugas</span>
                                        @else
                                            <span class="badge bg-warning">Mahasiswa</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $user->created_at->format('d M Y') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.users.show', $user->id) }}" 
                                               class="btn btn-sm btn-info btn-action" 
                                               title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                                               class="btn btn-sm btn-warning btn-action" 
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger btn-action btn-delete" 
                                                    onclick="confirmDelete({{ $user->id }})"
                                                    title="Hapus"
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
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-inbox fs-1 text-muted"></i>
                                        <p class="text-muted mt-2 mb-0">Belum ada data user</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($users->hasPages())
                <div class="card-footer bg-white border-0 py-3">
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
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

/* General Styles */
body {
    font-family: 'Inter', sans-serif;
    background: #f8fafc;
}

/* Container */
.container-fluid {
    padding: 20px;
}

/* Header */
h2 {
    font-size: 24px;
    animation: slideIn 0.6s ease-out;
}

/* Alerts */
.custom-alert {
    border: none;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 14px;
    margin-bottom: 20px;
    border-left: 4px solid #3b82f6;
    animation: alertSlide 0.5s ease-out;
}

.alert-success {
    background: #d1fae5;
    border-left-color: #10b981;
    color: #065f46;
}

.alert-danger {
    background: #fee2e2;
    border-left-color: #ef4444;
    color: #991b1b;
}

/* Stats Cards */
.stat-card {
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    animation: slideIn 0.6s ease-out;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(30, 64, 175, 0.2);
}

.stat-card .card-body {
    padding: 16px;
}

.stat-card h6 {
    font-size: 13px;
    letter-spacing: 0.5px;
}

.stat-card h3 {
    font-size: 24px;
    font-weight: 700;
}

/* Avatar Circle */
.avatar-circle {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 14px;
    flex-shrink: 0;
}

/* Table */
.card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    animation: slideIn 0.6s ease-out;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card-header {
    border-radius: 12px 12px 0 0;
    padding: 16px;
}

.table {
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #1e293b;
    padding: 12px;
}

.table td {
    font-size: 14px;
    color: #475569;
    padding: 12px;
    vertical-align: middle;
}

.table-row {
    transition: all 0.3s ease;
}

.table-row:hover {
    background: #f8fafc;
}

.badge {
    font-size: 12px;
    padding: 6px 12px;
    border-radius: 8px;
}

/* Buttons */
.btn {
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    padding: 8px 16px;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
}

.btn-action {
    padding: 6px 10px;
    font-size: 12px;
}

.btn-info {
    background: #0ea5e9;
    border: none;
}

.btn-warning {
    background: #f59e0b;
    border: none;
}

.btn-danger {
    background: #ef4444;
    border: none;
}

.btn-danger:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-delete.loading {
    pointer-events: none;
    opacity: 0.8;
    position: relative;
}

.btn-delete.loading::after {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    top: 50%;
    left: 50%;
    margin-left: -8px;
    margin-top: -8px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 0.7s linear infinite;
}

/* Custom Pagination Styles */
.pagination {
    margin: 0;
    gap: 4px;
}

.pagination .page-link {
    border: none;
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    background: #f1f5f9;
    transition: all 0.3s ease;
    margin: 0 2px;
}

.pagination .page-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    border: none;
}

.pagination .page-item.disabled .page-link {
    background: #e2e8f0;
    color: #94a3b8;
    cursor: not-allowed;
    border: none;
}

.pagination .page-link:focus {
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    outline: none;
}

/* Pagination Icons */
.pagination .page-link[aria-label="Previous"]::before {
    content: "‹";
    font-size: 18px;
    font-weight: bold;
}

.pagination .page-link[aria-label="Next"]::before {
    content: "›";
    font-size: 18px;
    font-weight: bold;
}

.pagination .page-link[aria-label="Previous"],
.pagination .page-link[aria-label="Next"] {
    padding: 8px 12px;
}

/* Animations */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes alertSlide {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding: 15px;
    }

    .stat-card {
        margin-bottom: 12px;
    }

    .table th, .table td {
        font-size: 13px;
        padding: 10px;
    }

    .btn-action {
        padding: 5px 8px;
        font-size: 11px;
    }

    .pagination .page-link {
        padding: 6px 10px;
        font-size: 12px;
    }

    .card-footer {
        flex-direction: column;
        gap: 10px;
    }

    .card-footer > div {
        width: 100%;
        text-align: center;
    }
}

@media (max-width: 576px) {
    h2 {
        font-size: 20px;
    }

    .btn-add-user {
        padding: 8px 12px;
        font-size: 12px;
    }

    .stat-card h6 {
        font-size: 12px;
    }

    .stat-card h3 {
        font-size: 20px;
    }

    .avatar-circle {
        width: 28px;
        height: 28px;
        font-size: 12px;
    }

    .table th, .table td {
        font-size: 12px;
        padding: 8px;
    }

    .pagination .page-link {
        padding: 5px 8px;
        font-size: 11px;
    }
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