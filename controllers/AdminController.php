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
        // 1. TANGKAP REQUEST POST (UNTUK SIMPAN/UPDATE DATA)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->simpanProduk();
            return;
        }

        // 2. TANGKAP REQUEST PAGE (NAVIGASI)
        $view = isset($_GET['page']) ? $_GET['page'] : 'daftar_produk';
        $data = [];

        switch ($view) {
            case 'daftar_produk':
                $data = $this->getProduk();
                break;
            
            case 'detail_produk':
                // Cek apakah mode EDIT (ada ID) atau TAMBAH BARU
                $id = isset($_GET['id']) ? $_GET['id'] : null;
                $data = $id ? $this->getProdukById($id) : null;
                break;
            
            case 'hapus_produk':
                // Logic Hapus
                $id = isset($_GET['id']) ? $_GET['id'] : null;
                if ($id) {
                    $this->hapusProduk($id);
                } else {
                    header("Location: index.php?action=dashboard&page=daftar_produk");
                }
                return; // Stop disini agar tidak load view

            case 'laporan_keuangan':
                $data = $this->getLaporanKeuangan();
                break;
            
            case 'laporan_produk':
                $data = $this->getLaporanProduk();
                break;
        }

        require 'views/admin/dashboard.php';
    }

    // --- FUNGSI SIMPAN (CREATE / UPDATE) ---
    private function simpanProduk() {
        // Ambil data dari form
        $id = isset($_POST['id']) ? $_POST['id'] : null; // Cek apakah ada ID (untuk edit)
        $kode = $_POST['kode'];
        $nama = $_POST['nama'];
        $harga = $_POST['harga']; // Ini ambil dari input hidden 'hargaReal'
        $stok = $_POST['stok'];
        $detail = $_POST['detail'];
        
        // Validasi Sederhana
        if (empty($nama) || empty($harga) || empty($stok)) {
            echo "<script>alert('Data tidak boleh kosong!'); history.back();</script>";
            return;
        }

        // Upload Gambar
        $gambarQuery = ""; 
        $params = [
            ':kode' => $kode,
            ':nama' => $nama,
            ':harga' => $harga,
            ':stok' => $stok,
            ':detail' => $detail
        ];

        // Cek apakah user upload gambar baru?
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
            // Jika tambah baru tapi tidak upload gambar, pakai default
            $gambarQuery = ", gambar = :gambar";
            $params[':gambar'] = 'default.png';
        }

        if ($id) {
            // --- MODE UPDATE ---
            $sql = "UPDATE produk SET kode_barang=:kode, nama_produk=:nama, harga=:harga, stok=:stok, deskripsi=:detail $gambarQuery WHERE id=:id";
            $params[':id'] = $id;
        } else {
            // --- MODE INSERT ---
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

    // --- FUNGSI HAPUS ---
    // --- FUNGSI HAPUS (LENGKAP DENGAN GAMBAR) ---
    private function hapusProduk($id) {
        // 1. Ambil nama gambar dulu sebelum dihapus
        $stmtCheck = $this->db->prepare("SELECT gambar FROM produk WHERE id = :id");
        $stmtCheck->bindParam(':id', $id);
        $stmtCheck->execute();
        $produk = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        // 2. Hapus Data dari Database
        $stmt = $this->db->prepare("DELETE FROM produk WHERE id = :id");
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            // 3. Hapus File Gambar jika ada dan bukan default
            if ($produk && $produk['gambar'] !== 'default.png') {
                $filePath = "uploads/" . $produk['gambar'];
                if (file_exists($filePath)) {
                    unlink($filePath); // Hapus file fisik
                }
            }

            $_SESSION['flash_icon'] = 'success';
            $_SESSION['flash_title'] = 'Terhapus!';
            $_SESSION['flash_text'] = 'Produk berhasil dihapus.';
        } else {
            $_SESSION['flash_icon'] = 'error';
            $_SESSION['flash_title'] = 'Gagal!';
            $_SESSION['flash_text'] = 'Gagal menghapus produk dari database.';
        }
        
        header("Location: index.php?action=dashboard&page=daftar_produk");
        exit;
    }

    // --- FUNGSI READ (HELPER) ---
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

    // --- FUNGSI LAPORAN (DUMMY DATA) ---
    private function getLaporanKeuangan() {
        return [
            'pendapatan' => [1500000, 2300000, 1800000, 3200000],
            'pengeluaran' => [500000, 800000, 600000, 1200000],
            'bulan' => ['Jan', 'Feb', 'Mar', 'Apr']
        ];
    }

    private function getLaporanProduk() {
        // Ambil data real jika ada, atau kosongkan
        try {
            $stmt = $this->db->query("SELECT nama_produk, terjual FROM produk ORDER BY terjual DESC LIMIT 5");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}
?>