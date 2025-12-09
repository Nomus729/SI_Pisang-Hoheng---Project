<footer id="contact" class="main-footer">
    <div class="footer-content">
        <div class="footer-section brand">
            <h3>SI PISANG</h3>
            <p>Rasakan kelezatan pisang goreng kekinian dengan berbagai topping pilihan. Renyah di luar, lembut di dalam!</p>
            <div class="social-links">
                <?php
                $s = isset($data['settings']) ? $data['settings'] : [];
                if (!empty($s['social_ig'])) echo '<a href="' . $s['social_ig'] . '" target="_blank"><i class="fab fa-instagram"></i></a>';
                if (!empty($s['social_fb'])) echo '<a href="' . $s['social_fb'] . '" target="_blank"><i class="fab fa-facebook-f"></i></a>';
                if (!empty($s['social_wa'])) echo '<a href="' . $s['social_wa'] . '" target="_blank"><i class="fab fa-whatsapp"></i></a>';
                if (!empty($s['social_tt'])) echo '<a href="' . $s['social_tt'] . '" target="_blank"><i class="fab fa-tiktok"></i></a>';
                ?>
            </div>
        </div>

        <div class="footer-section contact-info">
            <h4>Hubungi Kami</h4>
            <ul>
                <?php
                $s = isset($data['settings']) ? $data['settings'] : [];
                $addr = !empty($s['address']) ? $s['address'] : 'Jl. Pisang Raja No. 123, Jakarta Selatan';
                $phone = !empty($s['phone']) ? $s['phone'] : '+62 812-3456-7890';
                $email = !empty($s['email']) ? $s['email'] : 'info@sipisang.com';
                $hours = !empty($s['hours_mon_fri']) ? $s['hours_mon_fri'] : '10.00 - 22.00';
                ?>
                <li><i class="fas fa-map-marker-alt"></i> <?= $addr ?></li>
                <li><i class="fas fa-phone-alt"></i> <?= $phone ?></li>
                <li><i class="fas fa-envelope"></i> <?= $email ?></li>
                <li><i class="fas fa-clock"></i> Senin - Jumat: <?= $hours ?></li>
            </ul>
        </div>

        <div class="footer-section quick-links">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php#menu">Menu</a></li>
                <li><a href="index.php?action=info">Info & Lokasi</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> Si Pisang Hoheng. All rights reserved.</p>
    </div>
</footer>

<style>
    .main-footer {
        background: #222;
        color: #fff;
        padding-top: 60px;
        margin-top: 50px;
    }

    .footer-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px 50px;
    }

    .footer-section h3 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #f1c40f;
    }

    .footer-section h4 {
        font-size: 18px;
        margin-bottom: 20px;
        color: #fff;
        border-bottom: 2px solid #f1c40f;
        display: inline-block;
        padding-bottom: 5px;
    }

    .footer-section p {
        color: #bbb;
        line-height: 1.6;
    }

    .contact-info ul,
    .quick-links ul {
        list-style: none;
        padding: 0;
    }

    .contact-info li {
        margin-bottom: 15px;
        color: #bbb;
        display: flex;
        align-items: start;
        gap: 10px;
    }

    .contact-info li i {
        color: #f1c40f;
        margin-top: 5px;
    }

    .quick-links li {
        margin-bottom: 10px;
    }

    .quick-links a {
        color: #bbb;
        text-decoration: none;
        transition: 0.3s;
    }

    .quick-links a:hover {
        color: #f1c40f;
        padding-left: 5px;
    }

    .social-links {
        margin-top: 20px;
        display: flex;
        gap: 15px;
    }

    .social-links a {
        width: 40px;
        height: 40px;
        background: #333;
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        font-size: 18px;
        transition: 0.3s;
    }

    .social-links a:hover {
        background: #f1c40f;
        color: #222;
        transform: translateY(-3px);
    }

    .footer-bottom {
        background: #111;
        text-align: center;
        padding: 20px;
        border-top: 1px solid #333;
        color: #777;
        font-size: 14px;
    }
</style>