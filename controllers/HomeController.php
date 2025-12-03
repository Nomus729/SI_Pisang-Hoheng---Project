<?php
require_once 'config/Database.php';

class HomeController {
    
    public function index() {
        $database = new Database();
        $db = $database->getConnection();

        // --- 1. LOGIKA PAGINATION UNTUK MENU ---
        $limit = 8; // Jumlah item per halaman
        $page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
        $start = ($page > 1) ? ($page * $limit) - $limit : 0;

        // Hitung total produk makanan
        $totalQuery = $db->query("SELECT COUNT(*) as total FROM produk WHERE kategori = 'Makanan'");
        $totalItems = $totalQuery->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalItems / $limit);

        // Ambil data menu sesuai halaman
        $queryMenu = "SELECT * FROM produk WHERE kategori = 'Makanan' ORDER BY created_at DESC LIMIT :start, :limit";
        $stmtMenu = $db->prepare($queryMenu);
        $stmtMenu->bindParam(':start', $start, PDO::PARAM_INT);
        $stmtMenu->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmtMenu->execute();
        $menu_items = $stmtMenu->fetchAll(PDO::FETCH_ASSOC);


        // --- 2. AMBIL SEMUA PRODUK UNTUK SLIDER BAWAH ---
        $queryProduk = "SELECT * FROM produk ORDER BY RAND()"; // Ambil semua secara acak
        $stmtProduk = $db->prepare($queryProduk);
        $stmtProduk->execute();
        $all_products = $stmtProduk->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'title' => 'Si Pisang - Renyah & Manis',
            'hero_product' => [
                'name' => 'PISANG GORENG',
                'desc' => 'Pisang Goreng Panas dengan taburan keju dan coklat',
                'rating' => '4,9',
                'image' => 'public/img/hero-pisang.png'
            ],
            'menu_items' => $menu_items,
            'all_products' => $all_products, // Data untuk slider
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages
            ]
        ];

        require 'views/home.php';
    }
}
?>