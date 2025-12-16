<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Perpustakaan') }} - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @stack('styles')

    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-width: 280px;
            --header-height: 70px;
            --primary-color: #2563eb;
            --primary-dark: #1e40af;
            --secondary-color: #f59e0b;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --accent-color: #8b5cf6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            overflow-x: hidden;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            padding-top: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.12);
        }

        .sidebar-brand {
            padding: 25px 20px;
            background: rgba(37, 99, 235, 0.1);
            border-bottom: 2px solid rgba(37, 99, 235, 0.2);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .brand-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .brand-text h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 1.3rem;
            letter-spacing: -0.5px;
        }

        .brand-text p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8rem;
            margin: 2px 0 0 0;
            font-weight: 500;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 15px;
        }

        .menu-section-title {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px 10px 10px;
            margin-top: 10px;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 14px 15px;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            transition: all 0.3s;
            border-radius: 10px;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .sidebar-menu a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: var(--primary-color);
            transform: scaleY(0);
            transition: transform 0.3s;
        }

        .sidebar-menu a:hover {
            background: rgba(37, 99, 235, 0.15);
            color: white;
            padding-left: 20px;
        }

        .sidebar-menu a.active {
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.2) 0%, rgba(139, 92, 246, 0.1) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .sidebar-menu a.active::before {
            transform: scaleY(1);
        }

        .sidebar-menu a i {
            margin-right: 15px;
            font-size: 1.3rem;
            width: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-menu a .badge {
            margin-left: auto;
            background: var(--secondary-color);
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 0.7rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
        }

        .top-navbar {
            background: white;
            height: var(--header-height);
            padding: 0 35px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 999;
            border-bottom: 3px solid var(--primary-color);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .menu-toggle {
            background: none;
            border: none;
            font-size: 1.6rem;
            color: #334155;
            cursor: pointer;
            display: none;
            transition: transform 0.3s;
        }

        .menu-toggle:hover {
            transform: scale(1.1);
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title h5 {
            margin: 0;
            color: #1e293b;
            font-weight: 700;
            font-size: 1.4rem;
        }

        .page-title .title-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .notification-bell {
            position: relative;
            cursor: pointer;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .notification-bell:hover {
            background: #f8fafc;
            border-color: var(--primary-color);
        }

        .notification-bell i {
            font-size: 1.5rem;
            color: #64748b;
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

        .notification-dropdown {
            width: 380px;
            max-height: 500px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            border-radius: 16px;
            padding: 0;
            margin-top: 15px;
            border: none;
        }

        .notification-dropdown-header {
            padding: 20px;
            border-bottom: 2px solid #f1f3f5;
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: white;
            border-radius: 16px 16px 0 0;
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
            background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
            border-left: 4px solid #2563eb;
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
            color: #1e293b;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .notification-item-text {
            font-size: 0.8rem;
            color: #64748b;
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
            background: #f8fafc;
            border-radius: 0 0 16px 16px;
        }

        .notification-dropdown-footer a {
            color: #2563eb;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .notification-dropdown-footer a:hover {
            color: #1e40af;
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

        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bg-gradient-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .bg-gradient-danger { background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); }
        .bg-gradient-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .bg-gradient-warning { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .bg-gradient-secondary { background: linear-gradient(135deg, #a8caba 0%, #5d4e6d 100%); }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 12px;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .user-info:hover {
            background: #f8fafc;
            border-color: var(--primary-color);
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 0.95rem;
        }

        .user-role {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
            background: #e0f2fe;
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 2px;
        }

        .content-area {
            padding: 35px;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
            border-radius: 12px;
            padding: 10px;
            margin-top: 10px;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }

        .dropdown-item:hover {
            background: #f1f5f9;
            transform: translateX(5px);
        }

        .dropdown-item i {
            width: 20px;
        }

        @media (max-width: 768px) {
            .sidebar {
                left: calc(-1 * var(--sidebar-width));
            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .user-details {
                display: none;
            }

            .page-title .title-icon {
                display: none;
            }

            .notification-dropdown {
                width: 320px;
                right: 0 !important;
                left: auto !important;
            }
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.7);
            z-index: 999;
            backdrop-filter: blur(4px);
        }

        .sidebar-overlay.active {
            display: block;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(37, 99, 235, 0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(37, 99, 235, 0.5);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .sidebar-menu li {
            animation: slideIn 0.3s ease-out forwards;
            opacity: 0;
        }

        .sidebar-menu li:nth-child(1) { animation-delay: 0.1s; }
        .sidebar-menu li:nth-child(2) { animation-delay: 0.2s; }
        .sidebar-menu li:nth-child(3) { animation-delay: 0.3s; }
        .sidebar-menu li:nth-child(4) { animation-delay: 0.4s; }
        .sidebar-menu li:nth-child(5) { animation-delay: 0.5s; }
        .sidebar-menu li:nth-child(6) { animation-delay: 0.6s; }
        .sidebar-menu li:nth-child(7) { animation-delay: 0.7s; }
        .sidebar-menu li:nth-child(8) { animation-delay: 0.8s; }
        .sidebar-menu li:nth-child(9) { animation-delay: 0.9s; }
    </style>
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="bi bi-book-half"></i>
            </div>
            <div class="brand-text">
                <h4>Perpustakaan</h4>
                <p>Sistem Manajemen</p>
            </div>
        </div>

        <ul class="sidebar-menu">
            <div class="menu-section-title">Menu Utama</div>
            <li>
                <a href="{{ route('petugas.dashboard') }}"
                    class="{{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('petugas.buku.index') }}"
                    class="{{ request()->routeIs('petugas.buku.*') ? 'active' : '' }}">
                    <i class="bi bi-book-fill"></i> <span>Manajemen Buku</span>
                </a>
            </li>
            <li>
    <a href="{{ route('petugas.peminjaman.index') }}"
        class="{{ request()->routeIs('petugas.peminjaman.*') ? 'active' : '' }}"
        id="peminjamanLink">
        <i class="bi bi-journal-bookmark-fill"></i>
        <span>Peminjaman</span>
        @php
            $peminjamanAktif = \App\Models\Peminjaman::where('status', 'dipinjam')->count();
        @endphp
        @if ($peminjamanAktif > 0)
            <span class="badge" id="peminjamanBadge">{{ $peminjamanAktif }}</span>
        @endif
    </a>
</li>
            <li>
                <a href="{{ route('petugas.pengembalian.index') }}"
                    class="{{ request()->routeIs('petugas.pengembalian.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-return-left"></i> <span>Pengembalian</span>
                </a>
            </li>
            
            <li>
    <a href="{{ route('petugas.perpanjangan.index') }}"
        class="{{ request()->routeIs('petugas.perpanjangan.*') ? 'active' : '' }}"
        id="perpanjanganLink">
        <i class="bi bi-arrow-clockwise"></i>
        <span>Perpanjangan</span>
        @php
            $perpanjanganMenunggu = \App\Models\Perpanjangan::where('status', 'menunggu')->count();
        @endphp
        @if ($perpanjanganMenunggu > 0)
            <span class="badge" id="perpanjanganBadge">{{ $perpanjanganMenunggu }}</span>
        @endif
    </a>
</li>

            <li>
    <a href="{{ route('petugas.notifikasi.index') }}"
        class="{{ request()->routeIs('petugas.notifikasi.*') ? 'active' : '' }}"
        id="notifikasiLink">
        <i class="bi bi-bell"></i>
        <span>Notifikasi</span>
        @php
            $unreadNotifCount = \App\Models\Notifikasi::where('user_id', auth()->id())
                ->where('dibaca', false)
                ->count();
        @endphp
        @if ($unreadNotifCount > 0)
            <span class="badge" id="notifikasiBadge">{{ $unreadNotifCount > 99 ? '99+' : $unreadNotifCount }}</span>
        @endif
    </a>
</li>

            <div class="menu-section-title">Lainnya</div>
            <li>
                <a href="{{ route('petugas.laporan.index') }}"
                    class="{{ request()->routeIs('petugas.laporan.*') ? 'active' : '' }}">
                    <i class="bi bi-file-text-fill"></i> <span>Laporan</span>
                </a>
            </li>
        
            <li>
                <a href="{{ route('logout') }}" class="text-danger"
                    onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                    <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                </a>

                <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Navbar -->
        <nav class="top-navbar">
            <div class="navbar-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="page-title">
                    <div class="title-icon">
                        <i class="bi bi-grid-1x2-fill"></i>
                    </div>
                    <h5>@yield('page-title', 'Dashboard')</h5>
                </div>
            </div>

            <div class="navbar-right">
                <div class="dropdown">
                    <div class="notification-bell" data-bs-toggle="dropdown" id="notificationBell">
                        <i class="bi bi-bell-fill"></i>
                        @php
                            $unreadNotifCount = \App\Models\Notifikasi::where('user_id', auth()->id())
                                ->where('dibaca', false)
                                ->count();
                        @endphp
                        @if ($unreadNotifCount > 0)
                            <span class="notification-badge">{{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}</span>
                        @endif
                    </div>

                    <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                        <div class="notification-dropdown-header">
                            <h6><i class="bi bi-bell me-2"></i>Notifikasi</h6>
                            <small>{{ $unreadNotifCount }} Belum Dibaca</small>
                        </div>
                        <div class="notification-dropdown-body" id="notificationList">
                            <div class="text-center py-3">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="notification-dropdown-footer">
                            <a href="{{ route('petugas.notifikasi.index') }}">
                                Lihat Semua Notifikasi
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="dropdown">
                    <div class="user-info" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="user-details">
                            <span class="user-name">{{ Auth::user()->name ?? 'Petugas' }}</span>
                            <span class="user-role">{{ ucfirst(Auth::user()->role ?? 'Petugas') }}</span>
                        </div>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('petugas.profile.index') }}">
                                <i class="bi bi-person-circle"></i> Profil Saya
                            </a>
                        </li>
                       
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="content-area">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Sidebar Toggle
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });
    }

    if (window.innerWidth <= 768) {
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            link.addEventListener('click', () => {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            });
        });
    }

    // ========== BADGE PEMINJAMAN - HILANG SETELAH DIKLIK ==========
document.addEventListener('DOMContentLoaded', function() {
    const peminjamanLink = document.getElementById('peminjamanLink');
    const peminjamanBadge = document.getElementById('peminjamanBadge');

    if (peminjamanBadge) {
        const currentCount = parseInt(peminjamanBadge.textContent) || 0;
        const lastSeenCount = parseInt(localStorage.getItem('lastSeenPeminjamanCount')) || 0;

        // Sembunyikan badge jika jumlah sama dengan terakhir dilihat
        if (currentCount <= lastSeenCount) {
            peminjamanBadge.style.display = 'none';
        }

        // Saat link diklik, simpan jumlah saat ini dan sembunyikan badge
        if (peminjamanLink) {
            peminjamanLink.addEventListener('click', function() {
                localStorage.setItem('lastSeenPeminjamanCount', currentCount);
                peminjamanBadge.style.display = 'none';
            });
        }
    }
});

// ========== BADGE PERPANJANGAN - HILANG SETELAH DIKLIK ==========
document.addEventListener('DOMContentLoaded', function() {
    const perpanjanganLink = document.getElementById('perpanjanganLink');
    const perpanjanganBadge = document.getElementById('perpanjanganBadge');

    if (perpanjanganBadge) {
        const currentCount = parseInt(perpanjanganBadge.textContent) || 0;
        const lastSeenCount = parseInt(localStorage.getItem('lastSeenPerpanjanganCount')) || 0;

        // Sembunyikan badge jika jumlah sama dengan terakhir dilihat
        if (currentCount <= lastSeenCount) {
            perpanjanganBadge.style.display = 'none';
        }

        // Saat link diklik, simpan jumlah saat ini dan sembunyikan badge
        if (perpanjanganLink) {
            perpanjanganLink.addEventListener('click', function() {
                localStorage.setItem('lastSeenPerpanjanganCount', currentCount);
                perpanjanganBadge.style.display = 'none';
            });
        }
    }
});

// ========== BADGE NOTIFIKASI - HILANG SETELAH DIKLIK ==========
document.addEventListener('DOMContentLoaded', function() {
    const notifikasiLink = document.getElementById('notifikasiLink');
    const notifikasiBadge = document.getElementById('notifikasiBadge');

    if (notifikasiBadge) {
        const currentCount = parseInt(notifikasiBadge.textContent.replace('+', '')) || 0;
        const lastSeenCount = parseInt(localStorage.getItem('lastSeenNotifikasiCount')) || 0;

        // Sembunyikan badge jika jumlah sama atau lebih kecil dari terakhir dilihat
        if (currentCount <= lastSeenCount) {
            notifikasiBadge.style.display = 'none';
        }

        // Saat link diklik, simpan jumlah saat ini dan sembunyikan badge
        if (notifikasiLink) {
            notifikasiLink.addEventListener('click', function() {
                localStorage.setItem('lastSeenNotifikasiCount', currentCount);
                notifikasiBadge.style.display = 'none';
            });
        }
    }
});

// Fungsi reset badge (opsional, untuk testing)
function resetAllBadges() {
    localStorage.removeItem('lastSeenPeminjamanCount');
    localStorage.removeItem('lastSeenPerpanjanganCount');
    localStorage.removeItem('lastSeenNotifikasiCount');
    location.reload();
}

    // Load Notifications via AJAX
    document.getElementById('notificationBell').addEventListener('click', function() {
        loadNotifications();
    });

    function loadNotifications() {
        fetch('/petugas/notifikasi/latest')
            .then(response => response.json())
            .then(data => {
                const notificationList = document.getElementById('notificationList');
                
                if (data.notifikasi && data.notifikasi.length > 0) {
                    let html = '';
                    data.notifikasi.forEach(notif => {
                        const unreadClass = !notif.dibaca ? 'unread' : '';
                        const iconBg = getNotificationBgClass(notif.tipe);
                        const icon = getNotificationIcon(notif.tipe);
                        const timeAgo = getTimeAgo(notif.created_at);
                        
                        html += `
                            <a href="${notif.url || '#'}" class="notification-item ${unreadClass}" data-id="${notif.id}">
                                <div class="notification-item-icon ${iconBg}">
                                    <i class="bi ${icon}"></i>
                                </div>
                                <div class="notification-item-content">
                                    <div class="notification-item-title">${notif.judul}</div>
                                    <div class="notification-item-text">${notif.pesan}</div>
                                    <div class="notification-item-time">
                                        <i class="bi bi-clock"></i> ${timeAgo}
                                    </div>
                                </div>
                            </a>
                        `;
                    });
                    notificationList.innerHTML = html;
                    
                    // Mark as read when clicked
                    document.querySelectorAll('.notification-item').forEach(item => {
                        item.addEventListener('click', function() {
                            const notifId = this.dataset.id;
                            markAsRead(notifId);
                        });
                    });
                } else {
                    notificationList.innerHTML = `
                        <div class="notification-empty">
                            <i class="bi bi-bell-slash"></i>
                            <p>Tidak ada notifikasi</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                document.getElementById('notificationList').innerHTML = `
                    <div class="notification-empty">
                        <i class="bi bi-exclamation-triangle"></i>
                        <p>Gagal memuat notifikasi</p>
                    </div>
                `;
            });
    }

    function getNotificationBgClass(tipe) {
        const bgClasses = {
            'peminjaman_baru': 'bg-gradient-info',
            'perpanjangan_baru': 'bg-gradient-warning',
            'user_baru': 'bg-gradient-success',
            'buku_baru': 'bg-gradient-primary',
            'stok_menipis': 'bg-gradient-danger',
            'pengembalian': 'bg-gradient-success',
            'keterlambatan': 'bg-gradient-danger',
            'perpanjangan': 'bg-gradient-warning',
            'persetujuan': 'bg-gradient-primary',
            'penolakan': 'bg-gradient-danger',
            'pengingat': 'bg-gradient-warning',
            'sistem': 'bg-gradient-secondary'
        };
        return bgClasses[tipe] || 'bg-gradient-secondary';
    }

    function getNotificationIcon(tipe) {
        const icons = {
            'peminjaman_baru': 'bi-journal-plus',
            'perpanjangan_baru': 'bi-arrow-clockwise',
            'user_baru': 'bi-person-plus-fill',
            'buku_baru': 'bi-book-fill',
            'stok_menipis': 'bi-exclamation-triangle-fill',
            'pengembalian': 'bi-arrow-return-left',
            'keterlambatan': 'bi-exclamation-triangle-fill',
            'perpanjangan': 'bi-arrow-clockwise',
            'persetujuan': 'bi-check-circle-fill',
            'penolakan': 'bi-x-circle-fill',
            'pengingat': 'bi-bell-fill',
            'sistem': 'bi-gear-fill'
        };
        return icons[tipe] || 'bi-info-circle-fill';
    }

    function getTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);
        
        if (seconds < 60) return 'Baru saja';
        if (seconds < 3600) return Math.floor(seconds / 60) + ' menit lalu';
        if (seconds < 86400) return Math.floor(seconds / 3600) + ' jam lalu';
        if (seconds < 604800) return Math.floor(seconds / 86400) + ' hari lalu';
        
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    function markAsRead(notifId) {
        fetch(`/petugas/notifikasi/${notifId}/mark-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const badge = document.querySelector('.notification-badge');
                if (badge) {
                    const currentCount = parseInt(badge.textContent);
                    const newCount = currentCount - 1;
                    if (newCount > 0) {
                        badge.textContent = newCount > 9 ? '9+' : newCount;
                    } else {
                        badge.remove();
                    }
                }
            }
        })
        .catch(error => console.error('Error marking notification as read:', error));
    }

    // Auto-refresh notifications every 60 seconds
    setInterval(() => {
        fetch('/petugas/notifikasi/count')
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('.notification-badge');
                if (data.count > 0) {
                    if (badge) {
                        badge.textContent = data.count > 9 ? '9+' : data.count;
                    } else {
                        const bellIcon = document.querySelector('.notification-bell');
                        const newBadge = document.createElement('span');
                        newBadge.className = 'notification-badge';
                        newBadge.textContent = data.count > 9 ? '9+' : data.count;
                        bellIcon.appendChild(newBadge);
                    }
                } else if (badge) {
                    badge.remove();
                }
            })
            .catch(error => console.error('Error checking notification count:', error));
    }, 60000);
</script>

    @stack('scripts')

</body>

</html>