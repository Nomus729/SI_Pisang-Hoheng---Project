<?php
// Load file database
require_once 'config/Database.php';

// Koneksi ke Database
$database = new Database();
$db = $database->getConnection();

// 1. Hapus User Admin Lama (Supaya tidak duplikat/bingung)
$sqlDelete = "DELETE FROM users WHERE email = 'admin@sipisang.com'";
$db->exec($sqlDelete);

// 2. Buat Password Baru
$passwordAsli = 'admin123';
$passwordHash = password_hash($passwordAsli, PASSWORD_DEFAULT);

// 3. Masukkan Admin Baru
$sqlInsert = "INSERT INTO users (nama_user, email, password, role, alamat) 
              VALUES (:nama, :email, :pass, 'admin', 'Kantor Pusat')";

$stmt = $db->prepare($sqlInsert);
$params = [
    ':nama' => 'Super Admin',
    ':email' => 'admin@sipisang.com',
    ':pass' => $passwordHash
];

if ($stmt->execute($params)) {
    echo "<h1>SUKSES! ✅</h1>";
    echo "Akun Admin berhasil di-reset.<br>";
    echo "Silakan Login dengan:<br>";
    echo "Email: <b>admin@sipisang.com</b><br>";
    echo "Password: <b>admin123</b><br>";
    echo "<br><a href='index.php'>Kembali ke Website</a>";
} else {
    echo "<h1>GAGAL ❌</h1>";
    print_r($stmt->errorInfo());
}
?>