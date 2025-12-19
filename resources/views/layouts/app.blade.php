<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- ✅ TAMBAHAN: Meta tag untuk base URL --}}
    <meta name="base-url" content="{{ url('/') }}">

    <title>{{ config('app.name', 'Admin Dashboard') }} - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <style>
        /* admin-layout.css - Complete Admin Layout Styles */

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

/* ========== SIDEBAR STYLES ========== */
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
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
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

/* ========== MAIN CONTENT ========== */
.main-content {
    margin-left: var(--sidebar-width);
    min-height: 100vh;
    transition: all 0.3s;
}

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

/* ========== NOTIFICATION STYLES ========== */
.notification-icon {
    position: relative;
    background: #f0f4ff;
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.notification-icon:hover {
    background: var(--accent-color);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(94, 114, 228, 0.4);
    border: 2px solid var(--accent-color);
}

.notification-icon:hover i {
    color: white;
}

.notification-icon i {
    font-size: 1.3rem;
    color: var(--accent-color);
    transition: all 0.3s;
}

.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    border-radius: 50%;
    width: 22px;
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(245, 87, 108, 0.5);
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.notification-dropdown {
    width: 400px;
    max-height: 500px;
    overflow-y: auto;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    border-radius: 16px;
    border: none;
}

.notification-header {
    padding: 18px 20px;
    background: linear-gradient(135deg, var(--accent-color) 0%, #764ba2 100%);
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
    border-radius: 16px 16px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification-item {
    padding: 16px 20px;
    border-bottom: 1px solid #e9ecef;
    transition: all 0.3s;
    cursor: pointer;
    display: flex;
    gap: 15px;
    align-items: flex-start;
}

.notification-item:hover {
    background: #f8f9fc;
    transform: translateX(5px);
}

.notification-item.unread {
    background: #f0f4ff;
    border-left: 4px solid var(--accent-color);
}

.notification-icon-small {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.notification-icon-small i {
    font-size: 1.3rem;
    color: white;
}

.notification-content {
    flex: 1;
}

.notification-title {
    font-weight: 600;
    color: #2d3748;
    font-size: 0.95rem;
    margin-bottom: 4px;
}

.notification-text {
    color: #718096;
    font-size: 0.85rem;
    line-height: 1.4;
}

.notification-time {
    color: #a0aec0;
    font-size: 0.75rem;
    margin-top: 6px;
}

.notification-footer {
    padding: 12px 20px;
    background: #f8f9fc;
    text-align: center;
    border-radius: 0 0 16px 16px;
}

.notification-footer a {
    color: var(--accent-color);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.notification-footer a:hover {
    color: #764ba2;
}

.empty-notification {
    padding: 40px 20px;
    text-align: center;
    color: #a0aec0;
}

.empty-notification i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.5;
}

/* ========== USER INFO ========== */
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

/* ========== CONTENT AREA ========== */
.content-area {
    padding: 35px;
}

/* ========== DROPDOWN MENU ========== */
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

/* ========== RESPONSIVE ========== */
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

    .notification-dropdown {
        width: 320px;
    }
}

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

/* ========== SCROLLBAR ========== */
.sidebar::-webkit-scrollbar,
.notification-dropdown::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-track,
.notification-dropdown::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
}

.sidebar::-webkit-scrollbar-thumb,
.notification-dropdown::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, rgba(94, 114, 228, 0.6), rgba(94, 114, 228, 0.3));
    border-radius: 3px;
}

