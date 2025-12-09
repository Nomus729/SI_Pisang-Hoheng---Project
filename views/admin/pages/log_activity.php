<div class="page-header">
    <h2 class="page-title"><i class="fas fa-history"></i> Log Aktivitas</h2>
</div>

<div class="card fade-in-up">
    <div class="card-header">
        <h4>Riwayat Login System</h4>
        <div class="header-actions">
            <!-- Bisa ditambah filter tanggal nanti -->
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User / Admin</th>
                        <th>Role</th>
                        <th>Aktivitas</th>
                        <th>IP Address</th>
                        <th>Perangkat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data)): ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data aktivitas.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data as $log): ?>
                            <tr class="slide-in-row">
                                <td><?= date('d M Y H:i:s', strtotime($log['created_at'])) ?></td>
                                <td>
                                    <div class="user-info">
                                        <div class="avatar-circle <?= $log['role'] == 'admin' ? 'admin-avatar' : 'user-avatar' ?>">
                                            <?= strtoupper(substr($log['nama_user'], 0, 1)) ?>
                                        </div>
                                        <span><?= htmlspecialchars($log['nama_user']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge <?= $log['role'] == 'admin' ? 'badge-danger' : 'badge-primary' ?>">
                                        <?= ucfirst($log['role']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-text success"><i class="fas fa-check-circle"></i> <?= $log['action'] ?></span>
                                </td>
                                <td><span class="text-muted"><?= $log['ip_address'] ?></span></td>
                                <td class="text-small text-muted" title="<?= $log['device_info'] ?>">
                                    <?= substr($log['device_info'], 0, 30) ?>...
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .slide-in-row {
        animation: slideInRight 0.4s ease-out forwards;
        opacity: 0;
        /* Awal tersembunyi */
    }

    /* Staggered Delay for Rows */
    /* Assuming max 20 rows visible for performance */
    tr.slide-in-row:nth-child(1) {
        animation-delay: 0.1s;
    }

    tr.slide-in-row:nth-child(2) {
        animation-delay: 0.2s;
    }

    tr.slide-in-row:nth-child(3) {
        animation-delay: 0.3s;
    }

    tr.slide-in-row:nth-child(4) {
        animation-delay: 0.4s;
    }

    tr.slide-in-row:nth-child(5) {
        animation-delay: 0.5s;
    }

    tr.slide-in-row:nth-child(6) {
        animation-delay: 0.6s;
    }

    tr.slide-in-row:nth-child(7) {
        animation-delay: 0.7s;
    }

    tr.slide-in-row:nth-child(8) {
        animation-delay: 0.8s;
    }

    tr.slide-in-row:nth-child(9) {
        animation-delay: 0.9s;
    }

    tr.slide-in-row:nth-child(10) {
        animation-delay: 1.0s;
    }

    /* Styles */
    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .avatar-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
    }

    .admin-avatar {
        background: linear-gradient(135deg, #ff416c, #ff4b2b);
    }

    .user-avatar {
        background: linear-gradient(135deg, #1fa2ff, #12d8fa);
    }

    .badge-danger {
        background-color: #ffecec;
        color: #ff416c;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
    }

    .badge-primary {
        background-color: #e3f2fd;
        color: #1e88e5;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
    }

    .text-small {
        font-size: 0.85rem;
    }
</style>