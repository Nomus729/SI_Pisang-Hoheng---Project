<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Si Pisang</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="public/css/admin.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> </head>
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
                <div class="menu-group">
                    <div class="menu-title"><i class="fas fa-box"></i> Manajemen Produk</div>
                    <a href="index.php?action=dashboard&page=daftar_produk" class="menu-item <?= ($view=='daftar_produk')?'active':'' ?>">
                        <i class="fas fa-list"></i> Daftar Produk
                    </a>
                    <a href="index.php?action=dashboard&page=detail_produk" class="menu-item <?= ($view=='detail_produk')?'active':'' ?>">
                        <i class="fas fa-plus-circle"></i> Tambah / Detail
                    </a>
                </div>

                <div class="menu-group">
                    <div class="menu-title"><i class="fas fa-chart-bar"></i> Laporan</div>
                    <a href="index.php?action=dashboard&page=laporan_keuangan" class="menu-item <?= ($view=='laporan_keuangan')?'active':'' ?>">
                        <i class="fas fa-wallet"></i> Keuangan
                    </a>
                    <a href="index.php?action=dashboard&page=laporan_produk" class="menu-item <?= ($view=='laporan_produk')?'active':'' ?>">
                        <i class="fas fa-chart-pie"></i> Statistik Produk
                    </a>
                </div>
            </div>
        </aside>

        <main class="main-content">
            
            <header class="admin-header">
                <h2 class="page-title-text">
                    <?= ucfirst(str_replace('_', ' ', $view)) ?>
                </h2>
                
                <div class="header-right">
                    <div class="notif-wrapper" id="notifBtn">
                        <i class="fas fa-bell"></i>
                        <span class="notif-badge hidden" id="notifCount">0</span>
                        
                        <div class="notif-dropdown hidden" id="notifDropdown">
                            <div class="notif-header">Pesanan Baru</div>
                            <div class="notif-body" id="notifList">
                                <p class="empty-notif">Belum ada pesanan baru</p>
                            </div>
                        </div>
                    </div>

                    <div class="admin-profile">
                        <span>Halo, Admin</span>
                        <div class="profile-icon"><i class="fas fa-user"></i></div>
                        <a href="index.php?action=logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i></a>
                    </div>
                </div>
            </header>

            <div class="content-body">
                <?php 
                    // Load file view parsial berdasarkan menu
                    $file = 'views/admin/pages/' . $view . '.php';
                    if (file_exists($file)) {
                        include $file;
                    } else {
                        echo "<h3>Halaman tidak ditemukan</h3>";
                    }
                ?>
            </div>

        </main>
    </div>

    <audio id="notifSound" src="public/audio/notification.mp3"></audio>
    
    <?php if(isset($_SESSION['flash_icon'])): ?>
        <div id="flash-data" 
             data-icon="<?= $_SESSION['flash_icon'] ?>" 
             data-title="<?= $_SESSION['flash_title'] ?>" 
             data-text="<?= $_SESSION['flash_text'] ?>">
        </div>
        <?php 
            unset($_SESSION['flash_icon']);
            unset($_SESSION['flash_title']);
            unset($_SESSION['flash_text']);
        ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="public/js/admin.js"></script>
</body>
</html>