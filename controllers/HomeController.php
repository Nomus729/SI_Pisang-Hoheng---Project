<?php
require_once 'config/Database.php';

class HomeController
{

    private function getSettings($db)
    {
        try {
            $stmt = $db->query("SELECT setting_key, setting_value FROM site_settings");
            return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        } catch (Exception $e) {
            return []; // Return empty if table doesn't exist yet
        }
    }

    public function info()
    {
        $database = new Database();
        $db = $database->getConnection();
        $settings = $this->getSettings($db);

        $data = [
            'title' => 'Info & Lokasi - Si Pisang',
            'settings' => $settings
        ];
        require 'views/info.php';
    }

    public function index()
    {
        $database = new Database();
        $db = $database->getConnection();
        $settings = $this->getSettings($db);

        // --- 1. LOGIKA PAGINATION UNTUK MENU ---
        $limit = 9; // Jumlah item per halaman
        $page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $start = ($page > 1) ? ($page * $limit) - $limit : 0;

        // Base Query
        $whereClauses = [];
        $params = [];

        // 1. Filter Kategori
        $kategori = isset($_GET['kategori']) ? strtolower($_GET['kategori']) : '';
        if ($kategori === 'makanan') {
            $whereClauses[] = "kategori = 'Makanan'";
        } elseif ($kategori === 'minuman') {
            $whereClauses[] = "kategori = 'Minuman'";
        }

        // 2. Filter Search
        if (!empty($search)) {
            $whereClauses[] = "nama_produk LIKE :search";
            $params[':search'] = "%$search%";
        }

        // Build SQL Where
        $whereSql = "";
        if (!empty($whereClauses)) {
            $whereSql = "WHERE " . implode(" AND ", $whereClauses);
        }

        // Hitung total produk makanan (dengan filter jika ada)
        $queryCount = "SELECT COUNT(*) as total FROM produk $whereSql";
        $stmtCount = $db->prepare($queryCount);
        foreach ($params as $key => $val) {
            $stmtCount->bindValue($key, $val);
        }
        $stmtCount->execute();
        $totalItems = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalItems / $limit);

        // Ambil data menu sesuai halaman (dengan filter jika ada)
        $queryMenu = "SELECT * FROM produk $whereSql ORDER BY created_at DESC LIMIT :start, :limit";
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

        // --- 3. AMBIL DATA HERO SECTION (PISANG COKELAT) ---
        $queryHero = "SELECT * FROM produk WHERE nama_produk LIKE :name LIMIT 1";
        $stmtHero = $db->prepare($queryHero);
        $stmtHero->bindValue(':name', '%Pisang Cokelat%');
        $stmtHero->execute();
        $heroProduct = $stmtHero->fetch(PDO::FETCH_ASSOC);

        // Fallback: Jika Pisang Cokelat tidak ada, ambil yang terlaris
        if (!$heroProduct) {
            $queryHero = "SELECT * FROM produk ORDER BY terjual DESC LIMIT 1";
            $stmtHero = $db->prepare($queryHero);
            $stmtHero->execute();
            $heroProduct = $stmtHero->fetch(PDO::FETCH_ASSOC);
        }

        // Fallback 2: Jika database kosong total
        if (!$heroProduct) {
            $heroProduct = [
                'nama_produk' => 'SI PISANG',
                'deskripsi' => 'Pisang Goreng Panas dengan taburan keju dan coklat',
                'gambar' => 'default.png',
                'id' => null,
                'terjual' => 0,
                'harga' => 0
            ];
        }

        $data = [
            'title' => 'Si Pisang - Renyah & Manis',
            'hero_product' => $heroProduct,
            'cat_counts' => [
                'makanan' => $countMak,
                'minuman' => $countMin
            ],
            'menu_items' => $menu_items,
            'all_products' => $all_products, // Data untuk slider
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'search_query' => $search,
                'current_category' => $kategori
            ],
            'settings' => $settings
        ];

        if (isset($_GET['action_ajax']) && $_GET['action_ajax'] === 'filter_menu') {
            ob_start();
            include 'views/components/menu_list.php';
            $html = ob_get_clean();
            header('Content-Type: application/json');
            echo json_encode(['html' => $html]);
            exit;
        }

        require 'views/home.php';
    }
}
