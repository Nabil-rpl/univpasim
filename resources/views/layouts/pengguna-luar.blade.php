<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'Dashboard Pengguna Luar')</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font & Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f6fa;
        }

        .sidebar {
            width: 240px;
            height: 100vh;
            background: linear-gradient(135deg, #60A5FA, #3B82F6);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            box-shadow: 4px 0 15px rgba(96, 165, 250, 0.2);
            z-index: 1000;
        }

        .sidebar h4 {
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
            font-size: 1.4rem;
            color: #fff;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .sidebar a {
            color: #e0f2fe;
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }

        .sidebar a i {
            font-size: 20px;
            margin-right: 15px;
            width: 24px;
            text-align: center;
            z-index: 2;
            position: relative;
        }

        .sidebar a span {
            z-index: 2;
            position: relative;
            font-weight: 500;
        }

        .sidebar a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 0%;
            width: 4px;
            background-color: #fff;
            border-radius: 0 4px 4px 0;
            transition: height 0.3s ease;
        }

        .sidebar a::after {
            content: '';
            position: absolute;
            left: -100%;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.15);
            transition: left 0.3s ease;
            z-index: 1;
        }

        .sidebar a:hover {
            color: #fff;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transform: translateX(5px);
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar a:hover::before {
            height: 70%;
        }

        .sidebar a:hover::after {
            left: 0;
        }

        .sidebar a.active {
            background: rgba(255, 255, 255, 0.25);
            color: #fff;
            border: 2px solid rgba(255, 255, 255, 0.5);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        .sidebar a.active::before {
            height: 80%;
            width: 5px;
            background-color: #fff;
        }

        .sidebar a.active i {
            transform: scale(1.1);
        }

        .main-content {
            margin-left: 240px;
            padding: 20px;
            min-height: 100vh;
        }

        .navbar {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            padding: 16px 28px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h5 {
            margin: 0;
            font-weight: 600;
            color: #1e293b;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info span {
            color: #64748b;
            font-size: 0.95rem;
        }

        .user-info img {
            border: 2px solid #dbeafe;
        }

        /* Notifikasi Styling */
        .notification-bell {
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notification-bell:hover {
            transform: scale(1.1);
        }

        .notification-bell i {
            font-size: 24px;
            color: #64748b;
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            border: 2px solid #fff;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .notification-dropdown {
            position: absolute;
            top: 60px;
            right: 20px;
            width: 380px;
            max-height: 500px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            display: none;
            z-index: 1050;
            overflow: hidden;
        }

        .notification-dropdown.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #60A5FA, #3B82F6);
            color: white;
        }

        .notification-header h6 {
            margin: 0;
            font-weight: 600;
            font-size: 16px;
        }

        .mark-all-read {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mark-all-read:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .notification-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-list::-webkit-scrollbar {
            width: 6px;
        }

        .notification-list::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .notification-list::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .notification-list::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* ✅ UPDATED: Item notifikasi tidak bisa diklik */
        .notification-item {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            cursor: default !important;
            pointer-events: none !important;
            transition: none;
            display: flex;
            gap: 12px;
            text-decoration: none;
            color: inherit;
        }

        .notification-item:hover {
            background: transparent !important;
        }

        .notification-item.unread {
            background: #eff6ff;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notification-icon.terlambat {
            background: #fee2e2;
            color: #dc2626;
        }

        .notification-icon.reminder_deadline {
            background: #fef3c7;
            color: #f59e0b;
        }

        .notification-icon.denda_belum_dibayar {
            background: #fce7f3;
            color: #ec4899;
        }

        .notification-icon.peminjaman_disetujui {
            background: #dcfce7;
            color: #16a34a;
        }

        .notification-icon.peminjaman_ditolak {
            background: #fee2e2;
            color: #dc2626;
        }

        .notification-icon.perpanjangan_disetujui {
            background: #dcfce7;
            color: #16a34a;
        }

        .notification-icon.perpanjangan_ditolak {
            background: #fee2e2;
            color: #dc2626;
        }

        .notification-icon.pengembalian_sukses {
            background: #dcfce7;
            color: #16a34a;
        }

        .notification-icon.buku_tersedia {
            background: #dbeafe;
            color: #3b82f6;
        }

        .notification-icon.sistem {
            background: #e2e8f0;
            color: #64748b;
        }

        .notification-icon.default {
            background: #e0e7ff;
            color: #6366f1;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            font-size: 14px;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .notification-text {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .notification-time {
            font-size: 11px;
            color: #94a3b8;
        }

        /* ✅ Footer tetap bisa diklik */
        .notification-footer {
            padding: 12px 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            pointer-events: auto !important;
        }

        .notification-footer a {
            color: #3B82F6;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
            cursor: pointer !important;
            pointer-events: auto !important;
        }

        .notification-footer a:hover {
            color: #2563eb;
        }

        .empty-notification {
            padding: 40px 20px;
            text-align: center;
            color: #94a3b8;
        }

        .empty-notification i {
            font-size: 48px;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        .content {
            background: #fff;
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            min-height: calc(100vh - 180px);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 80px;
                padding: 15px 10px;
            }
            .sidebar h4, .sidebar a span {
                display: none;
            }
            .sidebar a {
                justify-content: center;
                padding: 12px;
            }
            .sidebar a i {
                margin-right: 0;
                font-size: 22px;
            }
            .main-content {
                margin-left: 80px;
            }
            .notification-dropdown {
                width: 320px;
                right: 10px;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 15px;
            }
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            .navbar {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            .notification-dropdown {
                width: calc(100% - 30px);
                right: 15px;
                left: 15px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    {{-- Sidebar --}}
    <div class="sidebar">
        <h4>Pengguna Luar</h4>
        <a href="{{ route('pengguna-luar.dashboard') }}" class="{{ request()->routeIs('pengguna-luar.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('pengguna-luar.peminjaman.riwayat') }}" class="{{ request()->routeIs('pengguna-luar.peminjaman.riwayat') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span>Riwayat</span>
        </a>
        <a href="{{ route('pengguna-luar.buku.index') }}" class="{{ request()->routeIs('pengguna-luar.buku.index') ? 'active' : '' }}">
            <i class="bi bi-book"></i>
            <span>Data Buku</span>
        </a>
        <a href="{{ route('pengguna-luar.notifikasi.index') }}" class="{{ request()->routeIs('pengguna-luar.notifikasi.*') ? 'active' : '' }}">
            <i class="bi bi-bell"></i>
            <span>Notifikasi</span>
        </a>
        <a href="{{ route('pengguna-luar.pengaturan.index') }}" class="{{ request()->routeIs('pengguna-luar.pengaturan') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i>
            <span>Profil</span>
        </a>
        <a href="{{ route('logout') }}" 
           onclick="event.preventDefault(); document.getElementById('logout-form-pengguna').submit();">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>

        <form id="logout-form-pengguna" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    {{-- Main Content --}}
    <div class="main-content">
        {{-- Navbar --}}
        <div class="navbar">
            <h5>@yield('page-title', 'Dashboard Pengguna Luar')</h5>
            <div class="user-info">
                {{-- Notification Bell --}}
                <div class="notification-bell" id="notificationBell">
                    <i class="bi bi-bell-fill"></i>
                    <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                </div>

                <span>{{ auth()->user()->name ?? 'Pengguna' }}</span>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Pengguna') }}&background=60A5FA&color=fff&bold=true" 
                     class="rounded-circle" width="40" height="40" alt="User">
            </div>
        </div>

        {{-- Notification Dropdown --}}
        <div class="notification-dropdown" id="notificationDropdown">
            <div class="notification-header">
                <h6>Notifikasi</h6>
                <button class="mark-all-read" id="markAllReadBtn">
                    <i class="bi bi-check-all"></i> Tandai Semua
                </button>
            </div>
            <div class="notification-list" id="notificationList">
                {{-- Loading state --}}
                <div class="empty-notification">
                    <i class="bi bi-arrow-clockwise"></i>
                    <p class="mb-0">Memuat notifikasi...</p>
                </div>
            </div>
            <div class="notification-footer">
                <a href="{{ route('pengguna-luar.notifikasi.index') }}">Lihat Semua Notifikasi</a>
            </div>
        </div>

        {{-- Konten Dinamis --}}
        <div class="content">
            @yield('content')
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Setup CSRF token untuk AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Notification Dropdown Toggle
        const notificationBell = document.getElementById('notificationBell');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const notificationBadge = document.getElementById('notificationBadge');
        const notificationList = document.getElementById('notificationList');
        const markAllReadBtn = document.getElementById('markAllReadBtn');

        // Toggle dropdown
        notificationBell.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle('show');
            if (notificationDropdown.classList.contains('show')) {
                loadNotifications();
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!notificationDropdown.contains(e.target) && !notificationBell.contains(e.target)) {
                notificationDropdown.classList.remove('show');
            }
        });

        // Load notifications
        function loadNotifications() {
            console.log('🔔 Loading notifications...');
            
            fetch('{{ route("pengguna-luar.notifikasi.latest") }}', {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('📥 Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('✅ Data received:', data);
                if (data.success) {
                    displayNotifications(data.notifikasi);
                    updateBadge(data.unread_count);
                } else {
                    throw new Error(data.message || 'Gagal memuat');
                }
            })
            .catch(error => {
                console.error('❌ Error loading notifications:', error);
                notificationList.innerHTML = `
                    <div class="empty-notification">
                        <i class="bi bi-exclamation-circle"></i>
                        <p class="mb-0">Gagal memuat notifikasi</p>
                        <small class="text-muted d-block mt-1">${error.message}</small>
                    </div>
                `;
            });
        }

        // ✅ UPDATED: Display notifications (item TIDAK bisa diklik)
        function displayNotifications(notifikasi) {
            console.log('📋 Displaying notifications:', notifikasi.length, 'items');
            
            if (!notifikasi || notifikasi.length === 0) {
                notificationList.innerHTML = `
                    <div class="empty-notification">
                        <i class="bi bi-bell-slash"></i>
                        <p class="mb-0">Tidak ada notifikasi</p>
                    </div>
                `;
                return;
            }

            let html = '';
            notifikasi.forEach(notif => {
                const unreadClass = !notif.dibaca ? 'unread' : '';
                const iconClass = notif.tipe || 'default';
                const icon = getNotificationIcon(notif.tipe);
                
                // ✅ PENTING: Menggunakan div biasa, BUKAN anchor tag
                // ✅ Item notifikasi tidak bisa diklik
                html += `
                    <div class="notification-item ${unreadClass}" data-id="${notif.id}">
                        <div class="notification-icon ${iconClass}">
                            <i class="${icon}"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">${escapeHtml(notif.judul)}</div>
                            <div class="notification-text">${escapeHtml(notif.isi)}</div>
                            <div class="notification-time">
                                <i class="bi bi-clock"></i> ${notif.waktu}
                            </div>
                        </div>
                    </div>
                `;
            });

            notificationList.innerHTML = html;
            console.log('✅ Notifications displayed (items are NOT clickable)');
        }

        // Get notification icon
        function getNotificationIcon(tipe) {
            const icons = {
                'terlambat': 'bi bi-exclamation-triangle-fill',
                'reminder_deadline': 'bi bi-clock-fill',
                'denda_belum_dibayar': 'bi bi-cash-coin',
                'peminjaman_disetujui': 'bi bi-check-circle-fill',
                'peminjaman_ditolak': 'bi bi-x-circle-fill',
                'peminjaman_baru': 'bi bi-book-fill',
                'perpanjangan_disetujui': 'bi bi-check2-circle',
                'perpanjangan_ditolak': 'bi bi-x-octagon',
                'perpanjangan_baru': 'bi bi-arrow-clockwise',
                'pengembalian_sukses': 'bi bi-check-circle',
                'buku_tersedia': 'bi bi-bell-fill',
                'sistem': 'bi bi-info-circle-fill',
                'default': 'bi bi-bell-fill'
            };
            return icons[tipe] || icons['default'];
        }

        // Update badge
        function updateBadge(count) {
            console.log('🔔 Updating badge count:', count);
            if (count > 0) {
                notificationBadge.textContent = count > 99 ? '99+' : count;
                notificationBadge.style.display = 'flex';
            } else {
                notificationBadge.style.display = 'none';
            }
        }

        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Mark all as read
        markAllReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('🔔 Mark all as read clicked');
            
            if (confirm('Tandai semua notifikasi sebagai dibaca?')) {
                fetch('{{ route("pengguna-luar.notifikasi.mark-all-read") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('✅ Mark all read response:', data);
                    if (data.success) {
                        loadNotifications();
                    }
                })
                .catch(error => console.error('❌ Error marking all as read:', error));
            }
        });

        // Load initial notification count
        console.log('🚀 Initializing notifications...');
        loadNotifications();

        // Auto refresh every 60 seconds
        setInterval(function() {
            console.log('🔄 Auto-refreshing notifications...');
            loadNotifications();
        }, 60000);

        // Tooltip Bootstrap
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>