<?php
class Cart {
    private $conn;
    private $table = "keranjang";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addToCart($userId, $name, $price, $qty, $image) {
        $checkQuery = "SELECT id, qty FROM " . $this->table . " WHERE user_id = :uid AND product_name = :pname LIMIT 1";
        $stmt = $this->conn->prepare($checkQuery);
        $stmt->bindParam(":uid", $userId);
        $stmt->bindParam(":pname", $name);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $newQty = $row['qty'] + $qty;
            
            $updateQuery = "UPDATE " . $this->table . " SET qty = :qty WHERE id = :id";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bindParam(":qty", $newQty);
            $updateStmt->bindParam(":id", $row['id']);
            return $updateStmt->execute();
        } else {
            $insertQuery = "INSERT INTO " . $this->table . " (user_id, product_name, price, qty, image) VALUES (:uid, :pname, :price, :qty, :img)";
            $insertStmt = $this->conn->prepare($insertQuery);
            $insertStmt->bindParam(":uid", $userId);
            $insertStmt->bindParam(":pname", $name);
            $insertStmt->bindParam(":price", $price);
            $insertStmt->bindParam(":qty", $qty);
            $insertStmt->bindParam(":img", $image);
            return $insertStmt->execute();
        }
    }

    public function getCartItems($userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :uid ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
public function updateQty($cartId, $qty) {
        // Pastikan qty tidak kurang dari 1
        if ($qty < 1) return false;

        $query = "UPDATE " . $this->table . " SET qty = :qty WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":qty", $qty);
        $stmt->bindParam(":id", $cartId);
        return $stmt->execute();
    }

    // FUNGSI BARU: Hapus Item
    // Cek file models/Cart.php
public function removeItem($cartId) {
    // Pastikan parameternya id = :id (ID unik keranjang)
    $query = "DELETE FROM " . $this->table . " WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id", $cartId);
    return $stmt->execute();
}
public function createOrder($userId, $data, $total) {
        try {
            $this->conn->beginTransaction();

            // 1. Buat Kode Pesanan Unik
            $kodePesanan = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);

            // 2. Insert ke PESANAN (Pastikan kolom sesuai dengan DB Anda)
            // Default status 'pending', tanggal otomatis CURRENT_TIMESTAMP
            $queryOrder = "INSERT INTO pesanan (kode_pesanan, user_id, total_harga, metode_bayar, metode_kirim, alamat_kirim, catatan, status) 
                           VALUES (:kode, :uid, :total, :bayar, :kirim, :alamat, :catatan, 'pending')";
            
            $stmt = $this->conn->prepare($queryOrder);
            $stmt->bindParam(':kode', $kodePesanan);
            $stmt->bindParam(':uid', $userId);
            $stmt->bindParam(':total', $total);
            $stmt->bindParam(':bayar', $data['payment']);
            $stmt->bindParam(':kirim', $data['delivery']);
            $stmt->bindParam(':alamat', $data['address']);
            
            // Handle Catatan Kosong
            $catatan = !empty($data['note']) ? $data['note'] : '-';
            $stmt->bindParam(':catatan', $catatan);
            
            if (!$stmt->execute()) {
                throw new Exception("Gagal insert pesanan");
            }
            
            $pesananId = $this->conn->lastInsertId();

            // 3. Ambil Item Keranjang
            $cartItems = $this->getCartItems($userId);
            if (empty($cartItems)) {
                throw new Exception("Keranjang kosong");
            }

            // 4. Insert ke DETAIL_PESANAN
            $queryDetail = "INSERT INTO detail_pesanan (pesanan_id, product_name, harga, qty, subtotal) 
                            VALUES (:pid, :name, :price, :qty, :sub)";
            $stmtDetail = $this->conn->prepare($queryDetail);

            foreach ($cartItems as $item) {
                $subtotalItem = $item['price'] * $item['qty'];
                $stmtDetail->bindParam(':pid', $pesananId);
                $stmtDetail->bindParam(':name', $item['product_name']);
                $stmtDetail->bindParam(':price', $item['price']);
                $stmtDetail->bindParam(':qty', $item['qty']);
                $stmtDetail->bindParam(':sub', $subtotalItem);
                
                if (!$stmtDetail->execute()) {
                    throw new Exception("Gagal insert detail");
                }
            }

            // 5. Hapus Keranjang
            $queryClear = "DELETE FROM keranjang WHERE user_id = :uid";
            $stmtClear = $this->conn->prepare($queryClear);
            $stmtClear->bindParam(':uid', $userId);
            $stmtClear->execute();

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            // Log error jika perlu: error_log($e->getMessage());
            return false;
        }
    }

}
?>