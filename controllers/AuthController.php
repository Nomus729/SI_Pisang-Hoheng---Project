<?php
namespace App\Controllers;

use App\Config\Database;
use App\Models\User;
use App\Models\Log;

class AuthController
{
    public function login()
    {
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        $user->email = $_POST['email'];
        $user->password = $_POST['password'];

        $loggedInUser = $user->login();

        if ($loggedInUser) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            session_regenerate_id(true);
            $_SESSION['user_id'] = $loggedInUser['id'];
            $_SESSION['nama_user'] = $loggedInUser['nama_user'];
            $_SESSION['role'] = $loggedInUser['role'];

            $_SESSION['flash_icon'] = 'success';
            $_SESSION['flash_title'] = 'Login Berhasil!';
            $_SESSION['flash_text'] = 'Selamat datang, ' . $loggedInUser['nama_user'];

            // --- LOGIKA REDIRECT BERDASARKAN ROLE ---

            // --- LOG ACTIVITY START ---
            $log = new Log($db);
            $log->create($loggedInUser['id'], $loggedInUser['nama_user'], $loggedInUser['role'], 'Login Success');
            // --- LOG ACTIVITY END ---

            if ($loggedInUser['role'] === 'admin') {
                header("Location: index.php?action=dashboard");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['flash_icon'] = 'error';
            $_SESSION['flash_title'] = 'Login Gagal!';
            $_SESSION['flash_text'] = 'Email atau Password salah.';
            $_SESSION['keep_modal'] = 'login';

            header("Location: index.php");
            exit();
        }
    }

    public function register()
    {
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

    public function logout()
    {
        session_destroy(); // Hancurkan session lama
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['flash_icon'] = 'success';
        $_SESSION['flash_title'] = 'Logout Berhasil';
        $_SESSION['flash_text'] = 'Sampai jumpa lagi!';

        header("Location: index.php");
        exit();
    }
}
