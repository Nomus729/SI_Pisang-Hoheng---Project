<?php
require_once 'config/Database.php';

class AdminController {
    private $db;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        // Cek Login Admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php");
            exit;
        }
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function dashboard() {
        // --- 1. HANDLER AJAX (BARU: UNTUK PESANAN & NOTIFIKASI) ---
        if (isset($_GET['action_ajax'])) {
            if ($_GET['action_ajax'] == 'update_status') {
                $this->updateStatusPesanan();
                return;
            }
            if ($_GET['action_ajax'] == 'get_detail') {
                $this->getDetailPesanan();
                return;
            }
            
            // --- TAMBAHAN BARU: CEK NOTIFIKASI ---
            if ($_GET['action_ajax'] == 'check_notif') {
                $this->checkNewOrders();
                return;
            }
        }

        // --- 2. HANDLER POST (SIMPAN PRODUK) ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->simpanProduk();
            return;
        }

        // --- 3. HANDLER VIEW (NAVIGASI) ---
        $view = isset($_GET['page']) ? $_GET['page'] : 'daftar_produk';
        $data = [];

        switch ($view) {
            case 'daftar_produk':
                $data = $this->getProduk();
                break;
            
            case 'detail_produk':
                $id = isset($_GET['id']) ? $_GET['id'] : null;
                $data = $id ? $this->getProdukById($id) : null;
                break;
            
            case 'hapus_produk':
                $id = isset($_GET['id']) ? $_GET['id'] : null;
                if ($id) {
                    $this->hapusProduk($id);
                } else {
                    header("Location: index.php?action=dashboard&page=daftar_produk");
                }
                return;

            case 'pesanan':
                // Tangkap filter status dari URL (default: all)
                $statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';
                $data = $this->getAllPesanan($statusFilter); // Kirim status ke fungsi
                break;

            case 'laporan_keuangan':
                $data = $this->getLaporanKeuangan();
                break;
            
            case 'laporan_produk':
                $data = $this->getLaporanProduk();
                break;
        }

        require 'views/admin/dashboard.php';
    }

    // --- FUNGSI PESANAN (BARU) ---
    // --- FUNGSI PESANAN (DENGAN FILTER) ---
    // --- FUNGSI PESANAN (DENGAN FILTER) ---
    private function getAllPesanan($status = 'all') {
        $sql = "SELECT p.*, u.nama_user 
                FROM pesanan p 
                JOIN users u ON p.user_id = u.id";
        
        // Jika status bukan 'all', tambahkan kondisi WHERE
        if ($status != 'all') {
            $sql .= " WHERE p.status = :status";
        }
        
        $sql .= " ORDER BY p.tanggal DESC"; // Selalu urutkan dari yang terbaru

        $stmt = $this->db->prepare($sql);

        // Bind parameter status jika ada filter
        if ($status != 'all') {
            $stmt->bindParam(':status', $status);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getDetailPesanan() {
        $id = $_GET['id'];
        
        $sqlOrder = "SELECT p.*, u.nama_user, u.email 
                     FROM pesanan p 
                     JOIN users u ON p.user_id = u.id 
                     WHERE p.id = :id";
        $stmt = $this->db->prepare($sqlOrder);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        $sqlItems = "SELECT * FROM detail_pesanan WHERE pesanan_id = :id";
        $stmt2 = $this->db->prepare($sqlItems);
        $stmt2->bindParam(':id', $id);
        $stmt2->execute();
        $items = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode(['order' => $order, 'items' => $items]);
        exit;
    }

    // --- REVISI: UPDATE STATUS & JUMLAH TERJUAL ---
    // --- REVISI 2: LOGIKA STOK & TERJUAL ---
    private function updateStatusPesanan() {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'];
        $status = $input['status']; // status baru (proses, selesai, batal)

        try {
            $this->db->beginTransaction();

            // 1. Update Status Pesanan
            $sql = "UPDATE pesanan SET status = :status WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Ambil detail barang untuk logika stok
            $sqlItems = "SELECT product_name, qty FROM detail_pesanan WHERE pesanan_id = :id";
            $stmtItems = $this->db->prepare($sqlItems);
            $stmtItems->bindParam(':id', $id);
            $stmtItems->execute();
            $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            // 2. Logika Khusus
            if ($status === 'selesai') {
                // Pesanan Sukses: TAMBAH JUMLAH TERJUAL
                $sqlUpdate = "UPDATE produk SET terjual = terjual + :qty WHERE nama_produk = :name";
                $stmtUpdate = $this->db->prepare($sqlUpdate);

                foreach ($items as $item) {
                    $stmtUpdate->bindParam(':qty', $item['qty']);
                    $stmtUpdate->bindParam(':name', $item['product_name']);
                    $stmtUpdate->execute();
                }
            }
            elseif ($status === 'batal') {
                // Pesanan Batal: KEMBALIKAN STOK (RESTOCK)
                $sqlRestock = "UPDATE produk SET stok = stok + :qty WHERE nama_produk = :name";
                $stmtRestock = $this->db->prepare($sqlRestock);

                foreach ($items as $item) {
                    $stmtRestock->bindParam(':qty', $item['qty']);
                    $stmtRestock->bindParam(':name', $item['product_name']);
                    $stmtRestock->execute();
                }
            }

            $this->db->commit();
            
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success']);

        } catch (Exception $e) {
            $this->db->rollBack();
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }

    // --- FUNGSI CEK PESANAN BARU (REALTIME) ---
    private function checkNewOrders() {
        // Ambil pesanan yang statusnya masih 'pending'
        $sql = "SELECT p.id, p.total_harga, u.nama_user 
                FROM pesanan p
                JOIN users u ON p.user_id = u.id
                WHERE p.status = 'pending' 
                ORDER BY p.tanggal DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Hitung jumlahnya
        $count = count($orders);

        header('Content-Type: application/json');
        echo json_encode([
            'count' => $count,
            'orders' => $orders
        ]);
        exit;
    }

    // --- FUNGSI PRODUK (LAMA) ---
    private function simpanProduk() {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $kode = $_POST['kode'];
        $nama = $_POST['nama'];
        $harga = $_POST['harga'];
        $stok = $_POST['stok'];
        $detail = $_POST['detail'];
        
        if (empty($nama) || empty($harga) || empty($stok)) {
            echo "<script>alert('Data tidak boleh kosong!'); history.back();</script>";
            return;
        }

        $gambarQuery = ""; 
        $params = [
            ':kode' => $kode,
            ':nama' => $nama,
            ':harga' => $harga,
            ':stok' => $stok,
            ':detail' => $detail
        ];

        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
            $targetDir = "uploads/";
            if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
            $fileName = time() . "_" . basename($_FILES["gambar"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFilePath)) {
                $gambarQuery = ", gambar = :gambar";
                $params[':gambar'] = $fileName;
            }
        } elseif (!$id) {
            $gambarQuery = ", gambar = :gambar";
            $params[':gambar'] = 'default.png';
        }

        if ($id) {
            $sql = "UPDATE produk SET kode_barang=:kode, nama_produk=:nama, harga=:harga, stok=:stok, deskripsi=:detail $gambarQuery WHERE id=:id";
            $params[':id'] = $id;
        } else {
            $sql = "INSERT INTO produk (kode_barang, nama_produk, harga, stok, deskripsi, gambar, kategori) 
                    VALUES (:kode, :nama, :harga, :stok, :detail, " . (isset($params[':gambar']) ? ":gambar" : "'default.png'") . ", 'Makanan')";
        }

        $stmt = $this->db->prepare($sql);
        
        if ($stmt->execute($params)) {
            $_SESSION['flash_icon'] = 'success';
            $_SESSION['flash_title'] = 'Berhasil!';
            $_SESSION['flash_text'] = 'Data produk berhasil disimpan.';
            header("Location: index.php?action=dashboard&page=daftar_produk");
        } else {
            $_SESSION['flash_icon'] = 'error';
            $_SESSION['flash_title'] = 'Gagal!';
            $_SESSION['flash_text'] = 'Terjadi kesalahan database.';
            header("Location: index.php?action=dashboard&page=daftar_produk");
        }
    }

    private function hapusProduk($id) {
        $stmtCheck = $this->db->prepare("SELECT gambar FROM produk WHERE id = :id");
        $stmtCheck->bindParam(':id', $id);
        $stmtCheck->execute();
        $produk = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        $stmt = $this->db->prepare("DELETE FROM produk WHERE id = :id");
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            if ($produk && $produk['gambar'] !== 'default.png') {
                $filePath = "uploads/" . $produk['gambar'];
                if (file_exists($filePath)) unlink($filePath);
            }
            $_SESSION['flash_icon'] = 'success';
            $_SESSION['flash_title'] = 'Terhapus!';
            $_SESSION['flash_text'] = 'Produk berhasil dihapus.';
        } else {
            $_SESSION['flash_icon'] = 'error';
            $_SESSION['flash_title'] = 'Gagal!';
            $_SESSION['flash_text'] = 'Gagal menghapus produk.';
        }
        header("Location: index.php?action=dashboard&page=daftar_produk");
        exit;
    }

    private function getProduk() {
        $stmt = $this->db->query("SELECT * FROM produk ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getProdukById($id) {
        $stmt = $this->db->prepare("SELECT * FROM produk WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function getLaporanKeuangan() {
        return [
            'pendapatan' => [1500000, 2300000, 1800000, 3200000],
            'pengeluaran' => [500000, 800000, 600000, 1200000],
            'bulan' => ['Jan', 'Feb', 'Mar', 'Apr']
        ];
    }

    private function getLaporanProduk() {
        try {
            $stmt = $this->db->query("SELECT nama_produk, terjual FROM produk ORDER BY terjual DESC LIMIT 5");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}
?>