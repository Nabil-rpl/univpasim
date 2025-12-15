<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'Dashboard Mahasiswa')</title>

    {{-- CSS Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font dan Icon --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f6fa;
        }

        .sidebar {
            width: 240px;
            height: 100vh;
            background-color: #0d6efd;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            overflow-y: auto;
        }

        .sidebar h4 {
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
        }

        .sidebar a {
            color: #ffffffcc;
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
        }

        /* Badge untuk notifikasi di sidebar */
        .sidebar a .badge {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 3;
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 12px;
            background: #dc3545;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: translateY(-50%) scale(1); }
            50% { transform: translateY(-50%) scale(1.1); }
        }

        /* Garis indikator kiri */
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

        /* Background effect saat hover */
        .sidebar a::after {
            content: '';
            position: absolute;
            left: -100%;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: left 0.3s ease;
            z-index: 1;
        }

        .sidebar a:hover {
            color: #fff;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transform: translateX(5px);
        }

        .sidebar a:hover::before {
            height: 70%;
        }

        .sidebar a:hover::after {
            left: 0;
        }

        .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            border: 2px solid rgba(255, 255, 255, 0.4);
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .sidebar a.active::before {
            height: 80%;
            width: 5px;
            background-color: #676666;
        }

        .sidebar a.active i {
            transform: scale(1.1);
        }

        .main-content {
            margin-left: 240px;
            padding: 20px;
        }

        .navbar {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            padding: 15px 25px;
            margin-bottom: 25px;
        }

        .content {
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        /* ✅ Notification Bell Styles */
        .notification-bell {
            position: relative;
            cursor: pointer;
            margin-right: 25px;
        }

        .notification-bell-icon {
            font-size: 24px;
            color: #6c757d;
            transition: all 0.3s;
        }

        .notification-bell:hover .notification-bell-icon {
            color: #0d6efd;
            transform: scale(1.1);
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(245, 87, 108, 0.4);
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        /* ✅ Notification Dropdown */
        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 15px;
            width: 400px;
            max-height: 500px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            display: none;
            z-index: 1000;
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

        .notification-dropdown-header {
            padding: 20px;
            border-bottom: 2px solid #f1f3f5;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .notification-dropdown-header h6 {
            margin: 0;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .notification-dropdown-body {
            max-height: 350px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f1f3f5;
            transition: all 0.3s;
            cursor: pointer;
            display: flex;
            gap: 12px;
            text-decoration: none;
            color: inherit;
        }

        .notification-item:hover {
            background: #f8f9fa;
        }

        .notification-item.unread {
            background: linear-gradient(135deg, #f0f4ff 0%, #ffffff 100%);
            border-left: 4px solid #0d6efd;
        }

        .notification-item-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            flex-shrink: 0;
        }

        .notification-item-content {
            flex: 1;
        }

        .notification-item-title {
            font-weight: 600;
            font-size: 0.9rem;
            color: #2d3748;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .notification-item-text {
            font-size: 0.8rem;
            color: #718096;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .notification-item-time {
            font-size: 0.75rem;
            color: #a0aec0;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .notification-dropdown-footer {
            padding: 12px 20px;
            border-top: 2px solid #f1f3f5;
            text-align: center;
        }

        .notification-dropdown-footer a {
            color: #0d6efd;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .notification-dropdown-footer a:hover {
            color: #0a58ca;
        }

        .notification-empty {
            padding: 40px 20px;
            text-align: center;
            color: #a0aec0;
        }

        .notification-empty i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Gradient backgrounds for notification icons */
        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bg-gradient-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .bg-gradient-danger { background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); }
        .bg-gradient-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .bg-gradient-warning { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .bg-gradient-secondary { background: linear-gradient(135deg, #a8caba 0%, #5d4e6d 100%); }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .sidebar h4,
            .sidebar a span {
                display: none;
            }

            .sidebar a {
                justify-content: center;
                padding: 12px;
            }

            .sidebar a i {
                margin-right: 0;
            }

            .main-content {
                margin-left: 70px;
            }

            .notification-dropdown {
                width: 320px;
                right: -80px;
            }
        }
    </style>

    {{-- Custom styles dari halaman child --}}
    @stack('styles')
</head>

<body>
    {{-- Sidebar --}}
    <div class="sidebar">
        <h4>Mahasiswa</h4>
        <a href="{{ route('mahasiswa.dashboard') }}" class="{{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('mahasiswa.peminjaman.riwayat') }}" class="{{ request()->routeIs('mahasiswa.peminjaman.riwayat') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span>Riwayat</span>
        </a>
        <a href="{{ route('mahasiswa.buku.index') }}" class="{{ request()->routeIs('mahasiswa.buku.index') ? 'active' : '' }}">
            <i class="bi bi-book"></i>
            <span>Data Buku</span>
        </a>
        {{-- ✅ Menu Notifikasi dengan Badge --}}
        <a href="{{ route('mahasiswa.notifikasi.index') }}" class="{{ request()->routeIs('mahasiswa.notifikasi.*') ? 'active' : '' }}">
            <i class="bi bi-bell"></i>
            <span>Notifikasi</span>
            @if(isset($unreadNotifCount) && $unreadNotifCount > 0)
            <span class="badge">{{ $unreadNotifCount > 99 ? '99+' : $unreadNotifCount }}</span>
            @endif
        </a>
        <a href="{{ route('mahasiswa.pengaturan.index') }}" class="{{ request()->routeIs('mahasiswa.pengaturan.index') ? 'active' : '' }}">
            <i class="bi bi-gear"></i>
            <span>Pengaturan</span>
        </a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </div>

    {{-- Main Content --}}
    <div class="main-content">
        {{-- Navbar --}}
        <div class="navbar d-flex justify-content-between align-items-center">
            <h5 class="m-0">@yield('page-title', 'Dashboard Mahasiswa')</h5>
            <div class="d-flex align-items-center">
                {{-- ✅ Notification Bell Dropdown --}}
                <div class="notification-bell" id="notificationBell">
                    <i class="bi bi-bell-fill notification-bell-icon"></i>
                    @if(isset($unreadNotifCount) && $unreadNotifCount > 0)
                    <span class="notification-badge" id="topBadge">{{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}</span>
                    @endif

                    {{-- Dropdown --}}
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-dropdown-header">
                            <h6><i class="bi bi-bell me-2"></i>Notifikasi</h6>
                            <small><span id="unreadCountText">{{ $unreadNotifCount ?? 0 }}</span> Belum Dibaca</small>
                        </div>
                        <div class="notification-dropdown-body" id="notificationList">
                            {{-- Akan diisi via AJAX --}}
                            <div class="text-center py-3">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="notification-dropdown-footer">
                            <a href="{{ route('mahasiswa.notifikasi.index') }}">
                                Lihat Semua Notifikasi
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- User Info --}}
                <span class="text-muted me-3">{{ Auth::user()->name ?? 'Mahasiswa' }}</span>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Mahasiswa') }}" 
                     class="rounded-circle" width="40" height="40" alt="User">
            </div>
        </div>

        {{-- Konten Dinamis --}}
        <div class="content">
            @yield('content')
        </div>
    </div>

    {{-- JS Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- ✅ Notification Dropdown Script --}}
    <script>
        // Toggle notification dropdown
        const notificationBell = document.getElementById('notificationBell');
        const notificationDropdown = document.getElementById('notificationDropdown');

        if (notificationBell) {
            notificationBell.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationDropdown.classList.toggle('show');
                
                // Load notifications via AJAX
                if (notificationDropdown.classList.contains('show')) {
                    loadNotifications();
                }
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!notificationBell.contains(e.target)) {
                notificationDropdown.classList.remove('show');
            }
        });

        // ✅ Load notifications via AJAX (FIXED ROUTE)
        function loadNotifications() {
            fetch('{{ route("mahasiswa.notifikasi.latest") }}')
                .then(response => response.json())
                .then(data => {
                    const notificationList = document.getElementById('notificationList');
                    
                    if (data.success && data.notifikasi && data.notifikasi.length > 0) {
                        let html = '';
                        data.notifikasi.forEach(notif => {
                            const unreadClass = !notif.dibaca ? 'unread' : '';
                            const iconBg = getNotificationBgClass(notif.tipe);
                            const icon = getNotificationIcon(notif.tipe);
                            const timeAgo = getTimeAgo(notif.created_at);
                            
                            html += `
                                <a href="{{ url('/mahasiswa/notifikasi') }}/${notif.id}" class="notification-item ${unreadClass}">
                                    <div class="notification-item-icon ${iconBg}">
                                        <i class="bi bi-${icon}"></i>
                                    </div>
                                    <div class="notification-item-content">
                                        <div class="notification-item-title">${escapeHtml(notif.judul)}</div>
                                        <div class="notification-item-text">${escapeHtml(truncate(notif.isi, 60))}</div>
                                        <div class="notification-item-time">
                                            <i class="bi bi-clock"></i> ${timeAgo}
                                        </div>
                                    </div>
                                </a>
                            `;
                        });
                        notificationList.innerHTML = html;
                        
                        // Update badge count
                        updateBadgeCount(data.unread_count || 0);
                    } else {
                        notificationList.innerHTML = `
                            <div class="notification-empty">
                                <i class="bi bi-bell-slash"></i>
                                <p class="mb-0">Tidak ada notifikasi</p>
                            </div>
                        `;
                        updateBadgeCount(0);
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    document.getElementById('notificationList').innerHTML = `
                        <div class="notification-empty">
                            <i class="bi bi-exclamation-triangle"></i>
                            <p class="mb-0">Gagal memuat notifikasi</p>
                        </div>
                    `;
                });
        }

        // ✅ Update badge count
        function updateBadgeCount(count) {
            const badges = document.querySelectorAll('.notification-badge, .badge');
            const unreadText = document.getElementById('unreadCountText');
            
            badges.forEach(badge => {
                if (count > 0) {
                    badge.textContent = count > 9 ? '9+' : count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            });
            
            if (unreadText) {
                unreadText.textContent = count;
            }
        }

        // Helper functions
        function getNotificationBgClass(tipe) {
            const colors = {
                'peminjaman_disetujui': 'bg-gradient-success',
                'peminjaman_ditolak': 'bg-gradient-danger',
                'perpanjangan_disetujui': 'bg-gradient-success',
                'perpanjangan_ditolak': 'bg-gradient-danger',
                'reminder_deadline': 'bg-gradient-warning',
                'terlambat': 'bg-gradient-danger',
                'pengembalian_sukses': 'bg-gradient-success',
                'buku_tersedia': 'bg-gradient-info',
                'denda_belum_dibayar': 'bg-gradient-warning',
                'sistem': 'bg-gradient-secondary'
            };
            return colors[tipe] || 'bg-gradient-primary';
        }

        function getNotificationIcon(tipe) {
            const icons = {
                'peminjaman_disetujui': 'check-circle-fill',
                'peminjaman_ditolak': 'x-circle-fill',
                'perpanjangan_disetujui': 'check2-circle',
                'perpanjangan_ditolak': 'x-octagon',
                'reminder_deadline': 'alarm',
                'terlambat': 'exclamation-triangle-fill',
                'pengembalian_sukses': 'check-circle',
                'buku_tersedia': 'bell-fill',
                'denda_belum_dibayar': 'cash-coin',
                'sistem': 'info-circle-fill'
            };
            return icons[tipe] || 'bell';
        }

        function getTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);
            
            if (seconds < 60) return 'Baru saja';
            if (seconds < 3600) return Math.floor(seconds / 60) + ' menit lalu';
            if (seconds < 86400) return Math.floor(seconds / 3600) + ' jam lalu';
            if (seconds < 604800) return Math.floor(seconds / 86400) + ' hari lalu';
            return date.toLocaleDateString('id-ID');
        }

        function truncate(str, length) {
            if (!str) return '';
            return str.length > length ? str.substring(0, length) + '...' : str;
        }

        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
        }

        // ✅ Auto-refresh notification count every 30 seconds (FIXED ROUTE)
        setInterval(function() {
            fetch('{{ route("mahasiswa.notifikasi.count") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateBadgeCount(data.count || 0);
                    }
                })
                .catch(error => {
                    console.error('Error fetching notification count:', error);
                });
        }, 30000);
    </script>

    {{-- Custom scripts dari halaman child --}}
    @stack('scripts')
</body>
</html>