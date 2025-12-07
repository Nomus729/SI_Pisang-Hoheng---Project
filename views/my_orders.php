<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?></title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="no-animation">
    
    <?php include 'views/components/header.php'; ?>

    <main class="container" style="margin-top: 40px;">
        
        <div class="page-header-wrapper">
            <a href="index.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Menu
            </a>
            <div class="page-title-box">
                <i class="fas fa-receipt"></i> Pesanan Saya
            </div>
        </div>

        <div class="orders-container">
            
            <?php $s = $data['current_status']; ?>
            <div class="user-filter-bar">
                <a href="index.php?action=my_orders&status=all" 
                   class="user-filter-btn <?= $s == 'all' ? 'active' : '' ?>">Semua</a>
                   
                <a href="index.php?action=my_orders&status=pending" 
                   class="user-filter-btn <?= $s == 'pending' ? 'active' : '' ?>">Menunggu</a>
                   
                <a href="index.php?action=my_orders&status=proses" 
                   class="user-filter-btn <?= $s == 'proses' ? 'active' : '' ?>">Diproses</a>
                   
                <a href="index.php?action=my_orders&status=selesai" 
                   class="user-filter-btn <?= $s == 'selesai' ? 'active' : '' ?>">Selesai</a>
                   
                <a href="index.php?action=my_orders&status=batal" 
                   class="user-filter-btn <?= $s == 'batal' ? 'active' : '' ?>">Dibatalkan</a>
            </div>

            <?php if(empty($data['orders'])): ?>
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h3>Tidak ada pesanan di tab ini</h3>
                    <p>Coba cek tab lain atau buat pesanan baru.</p>
                    <a href="index.php" class="btn-checkout">Belanja Sekarang</a>
                </div>
            <?php else: ?>
                
                <div class="order-grid">
                    <?php foreach($data['orders'] as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-date">
                                <i class="far fa-calendar-alt"></i> 
                                <?= date('d M Y, H:i', strtotime($order['tanggal'])) ?>
                            </span>
                            
                            <?php
                                $statusClass = '';
                                if($order['status'] == 'pending') $statusClass = 'status-pending';
                                else if($order['status'] == 'proses') $statusClass = 'status-proses';
                                else if($order['status'] == 'selesai') $statusClass = 'status-selesai';
                                else $statusClass = 'status-batal';
                            ?>
                            <span class="status-pill <?= $statusClass ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </div>

                        <div class="order-body">
                            <div class="order-items-list">
                                <?php foreach($order['items'] as $item): ?>
                                    <div class="item-row">
                                        <span class="item-name"><?= $item['product_name'] ?></span>
                                        <span class="item-qty">x<?= $item['qty'] ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <?php if($order['status'] == 'batal' && !empty($order['catatan_tolak'])): ?>
                            <div class="reject-alert">
                                <i class="fas fa-exclamation-circle"></i>
                                <div>
                                    <strong>Pesanan Ditolak:</strong><br>
                                    "<?= $order['catatan_tolak'] ?>"
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="order-footer">
                            <div class="total-price">
                                <small>Total Belanja</small>
                                <strong>Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></strong>
                            </div>
                            
                           <?php if($order['status'] == 'selesai'): ?>
        
                                <a href="index.php?action=reorder&id=<?= $order['id'] ?>" class="btn-review" style="text-decoration: none; display: inline-block; text-align: center;">
                                    Beli Lagi
                                </a>
                            <?php else: ?>
                                <button class="btn-track" onclick="trackOrder('<?= $order['kode_pesanan'] ?>')">
                                    Lacak
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>
        </div>

    </main>

    <?php include 'views/components/footer_scripts.php'; ?>
    
    <script>
        function trackOrder(kode) {
            Swal.fire({
                title: 'Lacak Pesanan',
                text: 'Kode Pesanan: ' + kode + '\nSilakan hubungi admin jika pesanan belum diproses.',
                icon: 'info',
                confirmButtonColor: '#89CFF0'
            });
        }
    </script>
</body>
</html>