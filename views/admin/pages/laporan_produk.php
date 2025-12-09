<?php
$months = [
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember'
];
?>

<div class="page-header highlight-text">
    <h2 class="page-title"><i class="fas fa-chart-pie"></i> Statistik Produk</h2>
</div>

<!-- Filter Section -->
<div class="filter-card fade-in">
    <form method="GET" action="index.php" class="filter-form">
        <input type="hidden" name="action" value="dashboard">
        <input type="hidden" name="page" value="laporan_produk">

        <div class="filter-group">
            <label>Bulan:</label>
            <select name="bulan" onchange="this.form.submit()">
                <?php foreach ($months as $k => $v): ?>
                    <option value="<?= $k ?>" <?= $k == $data['selected_bulan'] ? 'selected' : '' ?>>
                        <?= $v ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-group">
            <label>Tahun:</label>
            <select name="tahun" onchange="this.form.submit()">
                <?php for ($y = date('Y'); $y >= 2023; $y--): ?>
                    <option value="<?= $y ?>" <?= $y == $data['selected_tahun'] ? 'selected' : '' ?>>
                        <?= $y ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="stats-grid">
    <div class="stat-card blue fade-in-up" style="animation-delay: 0.1s">
        <div class="icon"><i class="fas fa-boxes"></i></div>
        <div class="info">
            <h3><?= $data['total_terjual'] ?></h3>
            <p>Terjual Bulan Ini</p>
        </div>
    </div>

    <div class="stat-card red fade-in-up" style="animation-delay: 0.2s">
        <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="info">
            <h3><?= $data['low_stock'] ?></h3>
            <p>Stok Menipis (< 10)</p>
        </div>
    </div>

    <div class="stat-card green fade-in-up" style="animation-delay: 0.3s">
        <div class="icon"><i class="fas fa-crown"></i></div>
        <div class="info">
            <h3><?= !empty($data['top_products']) ? substr($data['top_products'][0]['product_name'], 0, 15) . '..' : '-' ?></h3>
            <p>Produk Terlaris</p>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Chart Section -->
    <div class="card chart-card fade-in-up" style="animation-delay: 0.4s">
        <div class="card-header">
            <h4>Tren Penjualan (<?= $months[$data['selected_bulan']] ?> <?= $data['selected_tahun'] ?>)</h4>
        </div>
        <div class="card-body">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Top Products Table -->
    <div class="card top-products-card fade-in-up" style="animation-delay: 0.5s">
        <div class="card-header">
            <h4>Top 5 Produk Terlaris</h4>
        </div>
        <div class="card-body">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th class="text-right">Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['top_products'])): ?>
                        <tr>
                            <td colspan="2" class="text-center">Tidak ada data penjualan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data['top_products'] as $idx => $prod): ?>
                            <tr>
                                <td>
                                    <?php if ($idx == 0): ?> <i class="fas fa-medal gold"></i> <?php endif; ?>
                                    <?php if ($idx == 1): ?> <i class="fas fa-medal silver"></i> <?php endif; ?>
                                    <?php if ($idx == 2): ?> <i class="fas fa-medal bronze"></i> <?php endif; ?>
                                    <?= htmlspecialchars($prod['product_name']) ?>
                                </td>
                                <td class="text-right fw-bold"><?= $prod['total_qty'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($data['chart_labels']) ?>,
            datasets: [{
                label: 'Jumlah Terjual',
                data: <?= json_encode($data['chart_data']) ?>,
                borderColor: '#4facfe',
                backgroundColor: 'rgba(79, 172, 254, 0.2)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#00f2fe',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        borderDash: [5, 5]
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>

<style>
    /* Dashboard Specific Layout */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-top: 20px;
    }

    @media (max-width: 900px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card .icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .stat-card.blue .icon {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
    }

    .stat-card.red .icon {
        background: linear-gradient(135deg, #ff416c, #ff4b2b);
    }

    .stat-card.green .icon {
        background: linear-gradient(135deg, #43e97b, #38f9d7);
    }

    .stat-card .info h3 {
        margin: 0;
        font-size: 1.8rem;
        color: #333;
    }

    .stat-card .info p {
        margin: 0;
        color: #777;
        font-size: 0.9rem;
    }

    /* Filter */
    .filter-card {
        background: white;
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        display: inline-block;
    }

    .filter-form {
        display: flex;
        gap: 20px;
        align-items: center;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-group select {
        padding: 8px 15px;
        border: 1px solid #eee;
        border-radius: 8px;
        background: #f9f9f9;
        cursor: pointer;
        outline: none;
    }

    /* Medals */
    .gold {
        color: #ffd700;
    }

    .silver {
        color: #c0c0c0;
    }

    .bronze {
        color: #cd7f32;
    }

    /* Animations */
    .fade-in {
        animation: fadeIn 0.8s ease-out forwards;
        opacity: 0;
    }

    .fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
        opacity: 0;
    }

    @keyframes fadeIn {
        to {
            opacity: 1;
        }
    }

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
</style>