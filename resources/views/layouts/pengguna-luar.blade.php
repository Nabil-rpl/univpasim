<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        /* Indikator kiri */
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

        /* Efek hover background */
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
            gap: 12px;
        }

        .user-info span {
            color: #64748b;
            font-size: 0.95rem;
        }

        .user-info img {
            border: 2px solid #dbeafe;
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
                <span>{{ auth()->user()->name ?? 'Pengguna' }}</span>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Pengguna') }}&background=60A5FA&color=fff&bold=true" 
                     class="rounded-circle" width="40" height="40" alt="User">
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
        // Tooltip Bootstrap (opsional)
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