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
            <input type="text" placeholder="Search...">
            <button><i class="fas fa-search"></i></button>
        </div>

        <div class="user-actions">
            <a href="index.php?action=cart" class="icon-link" id="cartBtn" 
               data-login="<?= isset($_SESSION['nama_user']) ? 'true' : 'false' ?>">
               <i class="fas fa-shopping-cart"></i>
            </a>
            
            <?php if(isset($_SESSION['nama_user'])): ?>
                <span style="font-weight:bold; font-size: 0.9rem;">Hai, <?= $_SESSION['nama_user'] ?></span>
                <a href="index.php?action=logout" class="btn-signin" style="background:#ff6b6b; color:white;">Logout</a>
            <?php else: ?>
                <button class="btn-signin" id="openLoginBtn">Sign In / Sign Up</button>
            <?php endif; ?>
            
            <a href="#" class="icon-link"><i class="fas fa-user"></i></a>
        </div>

        <nav class="nav-links">
            <a href="index.php#hero">Home</a>
            <a href="index.php#menu">Menu</a>
            <a href="index.php#produk">Produk</a>
            <a href="index.php#contact">Contact</a>
            <div class="nav-indicator"></div> 
        </nav>
    </div>
</header>