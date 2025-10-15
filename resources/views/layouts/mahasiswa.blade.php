<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - PASIM SITE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 60px;
            --primary-color: #4e73df;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
        }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fc; }
        .sidebar { position: fixed; top: 0; left: 0; width: var(--sidebar-width); height: 100vh; background: var(--sidebar-bg); padding-top: 20px; }
        .sidebar-brand { padding: 0 20px 30px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-brand h4 { color: white; font-weight: 700; font-size: 1.5rem; }
        .sidebar-menu { list-style: none; padding: 20px 0; }
        .sidebar-menu a { display: flex; align-items: center; padding: 12px 20px; color: rgba(255,255,255,0.8); text-decoration: none; }
        .sidebar-menu a:hover { background: var(--sidebar-hover); color: white; }
        .sidebar-menu a i { margin-right: 12px; font-size: 1.2rem; }
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; }
        .content-area { padding: 30px; }
        .card { border: none; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; padding: 20px; }
        .card i { font-size: 2rem; color: #4e73df; }
        .category-card { width: 150px; height: 200px; display: inline-block; margin: 10px; text-align: center; padding: 15px; border: 1px solid #ddd; border-radius: 10px; }
        .category-card img { width: 100%; height: 120px; object-fit: cover; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h4><i class="bi bi-mortarboard-fill"></i> PASIM SITE</h4>
        </div>
        <ul class="sidebar-menu">
            <li><a href="#"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a href="#"><i class="bi bi-person"></i> Profile</a></li>
            <li><a href="#"><i class="bi bi-book"></i> Kelola Buku</a></li>
            <li><a href="#"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-area">
            <h2>Selamat Datang Kembali, Regil</h2>
            <p class="text-muted">Memahami dan investasi buku</p>
            <h3>Kategori</h3>
            <div>
                <div class="category-card">
                    <img src="https://via.placeholder.com/150x120" alt="Buku">
                    <h6>Pemrograman C++</h6>
                </div>
                <div class="category-card">
                    <img src="https://via.placeholder.com/150x120" alt="Buku">
                    <h6>Pemrograman Teori</h6>
                </div>
                <div class="category-card">
                    <img src="https://via.placeholder.com/150x120" alt="Buku">
                    <h6>Pemrograman C++</h6>
                </div>
                <div class="category-card">
                    <img src="https://via.placeholder.com/150x120" alt="Buku">
                    <h6>Pemrograman Teori</h6>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>













































