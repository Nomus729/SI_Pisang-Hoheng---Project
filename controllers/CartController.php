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
        // Bersihkan output sebelumnya agar tidak merusak JSON
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

    // --- FUNGSI UPDATE JUMLAH ---
    public function update_cart() {
        // Bersihkan output sebelumnya
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

    // --- FUNGSI HAPUS ITEM (REVISI PENTING) ---
    public function remove_item() {
        // PENTING: Bersihkan buffer agar tidak ada spasi/error PHP yang ikut terkirim
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
}
?>