<?php
require_once 'config/Database.php';
require_once 'models/User.php';

class AuthController {
    public function login() {
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        $user->email = $_POST['email'];
        $user->password = $_POST['password'];

        $loggedInUser = $user->login();

        if ($loggedInUser) {
            // ... (Bagian Login Sukses biarkan sama) ...
            session_start();
            $_SESSION['user_id'] = $loggedInUser['id'];
            $_SESSION['nama_user'] = $loggedInUser['nama_user'];
            $_SESSION['role'] = $loggedInUser['role'];
            
            $_SESSION['flash_icon'] = 'success';
            $_SESSION['flash_title'] = 'Login Berhasil!';
            $_SESSION['flash_text'] = 'Selamat datang kembali, ' . $loggedInUser['nama_user'];
            
            header("Location: index.php");
        } else {
            // --- BAGIAN INI YANG DIUBAH ---
            session_start();
            $_SESSION['flash_icon'] = 'error';
            $_SESSION['flash_title'] = 'Login Gagal!';
            $_SESSION['flash_text'] = 'Email atau Password yang anda masukkan salah.';
            
            // TAMBAHAN: Sinyal agar modal tetap terbuka
            $_SESSION['keep_modal'] = 'login'; 
            
            header("Location: index.php");
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
            session_start();
            $_SESSION['flash_icon'] = 'success';
            $_SESSION['flash_title'] = 'Registrasi Berhasil!';
            $_SESSION['flash_text'] = 'Silakan login dengan akun baru anda.';
            
            header("Location: index.php");
        } else {
            session_start();
            $_SESSION['flash_icon'] = 'error';
            $_SESSION['flash_title'] = 'Registrasi Gagal!';
            $_SESSION['flash_text'] = 'Email mungkin sudah digunakan.';
            
            header("Location: index.php");
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        
        session_start();
        $_SESSION['flash_icon'] = 'success';
        $_SESSION['flash_title'] = 'Logout Berhasil';
        $_SESSION['flash_text'] = 'Sampai jumpa lagi!';
        
        header("Location: index.php");
    }
}
?>