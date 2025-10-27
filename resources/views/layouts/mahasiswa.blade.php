<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'Dashboard Mahasiswa')</title>

    {{-- CSS Bootstrap / Tailwind (pilih salah satu sesuai project kamu) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Optional: Font dan Icon --}}
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
            background-color: #0d6efd;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
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
    </style>
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
            <div>
                <span class="text-muted me-3">{{ Auth::user()->name ?? 'Mahasiswa' }}</span>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Mahasiswa') }}" class="rounded-circle" width="40" height="40" alt="User">
            </div>
        </div>

        {{-- Konten Dinamis --}}
        <div class="content">
            @yield('content')
        </div>
    </div>

    {{-- JS Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>