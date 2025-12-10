<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?></title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="no-animation">

    <?php include 'views/components/header.php'; ?>

    <main class="container">

        <div class="page-header-wrapper">
            <a href="index.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Menu
            </a>
            <div class="page-title-box">
                <i class="fas fa-shopping-cart"></i> Keranjang Belanja
            </div>
        </div>

        <div class="cart-layout">

            <div class="cart-items-container">
                <?php if (empty($data['cart_items'])): ?>
                    <div style="text-align: center; padding: 60px; background: #fff; border-radius: 15px; width: 100%;">
                        <i class="fas fa-shopping-basket" style="font-size: 3rem; color: #ddd; margin-bottom: 15px;"></i>
                        <h3 style="color: #555;">Keranjang Masih Kosong</h3>
                        <p style="color: #888;">Yuk pesan pisang goreng sekarang!</p>
                        <a href="index.php" class="btn-checkout" style="width: fit-content; margin: 20px auto; padding: 10px 30px;">Belanja Sekarang</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($data['cart_items'] as $item): ?>
                        <div class="cart-item-card" data-id="<?= $item['id'] ?>" data-price="<?= $item['price'] ?>">

                            <div class="cart-img">
                                <img src="<?= $item['image'] ?>" alt="<?= $item['product_name'] ?>">
                            </div>

                            <div class="cart-details">
                                <h3><?= $item['product_name'] ?></h3>
                                <div class="cart-toppings">
                                    <span class="badge">Original</span>
                                </div>
                                <div class="cart-price">Rp <?= number_format($item['price'], 0, ',', '.') ?></div>
                            </div>

                            <div class="cart-actions-right">
                                <div class="qty-control sm">
                                    <button class="cart-qty-btn" data-action="decrease">-</button>
                                    <span class="cart-qty-val"><?= $item['qty'] ?></span>
                                    <button class="cart-qty-btn" data-action="increase">+</button>
                                </div>

                                <button class="btn-trash" onclick="deleteCartItem(this, <?= $item['id'] ?>)" title="Hapus Item">
                                    <i class="fas fa-trash" style="pointer-events: none;"></i>
                                </button>
                            </div>

                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="cart-sidebar">
                <div class="note-box">
                    <label>Catatan Pesanan</label>
                    <textarea id="cartNote"
                        placeholder="Contoh: Jangan terlalu gosong..."
                        oninput="localStorage.setItem('userOrderNote', this.value)"></textarea>
                </div>

                <div class="cart-summary">
                    <div class="summary-row">
                        <span>Total Item</span>
                        <span id="cart-total-qty"><?= $data['total_qty'] ?> Pcs</span>
                    </div>

                    <div class="summary-row total">
                        <span>Subtotal</span>
                        <span id="cart-subtotal">Rp <?= number_format($data['subtotal'], 0, ',', '.') ?></span>
                    </div>

                    <?php if (!empty($data['cart_items'])): ?>
                        <a href="index.php?action=checkout" class="btn-checkout">Buat Pesanan</a>
                    <?php else: ?>
                        <button class="btn-checkout" style="background: #ccc; cursor: not-allowed;" disabled>Keranjang Kosong</button>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>
    <?php include 'views/components/footer_scripts.php'; ?>

    <script>
        // Isi otomatis catatan dari memori jika ada
        document.addEventListener("DOMContentLoaded", function() {
            const savedNote = localStorage.getItem('userOrderNote');
            const noteInput = document.getElementById('cartNote');
            if (savedNote && noteInput) {
                noteInput.value = savedNote;
            }
        });

        // FUNGSI HAPUS ITEM (DIRECT HANDLER)
        function deleteCartItem(btn, cartId) {
            Swal.fire({
                title: 'Hapus item?',
                text: "Item akan dihapus permanen dari keranjang.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const card = btn.closest('.cart-item-card');

                    fetch('index.php?action=remove_item', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                cart_id: cartId
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                if (card) {
                                    card.style.transition = "all 0.4s ease";
                                    card.style.opacity = "0";
                                    setTimeout(() => {
                                        card.remove();
                                        // Reload halaman agar kalkulasi ulang
                                        location.reload();
                                    }, 400);
                                } else {
                                    location.reload();
                                }
                            } else {
                                Swal.fire('Gagal', 'Tidak dapat menghapus item.', 'error');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error');
                        });
                }
            });
        }
    </script>
</body>

</html>