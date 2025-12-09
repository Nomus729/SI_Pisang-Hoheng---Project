<?php
require_once 'config/Database.php';

class HomeController
{

    public function index()
    {
        $database = new Database();
        $db = $database->getConnection();

        // --- 1. LOGIKA PAGINATION UNTUK MENU ---
        $limit = 9; // Jumlah item per halaman
        $page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $start = ($page > 1) ? ($page * $limit) - $limit : 0;

        // Base Query
        $whereClause = "WHERE kategori = 'Makanan'";
        $params = [];

        // Tambahkan filter search jika ada
        if (!empty($search)) {
            $whereClause .= " AND nama_produk LIKE :search";
            $params[':search'] = "%$search%";
        }

        // Hitung total produk makanan (dengan filter jika ada)
        $queryCount = "SELECT COUNT(*) as total FROM produk $whereClause";
        $stmtCount = $db->prepare($queryCount);
        foreach ($params as $key => $val) {
            $stmtCount->bindValue($key, $val);
        }
        $stmtCount->execute();
        $totalItems = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalItems / $limit);

        // Ambil data menu sesuai halaman (dengan filter jika ada)
        $queryMenu = "SELECT * FROM produk $whereClause ORDER BY created_at DESC LIMIT :start, :limit";
        $stmtMenu = $db->prepare($queryMenu);
        foreach ($params as $key => $val) {
            $stmtMenu->bindValue($key, $val);
        }
        $stmtMenu->bindValue(':start', (int)$start, PDO::PARAM_INT);
        $stmtMenu->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmtMenu->execute();
        $menu_items = $stmtMenu->fetchAll(PDO::FETCH_ASSOC);


        // --- 2. AMBIL SEMUA PRODUK UNTUK SLIDER BAWAH ---
        $queryProduk = "SELECT * FROM produk ORDER BY created_at DESC";

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
                'total_pages' => $totalPages,
                'search_query' => $search
            ]
        ];

        require 'views/home.php';
    }
}