.sidebar::-webkit-scrollbar-thumb:hover,
.notification-dropdown::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, rgba(94, 114, 228, 0.8), rgba(94, 114, 228, 0.5));
}
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h4><i class="bi bi-mortarboard-fill"></i> AdminPASIM</h4>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" 
                   class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
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
                   class="{{ request()->routeIs('admin.mahasiswa.*') ? 'active' : '' }}"
                   id="mahasiswaLink">
                    <i class="bi bi-person-badge-fill"></i>
                    <span>Data Mahasiswa</span>
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
                    <span>Kelola Buku</span>
                </a>
            </li>
            
            <div class="menu-section">Transaksi</div>
            
            <li>
                <a href="{{ route('admin.peminjaman.index') }}" 
                   class="{{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}"
                   id="peminjamanLink">
                    <i class="bi bi-journal-text"></i>
                    <span>Peminjaman</span>
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
                    <span>Perpanjangan</span>
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
                    <span>Notifikasi</span>
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
                    <span>Laporan Petugas</span>
                </a>
            </li>
        </ul>
    </aside>

    <main class="main-content">
        <nav class="top-navbar">
            <div class="navbar-left">
                <button class="menu-toggle" id="menuToggle"><i class="bi bi-list"></i></button>
                <h5>@yield('page-title', 'Dashboard')</h5>
            </div>
            <div class="navbar-right">
                <div class="dropdown">
                    <div class="notification-icon" data-bs-toggle="dropdown" id="notificationIcon">
                        <i class="bi bi-bell-fill"></i>
                        <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                        <div class="notification-header">
                            <span>Notifikasi</span>
                            <button class="btn btn-sm btn-light" onclick="markAllAsRead()" style="font-size: 0.75rem; padding: 4px 10px;">
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
                            <a href="{{ route('admin.notifikasi.index') }}">Lihat Semua Notifikasi</a>
                        </div>
                    </div>
                </div>
                
                <div class="dropdown">
                    <div class="user-info" data-bs-toggle="dropdown">
                        <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
                        <div class="user-details">
                            <span class="user-name">{{ Auth::user()->name ?? 'Admin' }}</span>
                            <span class="user-role">{{ ucfirst(Auth::user()->role ?? 'Administrator') }}</span>
                        </div>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin.profile.index') }}">
                            <i class="bi bi-person-fill"></i> Profil Saya</a></li>
                        <li><hr class="dropdown-divider"></li>
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
        
        <div class="content-area">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ✅ CUSTOM JAVASCRIPT - DIPERBAIKI -->
    <script>
        // ========== MOBILE MENU ==========
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (menuToggle) {
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            });
        }

        // Close sidebar when clicking menu on mobile
        if (window.innerWidth <= 768) {
            document.querySelectorAll('.sidebar-menu a').forEach(link => {
                link.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                });
            });
        }

        // ========== NOTIFICATION SYSTEM ==========
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
            setInterval(loadNotifications, 30000); // Refresh setiap 30 detik
        });

        function loadNotifications() {
            const baseUrl = document.querySelector('meta[name="base-url"]')?.content || '';
            
            // ✅ PERBAIKAN: Route tanpa /api/
            fetch(`${baseUrl}/admin/notifikasi/latest`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('✅ Notifikasi loaded:', data);
                updateNotificationUI(data.notifikasi, data.count);
            })
            .catch(error => {
                console.error('❌ Error loading notifications:', error);
            });
        }

        function updateNotificationUI(notifikasi, count) {
            const badge = document.getElementById('notificationBadge');
            const list = document.getElementById('notificationList');
            
            if (!badge || !list) {
                console.error('❌ Notification elements not found');
                return;
            }
            
            // Update badge
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
            
            // Update notification list
            if (!notifikasi || notifikasi.length === 0) {
                list.innerHTML = `
                    <div class="empty-notification">
                        <i class="bi bi-bell-slash"></i>
                        <p>Tidak ada notifikasi</p>
                    </div>
                `;
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
                            <div class="notification-time">
                                <i class="bi bi-clock"></i> ${timeAgo}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function getNotificationColor(tipe) {
            const colors = {
                'peminjaman_baru': 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'peminjaman_disetujui': 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
                'peminjaman_ditolak': 'linear-gradient(135deg, #eb3349 0%, #f45c43 100%)',
                'perpanjangan_baru': 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                'perpanjangan_disetujui': 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
                'perpanjangan_ditolak': 'linear-gradient(135deg, #eb3349 0%, #f45c43 100%)',
                'reminder_deadline': 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                'terlambat': 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                'user_baru': 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'buku_baru': 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                'sistem': 'linear-gradient(135deg, #a8caba 0%, #5d4e6d 100%)'
            };
            return colors[tipe] || 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
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
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);
            
            const intervals = {
                tahun: 31536000,
                bulan: 2592000,
                minggu: 604800,
                hari: 86400,
                jam: 3600,
                menit: 60,
                detik: 1
            };
            
            for (const [name, secondsCount] of Object.entries(intervals)) {
                const interval = Math.floor(seconds / secondsCount);
                if (interval >= 1) {
                    return `${interval} ${name} yang lalu`;
                }
            }
            
            return 'Baru saja';
        }

        function truncateText(text, maxLength) {
            if (!text) return '';
            if (text.length <= maxLength) return text;
            return text.substr(0, maxLength) + '...';
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
            return text.toString().replace(/[&<>"']/g, m => map[m]);
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
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('✅ Notifikasi ditandai sebagai dibaca');
                    loadNotifications();
                }
            })
            .catch(error => console.error('❌ Error marking as read:', error));
        }

        function markAllAsRead() {
            if (!confirm('Tandai semua notifikasi sebagai dibaca?')) return;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const baseUrl = document.querySelector('meta[name="base-url"]')?.content || '';
            
            fetch(`${baseUrl}/admin/notifikasi/baca-semua`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                    console.log('✅ Semua notifikasi ditandai sebagai dibaca');
                }
            })
            .catch(error => console.error('❌ Error marking all as read:', error));
        }

        // ========== 🎯 BADGE MANAGEMENT SYSTEM ==========
        document.addEventListener('DOMContentLoaded', function() {
            
            // ========== BADGE PEMINJAMAN ==========
            const peminjamanLink = document.getElementById('peminjamanLink');
            const peminjamanBadge = document.getElementById('peminjamanBadge');

            if (peminjamanBadge) {
                const currentCount = parseInt(peminjamanBadge.textContent) || 0;
                const lastSeenCount = parseInt(localStorage.getItem('admin_lastSeenPeminjamanCount')) || 0;

                if (currentCount <= lastSeenCount) {
                    peminjamanBadge.style.display = 'none';
                }

                if (peminjamanLink) {
                    peminjamanLink.addEventListener('click', function() {
                        localStorage.setItem('admin_lastSeenPeminjamanCount', currentCount);
                        peminjamanBadge.style.display = 'none';
                    });
                }
            }

            // ========== BADGE PERPANJANGAN ==========
            const perpanjanganLink = document.getElementById('perpanjanganLink');
            const perpanjanganBadge = document.getElementById('perpanjanganBadge');

            if (perpanjanganBadge) {
                const currentCount = parseInt(perpanjanganBadge.textContent) || 0;
                const lastSeenCount = parseInt(localStorage.getItem('admin_lastSeenPerpanjanganCount')) || 0;

                if (currentCount <= lastSeenCount) {
                    perpanjanganBadge.style.display = 'none';
                }

                if (perpanjanganLink) {
                    perpanjanganLink.addEventListener('click', function() {
                        localStorage.setItem('admin_lastSeenPerpanjanganCount', currentCount);
                        perpanjanganBadge.style.display = 'none';
                    });
                }
            }

            // ========== BADGE NOTIFIKASI SIDEBAR ==========
            const notifikasiLink = document.getElementById('notifikasiLink');
            const notifikasiBadge = document.getElementById('notifikasiBadge');

            if (notifikasiBadge) {
                const currentCount = parseInt(notifikasiBadge.textContent.replace('+', '')) || 0;
                const lastSeenCount = parseInt(localStorage.getItem('admin_lastSeenNotifikasiCount')) || 0;

                if (currentCount <= lastSeenCount) {
                    notifikasiBadge.style.display = 'none';
                }

                if (notifikasiLink) {
                    notifikasiLink.addEventListener('click', function() {
                        localStorage.setItem('admin_lastSeenNotifikasiCount', currentCount);
                        notifikasiBadge.style.display = 'none';
                    });
                }
            }

            // ========== BADGE MAHASISWA BARU (Per Hari) ==========
            const mahasiswaLink = document.getElementById('mahasiswaLink');
            const mahasiswaBadge = document.getElementById('mahasiswaBadge');

            if (mahasiswaBadge) {
                const currentCount = parseInt(mahasiswaBadge.textContent) || 0;
                const today = new Date().toDateString();
                const lastSeenDate = localStorage.getItem('admin_mahasiswaLastSeenDate');

                // Reset jika ganti hari
                if (lastSeenDate !== today) {
                    localStorage.removeItem('admin_mahasiswaLastSeenCount');
                    localStorage.setItem('admin_mahasiswaLastSeenDate', today);
                }

                const lastSeenCount = parseInt(localStorage.getItem('admin_mahasiswaLastSeenCount')) || 0;

                if (currentCount <= lastSeenCount) {
                    mahasiswaBadge.style.display = 'none';
                }

                if (mahasiswaLink) {
                    mahasiswaLink.addEventListener('click', function() {
                        localStorage.setItem('admin_mahasiswaLastSeenCount', currentCount);
                        localStorage.setItem('admin_mahasiswaLastSeenDate', today);
                        mahasiswaBadge.style.display = 'none';
                    });
                }
            }
        });

        // Fungsi reset badge untuk testing
        function resetAdminBadges() {
            localStorage.removeItem('admin_lastSeenPeminjamanCount');
            localStorage.removeItem('admin_lastSeenPerpanjanganCount');
            localStorage.removeItem('admin_lastSeenNotifikasiCount');
            localStorage.removeItem('admin_mahasiswaLastSeenCount');
            localStorage.removeItem('admin_mahasiswaLastSeenDate');
            location.reload();
            console.log('✅ Admin badges reset!');
        }

        console.log('✅ Admin Layout & Notification System Loaded');
    </script>

    @stack('scripts')
</body>

</html>