<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info & Lokasi - Si Pisang Hoheng</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .info-header {
            background: linear-gradient(135deg, #f1c40f, #f39c12);
            padding: 120px 20px 60px;
            text-align: center;
            color: white;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
        }

        .info-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .info-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .info-container {
            max-width: 1000px;
            margin: -40px auto 50px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            position: relative;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .info-details h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5rem;
            border-left: 5px solid #f1c40f;
            padding-left: 15px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-item i {
            width: 40px;
            height: 40px;
            background: #FFF9E5;
            color: #f39c12;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            font-size: 18px;
            flex-shrink: 0;
        }

        .info-item div h5 {
            margin: 0 0 5px;
            font-size: 1rem;
            color: #555;
        }

        .info-item div p {
            margin: 0;
            color: #777;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .map-frame {
            width: 100%;
            height: 100%;
            min-height: 350px;
            border-radius: 15px;
            border: 2px solid #eee;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .info-container {
                grid-template-columns: 1fr;
                margin-top: 20px;
                width: 90%;
            }

            .info-header {
                padding-top: 100px;
            }
        }
    </style>
</head>

<body>
    <?php include 'views/components/header.php'; ?>

    <!-- Header Section -->
    <div class="info-header">
        <h1>Tentang Kami</h1>
        <p>Temukan lokasi kami dan hubungi kami kapan saja!</p>
    </div>

    <!-- Content Section -->
    <div class="info-container">

        <?php
        $s = isset($data['settings']) ? $data['settings'] : [];
        $addr = !empty($s['address']) ? $s['address'] : 'Jl. Pisang Raja No. 123, Jakarta Selatan';
        $phone = !empty($s['phone']) ? $s['phone'] : '+62 812-3456-7890';
        $email = !empty($s['email']) ? $s['email'] : 'info@sipisang.com';
        $h_wk = !empty($s['hours_mon_fri']) ? $s['hours_mon_fri'] : '10.00 - 22.00';
        $h_we = !empty($s['hours_sat_sun']) ? $s['hours_sat_sun'] : '09.00 - 23.00';
        $map = !empty($s['map_embed']) ? $s['map_embed'] : '';
        ?>

        <div class="info-text">
            <div class="info-details">
                <h3>Informasi Kontak</h3>

                <div class="info-item">
                    <i class="fas fa-map-marked-alt"></i>
                    <div>
                        <h5>Alamat Outlet</h5>
                        <p><?= nl2br($addr) ?></p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-phone-volume"></i>
                    <div>
                        <h5>Telepon / WhatsApp</h5>
                        <p><?= $phone ?></p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-envelope-open-text"></i>
                    <div>
                        <h5>Email & Bisnis</h5>
                        <p><?= $email ?></p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h5>Jam Operasional</h5>
                        <p>Senin - Jumat: <?= $h_wk ?></p>
                        <p>Sabtu - Minggu: <?= $h_we ?></p>
                    </div>
                </div>
            </div>

            <div class="social-links" style="margin-top: 30px;">
                <h5 style="margin-bottom: 10px; color:#555;">Ikuti Kami:</h5>
                <?php if (!empty($s['social_ig'])) echo '<a href="' . $s['social_ig'] . '" target="_blank" style="margin-right:10px; font-size:24px; color:#E1306C;"><i class="fab fa-instagram"></i></a>'; ?>
                <?php if (!empty($s['social_fb'])) echo '<a href="' . $s['social_fb'] . '" target="_blank" style="margin-right:10px; font-size:24px; color:#1877f2;"><i class="fab fa-facebook"></i></a>'; ?>
                <?php if (!empty($s['social_tt'])) echo '<a href="' . $s['social_tt'] . '" target="_blank" style="margin-right:10px; font-size:24px; color:#000;"><i class="fab fa-tiktok"></i></a>'; ?>
                <?php if (!empty($s['social_wa'])) echo '<a href="' . $s['social_wa'] . '" target="_blank" style="font-size:24px; color:#25D366;"><i class="fab fa-whatsapp"></i></a>'; ?>
            </div>
        </div>

        <div class="map-section">
            <div class="map-frame">
                <!-- Google Maps Embed -->
                <?= $map ?>
                <?php if (empty($map)): ?>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.059497746594!2d106.79728631476932!3d-6.255902995471465!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f1a0b3676839%3A0xe545731338df13b2!2sBlok%20M%20Square!5e0!3m2!1sid!2sid!4v1633076841234!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- Reuse Footer -->
    <?php include 'views/components/footer.php'; ?>

    <!-- Modal Login (Needed for Header login button) -->
    <div class="modal-overlay" id="authModal">
        <div class="modal-container">
            <button class="close-modal">&times;</button>
            <div class="form-box login-box" id="loginForm">
                <h2>Welcome!</h2>
                <form id="formLogin" action="index.php?action=login" method="POST">
                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="email" class="email-input" required>
                    </div>
                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" name="password" class="pass-input" required>
                    </div>
                    <button type="submit" class="btn-full-blue btn-submit">LOG IN</button>
                    <!-- ... simplified for brevity, logic handled by script.js ... -->
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="public/js/script.js"></script>
</body>

</html>