<?php
class User {
    private $conn;
    private $table_name = "users";

    public $nama_user;
    public $email;
    public $password;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register() {
        // Cek apakah email sudah ada
        $checkQuery = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(":email", $this->email);
        $checkStmt->execute();
        
        if($checkStmt->rowCount() > 0){
            return false; // Email duplikat
        }

        // Jika aman, masukkan data baru
        $query = "INSERT INTO " . $this->table_name . " SET nama_user=:name, email=:email, password=:pass, role='customer'";
        $stmt = $this->conn->prepare($query);

        // Enkripsi password
        $hash = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindParam(":name", $this->nama_user);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":pass", $hash);

        return $stmt->execute();
    }

    public function login() {
        $query = "SELECT id, nama_user, password, role FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verifikasi Password (Hash vs Input)
            if (password_verify($this->password, $row['password'])) {
                return $row;
            }
        }
        return false;
    }
}
?>