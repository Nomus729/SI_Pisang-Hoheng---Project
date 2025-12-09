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
    <?php include 'views/components/header.php'; ?>

    <main class="container">
        <section id="hero" class="hero-grid">
            <div class="hero-main card-blue">
                <div class="hero-text">
                    <div class="badge-pill animate-fade-in">
                        <i class="fas fa-crown text-warning"></i> Produk Unggulan
                    </div>

                    <h1 class="animate-slide-up"><?= htmlspecialchars($data['hero_product']['nama_produk']) ?></h1>
                    <p class="animate-slide-up delay-1"><?= htmlspecialchars($data['hero_product']['deskripsi']) ?></p>

                    <div class="hero-stats animate-slide-up delay-2">
                        <div class="stat-item">
                            <i class="fas fa-star text-warning"></i> 4.9
                        </div>
                        <div class="stat-divider"></div>
                        <div class="stat-item">
                            <i class="fas fa-fire text-danger"></i> <?= $data['hero_product']['terjual'] ?? 0 ?>+ Terjual
                        </div>
                    </div>

                    <a href="#menu" class="btn-order animate-scale-in delay-3">
                        Order Now <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="hero-img animate-float">
                    <?php
                    $img = $data['hero_product']['gambar'];
                    $imgPath = ($img === 'default.png' || strpos($img, 'hero-pisang') !== false)
                        ? 'public/img/hero-pisang.png'
                        : 'uploads/' . $img;
                    ?>
                    <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($data['hero_product']['nama_produk']) ?>">

                    <!-- Floating Price Tag -->
                    <div class="floating-price">
                        Rp <?= number_format($data['hero_product']['harga'], 0, ',', '.') ?>
                    </div>
                </div>
            </div>

            <div class="hero-side">
                <div class="category-card card-cyan">
                    <div class="cat-content">
                        <h3>Makanan</h3>
                        <span class="cat-count"><?= $data['cat_counts']['makanan'] ?> Pilihan Menu</span>
                        <a href="index.php?kategori=makanan#menu" class="btn-cat-link">Lihat Menu <i class="fas fa-chevron-right"></i></a>
                    </div>
                    <img src="https://placehold.co/100x100/ffa500/fff?text=F" class="cat-img">
                </div>
                <div class="category-card card-blue-light">
                    <div class="cat-content">
                        <h3>Minuman</h3>
                        <span class="cat-count"><?= $data['cat_counts']['minuman'] ?> Pilihan Menu</span>
                        <a href="index.php?kategori=minuman#menu" class="btn-cat-link">Lihat Menu <i class="fas fa-chevron-right"></i></a>
                    </div>
                    <img src="https://placehold.co/80x100/8B4513/fff?text=D" class="cat-img">
                </div>
            </div>
        </section>

        <section id="menu" class="menu-section reveal-on-scroll">
            <h2>Daftar Menu</h2>

            <?php $curCat = isset($data['pagination']['current_category']) ? $data['pagination']['current_category'] : ''; ?>
            <div class="menu-filter">
                <button data-kategori="" class="filter-landing-btn <?= $curCat == '' ? 'active' : '' ?>">Semua</button>
                <button data-kategori="makanan" class="filter-landing-btn <?= $curCat == 'makanan' ? 'active' : '' ?>">Makanan</button>
                <button data-kategori="minuman" class="filter-landing-btn <?= $curCat == 'minuman' ? 'active' : '' ?>">Minuman</button>
            </div>

            <div id="menu-container">
                <?php include 'views/components/menu_list.php'; ?>
            </div>
        </section>

        <section id="produk" class="product-section reveal-on-scroll">
            <h2>Menu Selengkapnya</h2>
            <div class="swiper product-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($data['all_products'] as $prod): ?>
                        <div class="swiper-slide">
                            <div class="prod-card-fixed">
                                <div class="prod-circle-img">
                                    <img src="uploads/<?= $prod['gambar'] ?>" alt="Produk" onerror="this.src='https://placehold.co/150x150?text=Produk'">
                                </div>
                                <div class="prod-card-content">
                                    <h3><?= $prod['nama_produk'] ?></h3>

                                    <div class="prod-details">
                                        <ul>
                                            <?php
                                            // Pecah deskripsi berdasarkan tanda "-" atau baris baru
                                            $lines = preg_split('/[-\n]/', $prod['deskripsi']);
                                            foreach ($lines as $line):
                                                $line = trim($line);
                                                if (!empty($line)):
                                            ?>
                                                    <li><?= $line ?></li>
                                            <?php
                                                endif;
                                            endforeach;
                                            ?>
                                        </ul>
                                    </div>

                                    <span class="prod-price">Price : Rp. <?= number_format($prod['harga'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </section>
    </main>

    <?php include 'views/components/footer.php'; ?>

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

    <?php if (isset($_SESSION['flash_icon'])): ?>
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
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- GSAP & ScrollTrigger -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="public/js/script.js"></script>
</body>

</html>