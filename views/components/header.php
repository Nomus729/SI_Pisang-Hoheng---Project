<?php
// ==========================================
// LOGIKA BADGE NOTIFIKASI (Global Header)
// ==========================================
$cartCount = 0;
$orderCount = 0;

// Pastikan session sudah mulai
if (session_status() == PHP_SESSION_NONE) session_start();

if (isset($_SESSION['user_id'])) {
    // Buat koneksi database khusus untuk header
    // Kita gunakan try-catch agar jika file database sudah di-require sebelumnya tidak error
    try {
        require_once 'config/Database.php';
        $db_header = (new Database())->getConnection();
        $uid = $_SESSION['user_id'];

        // 1. HITUNG JUMLAH BARANG DI KERANJANG (SUM QTY)
        $stmt = $db_header->prepare("SELECT SUM(qty) as total FROM keranjang WHERE user_id = :uid");
        $stmt->bindParam(':uid', $uid);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // Jika null (kosong), set 0
        $cartCount = $row['total'] ? $row['total'] : 0;

        // 2. HITUNG PESANAN AKTIF (Pending & Proses)
        // Ini agar user tahu ada pesanan yang sedang berjalan
        $stmt2 = $db_header->prepare("SELECT COUNT(*) as total FROM pesanan WHERE user_id = :uid AND status IN ('pending', 'proses')");
        $stmt2->bindParam(':uid', $uid);
        $stmt2->execute();
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $orderCount = $row2['total'];
    } catch (Exception $e) {
        // Silent error jika terjadi konflik koneksi
        $cartCount = 0;
        $orderCount = 0;
    }
}
?>

<header class="main-header" id="mainHeader">
    <div class="header-content">
        <div class="logo">
            <img src="public/img/logoHoheng.webp" alt="Logo">
            <div class="logo-text">
                <span class="brand-name">SI PISANG</span>
                <span class="brand-sub">By KNine</span>
            </div>
        </div>

        <div class="search-bar">
            <form action="index.php#menu" method="GET" style="display: flex; width: 100%; align-items: center;">
                <input type="text" name="search" id="searchInput" placeholder="Search menu..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                    <a href="index.php#menu" id="clearSearch"><i class="fas fa-times"></i></a>
                <?php endif; ?>
                <button type="submit" id="searchBtn"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <div class="user-actions">
            <!-- Actions Content -->
            <a href="index.php?action=cart" class="icon-link" id="cartBtn"
                data-login="<?= isset($_SESSION['nama_user']) ? 'true' : 'false' ?>">
                <i class="fas fa-shopping-cart"></i>

                <span class="icon-badge <?= ($cartCount > 0) ? '' : 'hidden' ?>" id="cartBadge">
                    <?= $cartCount ?>
                </span>
            </a>

            <?php if (isset($_SESSION['nama_user'])): ?>

                <a href="index.php?action=my_orders" class="icon-link" title="Pesanan Saya">
                    <i class="fas fa-receipt"></i> <?php if ($orderCount > 0): ?>
                        <span class="icon-badge"><?= $orderCount ?></span>
                    <?php endif; ?>
                </a>

                <span class="user-name-display" style="font-weight:bold; font-size: 0.9rem;">
                    Hai, <?= (strlen($_SESSION['nama_user']) > 10) ? substr($_SESSION['nama_user'], 0, 10) . '...' : $_SESSION['nama_user'] ?>
                </span>
                <a href="index.php?action=logout" class="btn-signin" style="background:#ff6b6b; color:white;">Logout</a>

            <?php else: ?>
                <button class="btn-signin" id="openLoginBtn">Sign In / Sign Up</button>
            <?php endif; ?>

            <a href="#" class="icon-link"><i class="fas fa-user"></i></a>

            <!-- Mobile Menu Toggle -->
            <div class="menu-toggle" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </div>
        </div>

        <nav class="nav-links">
            <a href="index.php#hero" class="active">Home</a>
            <a href="index.php#menu">Menu</a>
            <a href="index.php#produk">Produk</a>
            <a href="index.php#contact">Contact</a>
            <div class="nav-indicator"></div>
        </nav>
    </div>
</header>