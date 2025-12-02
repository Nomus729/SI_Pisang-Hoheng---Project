<?php
class AdminController {
    
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Proteksi: Cek apakah user adalah ADMIN
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php"); // Tendang balik ke home jika bukan admin
            exit;
        }
    }

    public function dashboard() {
        $data = [
            'title' => 'Dashboard - Si Pisang',
            'page' => 'detail_produk' // Menandakan halaman aktif
        ];
        require 'views/admin/dashboard.php';
    }
    
    // Anda bisa menambahkan fungsi lain nanti, misal: daftarProduk(), laporan(), dll.
}
?>