<?php
require_once 'config/Database.php';
require_once 'models/Cart.php';

class CartController {
    
    public function index() {
        // Cek Session (Tidak perlu session_start karena sudah di index.php)
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_icon'] = 'warning';
            $_SESSION['flash_title'] = 'Akses Ditolak';
            $_SESSION['flash_text'] = 'Anda harus login untuk melihat keranjang.';
            $_SESSION['keep_modal'] = 'login';
            
            header("Location: index.php");
            exit;
        }

        $database = new Database();
        $db = $database->getConnection();
        $cartModel = new Cart($db);

        $userId = $_SESSION['user_id'];
        $items = $cartModel->getCartItems($userId);

        $subtotal = 0;
        $totalQty = 0;

        foreach ($items as $item) {
            $subtotal += $item['price'] * $item['qty'];
            $totalQty += $item['qty'];
        }

        $data = [
            'title' => 'Keranjang Belanja - Si Pisang',
            'cart_items' => $items,
            'subtotal' => $subtotal,
            'total_qty' => $totalQty
        ];

        require 'views/cart.php';
    }

    public function addToCart() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Harap login terlebih dahulu']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak valid']);
            exit;
        }

        $database = new Database();
        $db = $database->getConnection();
        $cartModel = new Cart($db);

        $userId = $_SESSION['user_id'];
        $name = $input['name'];
        $price = $input['price'];
        $qty = $input['qty'];
        $image = $input['image'];

        if ($cartModel->addToCart($userId, $name, $price, $qty, $image)) {
            echo json_encode(['status' => 'success', 'message' => 'Berhasil masuk keranjang']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data']);
        }
        exit;
    }

    public function update_cart() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($input['cart_id']) && isset($input['qty'])) {
            $database = new Database();
            $db = $database->getConnection();
            $cartModel = new Cart($db);

            if ($cartModel->updateQty($input['cart_id'], $input['qty'])) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal update database']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Input tidak lengkap']);
        }
        exit;
    }

    public function remove_item() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($input['cart_id'])) {
            $database = new Database();
            $db = $database->getConnection();
            $cartModel = new Cart($db);

            if ($cartModel->removeItem($input['cart_id'])) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus dari database']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID Cart tidak ditemukan']);
        }
        exit;
    }

    public function checkout() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit;
        }

        $database = new Database();
        $db = $database->getConnection();
        $cartModel = new Cart($db);

        $userId = $_SESSION['user_id'];
        $items = $cartModel->getCartItems($userId);

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['price'] * $item['qty'];
        }

        $data = [
            'title' => 'Pembayaran - Si Pisang',
            'items' => $items,
            'subtotal' => $subtotal
        ];
        require 'views/checkout.php';
    }

    // FUNGSI PROSES BAYAR (REVISI: HAPUS SESSION_START)
    public function place_order() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        
        // Cek Session (Tanpa session_start lagi jika sudah di index)
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Sesi habis, silakan login ulang']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validasi Alamat jika Delivery
        if ($input['delivery'] == 'delivery' && empty(trim($input['address']))) {
            echo json_encode(['status' => 'error', 'message' => 'Alamat pengiriman wajib diisi!']);
            exit;
        }

        $database = new Database();
        $db = $database->getConnection();
        $cartModel = new Cart($db);
        $userId = $_SESSION['user_id'];

        // Hitung Total di Server
        $items = $cartModel->getCartItems($userId);
        if (empty($items)) {
            echo json_encode(['status' => 'error', 'message' => 'Keranjang kosong!']);
            exit;
        }

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['price'] * $item['qty'];
        }
        
        $grandTotal = ($input['delivery'] == 'delivery') ? $subtotal + 10000 : $subtotal;

        // Panggil Model
        if ($cartModel->createOrder($userId, $input, $grandTotal)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan ke database']);
        }
        exit;
    }

    // ... di dalam CartController ...

    // FUNGSI PESANAN SAYA
    // --- FUNGSI PESANAN SAYA (DENGAN FILTER) ---
    public function my_orders() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all'; // Tangkap filter

        $database = new Database();
        $db = $database->getConnection();

        // Query Dasar
        $sql = "SELECT * FROM pesanan WHERE user_id = :uid";

        // Tambahkan Filter jika bukan 'all'
        if ($statusFilter != 'all') {
            $sql .= " AND status = :status";
        }

        $sql .= " ORDER BY tanggal DESC"; // Urutkan terbaru

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':uid', $userId);
        
        if ($statusFilter != 'all') {
            $stmt->bindParam(':status', $statusFilter);
        }

        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ambil Detail Item
        foreach ($orders as &$order) {
            $sqlItem = "SELECT product_name, qty FROM detail_pesanan WHERE pesanan_id = :pid";
            $stmtItem = $db->prepare($sqlItem);
            $stmtItem->bindParam(':pid', $order['id']);
            $stmtItem->execute();
            $order['items'] = $stmtItem->fetchAll(PDO::FETCH_ASSOC);
        }

        $data = [
            'title' => 'Pesanan Saya - Si Pisang',
            'orders' => $orders,
            'current_status' => $statusFilter // Kirim status aktif ke View
        ];

        require 'views/my_orders.php';
    }

    // --- FUNGSI REORDER ---
    // --- FUNGSI REORDER (Controller) ---
    public function reorder() {
        // Tidak perlu session_start() karena sudah di index.php
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $pesananId = isset($_GET['id']) ? $_GET['id'] : null;

        if ($pesananId) {
            $database = new Database();
            $db = $database->getConnection();
            $cartModel = new Cart($db);

            if ($cartModel->reorder($pesananId, $userId)) {
                // SUKSES: Redirect ke Keranjang
                $_SESSION['flash_icon'] = 'success';
                $_SESSION['flash_title'] = 'Berhasil!';
                $_SESSION['flash_text'] = 'Pesanan dimasukkan ke keranjang.';
                header("Location: index.php?action=cart");
            } else {
                // GAGAL: Redirect balik ke My Orders
                $_SESSION['flash_icon'] = 'error';
                $_SESSION['flash_title'] = 'Gagal!';
                $_SESSION['flash_text'] = 'Gagal memproses pesanan ulang. Produk mungkin sudah tidak tersedia.';
                header("Location: index.php?action=my_orders");
            }
        } else {
            header("Location: index.php?action=my_orders");
        }
        exit;
    }
}
?>