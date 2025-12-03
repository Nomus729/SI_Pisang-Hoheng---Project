<div class="card-box">
    <div style="display:flex; justify-content:space-between; margin-bottom:20px;">
        <h3>📊 Laporan Keuangan</h3>
        <select style="padding:5px; border-radius:5px;">
            <option>Bulan Ini</option>
            <option>Tahun Ini</option>
        </select>
    </div>
    <canvas id="financeChart" height="100"></canvas>
    <button onclick="window.print()" class="btn-signin" style="margin-top:20px; background:#555;">Cetak Laporan</button>
</div>

<script>
    const ctx = document.getElementById('financeChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($data['bulan']) ?>,
            datasets: [{
                label: 'Pendapatan',
                data: <?= json_encode($data['pendapatan']) ?>,
                borderColor: '#4CAF50',
                fill: false
            }, {
                label: 'Pengeluaran',
                data: <?= json_encode($data['pengeluaran']) ?>,
                borderColor: '#FF5722',
                fill: false
            }]
        }
    });
</script>