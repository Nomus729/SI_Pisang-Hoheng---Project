# 🍌 SI Pisang Hoheng - Sistem Informasi Penjualan UMKM

[![PHP Version](https://img.shields.io/badge/php-%5E7.4%20%7C%20%5E8.0-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Database](https://img.shields.io/badge/database-MySQL-orange.svg)](https://mysql.com)
[![Composer](https://img.shields.io/badge/dependency-Composer-purple.svg)](https://getcomposer.org)

**SI Pisang Hoheng** adalah aplikasi web Sistem Informasi Manajemen Penjualan yang dirancang khusus untuk UMKM kuliner pisang goreng (Pisang Hoheng). Proyek ini dirancang menggunakan arsitektur **Model-View-Controller (MVC) PHP Native** dengan mengadopsi standar industri modern untuk menghasilkan kode yang bersih, terstruktur, aman, dan siap dipublikasikan ke portfolio GitHub.

---

## ✨ Fitur Utama

### 🛒 Sisi Pelanggan (Customer Facing)
*   **Menu Interaktif & Dinamis**: Pencarian produk, filter kategori makanan/minuman, dan pagination.
*   **Keranjang Belanja Real-Time**: Penambahan, pengurangan kuantitas, dan penghapusan item langsung di keranjang.
*   **Checkout Fleksibel**: Pilihan opsi pengiriman (*Pickup* / *Delivery*) dan simulasi pembayaran terintegrasi (Cash / QRIS).
*   **Riwayat Pesanan & Beli Lagi (Reorder)**: Memungkinkan pelanggan memesan kembali menu favorit dari riwayat transaksi mereka dengan satu klik.

### 💼 Panel Admin (Back-Office Dashboard)
*   **Dashboard Statistik Ringkas**: Menampilkan total produk, total pesanan masuk, total omzet, dan log aktivitas terbaru.
*   **Grafik Analitik Finansial**: Visualisasi tren pendapatan harian dan grafik perbandingan kategori produk (Makanan vs Minuman).
*   **Manajemen Produk (CRUD)**: Kelola data menu, harga, stok, deskripsi komposisi, dan upload foto produk (dengan auto-restock saat pesanan batal).
*   **Manajemen Pesanan**: Sistem penerimaan, pemrosesan, penyelesaian, dan penolakan pesanan (dengan pengisian alasan pembatalan).
*   **Log Aktivitas Keamanan**: Rekaman audit log login pengguna, IP address, dan info perangkat per sesi.
*   **Pengaturan Situs Dinamis**: Edit alamat toko, nomor telepon, jam operasional, tautan sosial media, dan embed peta Google Maps secara realtime dari dashboard.

---

## 🛠️ Tech Stack & Standar Industri

*   **Backend**: PHP Native 7.4+ / 8.0+
*   **Database**: MySQL dengan konektivitas **PDO (PHP Data Objects)**
*   **Autoloading**: Standar **PSR-4 Autoloading** menggunakan Composer (menghapus `require_once` manual)
*   **Manajemen Environment**: Menggunakan **Dotenv (`.env`)** untuk memisahkan kredensial database sensitif dari kode sumber
*   **Keamanan**: 
    *   *SQL Injection Protection*: Penggunaan prepared statements & parameter binding pada seluruh query database.
    *   *Session Security*: Proteksi Session Fixation (`session_regenerate_id`) dan konfigurasi cookie session yang aman (`httponly`, `samesite`, `secure`).
    *   *Password Hashing*: Enkripsi kata sandi menggunakan `password_hash()` dengan algoritma bcrypt.
*   **Frontend**: HTML5, CSS3 (Custom Variables, Flexbox, Grid, Glassmorphic Glass-UI), JavaScript ES6 (Fetch API, Swiper.js, Chart.js, SweetAlert2).

---

## 📂 Struktur Direktori Project

```text
├── config/              # Konfigurasi Koneksi Database
│   └── Database.php
├── controllers/         # Logika Pengendali Aplikasi (Controller)
│   ├── AdminController.php
│   ├── AuthController.php
│   ├── CartController.php
│   └── HomeController.php
├── models/              # Logika Bisnis & Struktur Data (Model)
│   ├── Cart.php
│   ├── Log.php
│   └── User.php
├── views/               # Tampilan Antarmuka (View)
│   ├── admin/           # Halaman Dashboard Admin
│   ├── components/      # Komponen Header, Footer, dll
│   ├── home.php         # Tampilan Utama Landing Page
│   └── ...
├── public/              # Aset Statis Publik
│   ├── css/             # Stylesheet (style.css, admin.css)
│   ├── img/             # Logo, Ikon SVG, Banner
│   └── js/              # File Script JavaScript
├── uploads/             # Direktori Unggah Foto Produk
├── .env.example         # Template Konfigurasi Environment
├── composer.json        # Pengaturan Dependensi & Autoloader
├── database_sipisang.sql# Database MySQL Dump
├── index.php            # Front Controller & Entry Point Utama
└── README.md
```

---

## 🚀 Panduan Instalasi Lokal

### Prasyarat
1.  **PHP 7.4** ke atas terinstal di komputer.
2.  **MySQL Server** (misalnya via XAMPP, Laragon, atau Docker).
3.  **Composer** terinstal global.

### Langkah-Langkah
1.  **Clone repositori ini**:
    ```bash
    git clone https://github.com/username/si-pisang-hoheng.git
    cd si-pisang-hoheng
    ```
2.  **Instal dependensi Composer**:
    ```bash
    composer install
    ```
3.  **Setup Environment Variables**:
    Salin file `.env.example` menjadi `.env` dan sesuaikan kredensial database Anda:
    ```bash
    cp .env.example .env
    ```
    Isi di dalam `.env`:
    ```ini
    DB_HOST=localhost
    DB_PORT=3306
    DB_NAME=db_sipisang
    DB_USER=root
    DB_PASS=your_password
    ```
4.  **Import Database**:
    Buat database baru bernama `db_sipisang` di MySQL Anda, lalu import file `database_sipisang.sql`.
5.  **Jalankan Server Lokal**:
    Jalankan PHP built-in server melalui terminal:
    ```bash
    php -S localhost:8000
    ```
6.  **Akses Website**:
    Buka browser Anda dan navigasikan ke `http://localhost:8000`.

---

## 🔑 Kredensial Default Pengguna

Gunakan akun berikut untuk masuk ke sistem:

*   **Pelanggan (Customer)**:
    *   Email: `dwdw@gmail.com`
    *   Password: `dwdw`
*   **Administrator**:
    *   Akses halaman inisialisasi / reset jika diperlukan: `http://localhost:8000/reset_admin.php`
    *   Email: `admin@sipisang.com`
    *   Password: `admin123`

---

## 📄 Lisensi
Proyek ini dilisensikan di bawah Lisensi MIT - lihat file [LICENSE](LICENSE) untuk detailnya.
