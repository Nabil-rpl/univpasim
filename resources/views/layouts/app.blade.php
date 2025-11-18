<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Admin Dashboard') }} - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-width: 280px;
            --header-height: 70px;
            --primary-color: #4e73df;
            --sidebar-bg: #1a1f36;
            --sidebar-hover: #252b48;
            --accent-color: #5e72e4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1a1f36 0%, #0f1419 100%);
            padding-top: 20px;
            transition: all 0.3s;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.2);
        }

        .sidebar-brand {
            padding: 0 25px 25px;
            text-align: center;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 10px;
        }

        .sidebar-brand h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 1.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .sidebar-brand h4 i {
            font-size: 1.8rem;
            color: var(--accent-color);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 15px;
        }

        .sidebar-menu li {
            margin-bottom: 6px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 14px 18px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
            font-weight: 500;
            border: 2px solid transparent;
        }

        .sidebar-menu a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 0%;
            width: 4px;
            background: linear-gradient(180deg, #5e72e4 0%, #825ee4 100%);
            border-radius: 0 4px 4px 0;
            transition: height 0.3s ease;
        }

        .sidebar-menu a::after {
            content: '';
            position: absolute;
            right: -100%;
            top: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(94, 114, 228, 0.2));
            transition: right 0.4s ease;
        }

        .sidebar-menu a:hover {
            background: var(--sidebar-hover);
            color: white;
            transform: translateX(8px);
            border: 2px solid rgba(94, 114, 228, 0.3);
        }

        .sidebar-menu a:hover::before {
            height: 70%;
        }

        .sidebar-menu a:hover::after {
            right: 0;
        }

        .sidebar-menu a.active {
            background: linear-gradient(90deg, rgba(94, 114, 228, 0.2) 0%, rgba(94, 114, 228, 0.05) 100%);
            color: white;
            border: 2px solid rgba(94, 114, 228, 0.4);
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(94, 114, 228, 0.3);
        }

        .sidebar-menu a.active::before {
            height: 80%;
            width: 5px;
        }

        .sidebar-menu a.active i {
            color: #5e72e4;
            transform: scale(1.15);
        }

        .sidebar-menu a i {
            margin-right: 14px;
            font-size: 1.3rem;
            width: 28px;
            z-index: 2;
            position: relative;
            transition: all 0.3s ease;
        }

        .sidebar-menu a span {
            z-index: 2;
            position: relative;
        }

        /* Menu Section Header */
        .menu-section {
            padding: 25px 20px 12px;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 700;
            position: relative;
        }

        .menu-section::after {
            content: '';
            position: absolute;
            bottom: 8px;
            left: 20px;
            right: 20px;
            height: 2px;
            background: linear-gradient(90deg, rgba(94, 114, 228, 0.3), transparent);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
        }

        /* Header/Navbar */
        .top-navbar {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%);
            height: var(--header-height);
            padding: 0 35px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 999;
            border-bottom: 3px solid #5e72e4;
        }

        .navbar-left {
            display: flex;
            align-items: center;
        }

        .navbar-left h5 {
            color: #2d3748;
            font-weight: 700;
            margin: 0;
            font-size: 1.3rem;
        }

        .menu-toggle {
            background: none;
            border: none;
            font-size: 1.6rem;
            color: #5a5c69;
            cursor: pointer;
            margin-right: 20px;
            display: none;
            transition: all 0.3s;
        }

        .menu-toggle:hover {
            color: var(--accent-color);
            transform: scale(1.1);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar-right .btn {
            border-radius: 10px;
            padding: 10px 16px;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .navbar-right .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border: 2px solid var(--accent-color);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 10px 16px;
            border-radius: 12px;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .user-info:hover {
            background: #f0f4ff;
            border: 2px solid rgba(94, 114, 228, 0.3);
            transform: translateY(-2px);
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: #2d3748;
            font-size: 0.95rem;
        }

        .user-role {
            font-size: 0.75rem;
            color: #718096;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Content Area */
        .content-area {
            padding: 35px;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            padding: 10px;
            margin-top: 10px;
        }

        .dropdown-item {
            padding: 12px 18px;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, #f0f4ff 0%, #e8eeff 100%);
            transform: translateX(5px);
            color: var(--accent-color);
        }

        .dropdown-item i {
            margin-right: 12px;
            width: 20px;
        }

        /* Badge for menu items */
        .menu-badge {
            margin-left: auto;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(245, 87, 108, 0.4);
            z-index: 2;
            position: relative;
        }

        /* Notification Badge */
        .badge.bg-danger {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
            box-shadow: 0 2px 8px rgba(245, 87, 108, 0.4);
        }

        /* Responsive */
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
        }

        /* Sidebar Overlay for Mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 999;
            backdrop-filter: blur(4px);
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Scrollbar Styling */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(94, 114, 228, 0.6), rgba(94, 114, 228, 0.3));
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, rgba(94, 114, 228, 0.8), rgba(94, 114, 228, 0.5));
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h4><i class="bi bi-mortarboard-fill"></i> AdminPASIM</h4>
        </div>

        <ul class="sidebar-menu">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Management Section -->
            <div class="menu-section">Management</div>

            <li>
                <a href="{{ route('admin.users.index') }}"
                    class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Kelola User</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.mahasiswa.index') }}"
                    class="{{ request()->routeIs('admin.mahasiswa.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge-fill"></i>
                    <span>Data Mahasiswa</span>
                    <span class="menu-badge">New</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.buku.index') }}"
                    class="{{ request()->routeIs('admin.buku.*') ? 'active' : '' }}">
                    <i class="bi bi-book-fill"></i>
                    <span>Kelola Buku</span>
                </a>
            </li>

            <!-- Transactions Section -->
            <div class="menu-section">Transaksi</div>

            <li>
                <a href="{{ route('admin.peminjaman.index') }}"
                    class="{{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i>
                    <span>Peminjaman</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/perpanjangan*') ? 'active' : '' }}"
                    href="{{ route('admin.perpanjangan.index') }}">
                    <i class="bi bi-calendar-check"></i>
                    <span class="nav-link-text ms-1">Perpanjangan</span>
                </a>
            </li>

            <!-- Reports Section -->
            <div class="menu-section">Laporan</div>

            <li>
                <a href="{{ route('admin.laporan.index') }}"
                    class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text-fill"></i>
                    <span>Laporan Petugas</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="navbar-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h5>@yield('page-title', 'Dashboard')</h5>
            </div>

            <div class="navbar-right">
                <!-- Notifications -->
                <div class="dropdown">
                    <button class="btn btn-light position-relative" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bell-fill"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <h6 class="dropdown-header">Notifikasi</h6>
                        </li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-info-circle-fill text-info"></i>
                                User baru terdaftar</a></li>
                        <li><a class="dropdown-item" href="#"><i
                                    class="bi bi-exclamation-triangle-fill text-warning"></i> Update sistem tersedia</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-center" href="#">Lihat semua</a></li>
                    </ul>
                </div>

                <!-- User Dropdown -->
                <div class="dropdown">
                    <div class="user-info" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="user-details">
                            <span class="user-name">{{ Auth::user()->name ?? 'Admin' }}</span>
                            <span class="user-role">{{ ucfirst(Auth::user()->role ?? 'Administrator') }}</span>
                        </div>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.profile.index') }}">
                                <i class="bi bi-person-fill"></i> Profil Saya
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });

        // Close sidebar when clicking a menu item on mobile
        if (window.innerWidth <= 768) {
            document.querySelectorAll('.sidebar-menu a').forEach(link => {
                link.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                });
            });
        }
    </script>

    @stack('scripts')
</body>

</html>
