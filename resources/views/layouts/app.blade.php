<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">

    <title>{{ config('app.name', 'Admin Dashboard') }} - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <style>
        /* ========== CSS VARIABLES ========== */
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed: 72px;
            --header-height: 64px;
            --primary: #5e72e4;
            --primary-dark: #4a5cd0;
            --sidebar-bg: #16192c;
            --sidebar-hover: #1e2340;
            --sidebar-active-bg: rgba(94, 114, 228, 0.15);
            --sidebar-active-border: #5e72e4;
            --text-muted-sidebar: rgba(255,255,255,0.5);
            --transition: 0.25s ease;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f0f2f8;
            overflow-x: hidden;
            color: #2d3748;
        }

        /* ========== SIDEBAR ========== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            z-index: 1050;
            overflow-y: auto;
            overflow-x: hidden;
            transition: width var(--transition), left var(--transition), transform var(--transition);
            display: flex;
            flex-direction: column;
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(94,114,228,0.4); border-radius: 4px; }

        /* Brand */
        .sidebar-brand {
            padding: 20px 20px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            flex-shrink: 0;
            min-height: var(--header-height);
        }

        .sidebar-brand-icon {
            width: 36px;
            height: 36px;
            background: var(--primary);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-brand-icon i {
            color: white;
            font-size: 1.1rem;
        }

        .sidebar-brand-text {
            font-size: 1.05rem;
            font-weight: 700;
            color: white;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity var(--transition), width var(--transition);
        }

        /* Menu */
        .sidebar-scroll {
            flex: 1;
            padding: 12px 12px 20px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-scroll::-webkit-scrollbar { width: 0; }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 2px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            color: var(--text-muted-sidebar);
            text-decoration: none;
            border-radius: var(--radius-sm);
            transition: all var(--transition);
            white-space: nowrap;
            overflow: hidden;
            position: relative;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .sidebar-menu a i {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
            flex-shrink: 0;
            transition: color var(--transition);
        }

        .sidebar-menu a span.menu-label {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: opacity var(--transition);
        }

        .sidebar-menu a:hover {
            background: var(--sidebar-hover);
            color: rgba(255,255,255,0.9);
        }

        .sidebar-menu a:hover i {
            color: var(--primary);
        }

        .sidebar-menu a.active {
            background: var(--sidebar-active-bg);
            color: white;
            border-left: 3px solid var(--sidebar-active-border);
            padding-left: 9px;
        }

        .sidebar-menu a.active i {
            color: var(--primary);
        }

        .menu-section {
            padding: 16px 12px 6px;
            color: rgba(255,255,255,0.3);
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity var(--transition);
        }

        .menu-badge {
            margin-left: auto;
            flex-shrink: 0;
            background: #f5576c;
            color: white;
            padding: 2px 7px;
            border-radius: 20px;
            font-size: 0.68rem;
            font-weight: 700;
            line-height: 1.4;
            transition: opacity var(--transition);
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left var(--transition);
            display: flex;
            flex-direction: column;
        }

        /* ========== TOP NAVBAR ========== */
        .top-navbar {
            background: white;
            height: var(--header-height);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e8ecf4;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
        }

        .navbar-left h5 {
            font-size: 1rem;
            font-weight: 600;
            color: #1a202c;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .menu-toggle {
            background: none;
            border: 1px solid #e2e8f0;
            border-radius: var(--radius-sm);
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #4a5568;
            font-size: 1.1rem;
            transition: all var(--transition);
            flex-shrink: 0;
        }

        .menu-toggle:hover {
            background: #f7f8fc;
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Desktop toggle — always visible */
        .menu-toggle-desktop {
            display: flex;
        }

        /* Mobile toggle — only visible on small screens */
        .menu-toggle-mobile {
            display: none;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ========== NOTIFICATION ========== */
        .notif-btn {
            position: relative;
            width: 38px;
            height: 38px;
            border-radius: var(--radius-sm);
            background: #f7f8fc;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--transition);
            color: #4a5568;
            font-size: 1rem;
        }

        .notif-btn:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #f5576c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.62rem;
            font-weight: 700;
            border: 2px solid white;
        }

        .notification-dropdown {
            width: 360px;
            max-height: 480px;
            overflow-y: auto;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            border-radius: var(--radius-lg);
            border: 1px solid #e8ecf4;
        }

        .notification-dropdown::-webkit-scrollbar { width: 4px; }
        .notification-dropdown::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

        .notification-header {
            padding: 14px 18px;
            background: var(--sidebar-bg);
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            sticky: top;
        }

        .notification-item {
            padding: 14px 18px;
            border-bottom: 1px solid #f1f3f8;
            transition: background var(--transition);
            cursor: pointer;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .notification-item:hover { background: #f7f8fc; }

        .notification-item.unread {
            background: #f0f4ff;
            border-left: 3px solid var(--primary);
        }

        .notification-icon-small {
            width: 38px;
            height: 38px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notification-icon-small i { font-size: 1rem; color: white; }

        .notification-content { flex: 1; min-width: 0; }

        .notification-title {
            font-weight: 600;
            color: #1a202c;
            font-size: 0.85rem;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notification-text {
            color: #718096;
            font-size: 0.8rem;
            line-height: 1.4;
        }

        .notification-time {
            color: #a0aec0;
            font-size: 0.72rem;
            margin-top: 5px;
        }

        .notification-footer {
            padding: 12px 18px;
            background: #f7f8fc;
            text-align: center;
            border-radius: 0 0 var(--radius-lg) var(--radius-lg);
            border-top: 1px solid #e8ecf4;
        }

        .notification-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .empty-notification {
            padding: 32px 20px;
            text-align: center;
            color: #a0aec0;
        }

        .empty-notification i { font-size: 2rem; margin-bottom: 10px; opacity: 0.5; display: block; }
        .empty-notification p { font-size: 0.85rem; margin: 0; }

        /* ========== USER PROFILE ========== */
        .user-profile-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px 6px 6px;
            border-radius: var(--radius-md);
            border: 1px solid #e2e8f0;
            background: white;
            cursor: pointer;
            transition: all var(--transition);
        }

        .user-profile-btn:hover {
            background: #f7f8fc;
            border-color: var(--primary);
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .user-details {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .user-name {
            font-weight: 600;
            color: #1a202c;
            font-size: 0.85rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 120px;
            line-height: 1.2;
        }

        .user-role {
            font-size: 0.7rem;
            color: #718096;
            text-transform: capitalize;
            line-height: 1.2;
        }

        .user-profile-btn .bi-chevron-down {
            font-size: 0.7rem;
            color: #a0aec0;
            flex-shrink: 0;
        }

        /* ========== CONTENT ========== */
        .content-area {
            padding: 28px;
            flex: 1;
        }

        /* ========== DROPDOWN ========== */
        .dropdown-menu {
            border: 1px solid #e8ecf4;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            border-radius: var(--radius-md);
            padding: 8px;
            margin-top: 8px;
        }

        .dropdown-item {
            padding: 10px 14px;
            border-radius: var(--radius-sm);
            transition: all var(--transition);
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dropdown-item:hover {
            background: #f0f4ff;
            color: var(--primary);
        }

        .dropdown-item i { width: 16px; text-align: center; }

        /* ========== SIDEBAR OVERLAY ========== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.active { display: block; }

        /* ========== RESPONSIVE ========== */

        /* Tablet (768px - 1023px): sidebar narrows */
        @media (max-width: 1023px) and (min-width: 769px) {
            .content-area { padding: 20px; }
        }

        /* Mobile (≤768px): sidebar slides off-screen */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0 !important;
            }

            .menu-toggle-desktop { display: none; }
            .menu-toggle-mobile { display: flex; }

            .content-area { padding: 16px; }

            .notification-dropdown { width: 300px; }

            /* Hide user name text on very small screens, keep avatar */
            .user-details { display: none; }
            .user-profile-btn {
                padding: 5px;
                border-radius: 50%;
            }
            .user-profile-btn .bi-chevron-down { display: none; }

            .navbar-left h5 { font-size: 0.9rem; }

            .top-navbar { padding: 0 16px; }
        }

        /* Small mobile (≤480px) */
        @media (max-width: 480px) {
            .notification-dropdown {
                width: calc(100vw - 32px);
                right: -8px !important;
            }
        }

        /* Large screens: ensure name always visible */
        @media (min-width: 769px) {
            .user-details { display: flex !important; }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <span class="sidebar-brand-text">AdminPASIM</span>
        </div>

        <div class="sidebar-scroll">
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span class="menu-label">Dashboard</span>
                    </a>
                </li>

                <div class="menu-section">Management</div>

                <li>
                    <a href="{{ route('admin.users.index') }}"
                       class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span class="menu-label">Kelola User</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.mahasiswa.index') }}"
                       class="{{ request()->routeIs('admin.mahasiswa.*') ? 'active' : '' }}"
                       id="mahasiswaLink">
                        <i class="bi bi-person-badge-fill"></i>
                        <span class="menu-label">Data Mahasiswa</span>
                        @php
                            $mahasiswaBaru = \App\Models\Mahasiswa::whereDate('created_at', today())->count();
                        @endphp
                        @if($mahasiswaBaru > 0)
                            <span class="menu-badge" id="mahasiswaBadge">{{ $mahasiswaBaru }}</span>
                        @endif
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.buku.index') }}"
                       class="{{ request()->routeIs('admin.buku.*') ? 'active' : '' }}">
                        <i class="bi bi-book-fill"></i>
                        <span class="menu-label">Kelola Buku</span>
                    </a>
                </li>

                <div class="menu-section">Transaksi</div>

                <li>
                    <a href="{{ route('admin.peminjaman.index') }}"
                       class="{{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}"
                       id="peminjamanLink">
                        <i class="bi bi-journal-text"></i>
                        <span class="menu-label">Peminjaman</span>
                        @php
                            $peminjamanAktif = \App\Models\Peminjaman::where('status', 'dipinjam')->count();
                        @endphp
                        @if($peminjamanAktif > 0)
                            <span class="menu-badge" id="peminjamanBadge">{{ $peminjamanAktif }}</span>
                        @endif
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.perpanjangan.index') }}"
                       class="{{ request()->routeIs('admin.perpanjangan.*') ? 'active' : '' }}"
                       id="perpanjanganLink">
                        <i class="bi bi-calendar-check"></i>
                        <span class="menu-label">Perpanjangan</span>
                        @php
                            $perpanjanganMenunggu = \App\Models\Perpanjangan::where('status', 'menunggu')->count();
                        @endphp
                        @if($perpanjanganMenunggu > 0)
                            <span class="menu-badge" id="perpanjanganBadge">{{ $perpanjanganMenunggu }}</span>
                        @endif
                    </a>
                </li>

                <div class="menu-section">Komunikasi</div>

                <li>
                    <a href="{{ route('admin.notifikasi.index') }}"
                       class="{{ request()->routeIs('admin.notifikasi.*') ? 'active' : '' }}"
                       id="notifikasiLink">
                        <i class="bi bi-bell-fill"></i>
                        <span class="menu-label">Notifikasi</span>
                        @php
                            $unreadNotifCount = \App\Models\Notifikasi::where('user_id', auth()->id())
                                ->where('dibaca', false)
                                ->count();
                        @endphp
                        @if($unreadNotifCount > 0)
                            <span class="menu-badge" id="notifikasiBadge">{{ $unreadNotifCount > 99 ? '99+' : $unreadNotifCount }}</span>
                        @endif
                    </a>
                </li>

                <div class="menu-section">Laporan</div>

                <li>
                    <a href="{{ route('admin.laporan.index') }}"
                       class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text-fill"></i>
                        <span class="menu-label">Laporan Petugas</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <main class="main-content" id="mainContent">
        <nav class="top-navbar">
            <div class="navbar-left">
                <!-- Mobile toggle -->
                <button class="menu-toggle menu-toggle-mobile" id="menuToggleMobile" aria-label="Toggle menu">
                    <i class="bi bi-list"></i>
                </button>
                <!-- Desktop toggle -->
                <button class="menu-toggle menu-toggle-desktop" id="menuToggleDesktop" aria-label="Toggle sidebar">
                    <i class="bi bi-layout-sidebar"></i>
                </button>
                <h5>@yield('page-title', 'Dashboard')</h5>
            </div>

            <div class="navbar-right">
                <!-- Notification -->
                <div class="dropdown">
                    <div class="notif-btn" data-bs-toggle="dropdown" id="notificationIcon" role="button" aria-label="Notifikasi">
                        <i class="bi bi-bell-fill"></i>
                        <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end notification-dropdown p-0">
                        <div class="notification-header">
                            <span>Notifikasi</span>
                            <button class="btn btn-sm" onclick="markAllAsRead()"
                                style="font-size: 0.72rem; padding: 3px 8px; background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.3); border-radius: 6px;">
                                <i class="bi bi-check-all"></i> Tandai Semua
                            </button>
                        </div>
                        <div id="notificationList">
                            <div class="empty-notification">
                                <i class="bi bi-bell-slash"></i>
                                <p>Tidak ada notifikasi</p>
                            </div>
                        </div>
                        <div class="notification-footer">
                            <a href="{{ route('admin.notifikasi.index') }}">Lihat Semua Notifikasi →</a>
                        </div>
                    </div>
                </div>

                <!-- User Profile -->
                <div class="dropdown">
                    <div class="user-profile-btn" data-bs-toggle="dropdown" role="button">
                        <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
                        <div class="user-details">
                            <span class="user-name">{{ Auth::user()->name ?? 'Admin' }}</span>
                            <span class="user-role">{{ ucfirst(Auth::user()->role ?? 'Administrator') }}</span>
                        </div>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <div style="padding: 12px 14px 10px; border-bottom: 1px solid #f1f3f8;">
                                <div style="font-weight: 600; font-size: 0.9rem; color: #1a202c;">{{ Auth::user()->name ?? 'Admin' }}</div>
                                <div style="font-size: 0.75rem; color: #718096;">{{ Auth::user()->email ?? '' }}</div>
                            </div>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('admin.profile.index') }}">
                            <i class="bi bi-person-fill"></i> Profil Saya</a></li>
                        <li><hr class="dropdown-divider" style="margin: 6px 8px;"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger" style="width: 100%; background: none; border: none; text-align: left; cursor: pointer;">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
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

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ========== SIDEBAR TOGGLE ==========
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const menuToggleMobile = document.getElementById('menuToggleMobile');
        const menuToggleDesktop = document.getElementById('menuToggleDesktop');

        let sidebarCollapsed = false;

        // Desktop toggle: collapse sidebar
        if (menuToggleDesktop) {
            menuToggleDesktop.addEventListener('click', function () {
                sidebarCollapsed = !sidebarCollapsed;

                if (sidebarCollapsed) {
                    sidebar.style.width = 'var(--sidebar-collapsed)';
                    mainContent.style.marginLeft = 'var(--sidebar-collapsed)';
                    document.querySelectorAll('.menu-label, .sidebar-brand-text, .menu-section, .menu-badge').forEach(el => {
                        el.style.opacity = '0';
                        el.style.width = '0';
                        el.style.overflow = 'hidden';
                    });
                } else {
                    sidebar.style.width = 'var(--sidebar-width)';
                    mainContent.style.marginLeft = 'var(--sidebar-width)';
                    document.querySelectorAll('.menu-label, .sidebar-brand-text, .menu-section, .menu-badge').forEach(el => {
                        el.style.opacity = '';
                        el.style.width = '';
                        el.style.overflow = '';
                    });
                }
            });
        }

        // Mobile toggle: slide sidebar in/out
        if (menuToggleMobile) {
            menuToggleMobile.addEventListener('click', function () {
                sidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function () {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            });
        }

        // Auto-close sidebar on mobile after clicking a menu item
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            link.addEventListener('click', function () {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                }
            });
        });

        // ========== NOTIFICATION SYSTEM ==========
        document.addEventListener('DOMContentLoaded', function () {
            loadNotifications();
            setInterval(loadNotifications, 30000);
        });

        function loadNotifications() {
            const baseUrl = document.querySelector('meta[name="base-url"]')?.content || '';

            fetch(`${baseUrl}/admin/notifikasi/latest`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                return response.json();
            })
            .then(data => updateNotificationUI(data.notifikasi, data.count))
            .catch(error => console.error('Error loading notifications:', error));
        }

        function updateNotificationUI(notifikasi, count) {
            const badge = document.getElementById('notificationBadge');
            const list = document.getElementById('notificationList');
            if (!badge || !list) return;

            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = count > 0 ? 'flex' : 'none';

            if (!notifikasi || notifikasi.length === 0) {
                list.innerHTML = `<div class="empty-notification"><i class="bi bi-bell-slash"></i><p>Tidak ada notifikasi</p></div>`;
                return;
            }

            list.innerHTML = notifikasi.map(n => {
                const iconBg = getNotificationColor(n.tipe);
                const icon = getNotificationIcon(n.tipe);
                const timeAgo = formatTimeAgo(n.created_at);
                const unreadClass = !n.dibaca ? 'unread' : '';
                return `
                    <div class="notification-item ${unreadClass}" onclick="viewNotification(${n.id})">
                        <div class="notification-icon-small" style="background: ${iconBg}">
                            <i class="bi bi-${icon}"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">${escapeHtml(n.judul)}</div>
                            <div class="notification-text">${escapeHtml(truncateText(n.isi, 60))}</div>
                            <div class="notification-time"><i class="bi bi-clock" style="font-size:0.65rem;"></i> ${timeAgo}</div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function getNotificationColor(tipe) {
            const colors = {
                'peminjaman_baru': '#667eea',
                'peminjaman_disetujui': '#11998e',
                'peminjaman_ditolak': '#eb3349',
                'perpanjangan_baru': '#4facfe',
                'perpanjangan_disetujui': '#11998e',
                'perpanjangan_ditolak': '#eb3349',
                'reminder_deadline': '#fa709a',
                'terlambat': '#f5a623',
                'user_baru': '#667eea',
                'buku_baru': '#f093fb',
                'sistem': '#a8caba'
            };
            return colors[tipe] || '#667eea';
        }

        function getNotificationIcon(tipe) {
            const icons = {
                'peminjaman_baru': 'book-fill',
                'peminjaman_disetujui': 'check-circle-fill',
                'peminjaman_ditolak': 'x-circle-fill',
                'perpanjangan_baru': 'arrow-clockwise',
                'perpanjangan_disetujui': 'check2-circle',
                'perpanjangan_ditolak': 'x-octagon',
                'reminder_deadline': 'alarm',
                'terlambat': 'exclamation-triangle-fill',
                'user_baru': 'person-plus-fill',
                'buku_baru': 'journal-plus',
                'sistem': 'info-circle-fill'
            };
            return icons[tipe] || 'bell';
        }

        function formatTimeAgo(dateString) {
            const date = new Date(dateString);
            const seconds = Math.floor((new Date() - date) / 1000);
            const intervals = { tahun: 31536000, bulan: 2592000, minggu: 604800, hari: 86400, jam: 3600, menit: 60 };
            for (const [name, s] of Object.entries(intervals)) {
                const interval = Math.floor(seconds / s);
                if (interval >= 1) return `${interval} ${name} lalu`;
            }
            return 'Baru saja';
        }

        function truncateText(text, max) {
            if (!text) return '';
            return text.length <= max ? text : text.substr(0, max) + '...';
        }

        function escapeHtml(text) {
            if (!text) return '';
            return text.toString().replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
        }

        function viewNotification(id) {
            markAsRead(id);
            const baseUrl = document.querySelector('meta[name="base-url"]')?.content || '';
            window.location.href = `${baseUrl}/admin/notifikasi/${id}`;
        }

        function markAsRead(id) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const baseUrl = document.querySelector('meta[name="base-url"]')?.content || '';
            fetch(`${baseUrl}/admin/notifikasi/${id}/baca`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            }).then(r => r.json()).then(d => { if (d.success) loadNotifications(); })
              .catch(e => console.error(e));
        }

        function markAllAsRead() {
            if (!confirm('Tandai semua notifikasi sebagai dibaca?')) return;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const baseUrl = document.querySelector('meta[name="base-url"]')?.content || '';
            fetch(`${baseUrl}/admin/notifikasi/baca-semua`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            }).then(r => r.json()).then(d => { if (d.success) loadNotifications(); })
              .catch(e => console.error(e));
        }

        // ========== BADGE MANAGEMENT ==========
        document.addEventListener('DOMContentLoaded', function () {
            manageBadge('peminjamanBadge', 'peminjamanLink', 'admin_lastSeenPeminjamanCount');
            manageBadge('perpanjanganBadge', 'perpanjanganLink', 'admin_lastSeenPerpanjanganCount');
            manageBadge('notifikasiBadge', 'notifikasiLink', 'admin_lastSeenNotifikasiCount');
            manageMahasiswaBadge();
        });

        function manageBadge(badgeId, linkId, storageKey) {
            const badge = document.getElementById(badgeId);
            const link = document.getElementById(linkId);
            if (!badge) return;
            const currentCount = parseInt(badge.textContent.replace('+', '')) || 0;
            const lastSeen = parseInt(localStorage.getItem(storageKey)) || 0;
            if (currentCount <= lastSeen) badge.style.display = 'none';
            if (link) link.addEventListener('click', () => {
                localStorage.setItem(storageKey, currentCount);
                badge.style.display = 'none';
            });
        }

        function manageMahasiswaBadge() {
            const badge = document.getElementById('mahasiswaBadge');
            const link = document.getElementById('mahasiswaLink');
            if (!badge) return;
            const currentCount = parseInt(badge.textContent) || 0;
            const today = new Date().toDateString();
            if (localStorage.getItem('admin_mahasiswaLastSeenDate') !== today) {
                localStorage.removeItem('admin_mahasiswaLastSeenCount');
                localStorage.setItem('admin_mahasiswaLastSeenDate', today);
            }
            const lastSeen = parseInt(localStorage.getItem('admin_mahasiswaLastSeenCount')) || 0;
            if (currentCount <= lastSeen) badge.style.display = 'none';
            if (link) link.addEventListener('click', () => {
                localStorage.setItem('admin_mahasiswaLastSeenCount', currentCount);
                localStorage.setItem('admin_mahasiswaLastSeenDate', today);
                badge.style.display = 'none';
            });
        }

        function resetAdminBadges() {
            ['admin_lastSeenPeminjamanCount','admin_lastSeenPerpanjanganCount','admin_lastSeenNotifikasiCount','admin_mahasiswaLastSeenCount','admin_mahasiswaLastSeenDate']
                .forEach(k => localStorage.removeItem(k));
            location.reload();
        }
    </script>

    @stack('scripts')
</body>

</html>