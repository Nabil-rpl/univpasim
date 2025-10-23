@extends('layouts.app')

@section('page-title', 'Data Mahasiswa')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Data Mahasiswa</h2>
                    <p class="text-muted mb-0">Kelola data mahasiswa</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.mahasiswa.export') }}" class="btn btn-success">
                        <i class="bi bi-download"></i> Export CSV
                    </a>
                </div>
            </div>

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

    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Filter & Search -->
            <form action="{{ route('admin.mahasiswa.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-5">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Cari nama, email, NIM, atau jurusan..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="jurusan" class="form-select">
                            <option value="">Semua Jurusan</option>
                            @foreach($jurusanList as $jur)
                                <option value="{{ $jur }}" {{ request('jurusan') == $jur ? 'selected' : '' }}>
                                    {{ $jur }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="bi bi-search"></i> Cari
                            </button>
                            <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Nama</th>
                            <th width="20%">Email</th>
                            <th width="15%">NIM</th>
                            <th width="15%">Jurusan</th>
                            <th width="15%">Tanggal Daftar</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mahasiswas as $index => $mhs)
                            <tr>
                                <td>{{ $mahasiswas->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $mhs->nama }}</strong>
                                </td>
                                <td>{{ $mhs->email }}</td>
                                <td><span class="badge bg-info">{{ $mhs->nim }}</span></td>
                                <td>{{ $mhs->jurusan ?? '-' }}</td>
                                <td>{{ $mhs->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.mahasiswa.show', $mhs->id) }}" 
                                           class="btn btn-info" 
                                           title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.mahasiswa.edit', $mhs->id) }}" 
                                           class="btn btn-warning" 
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-danger" 
                                                onclick="confirmDelete({{ $mhs->id }}, '{{ $mhs->nama }}')"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                    <p class="text-muted mb-0">Tidak ada data mahasiswa</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Modern Pagination -->
            @if($mahasiswas->hasPages())
            <div class="pagination-wrapper mt-4">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="pagination-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Menampilkan <strong>{{ $mahasiswas->firstItem() ?? 0 }}</strong> - <strong>{{ $mahasiswas->lastItem() ?? 0 }}</strong> 
                            dari <strong>{{ $mahasiswas->total() }}</strong> total data
                        </div>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Pagination Navigation" class="d-flex justify-content-md-end justify-content-center">
                            {{ $mahasiswas->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                </div>
            </div>
            @else
            <div class="pagination-info mt-4">
                <i class="bi bi-info-circle me-2"></i>
                Total <strong>{{ $mahasiswas->total() }}</strong> data mahasiswa
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Form Delete (Hidden) -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<style>
.card {
    border: none;
    border-radius: 15px;
}

.table {
    margin-bottom: 0;
}

.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

.alert {
    border: none;
    border-radius: 12px;
}

.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Modern Pagination Styles */
.pagination-wrapper {
    background: #f8fafc;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e2e8f0;
}

.pagination-info {
    background: white;
    border-radius: 8px;
    padding: 12px 16px;
    border: 1px solid #e2e8f0;
    color: #64748b;
    font-size: 14px;
    font-weight: 500;
}

.pagination {
    margin: 0;
    gap: 4px;
    justify-content: center;
}

.pagination .page-item {
    margin: 0;
}

.pagination .page-link {
    border: none;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 14px;
    font-weight: 600;
    color: #64748b;
    background: white;
    transition: all 0.3s ease;
    margin: 0 2px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    min-width: 42px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pagination .page-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.3);
    text-decoration: none;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
    border: none;
    transform: translateY(-1px);
}

.pagination .page-item.disabled .page-link {
    background: #e2e8f0;
    color: #94a3b8;
    cursor: not-allowed;
    border: none;
    box-shadow: none;
}

.pagination .page-item.disabled .page-link:hover {
    background: #e2e8f0;
    color: #94a3b8;
    transform: none;
    box-shadow: none;
}

.pagination .page-link:focus {
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    outline: none;
    z-index: 3;
}

/* Navigation arrows */
.pagination .page-link[aria-label="Previous"],
.pagination .page-link[aria-label="Next"] {
    padding: 10px 12px;
    font-size: 16px;
}

.pagination .page-link[aria-label="Previous"]:before {
    content: "‹";
    font-weight: bold;
}

.pagination .page-link[aria-label="Next"]:before {
    content: "›";
    font-weight: bold;
}

/* Responsive pagination */
@media (max-width: 768px) {
    .pagination-wrapper {
        padding: 15px;
    }
    
    .pagination .page-link {
        padding: 8px 12px;
        font-size: 13px;
        min-width: 38px;
    }

    .pagination .page-link[aria-label="Previous"],
    .pagination .page-link[aria-label="Next"] {
        padding: 8px 10px;
    }

    .pagination-info {
        padding: 10px 14px;
        font-size: 13px;
        text-align: center;
        margin-bottom: 15px;
    }

    /* Hide some pagination links on mobile */
    .pagination .page-item:not(.active):not(:first-child):not(:last-child):not(:nth-child(2)):not(:nth-last-child(2)) {
        display: none;
    }
}

@media (max-width: 576px) {
    .pagination {
        gap: 2px;
    }

    .pagination .page-link {
        padding: 6px 10px;
        font-size: 12px;
        min-width: 34px;
        margin: 0 1px;
    }
}
</style>

<script>
function confirmDelete(id, nama) {
    if (confirm(`Apakah Anda yakin ingin menghapus data mahasiswa "${nama}"?\n\nData yang dihapus tidak dapat dikembalikan.`)) {
        const form = document.getElementById('delete-form');
        form.action = "{{ route('admin.mahasiswa.index') }}/" + id;
        form.submit();
    }
}

// Auto hide alerts
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Add smooth scroll to top when pagination is clicked
    const paginationLinks = document.querySelectorAll('.pagination .page-link');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function() {
            setTimeout(() => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }, 100);
        });
    });
});
</script>
@endsection 