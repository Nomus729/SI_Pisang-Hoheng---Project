<?php
require 'config/Database.php';

$db = (new Database())->getConnection();

// 1. Create Table
$sql = "CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value TEXT
)";
$db->exec($sql);

// 2. Default Data
$defaults = [
    'address' => 'Jl. Pisang Raja No. 123, Jakarta Selatan',
    'phone' => '+62 812-3456-7890',
    'email' => 'info@sipisang.com',
    'hours_mon_fri' => '10.00 - 22.00',
    'hours_sat_sun' => '09.00 - 23.00',
    'social_ig' => 'https://instagram.com',
    'social_fb' => 'https://facebook.com',
    'social_wa' => 'https://wa.me/6281234567890',
    'social_tt' => 'https://tiktok.com',
    'map_embed' => '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.059497746594!2d106.79728631476932!3d-6.255902995471465!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f1a0b3676839%3A0xe545731338df13b2!2sBlok%20M%20Square!5e0!3m2!1sid!2sid!4v1633076841234!5m2!1sid!2sid\" width=\"100%\" height=\"100%\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\"></iframe>'
];

foreach ($defaults as $key => $val) {
    // Insert Ignore to keep existing data if run multiple times
    $stmt = $db->prepare("INSERT IGNORE INTO site_settings (setting_key, setting_value) VALUES (:k, :v)");
    $stmt->execute([':k' => $key, ':v' => $val]);
}

echo "Table site_settings created and seeded successfully.";
