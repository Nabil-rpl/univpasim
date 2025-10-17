@extends('layouts.app')

@section('page-title', 'Detail Mahasiswa')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Detail Mahasiswa</h2>
                    <p class="text-muted mb-0">Informasi lengkap mahasiswa</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.mahasiswa.edit', $mahasiswa->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="text-center mb-4 mb-md-0">
                                <div class="profile-avatar mx-auto mb-3">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                                <h5 class="mb-0">{{ $mahasiswa->nama }}</h5>
                                <p class="text-muted mb-0">
                                    <span class="badge bg-info">{{ $mahasiswa->nim }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="info-group">
                                <div class="info-item">
                                    <label><i class="bi bi-person me-2"></i>Nama Lengkap</label>
                                    <p>{{ $mahasiswa->nama }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <label><i class="bi bi-envelope me-2"></i>Email</label>
                                    <p>{{ $mahasiswa->email }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <label><i class="bi bi-credit-card me-2"></i>NIM</label>
                                    <p>{{ $mahasiswa->nim }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <label><i class="bi bi-book me-2"></i>Jurusan</label>
                                    <p>{{ $mahasiswa->jurusan ?? '-' }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <label><i class="bi bi-calendar me-2"></i>Tanggal Daftar</label>
                                    <p>{{ $mahasiswa->created_at->format('d F Y, H:i') }}</p>
                                </div>
                                
                                <div class="info-item mb-0">
                                    <label><i class="bi bi-clock-history me-2"></i>Terakhir Update</label>
                                    <p>{{ $mahasiswa->updated_at->format('d F Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($mahasiswa->user)
                        <hr class="my-4">
                        <div class="alert alert-info mb-0">
                            <h6 class="alert-heading mb-2">
                                <i class="bi bi-info-circle me-2"></i>Informasi User Terkait
                            </h6>
                            <p class="mb-1"><strong>Username:</strong> {{ $mahasiswa->user->name }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $mahasiswa->user->email }}</p>
                            <p class="mb-0"><strong>Role:</strong> 
                                <span class="badge bg-primary">{{ $mahasiswa->user->role }}</span>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card shadow-sm mt-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Aksi Cepat</span>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.mahasiswa.edit', $mahasiswa->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Edit Data
                            </a>
                            <button type="button" 
                                    class="btn btn-sm btn-danger" 
                                    onclick="confirmDelete()">
                                <i class="bi bi-trash"></i> Hapus Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Delete (Hidden) -->
<form id="delete-form" action="{{ route('admin.mahasiswa.destroy', $mahasiswa->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<style>
.card {
    border: none;
    border-radius: 15px;
}

.profile-avatar {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.profile-avatar i {
    font-size: 4rem;
}

.info-group {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 12px;
}

.info-item {
    margin-bottom: 1.5rem;
}

.info-item label {
    font-weight: 600;
    color: #4a5568;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
    display: block;
}

.info-item p {
    color: #2d3748;
    font-size: 1rem;
    margin-bottom: 0;
}

.alert {
    border: none;
    border-radius: 12px;
}
</style>

<script>
function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus data mahasiswa "{{ $mahasiswa->nama }}"?\n\nData yang dihapus tidak dapat dikembalikan.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection