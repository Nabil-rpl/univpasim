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

        .notification-btn {
            position: relative;
            background: #f1f5f9;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 1.3rem;
            color: #334155;
        }

        .notification-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .notification-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 20px;
            height: 20px;
            background: #ef4444;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            color: white;
            font-weight: bold;
            border: 2px solid white;
        }

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

            .notification-btn {
                width: 40px;
                height: 40px;
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

        .sidebar-menu li:nth-child(1) {
            animation-delay: 0.1s;
        }

        .sidebar-menu li:nth-child(2) {
            animation-delay: 0.2s;
        }

        .sidebar-menu li:nth-child(3) {
            animation-delay: 0.3s;
        }

        .sidebar-menu li:nth-child(4) {
            animation-delay: 0.4s;
        }

        .sidebar-menu li:nth-child(5) {
            animation-delay: 0.5s;
        }

        .sidebar-menu li:nth-child(6) {
            animation-delay: 0.6s;
        }

        .sidebar-menu li:nth-child(7) {
            animation-delay: 0.7s;
        }

        .sidebar-menu li:nth-child(8) {
            animation-delay: 0.8s;
        }
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
                    class="{{ request()->routeIs('petugas.peminjaman.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-bookmark-fill"></i>
                    <span>Peminjaman</span>
                    @php
                        $peminjamanAktif = \App\Models\Peminjaman::where('status', 'dipinjam')->count();
                    @endphp
                    @if ($peminjamanAktif > 0)
                        <span class="badge">{{ $peminjamanAktif }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('petugas.pengembalian.index') }}"
                    class="{{ request()->routeIs('petugas.pengembalian.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-return-left"></i> <span>Pengembalian</span>
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
                <button class="notification-btn">
                    <i class="bi bi-bell-fill"></i>
                    @if ($peminjamanAktif > 0)
                        <span class="notification-badge">{{ $peminjamanAktif }}</span>
                    @endif
                </button>

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
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });

        if (window.innerWidth <= 768) {
            document.querySelectorAll('.sidebar-menu a').forEach(link => {
                link.addEventListener('click', () => {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                });
            });
        }

        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.getAttribute('href') === '#') {
                    e.preventDefault();
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>