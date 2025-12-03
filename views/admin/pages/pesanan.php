<div class="card-box">
    <h3>📋 Daftar Pesanan Masuk</h3>
    
    <?php $currentStatus = isset($_GET['status']) ? $_GET['status'] : 'all'; ?>

    <div class="filter-bar" style="margin: 20px 0; display:flex; gap:10px;">
        <a href="index.php?action=dashboard&page=pesanan&status=all" 
           class="btn-filter <?= $currentStatus == 'all' ? 'active' : '' ?>">
           Semua
        </a>
        
        <a href="index.php?action=dashboard&page=pesanan&status=pending" 
           class="btn-filter <?= $currentStatus == 'pending' ? 'active' : '' ?>">
           Pending
        </a>
        
        <a href="index.php?action=dashboard&page=pesanan&status=proses" 
           class="btn-filter <?= $currentStatus == 'proses' ? 'active' : '' ?>">
           Proses
        </a>
        
        <a href="index.php?action=dashboard&page=pesanan&status=selesai" 
           class="btn-filter <?= $currentStatus == 'selesai' ? 'active' : '' ?>">
           Selesai
        </a>
    </div>

    <table class="table-custom">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Pemesan</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($data)): ?>
                <?php foreach($data as $row): ?>
                <tr>
                    <td><strong>#<?= $row['kode_pesanan'] ?></strong></td>
                    <td><?= $row['nama_user'] ?></td>
                    <td><?= date('d M Y H:i', strtotime($row['tanggal'])) ?></td>
                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                    <td>
                        <span class="status-badge status-<?= $row['status'] ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn-action btn-info" onclick="viewDetail(<?= $row['id'] ?>)" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>

                        <?php if($row['status'] == 'pending'): ?>
                            <button class="btn-action btn-success" onclick="updateStatus(<?= $row['id'] ?>, 'proses')" title="Proses Pesanan">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn-action btn-danger" onclick="updateStatus(<?= $row['id'] ?>, 'batal')" title="Tolak Pesanan">
                                <i class="fas fa-times"></i>
                            </button>
                        
                        <?php elseif($row['status'] == 'proses'): ?>
                            <button class="btn-action btn-primary" onclick="updateStatus(<?= $row['id'] ?>, 'selesai')" title="Selesaikan Pesanan" style="background:#007bff;">
                                <i class="fas fa-check-double"></i>
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">Belum ada pesanan masuk.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>