<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?></title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>
<body>
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
                <button>
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <div class="user-actions">
                <a href="index.php?action=cart" class="icon-link" id="cartBtn" data-login="<?= isset($_SESSION['nama_user']) ? 'true' : 'false' ?>">
                    <i class="fas fa-shopping-cart"></i>
                </a>
                <?php if(isset($_SESSION['nama_user'])): ?>
                    <span style="font-weight:bold; font-size: 0.9rem;">Hai, <?= $_SESSION['nama_user'] ?></span>
                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="index.php?action=dashboard" class="btn-signin" style="background:#4CAF50; color:white;">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    <?php endif; ?>
                    <a href="index.php?action=logout" class="btn-signin" style="background:#ff6b6b; color:white;">Logout</a>
                <?php else: ?>
                    <button class="btn-signin" id="openLoginBtn">Sign In / Sign Up</button>
                <?php endif; ?>
                <a href="#" class="icon-link">
                    <i class="fas fa-user"></i>
                </a>
            </div>
            <nav class="nav-links">
                <a href="#hero" class="active">Home</a>
                <a href="#menu">Menu</a>
                <a href="#produk">Produk</a>
                <a href="#contact">Contact</a>
                <div class="nav-indicator"></div>
            </nav>
        </div>
    </header>

    <main class="container">
        <section id="hero" class="hero-grid">
            <div class="hero-main card-blue">
                <div class="hero-text">
                    <h2>Produk Unggulan</h2>
                    <h1><?= $data['hero_product']['name'] ?></h1>
                    <p><?= $data['hero_product']['desc'] ?></p>
                    <div class="rating-badge">
                        <span><?= $data['hero_product']['rating'] ?></span>
                        <i class="fas fa-star"></i>
                    </div>
                    <button class="btn-order">Order Now</button>
                </div>
                <div class="hero-img">
                    <img src="<?= $data['hero_product']['image'] ?>" alt="Hero">
                </div>
            </div>
            <div class="hero-side">
                <div class="category-card card-cyan">
                    <h3>Kategori<br>Makanan</h3>
                    <a href="#">Go Daftar Makanan <i class="fas fa-arrow-right"></i></a>
                    <img src="https://placehold.co/100x100/ffa500/fff?text=F" class="cat-img">
                </div>
                <div class="category-card card-blue-light">
                    <h3>Kategori<br>Minuman</h3>
                    <a href="#">Go Daftar Minuman <i class="fas fa-arrow-right"></i></a>
                    <img src="https://placehold.co/80x100/8B4513/fff?text=D" class="cat-img">
                </div>
            </div>
        </section>

        <section id="menu" class="menu-section reveal-on-scroll">
            <h2>Daftar Menu</h2>
            <div class="menu-grid">
                <?php foreach($data['menu_items'] as $item): ?>
                    <div class="menu-card">
                        <div class="card-img">
                            <img src="uploads/<?= $item['gambar'] ?>" alt="<?= $item['nama_produk'] ?>" onerror="this.src='https://placehold.co/200x150'">
                        </div>
                        <div class="card-body">
                            <h4><?= $item['nama_produk'] ?></h4>
                            <div class="meta-info">
                                <span class="pcs">100 pcs</span>
                                <span class="rating">4.9 <i class="fas fa-star"></i></span>
                            </div>
                            <div class="card-actions">
                                <div class="qty-control">
                                    <button class="btn-qty btn-minus">-</button>
                                    <span class="qty-val">1</span>
                                    <button class="btn-qty btn-plus">+</button>
                                </div>
                                <button class="cart-btn add-to-cart-btn" 
                                        data-name="<?= $item['nama_produk'] ?>" 
                                        data-price="<?= $item['harga'] ?>" 
                                        data-image="uploads/<?= $item['gambar'] ?>">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="pagination-container">
                <?php for($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
                    <a href="index.php?halaman=<?= $i ?>#menu" class="page-link <?= ($i == $data['pagination']['current_page']) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        </section>

        <section id="produk" class="product-section reveal-on-scroll">
            <h2>Produk Lainnya</h2>
            <div class="swiper product-swiper">
                <div class="swiper-wrapper">
                    <?php foreach($data['all_products'] as $prod): ?>
                        <div class="swiper-slide">
                            <div class="prod-card-fixed">
                                <div class="prod-circle-img">
                                    <img src="uploads/<?= $prod['gambar'] ?>" alt="Produk" onerror="this.src='https://placehold.co/150x150?text=Produk'">
                                </div>
                                <div class="prod-card-content">
                                    <h3><?= $prod['nama_produk'] ?></h3>
                                    <div class="prod-details">
                                        <?= $prod['deskripsi'] ?>
                                    </div>
                                    <span class="prod-price">
                                        Rp <?= number_format($prod['harga'], 0, ',', '.') ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </section>
    </main>

    <div class="modal-overlay" id="authModal">
        <div class="modal-container">
            <button class="close-modal">&times;</button>
            
            <div class="form-box login-box" id="loginForm">
                <h2>Welcome!</h2>
                <p class="subtitle">Welcome back!, Please enter your details</p>
                <form id="formLogin" action="index.php?action=login" method="POST">
                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="email" class="email-input" placeholder="Enter your email" required>
                        <span class="error-text">Format email tidak valid</span>
                    </div>
                    <div class="input-group">
                        <label>Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" class="pass-input" placeholder="Password" required>
                            <i class="fas fa-eye toggle-password"></i>
                        </div>
                    </div>
                    <div class="form-actions">
                        <a href="#" class="forgot-pass">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn-full-blue btn-submit">LOG IN</button>
                    <button type="button" class="btn-full-google">
                        <img src="../public/img/googleIcon.png" width="20"> Sign In With Google
                    </button>
                </form>
                <p class="switch-text">
                    Don't have an account? <a href="#" id="showRegister">Sign up for free</a>
                </p>
            </div>

            <div class="form-box register-box hidden" id="registerForm">
                <h2>Create Account</h2>
                <p class="subtitle">Join us and enjoy delicious snacks!</p>
                <form id="formRegister" action="index.php?action=register" method="POST">
                    <div class="input-group">
                        <label>Full Name</label>
                        <input type="text" name="name" placeholder="Enter your name" required>
                    </div>
                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="email" class="email-input" placeholder="Enter your email" required>
                        <span class="error-text">Format email tidak valid</span>
                    </div>
                    <div class="input-group">
                        <label>Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" class="pass-input" placeholder="Create a password" required>
                            <i class="fas fa-eye toggle-password"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn-full-blue btn-submit">SIGN UP</button>
                </form>
                <p class="switch-text">
                    Already have an account? <a href="#" id="showLogin">Log In</a>
                </p>
            </div>
        </div>
    </div>

    <?php if(isset($_SESSION['flash_icon'])): ?>
        <div id="flash-data" 
             data-icon="<?= $_SESSION['flash_icon'] ?>" 
             data-title="<?= $_SESSION['flash_title'] ?>" 
             data-text="<?= $_SESSION['flash_text'] ?>"
             data-modal="<?= isset($_SESSION['keep_modal']) ? $_SESSION['keep_modal'] : '' ?>">
        </div>
        <?php 
            unset($_SESSION['flash_icon']);
            unset($_SESSION['flash_title']);
            unset($_SESSION['flash_text']);
            unset($_SESSION['keep_modal']);
        ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="public/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="public/js/script.js"></script>
</body>
</html>