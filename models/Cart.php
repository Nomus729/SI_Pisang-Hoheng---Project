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
}
?>