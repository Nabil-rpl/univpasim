@extends('layouts.petugas')

@section('page-title', 'Konfirmasi Pembayaran Denda')

@section('content')
<style>
    :root {
        --primary: #667eea;
        --primary-dark: #5568d3;
        --secondary: #764ba2;
        --success: #48bb78;
        --danger: #f56565;
        --warning: #ed8936;
        --info: #4299e1;
        --light: #f7fafc;
        --dark: #2d3748;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-500: #6b7280;
        --gray-700: #374151;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    }

    body {
        background: linear-gradient(135deg, #667eea08 0%, #764ba208 100%);
    }

    .page-header {
        background: linear-gradient(135deg, var(--danger), #dc2626);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: var(--shadow-xl);
    }

    .page-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .page-header p {
        opacity: 0.9;
        margin: 0;
    }

    .info-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
        margin-bottom: 1.5rem;
    }

    .info-card h5 {
        color: var(--dark);
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-row {
        display: flex;
        padding: 1rem 0;
        border-bottom: 1px solid var(--gray-100);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        width: 200px;
        font-weight: 600;
        color: var(--gray-700);
        flex-shrink: 0;
    }

    .info-value {
        color: var(--dark);
        flex: 1;
    }

    .denda-highlight {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border-left: 4px solid var(--danger);
        padding: 1.5rem;
        border-radius: 12px;
        margin: 1.5rem 0;
    }

    .denda-highlight h3 {
        color: var(--danger);
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .denda-highlight p {
        color: var(--gray-700);
        margin: 0;
    }

    .form-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
        margin-bottom: 1.5rem;
    }

    .form-card h5 {
        color: var(--dark);
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control,
    .form-select {
        border: 1px solid var(--gray-200);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.2s;
        width: 100%;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success), #38a169);
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-secondary {
        background: var(--gray-200);
        color: var(--gray-700);
    }

    .btn-secondary:hover {
        background: var(--gray-300);
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: start;
        gap: 0.75rem;
        box-shadow: var(--shadow-sm);
    }

    .alert i {
        font-size: 1.25rem;
        margin-top: 0.125rem;
    }

    .badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .user-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: var(--gray-50);
        border-radius: 12px;
        margin-bottom: 1rem;
    }

    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .user-info strong {
        display: block;
        color: var(--dark);
        font-size: 1.125rem;
        margin-bottom: 0.25rem;
    }

    .user-info small {
        color: var(--gray-500);
        font-size: 0.875rem;
        display: block;
    }

    .book-card {
        display: flex;
        align-items: start;
        gap: 1rem;
        padding: 1rem;
        background: var(--gray-50);
        border-radius: 12px;
    }

    .book-cover {
        width: 80px;
        height: 110px;
        border-radius: 8px;
        object-fit: cover;
        box-shadow: var(--shadow-md);
        flex-shrink: 0;
    }

    .book-placeholder {
        width: 80px;
        height: 110px;
        border-radius: 8px;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #d97706;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        box-shadow: var(--shadow-md);
        flex-shrink: 0;
    }

    .book-details h6 {
        color: var(--dark);
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        font-size: 1rem;
    }

    .book-details p {
        color: var(--gray-600);
        margin: 0.25rem 0;
        font-size: 0.875rem;
    }

    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--gray-200);
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.5rem;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: white;
        border: 3px solid var(--primary);
    }

    .timeline-item.danger::before {
        border-color: var(--danger);
    }

    .timeline-content {
        background: var(--gray-50);
        padding: 1rem;
        border-radius: 8px;
    }

    .timeline-content strong {
        display: block;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }

    .timeline-content small {
        color: var(--gray-500);
    }

    @media (max-width: 768px) {
        .info-row {
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-label {
            width: 100%;
        }

        .user-card,
        .book-card {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>
                <i class="bi bi-cash-coin me-2"></i>
                Konfirmasi Pembayaran Denda
            </h1>
            <p>Update status pembayaran denda keterlambatan pengembalian buku</p>
        </div>
        <div class="d-none d-md-block">
            <div style="font-size: 3rem; opacity: 0.2;">💰</div>
        </div>
    </div>
</div>

<!-- Alert Info -->
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <div>
        <strong>Perhatian!</strong>
        <p class="mb-0">Pastikan pembayaran denda telah diterima sebelum mengkonfirmasi. Tindakan ini akan mengirim notifikasi ke peminjam.</p>
    </div>
</div>

<!-- Informasi Peminjam -->
<div class="info-card">
    <h5>
        <i class="bi bi-person-circle"></i>
        Informasi Peminjam
    </h5>
    
    <div class="user-card">
        <div class="user-avatar">
            {{ strtoupper(substr($pengembalian->peminjaman->mahasiswa->name, 0, 1)) }}
        </div>
        <div class="user-info">
            <strong>{{ $pengembalian->peminjaman->mahasiswa->name }}</strong>
            @if($pengembalian->peminjaman->mahasiswa->role == 'mahasiswa')
                <span class="badge bg-primary">
                    <i class="bi bi-mortarboard-fill"></i>Mahasiswa
                </span>
                <small>NIM: {{ $pengembalian->peminjaman->mahasiswa->nim ?? '-' }}</small>
            @else
                <span class="badge bg-info">
                    <i class="bi bi-person-fill"></i>Pengguna Luar
                </span>
                <small>
                    <i class="bi bi-telephone"></i> {{ $pengembalian->peminjaman->mahasiswa->no_hp ?? '-' }}
                </small>
            @endif
            <small>
                <i class="bi bi-envelope"></i> {{ $pengembalian->peminjaman->mahasiswa->email }}
            </small>
        </div>
    </div>
</div>

<!-- Informasi Buku -->
<div class="info-card">
    <h5>
        <i class="bi bi-book"></i>
        Informasi Buku
    </h5>
    
    <div class="book-card">
        @if($pengembalian->peminjaman->buku->foto)
            <img src="{{ asset('storage/' . $pengembalian->peminjaman->buku->foto) }}" 
                 alt="{{ $pengembalian->peminjaman->buku->judul }}" 
                 class="book-cover">
        @else
            <div class="book-placeholder">
                <i class="bi bi-book-fill"></i>
            </div>
        @endif
        <div class="book-details">
            <h6>{{ $pengembalian->peminjaman->buku->judul }}</h6>
            <p><i class="bi bi-pen"></i> {{ $pengembalian->peminjaman->buku->penulis }}</p>
            <p><i class="bi bi-building"></i> {{ $pengembalian->peminjaman->buku->penerbit }}</p>
            <p><i class="bi bi-calendar3"></i> {{ $pengembalian->peminjaman->buku->tahun_terbit }}</p>
        </div>
    </div>
</div>

<!-- Timeline Peminjaman -->
<div class="info-card">
    <h5>
        <i class="bi bi-clock-history"></i>
        Timeline Peminjaman
    </h5>
    
    <div class="timeline">
        <div class="timeline-item">
            <div class="timeline-content">
                <strong>Tanggal Pinjam</strong>
                <small>{{ $pengembalian->peminjaman->tanggal_pinjam->format('d M Y H:i') }} WIB</small>
            </div>
        </div>
        
        <div class="timeline-item danger">
            <div class="timeline-content">
                <strong>Deadline</strong>
                <small>{{ $pengembalian->peminjaman->tanggal_deadline->format('d M Y H:i') }} WIB</small>
                <span class="badge bg-warning text-dark mt-2">
                    <i class="bi bi-clock"></i>
                    Durasi: {{ $pengembalian->peminjaman->durasi_hari }} hari
                </span>
            </div>
        </div>
        
        <div class="timeline-item">
            <div class="timeline-content">
                <strong>Tanggal Pengembalian</strong>
                <small>{{ $pengembalian->tanggal_pengembalian->format('d M Y') }}</small>
                @php
                    $hariTerlambat = $pengembalian->peminjaman->getHariTerlambat();
                @endphp
                <span class="badge bg-danger mt-2">
                    <i class="bi bi-exclamation-triangle"></i>
                    Terlambat {{ $hariTerlambat }} hari
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Informasi Denda -->
<div class="info-card">
    <h5>
        <i class="bi bi-cash-stack"></i>
        Detail Denda
    </h5>
    
    <div class="denda-highlight">
        <h3>Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</h3>
        <p>
            <i class="bi bi-calculator"></i>
            {{ $pengembalian->peminjaman->getHariTerlambat() }} hari × Rp 5.000 = Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}
        </p>
    </div>
    
    <div class="info-row">
        <div class="info-label">Status Pembayaran</div>
        <div class="info-value">
            <span class="badge bg-danger">
                <i class="bi bi-x-circle"></i>
                Belum Dibayar
            </span>
        </div>
    </div>
</div>

<!-- Form Konfirmasi -->
<div class="form-card">
    <h5>
        <i class="bi bi-check-circle"></i>
        Konfirmasi Pembayaran
    </h5>
    
    <form action="{{ route('petugas.pengembalian.update-denda', $pengembalian->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="form-label">
                <i class="bi bi-chat-left-text"></i>
                Catatan Pembayaran (Opsional)
            </label>
            <textarea name="catatan_pembayaran" 
                      class="form-control @error('catatan_pembayaran') is-invalid @enderror" 
                      placeholder="Contoh: Dibayar tunai pada tanggal 21 Januari 2026 jam 14:00 WIB"
                      rows="4">{{ old('catatan_pembayaran') }}</textarea>
            @error('catatan_pembayaran')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">
                <i class="bi bi-info-circle"></i>
                Catatan ini akan disimpan sebagai bukti pembayaran dan dapat dilihat di riwayat
            </small>
        </div>
        
        <div class="alert alert-info">
            <i class="bi bi-bell"></i>
            <div>
                <strong>Notifikasi Otomatis</strong>
                <p class="mb-0">Setelah konfirmasi, sistem akan otomatis mengirim notifikasi ke <strong>{{ $pengembalian->peminjaman->mahasiswa->name }}</strong> bahwa denda telah lunas.</p>
            </div>
        </div>
        
        <div class="d-flex gap-3 justify-content-end">
            <a href="{{ route('petugas.pengembalian.riwayat') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Batal
            </a>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle"></i>
                Konfirmasi Pembayaran
            </button>
        </div>
    </form>
</div>

<!-- Informasi Petugas -->
<div class="info-card">
    <h5>
        <i class="bi bi-person-badge"></i>
        Informasi Petugas
    </h5>
    
    <div class="info-row">
        <div class="info-label">Diproses oleh</div>
        <div class="info-value">
            <strong>{{ $pengembalian->petugas->name ?? 'Sistem' }}</strong>
            @if($pengembalian->petugas)
                <small class="d-block text-muted">{{ $pengembalian->petugas->email }}</small>
            @endif
        </div>
    </div>
    
    <div class="info-row">
        <div class="info-label">Waktu Proses Pengembalian</div>
        <div class="info-value">{{ $pengembalian->created_at->format('d M Y H:i') }} WIB</div>
    </div>
</div>

<script>
    // Konfirmasi sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!confirm('Apakah Anda yakin pembayaran denda sebesar Rp {{ number_format($pengembalian->denda, 0, ',', '.') }} telah diterima?')) {
            e.preventDefault();
        }
    });
</script>
@endsection