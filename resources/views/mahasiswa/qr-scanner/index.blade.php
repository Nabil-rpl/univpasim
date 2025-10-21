@extends('layouts.mahasiswa')

@section('page-title', 'Scan QR Code')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="text-center">
                <h2 class="mb-2">📱 Scan QR Code</h2>
                <p class="text-muted">Arahkan kamera ke QR Code pada buku untuk meminjam</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Scanner Card -->
            <div class="card scanner-card mb-3">
                <div class="card-body p-0">
                    <div id="scanner-container">
                        <video id="qr-video" class="w-100"></video>
                        <div class="scanner-overlay">
                            <div class="scanner-frame"></div>
                        </div>
                    </div>
                    
                    <div class="scanner-status text-center p-3">
                        <div id="scanner-loading" class="d-none">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 mb-0">Memulai kamera...</p>
                        </div>
                        <div id="scanner-ready" class="d-none">
                            <i class="bi bi-camera text-success" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0 text-success">Kamera aktif - Silakan scan QR Code</p>
                        </div>
                        <div id="scanner-error" class="d-none text-danger">
                            <i class="bi bi-exclamation-circle" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0">Gagal mengakses kamera</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manual Input Alternative -->
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3"><i class="bi bi-keyboard me-2"></i>Atau Masukkan Kode Manual</h6>
                    <div class="input-group">
                        <input type="text" id="manual-code" class="form-control" placeholder="Masukkan kode QR secara manual">
                        <button class="btn btn-primary" onclick="processManualCode()">
                            <i class="bi bi-check-circle me-1"></i> Submit
                        </button>
                    </div>
                </div>
            </div>

            <!-- Book Preview Modal -->
            <div class="modal fade" id="bookPreviewModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi Peminjaman</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="book-preview-content">
                            <!-- Content will be loaded dynamically -->
                        </div>
                        <div class="modal-footer" id="modal-footer-buttons">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary d-none" id="confirm-borrow-btn" onclick="showDurationSelection()">
                                <i class="bi bi-arrow-right me-1"></i> Lanjutkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.scanner-card {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

#scanner-container {
    position: relative;
    background: #000;
    min-height: 400px;
}

#qr-video {
    display: block;
    width: 100%;
    height: auto;
    object-fit: cover;
}

.scanner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
}

.scanner-frame {
    width: 250px;
    height: 250px;
    border: 3px solid #4299e1;
    border-radius: 20px;
    box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% {
        border-color: #4299e1;
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
    }
    50% {
        border-color: #667eea;
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.6);
    }
}

.scanner-status {
    background: #f8f9fa;
    border-top: 1px solid #e2e8f0;
}

.book-preview-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.book-preview-item:last-child {
    border-bottom: none;
}

.book-preview-item label {
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 0.25rem;
}

.book-preview-item p {
    color: #2d3748;
    margin-bottom: 0;
}

.duration-option {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
}

.duration-option:hover {
    border-color: #4299e1;
    background-color: #f7fafc;
}

.duration-option.selected {
    border-color: #4299e1;
    background-color: #ebf8ff;
}

.duration-option .days {
    font-size: 2rem;
    font-weight: bold;
    color: #2d3748;
}

.duration-option .label {
    font-size: 0.9rem;
    color: #718096;
}

.duration-option .deadline {
    font-size: 0.85rem;
    color: #e53e3e;
    margin-top: 0.5rem;
}
</style>

<!-- Include QR Scanner Library -->
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

<script>
let currentQRCode = null;
let videoStream = null;
let scanning = false;
let selectedDuration = 3; // Default 3 hari

// Initialize Scanner
document.addEventListener('DOMContentLoaded', function() {
    initScanner();
});

async function initScanner() {
    const video = document.getElementById('qr-video');
    const loadingEl = document.getElementById('scanner-loading');
    const readyEl = document.getElementById('scanner-ready');
    const errorEl = document.getElementById('scanner-error');

    loadingEl.classList.remove('d-none');

    try {
        videoStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment' }
        });

        video.srcObject = videoStream;
        video.play();

        loadingEl.classList.add('d-none');
        readyEl.classList.remove('d-none');

        // Start scanning
        scanning = true;
        scanQRCode();
    } catch (error) {
        console.error('Error accessing camera:', error);
        loadingEl.classList.add('d-none');
        errorEl.classList.remove('d-none');
    }
}

function scanQRCode() {
    const video = document.getElementById('qr-video');
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');

    function tick() {
        if (!scanning) return;

        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height);

            if (code) {
                handleQRCodeDetected(code.data);
                return;
            }
        }

        requestAnimationFrame(tick);
    }

    tick();
}

function handleQRCodeDetected(qrData) {
    scanning = false;
    currentQRCode = qrData;
    showBookPreview(qrData);
}

