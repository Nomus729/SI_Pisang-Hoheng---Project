<div class="card-box" style="border:none; box-shadow:none; background:transparent;">
    <!-- Header & Filter -->
    <div class="report-header no-print">
        <h3>📊 Laporan Keuangan</h3>
        <form method="GET" action="index.php" class="filter-form">
            <input type="hidden" name="action" value="dashboard">
            <input type="hidden" name="page" value="laporan_keuangan">

            <div class="date-group">
                <label>Dari:</label>
                <input type="date" name="start_date" value="<?= $data['start_date'] ?>" required>
            </div>
            <div class="date-group">
                <label>Sampai:</label>
                <input type="date" name="end_date" value="<?= $data['end_date'] ?>" required>
            </div>
            <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> Terapkan</button>
            <button type="button" onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> Cetak</button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="dashboard-stats">
        <div class="stat-card revenue">
            <div class="icon"><i class="fas fa-wallet"></i></div>
            <div class="info">
                <small>Total Pendapatan</small>
                <h2>Rp <?= number_format($data['total_revenue'], 0, ',', '.') ?></h2>
            </div>
        </div>
        <div class="stat-card expenses">
            <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="info">
                <small>Estimasi Pengeluaran (60%)</small>
                <h2>Rp <?= number_format($data['total_revenue'] * 0.6, 0, ',', '.') ?></h2>
            </div>
        </div>
        <div class="stat-card profit">
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <div class="info">
                <small>Laba Kotor</small>
                <h2>Rp <?= number_format($data['total_revenue'] * 0.4, 0, ',', '.') ?></h2>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="charts-grid">
        <!-- Line Chart: Trend -->
        <div class="chart-container main-chart">
            <h4>📈 Grafik Tren Penjualan</h4>
            <canvas id="revenueChart"></canvas>
        </div>

        <!-- Doughnut: Categories -->
        <div class="chart-container sub-chart">
            <h4>🍕 Penjualan per Kategori</h4>
            <div style="height:250px; display:flex; justify-content:center;">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Products Table/Chart -->
    <div class="chart-container" style="margin-top:20px;">
        <h4>🏆 5 Produk Terlaris (Berdasarkan Pendapatan)</h4>
        <div class="bars-wrapper">
            <canvas id="productChart" height="100"></canvas>
        </div>
    </div>

    <!-- Print Footer -->
    <div class="print-only" style="display:none; margin-top:50px; text-align:center;">
        <p>Dicetak pada: <?= date('d M Y H:i') ?></p>
        <p>Si Pisang Hoheng Financial Report</p>
    </div>
</div>

<style>
    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .filter-form {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .date-group {
        display: flex;
        align-items: center;
        gap: 5px;
        background: #fff;
        padding: 5px 10px;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .date-group input {
        border: none;
        outline: none;
    }

    .btn-filter,
    .btn-print {
        border: none;
        padding: 8px 15px;
        border-radius: 8px;
        cursor: pointer;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
    }

    .btn-filter {
        background: #3498db;
    }

    .btn-print {
        background: #2c3e50;
    }

    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .stat-card .icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 24px;
        color: #fff;
    }

    .stat-card.revenue .icon {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
    }

    .stat-card.expenses .icon {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
    }

    .stat-card.profit .icon {
        background: linear-gradient(135deg, #3498db, #2980b9);
    }

    .stat-card h2 {
        margin: 0;
        font-size: 20px;
        color: #333;
    }

    .stat-card small {
        color: #888;
        font-weight: 600;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    .chart-container {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .chart-container h4 {
        margin: 0 0 20px 0;
        color: #444;
        font-weight: 600;
    }

    @media (max-width: 900px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    @media print {

        .no-print,
        .admin-sidebar,
        .header-admin {
            display: none !important;
        }

        .card-box {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .charts-grid {
            display: block;
        }

        .chart-container {
            break-inside: avoid;
            border: 1px solid #ddd;
            box-shadow: none;
            margin-bottom: 20px;
        }

        .print-only {
            display: block !important;
        }

        body {
            background: #fff;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const commonOptions = {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    };

    // 1. Revenue & Expense Line Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: <?= json_encode($data['chart_labels']) ?>,
            datasets: [{
                    label: 'Pendapatan',
                    data: <?= json_encode($data['chart_data']) ?>,
                    borderColor: '#2ecc71',
                    backgroundColor: 'rgba(46, 204, 113, 0.1)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Pengeluaran (Est)',
                    data: <?= json_encode($data['expense_data']) ?>,
                    borderColor: '#e74c3c',
                    backgroundColor: 'rgba(231, 76, 60, 0.05)',
                    fill: true,
                    tension: 0.3,
                    borderDash: [5, 5]
                }
            ]
        },
        options: {
            ...commonOptions,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // 2. Category Doughnut
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($data['category_labels']) ?>,
            datasets: [{
                data: <?= json_encode($data['category_data']) ?>,
                backgroundColor: ['#f1c40f', '#3498db', '#9b59b6', '#2ecc71'],
                borderWidth: 0
            }]
        },
        options: commonOptions
    });

    // 3. Product Bar Chart
    const products = <?= json_encode($data['top_products']) ?>;
    new Chart(document.getElementById('productChart'), {
        type: 'bar',
        data: {
            labels: products.map(p => p.product_name),
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: products.map(p => p.total_inc),
                backgroundColor: '#34495e',
                borderRadius: 5
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>