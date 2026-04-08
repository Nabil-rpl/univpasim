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
        :root {
            --sidebar-width: 240px;
            --primary-color: #0d6efd;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f6fa;
            overflow-x: hidden;
        }

        /* ========== SIDEBAR ========== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: #0d6efd;
            color: #fff;
            padding: 20px 15px;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            scrollbar-width: none;
        }

        .sidebar::-webkit-scrollbar { display: none; }

        .sidebar h4 {
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
            white-space: nowrap;
            color: #fff;
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
            white-space: nowrap;
        }

        .sidebar a i {
            font-size: 20px;
            margin-right: 15px;
            width: 24px;
            text-align: center;
            z-index: 2;
            position: relative;
            flex-shrink: 0;
        }

        .sidebar a span {
            z-index: 2;
            position: relative;
        }

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
            background: rgba(255, 255, 255, 0.1);
            transition: left 0.3s ease;
            z-index: 1;
        }

        .sidebar a:hover {
            color: #fff;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transform: translateX(5px);
        }

        .sidebar a:hover::before { height: 70%; }
        .sidebar a:hover::after  { left: 0; }

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
            background-color: #fff;
        }

        .sidebar a.active i { transform: scale(1.1); }

        /* ========== OVERLAY ========== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.active { display: block; }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* ========== TOP NAVBAR ========== */
        .top-navbar {
            background-color: #fff;
            height: 65px;
            padding: 0 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 998;
            border-bottom: 3px solid var(--primary-color);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .menu-toggle {
            background: none;
            border: none;
            font-size: 1.6rem;
            color: #334155;
            cursor: pointer;
            display: none;
            transition: transform 0.3s;
            padding: 4px 8px;
            border-radius: 8px;
        }

        .menu-toggle:hover {
            background: #f1f5f9;
            transform: scale(1.1);
        }

        .navbar-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* ========== NOTIFICATION BELL ========== */
        .notification-bell {
            position: relative;
            cursor: pointer;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .notification-bell:hover {
            background: #f8fafc;
            border-color: var(--primary-color);
        }

        .notification-bell i {
            font-size: 1.4rem;
            color: #6c757d;
            transition: all 0.3s;
        }

        .notification-bell:hover i {
            color: var(--primary-color);
            transform: scale(1.1);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(245, 87, 108, 0.4);
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }

        /* ========== NOTIFICATION DROPDOWN ========== */
        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 12px;
            width: 380px;
            max-height: 500px;
            background: white;
            border-radius: 16px;
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
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .notification-dropdown-header {
            padding: 18px 20px;
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
            font-size: 1rem;
        }

        .notification-dropdown-body {
            max-height: 350px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 14px 18px;
            border-bottom: 1px solid #f1f3f5;
            transition: all 0.3s;
            cursor: pointer;
            display: flex;
            gap: 12px;
            text-decoration: none;
            color: inherit;
        }

        .notification-item:hover { background: #f8f9fa; }

        .notification-item.unread {
            background: linear-gradient(135deg, #f0f4ff 0%, #ffffff 100%);
            border-left: 4px solid #0d6efd;
        }

        .notification-item-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: white;
            flex-shrink: 0;
        }

        .notification-item-content { flex: 1; min-width: 0; }

        .notification-item-title {
            font-weight: 600;
            font-size: 0.88rem;
            color: #2d3748;
            margin-bottom: 3px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .notification-item-text {
            font-size: 0.78rem;
            color: #718096;
            margin-bottom: 3px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .notification-item-time {
            font-size: 0.72rem;
            color: #a0aec0;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .notification-dropdown-footer {
            padding: 12px 20px;
            border-top: 2px solid #f1f3f5;
            text-align: center;
            background: #f8fafc;
        }

        .notification-dropdown-footer a {
            color: #0d6efd;
            font-weight: 600;
            font-size: 0.88rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .notification-dropdown-footer a:hover { color: #0b5ed7; }

        .notification-empty {
            padding: 35px 20px;
            text-align: center;
            color: #a0aec0;
        }

        .notification-empty i {
            font-size: 2.5rem;
            margin-bottom: 12px;
            opacity: 0.5;
            display: block;
        }

        /* Gradient backgrounds */
        .bg-gradient-primary   { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bg-gradient-success   { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .bg-gradient-danger    { background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); }
        .bg-gradient-info      { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .bg-gradient-warning   { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .bg-gradient-secondary { background: linear-gradient(135deg, #a8caba 0%, #5d4e6d 100%); }

        /* ========== USER INFO ========== */
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 10px;
            border-radius: 10px;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .user-info:hover {
            background: #f8fafc;
            border-color: var(--primary-color);
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .user-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .user-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.88rem;
            white-space: nowrap;
        }

        /* ========== CONTENT AREA ========== */
        .content-area { padding: 25px; }

        /* ========== RESPONSIVE: TABLET & MOBILE (≤ 768px) ========== */
        @media (max-width: 768px) {
            /* Sidebar tersembunyi ke kiri, muncul saat toggle */
            .sidebar {
                left: calc(-1 * var(--sidebar-width));
            }

            .sidebar.active {
                left: 0;
                box-shadow: 4px 0 24px rgba(0, 0, 0, 0.2);
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .top-navbar {
                padding: 0 15px;
            }

            .user-name {
                display: none;
            }

            .notification-dropdown {
                width: 320px;
                right: 0;
            }
        }

        /* ========== RESPONSIVE: MOBILE KECIL (≤ 480px) ========== */
        @media (max-width: 480px) {
            .content-area {
                padding: 12px;
            }

            .notification-dropdown {
                width: calc(100vw - 20px);
                right: -10px;
            }

            .navbar-title {
                font-size: 0.9rem;
                max-width: 140px;
            }
        }
    </style>

    {{-- Custom styles dari halaman child --}}
    @stack('styles')
</head>

<body>
    {{-- Overlay untuk mobile --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- Sidebar --}}
    <div class="sidebar" id="sidebar">
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

        {{-- Top Navbar --}}
        <div class="top-navbar">
            <div class="navbar-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="navbar-title">@yield('page-title', 'Dashboard Mahasiswa')</h5>
            </div>

            <div class="navbar-right">
                {{-- Notification Bell --}}
                <div class="notification-bell" id="notificationBell">
                    <i class="bi bi-bell-fill"></i>
                    @if(isset($unreadNotifCount) && $unreadNotifCount > 0)
                    <span class="notification-badge" id="topBadge">{{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}</span>
                    @endif

                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-dropdown-header">
                            <h6><i class="bi bi-bell me-2"></i>Notifikasi</h6>
                            <small><span id="unreadCountText">{{ $unreadNotifCount ?? 0 }}</span> Belum Dibaca</small>
                        </div>
                        <div class="notification-dropdown-body" id="notificationList">
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
                <div class="user-info">
                    <div class="user-avatar">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Mahasiswa') }}&background=0d6efd&color=fff"
                             alt="User">
                    </div>
                    <span class="user-name">{{ Auth::user()->name ?? 'Mahasiswa' }}</span>
                </div>
            </div>
        </div>

        {{-- Konten Dinamis --}}
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    {{-- JS Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ========== SIDEBAR TOGGLE ==========
        const menuToggle = document.getElementById('menuToggle');
        const sidebar    = document.getElementById('sidebar');
        const overlay    = document.getElementById('sidebarOverlay');

        if (menuToggle) {
            menuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });
        }

        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
        }

        // Tutup sidebar saat link diklik di mobile
        if (window.innerWidth <= 768) {
            document.querySelectorAll('.sidebar a').forEach(link => {
                link.addEventListener('click', () => {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            });
        }

        // ========== NOTIFICATION DROPDOWN ==========
        const notificationBell     = document.getElementById('notificationBell');
        const notificationDropdown = document.getElementById('notificationDropdown');

        if (notificationBell) {
            notificationBell.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationDropdown.classList.toggle('show');
                if (notificationDropdown.classList.contains('show')) {
                    loadNotifications();
                }
            });
        }

        document.addEventListener('click', function(e) {
            if (notificationBell && !notificationBell.contains(e.target)) {
                notificationDropdown.classList.remove('show');
            }
        });

        function loadNotifications() {
            fetch('{{ route("mahasiswa.notifikasi.latest") }}')
                .then(response => response.json())
                .then(data => {
                    const notificationList = document.getElementById('notificationList');

                    if (data.success && data.notifikasi && data.notifikasi.length > 0) {
                        let html = '';
                        data.notifikasi.forEach(notif => {
                            const unreadClass = !notif.dibaca ? 'unread' : '';
                            const iconBg  = getNotificationBgClass(notif.tipe);
                            const icon    = getNotificationIcon(notif.tipe);
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
                                </a>`;
                        });
                        notificationList.innerHTML = html;
                        updateBadgeCount(data.unread_count || 0);
                    } else {
                        notificationList.innerHTML = `
                            <div class="notification-empty">
                                <i class="bi bi-bell-slash"></i>
                                <p class="mb-0">Tidak ada notifikasi</p>
                            </div>`;
                        updateBadgeCount(0);
                    }
                })
                .catch(() => {
                    document.getElementById('notificationList').innerHTML = `
                        <div class="notification-empty">
                            <i class="bi bi-exclamation-triangle"></i>
                            <p class="mb-0">Gagal memuat notifikasi</p>
                        </div>`;
                });
        }

        function updateBadgeCount(count) {
            const badges    = document.querySelectorAll('.notification-badge');
            const unreadTxt = document.getElementById('unreadCountText');
            badges.forEach(b => {
                b.textContent    = count > 9 ? '9+' : count;
                b.style.display  = count > 0 ? 'flex' : 'none';
            });
            if (unreadTxt) unreadTxt.textContent = count;
        }

        function getNotificationBgClass(tipe) {
            const map = {
                peminjaman_disetujui:   'bg-gradient-success',
                peminjaman_ditolak:     'bg-gradient-danger',
                perpanjangan_disetujui: 'bg-gradient-success',
                perpanjangan_ditolak:   'bg-gradient-danger',
                reminder_deadline:      'bg-gradient-warning',
                terlambat:              'bg-gradient-danger',
                pengembalian_sukses:    'bg-gradient-success',
                buku_tersedia:          'bg-gradient-info',
                denda_belum_dibayar:    'bg-gradient-warning',
                sistem:                 'bg-gradient-secondary',
            };
            return map[tipe] || 'bg-gradient-primary';
        }

        function getNotificationIcon(tipe) {
            const map = {
                peminjaman_disetujui:   'check-circle-fill',
                peminjaman_ditolak:     'x-circle-fill',
                perpanjangan_disetujui: 'check2-circle',
                perpanjangan_ditolak:   'x-octagon',
                reminder_deadline:      'alarm',
                terlambat:              'exclamation-triangle-fill',
                pengembalian_sukses:    'check-circle',
                buku_tersedia:          'bell-fill',
                denda_belum_dibayar:    'cash-coin',
                sistem:                 'info-circle-fill',
            };
            return map[tipe] || 'bell';
        }

        function getTimeAgo(dateString) {
            const s = Math.floor((new Date() - new Date(dateString)) / 1000);
            if (s < 60)     return 'Baru saja';
            if (s < 3600)   return Math.floor(s / 60) + ' menit lalu';
            if (s < 86400)  return Math.floor(s / 3600) + ' jam lalu';
            if (s < 604800) return Math.floor(s / 86400) + ' hari lalu';
            return new Date(dateString).toLocaleDateString('id-ID');
        }

        function truncate(str, len) {
            if (!str) return '';
            return str.length > len ? str.substring(0, len) + '...' : str;
        }

        function escapeHtml(text) {
            if (!text) return '';
            return text.replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
        }

        // Auto-refresh setiap 30 detik
        setInterval(() => {
            fetch('{{ route("mahasiswa.notifikasi.count") }}')
                .then(r => r.json())
                .then(d => { if (d.success) updateBadgeCount(d.count || 0); })
                .catch(() => {});
        }, 30000);
    </script>

    {{-- Custom scripts dari halaman child --}}
    @stack('scripts')
</body>
</html>