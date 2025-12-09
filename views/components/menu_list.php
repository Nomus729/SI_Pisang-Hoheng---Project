<div class="menu-grid">
    <?php if (count($data['menu_items']) > 0): ?>
        <?php foreach ($data['menu_items'] as $item): ?>
            <div class="menu-card">
                <div class="card-img">
                    <img src="uploads/<?= $item['gambar'] ?>" alt="<?= $item['nama_produk'] ?>" onerror="this.src='https://placehold.co/200x150'">
                </div>
                <div class="card-body">
                    <h4><?= $item['nama_produk'] ?></h4>
                    <div class="meta-info" style="display: flex; gap: 10px; font-size: 0.8rem; color: #666; margin-bottom: 10px;">
                        <span class="storage"><i class="fas fa-box"></i> Stok: <?= $item['stok'] ?></span>
                        <span class="sold"><i class="fas fa-fire text-danger"></i> <?= $item['terjual'] ?? 0 ?> Terjual</span>
                        <span class="rating"><i class="fas fa-star text-warning"></i> 4.9</span>
                    </div>
                    <div class="card-actions">
                        <div class="qty-control">
                            <button class="btn-qty btn-minus">-</button>
                            <span class="qty-val">1</span>
                            <button class="btn-qty btn-plus">+</button>
                        </div>
                        <button class="cart-btn add-to-cart-btn"
                            data-name="<?= $item['nama_produk'] ?>"
                            data-price="<?= $item['harga'] ?>"
                            data-image="uploads/<?= $item['gambar'] ?>">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div style="grid-column: 1/-1; text-align: center; padding: 40px;">
            <p>Tidak ada produk ditemukan.</p>
        </div>
    <?php endif; ?>
</div>

<div class="pagination-container">
    <?php for ($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
        <?php
        $searchParam = isset($data['pagination']['search_query']) && !empty($data['pagination']['search_query'])
            ? '&search=' . urlencode($data['pagination']['search_query'])
            : '';
        $catParam = isset($data['pagination']['current_category']) && !empty($data['pagination']['current_category'])
            ? '&kategori=' . urlencode($data['pagination']['current_category'])
            : '';
        ?>
        <a href="index.php?halaman=<?= $i ?><?= $searchParam ?><?= $catParam ?>#menu" class="page-link <?= ($i == $data['pagination']['current_page']) ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
</div>