function showBookPreview(qrCode) {
    const modal = new bootstrap.Modal(document.getElementById('bookPreviewModal'));
    const content = document.getElementById('book-preview-content');
    const confirmBtn = document.getElementById('confirm-borrow-btn');
    
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 mb-0">Memuat informasi buku...</p>
        </div>
    `;
    
    confirmBtn.classList.add('d-none');
    modal.show();

    fetch('{{ route("mahasiswa.qr.preview") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ qr_code: qrCode })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const book = data.data;
            content.innerHTML = `
                <div class="text-center mb-3">
                    ${book.foto ? 
                        `<img src="${book.foto}" alt="${book.judul}" style="max-width: 150px; border-radius: 10px;">` :
                        `<div style="width: 150px; height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="bi bi-book-fill text-white" style="font-size: 4rem;"></i>
                        </div>`
                    }
                </div>
                <div class="book-preview-item">
                    <label>Judul Buku</label>
                    <p><strong>${book.judul}</strong></p>
                </div>
                <div class="book-preview-item">
                    <label>Penulis</label>
                    <p>${book.penulis}</p>
                </div>
                <div class="book-preview-item">
                    <label>Penerbit</label>
                    <p>${book.penerbit}</p>
                </div>
                <div class="book-preview-item">
                    <label>Tahun Terbit</label>
                    <p>${book.tahun_terbit}</p>
                </div>
                <div class="book-preview-item">
                    <label>Stok Tersedia</label>
                    <p><span class="badge ${book.stok > 0 ? 'bg-success' : 'bg-danger'}">${book.stok} buku</span></p>
                </div>
            `;
            
            if (book.stok > 0) {
                confirmBtn.classList.remove('d-none');
            }
        } else {
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i> ${data.message}
                </div>
            `;
            setTimeout(() => {
                modal.hide();
                scanning = true;
                scanQRCode();
            }, 2000);
        }
    })
    .catch(error => {
        content.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i> Terjadi kesalahan saat memuat data
            </div>
        `;
    });
}

function showDurationSelection() {
    const content = document.getElementById('book-preview-content');
    const footer = document.getElementById('modal-footer-buttons');
    
    // Hitung tanggal untuk setiap durasi
    const today = new Date();
    const durations = [];
    for (let i = 1; i <= 5; i++) {
        const deadline = new Date(today);
        deadline.setDate(deadline.getDate() + i);
        durations.push({
            days: i,
            deadline: deadline.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
        });
    }
    
    content.innerHTML = `
        <h6 class="mb-3 text-center"><i class="bi bi-calendar-check me-2"></i>Pilih Durasi Peminjaman</h6>
        <div class="alert alert-info" role="alert">
            <small><i class="bi bi-info-circle me-1"></i> 
            Denda keterlambatan: <strong>Rp 5.000/hari</strong></small>
        </div>
        <div class="row g-2">
            ${durations.map(d => `
                <div class="col-4">
                    <div class="duration-option ${d.days === 3 ? 'selected' : ''}" onclick="selectDuration(event, ${d.days})">
                        <div class="days">${d.days}</div>
                        <div class="label">Hari</div>
                        <div class="deadline">
                            <i class="bi bi-calendar-x me-1"></i>
                            ${d.deadline}
                        </div>
                    </div>
                </div>
            `).join('')}
        </div>
        <div class="mt-3 text-center">
            <small class="text-muted">Pilih durasi, lalu klik "Pinjam Buku"</small>
        </div>
    `;
    
    footer.innerHTML = `
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="confirmBorrow()">
            <i class="bi bi-check-circle me-1"></i> Pinjam Buku (${selectedDuration} Hari)
        </button>
    `;
}

function selectDuration(event, days) {
    selectedDuration = days;
    
    // Update visual selection
    document.querySelectorAll('.duration-option').forEach(el => {
        el.classList.remove('selected');
    });
    event.currentTarget.classList.add('selected');
    
    // Update button text
    const btn = document.querySelector('#modal-footer-buttons button:last-child');
    if (btn) {
        btn.innerHTML = `<i class="bi bi-check-circle me-1"></i> Pinjam Buku (${days} Hari)`;
    }
}

function confirmBorrow() {
    if (!currentQRCode) return;

    const modal = bootstrap.Modal.getInstance(document.getElementById('bookPreviewModal'));
    const content = document.getElementById('book-preview-content');
    const footer = document.getElementById('modal-footer-buttons');
    
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Processing...</span>
            </div>
            <p class="mt-3 mb-0">Memproses peminjaman...</p>
        </div>
    `;
    
    footer.innerHTML = '';

    fetch('{{ route("mahasiswa.qr.process") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ 
            qr_code: currentQRCode,
            durasi_hari: selectedDuration
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            content.innerHTML = `
                <div class="alert alert-success text-center">
                    <i class="bi bi-check-circle" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Peminjaman Berhasil!</h5>
                    <p>${data.message}</p>
                    <hr>
                    <p class="mb-0"><strong>${data.data.judul_buku}</strong></p>
                    <small class="text-muted">Tanggal Pinjam: ${data.data.tanggal_pinjam}</small><br>
                    <small class="text-danger"><strong>Deadline: ${data.data.tanggal_deadline}</strong></small><br>
                    <small class="text-muted">Durasi: ${data.data.durasi_hari} hari</small>
                </div>
            `;
            
            setTimeout(() => {
                modal.hide();
                window.location.href = '{{ route("mahasiswa.peminjaman.riwayat") }}';
            }, 3000);
        } else {
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i> ${data.message}
                </div>
            `;
            
            setTimeout(() => {
                modal.hide();
                scanning = true;
                scanQRCode();
            }, 2000);
        }
    })
    .catch(error => {
        content.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i> Terjadi kesalahan
            </div>
        `;
    });
}

function processManualCode() {
    const code = document.getElementById('manual-code').value.trim();
    
    if (!code) {
        alert('Silakan masukkan kode QR');
        return;
    }

    handleQRCodeDetected(code);
}

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (videoStream) {
        videoStream.getTracks().forEach(track => track.stop());
    }
});

// Restart scanning when modal is closed
document.getElementById('bookPreviewModal').addEventListener('hidden.bs.modal', () => {
    currentQRCode = null;
    selectedDuration = 3; // Reset to default
    scanning = true;
    scanQRCode();
});
</script>
@endsection