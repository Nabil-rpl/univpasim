<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa - PASIM SITE</title>
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
            <li><a href="#"><i class="bi bi-book"></i> Kelola Buku</a></li>
            <li><a href="#"><i class="bi bi-person"></i> Profile</a></li>
            <li><a href="#"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-area">
            <h2>Dashboard</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <i class="bi bi-book"></i>
                        <h4>Sedang di Pinjam</h4>
                        <p>2500</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <i class="bi bi-bookmark"></i>
                        <h4>Dikembalikan</h4>
                        <p>300</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <i class="bi bi-exclamation"></i>
                        <h4>Buku Terlambat</h4>
                        <p>150</p>
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>Peminjam</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Pemrograman Web dengan React</td>
                            <td>Budi Santoso</td>
                            <td>2024-10-01</td>
                            <td>2024-10-10</td>
                            <td><span class="badge bg-success">Dikembalikan</span></td>
                            <td><span class="text-success">Proses Pengembalian (Buku dikembalikan)</span></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Pemrograman Web dengan React</td>
                            <td>Budi Santoso</td>
                            <td>2024-10-01</td>
                            <td>2024-10-10</td>
                            <td><span class="badge bg-danger">Terlambat</span></td>
                            <td><span class="text-warning">Proses Pengembalian (Buku terlambat)</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>  