<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?></title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="no-animation">
    
    <?php include 'views/components/header.php'; ?>

    <main class="container" style="margin-top: 40px;">
        
        <div class="page-header-wrapper">
            <a href="index.php?action=cart" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Keranjang
            </a>
            <div class="page-title-box">
                <i class="fas fa-wallet"></i> Pembayaran
            </div>
        </div>

        <div class="checkout-wrapper">
            <div class="checkout-card-blue">
                
                <div class="checkout-section">
                    <h3>Rincian Pembelian</h3>
                    <div class="order-table">
                        <div class="tbl-head">
                            <span>Produk</span>
                            <span class="text-center">Qty</span>
                            <span class="text-right">Total</span>
                        </div>
                        <?php foreach($data['items'] as $item): ?>
                        <div class="tbl-row">
                            <span><?= $item['product_name'] ?></span>
                            <span class="text-center"><?= $item['qty'] ?></span>
                            <span class="text-right">Rp <?= number_format($item['price'] * $item['qty'], 0, ',', '.') ?></span>
                        </div>
                        <?php endforeach; ?>
                        <div class="tbl-row total-row">
                            <span><strong>Subtotal</strong></span>
                            <span></span>
                            <span class="text-right"><strong>Rp <?= number_format($data['subtotal'],0,',','.') ?></strong></span>
                        </div>
                    </div>
                </div>

                <hr class="divider-white">

                <div class="checkout-section">
                    <h3>Metode Pengiriman</h3>
                    <div class="option-grid">
                        <label class="option-card">
                            <input type="radio" name="delivery" value="pickup" checked onclick="toggleAddress(false)">
                            <div class="card-content">
                                <i class="fas fa-store"></i>
                                <span>Ambil Sendiri</span>
                            </div>
                        </label>
                        <label class="option-card">
                            <input type="radio" name="delivery" value="delivery" onclick="toggleAddress(true)">
                            <div class="card-content">
                                <i class="fas fa-motorcycle"></i>
                                <span>Delivery</span>
                            </div>
                        </label>
                    </div>
                    
                    <div id="addressBox" class="address-box hidden">
                        <label>Alamat Pengantaran</label>
                        <textarea placeholder="Masukkan alamat lengkap..."></textarea>
                        <div class="shipping-fee">
                            <span>Ongkir:</span>
                            <strong>Rp 10.000</strong>
                        </div>
                    </div>
                </div>

                <hr class="divider-white">
                <div class="checkout-section">
                    <h3>Catatan Pesanan</h3>
                    <textarea id="orderNote" 
                              style="width:100%; padding:10px; border:1px solid #ddd; border-radius:10px;" 
                              rows="3" 
                              placeholder="Pesan khusus untuk penjual..."
                              oninput="localStorage.setItem('userOrderNote', this.value)"></textarea>
                </div>
                <div class="checkout-section">
                    <h3>Metode Pembayaran</h3>
                    <div class="option-grid">
                        <label class="option-card">
                            <input type="radio" name="payment" value="cash" checked onclick="toggleQris(false)">
                            <div class="card-content">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Tunai</span>
                            </div>
                        </label>
                        <label class="option-card">
                            <input type="radio" name="payment" value="qris" onclick="toggleQris(true)">
                            <div class="card-content">
                                <i class="fas fa-qrcode"></i>
                                <span>QRIS</span>
                            </div>
                        </label>
                    </div>

                    <div id="qrisBox" class="qris-box hidden">
                        <p>Scan QR Code di bawah ini:</p>
                        <img src="https://placehold.co/200x200/fff/000?text=QRIS+CODE" alt="QRIS">
                    </div>
                </div>

            </div>

            <div class="checkout-footer">
                <div class="total-display">
                    <span>Total Bayar:</span>
                    <h2 id="grandTotal">Rp <?= number_format($data['subtotal'],0,',','.') ?></h2>
                </div>
                <button class="btn-pay" onclick="processPayment()">
                    Bayar Sekarang
                </button>
            </div>
        </div>
    </main>

    <script src="public/js/script.js"></script>
    <script>

        document.addEventListener("DOMContentLoaded", function() {
            const savedNote = localStorage.getItem('userOrderNote');
            const noteInput = document.getElementById('orderNote');
            if(savedNote && noteInput) {
                noteInput.value = savedNote;
            }
        });
        // ... (fungsi toggleAddress dan toggleQris biarkan sama) ...
        function toggleAddress(show) {
            const box = document.getElementById('addressBox');
            const totalElem = document.getElementById('grandTotal');
            let currentTotal = <?= $data['subtotal'] ?>;
            
            if(show) {
                box.classList.remove('hidden');
                totalElem.innerText = "Rp " + (currentTotal + 10000).toLocaleString('id-ID').replace(/,/g, '.');
            } else {
                box.classList.add('hidden');
                totalElem.innerText = "Rp " + currentTotal.toLocaleString('id-ID').replace(/,/g, '.');
            }
        }

        function toggleQris(show) {
            const box = document.getElementById('qrisBox');
            if(show) box.classList.remove('hidden');
            else box.classList.add('hidden');
        }

        // --- REVISI FUNGSI PEMBAYARAN ---
        
        function processPayment() {
            const deliveryType = document.querySelector('input[name="delivery"]:checked').value;
            const paymentType = document.querySelector('input[name="payment"]:checked').value;
            const addressVal = document.querySelector('#addressBox textarea').value;
            

            // AMBIL CATATAN
            const noteVal = document.getElementById('orderNote').value;

            if (deliveryType === 'delivery' && addressVal.trim() === "") {
                Swal.fire('Gagal', 'Alamat harus diisi!', 'warning');
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: "Pastikan pesanan sudah benar.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#89CFF0',
                confirmButtonText: 'Ya, Bayar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Memproses...', didOpen: () => Swal.showLoading() });

                    fetch('index.php?action=place_order', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            delivery: deliveryType,
                            payment: paymentType,
                            address: addressVal,
                            note: noteVal // KIRIM CATATAN
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.status === 'success') {
                            localStorage.removeItem('userOrderNote');
                            Swal.fire({
                                icon: 'success', title: 'Berhasil!', confirmButtonColor: '#89CFF0'
                            }).then(() => { window.location.href = 'index.php'; });
                        } else {
                            Swal.fire('Gagal', data.message, 'error');
                        }
                    });
                }
            });
        }
    </script>
</body>
</html>