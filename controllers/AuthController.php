<?php
require_once 'config/Database.php';
require_once 'models/User.php';

class AuthController {
    public function login() {
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        // Ambil data dari form
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        $user->email = $email;
        $user->password = $password;

        // Cek Login
        $loggedInUser = $user->login();

        if ($loggedInUser) {
            // LOGIN SUKSES
            // (Tidak perlu session_start lagi, sudah di index.php)
            
            $_SESSION['user_id'] = $loggedInUser['id'];
            $_SESSION['nama_user'] = $loggedInUser['nama_user'];
            $_SESSION['role'] = $loggedInUser['role'];
            
            $_SESSION['flash_icon'] = 'success';
            $_SESSION['flash_title'] = 'Login Berhasil!';
            $_SESSION['flash_text'] = 'Selamat datang, ' . $loggedInUser['nama_user'];
            
            header("Location: index.php");
            exit();
        } else {
            // LOGIN GAGAL
            $_SESSION['flash_icon'] = 'error';
            $_SESSION['flash_title'] = 'Login Gagal!';
            $_SESSION['flash_text'] = 'Email atau Password salah.';
            
            // Sinyal agar modal terbuka lagi
            $_SESSION['keep_modal'] = 'login'; 
            
            header("Location: index.php");
            exit();
        }
    }

    public function register() {
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        $user->nama_user = $_POST['name'];
        $user->email = $_POST['email'];
        $user->password = $_POST['password'];

        if ($user->register()) {
            $_SESSION['flash_icon'] = 'success';
            $_SESSION['flash_title'] = 'Registrasi Berhasil!';
            $_SESSION['flash_text'] = 'Silakan login sekarang.';
            
            // Buka modal login otomatis setelah register
            $_SESSION['keep_modal'] = 'login'; 
            
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['flash_icon'] = 'error';
            $_SESSION['flash_title'] = 'Registrasi Gagal!';
            $_SESSION['flash_text'] = 'Email mungkin sudah terdaftar.';
            
            $_SESSION['keep_modal'] = 'login'; // Buka modal lagi
            
            header("Location: index.php");
            exit();
        }
    }

    public function logout() {
        session_destroy(); // Hancurkan session lama
        session_start(); // Mulai session baru untuk pesan flash
        
        $_SESSION['flash_icon'] = 'success';
        $_SESSION['flash_title'] = 'Logout Berhasil';
        $_SESSION['flash_text'] = 'Sampai jumpa lagi!';
        
        header("Location: index.php");
        exit();
    }
}
?>