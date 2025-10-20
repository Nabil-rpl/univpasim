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

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $mahasiswas->firstItem() ?? 0 }} - {{ $mahasiswas->lastItem() ?? 0 }} 
                    dari {{ $mahasiswas->total() }} data
                </div>
                {{ $mahasiswas->links() }}
            </div>
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
});
</script>
@endsection