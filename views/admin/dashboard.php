<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?></title>
    <link rel="stylesheet" href="public/css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="admin-body">

    <div class="admin-layout">
        
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="public/img/logoHoheng.webp" alt="Logo">
                <div class="text">
                    <span class="brand">SI PISANG</span>
                    <span class="sub">By KNine</span>
                </div>
            </div>

            <div class="sidebar-menu">
                <div class="menu-label"><i class="fas fa-th-large"></i> Dashboard</div>
                
                <div class="menu-group">
                    <div class="menu-title"><i class="fas fa-bars"></i> Menu</div>
                    <a href="#" class="menu-item active">
                        <i class="fas fa-file-alt"></i> Detail Produk
                    </a>
                    <a href="#" class="menu-item">
                        <i class="fas fa-box-open"></i> Daftar Produk
                    </a>
                </div>

                <div class="menu-group">
                    <div class="menu-title"><i class="fas fa-file-invoice"></i> Laporan</div>
                    <a href="#" class="menu-item">
                        <i class="fas fa-money-bill-wave"></i> Laporan Keuangan
                    </a>
                    <a href="#" class="menu-item">
                        <i class="fas fa-chart-line"></i> Laporan Produk
                    </a>
                </div>
            </div>
        </aside>

        <main class="main-content">
            
            <header class="admin-header">
                <div class="page-title">
                    <i class="fas fa-file-alt"></i> Detail Produk
                </div>
                <div class="admin-profile">
                    <span>Halo, Admin</span>
                    <div class="profile-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <a href="index.php?action=logout" class="admin-logout" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </header>

            <div class="content-body">
                <div class="empty-state-card">
                    </div>
            </div>

        </main>
    </div>

</body>
</html>