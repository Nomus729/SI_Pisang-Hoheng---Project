<div class="card-box">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h3>📦 Daftar Produk</h3>
        <a href="index.php?action=dashboard&page=detail_produk" class="btn-signin" style="background:#89CFF0; color:fff;">+ Tambah Produk</a>
    </div>
    
    <table class="table-custom">
        <thead>
            <tr>
                <th>ID</th>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Terjual</th>
                <th>Aksi</th>
            </tr>
        </thead>
       <tbody>
            <?php if(!empty($data)): ?>
                <?php foreach($data as $row): ?>
                <tr>
                    <td>#<?= $row['kode_barang'] ?></td>
                    <td>
                        <img src="uploads/<?= $row['gambar'] ?>" width="50" height="50" style="object-fit:cover; border-radius:5px;" 
                             onerror="this.src='https://placehold.co/50'">
                    </td>
                    <td><?= $row['nama_produk'] ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td>
                        <span style="color: <?= ($row['stok'] < 5) ? 'red' : 'green' ?>; font-weight:bold;">
                            <?= $row['stok'] ?>
                        </span>
                    </td>
                    <td><?= $row['terjual'] ?></td>
                    <td>
                        <a href="index.php?action=dashboard&page=detail_produk&id=<?= $row['id'] ?>" 
                           class="btn-action btn-edit" title="Edit Data">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <a href="javascript:void(0);" onclick="confirmDelete('<?= $row['id'] ?>')" 
                           class="btn-action btn-delete" title="Hapus Data">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align:center; padding:20px;">Belum ada data produk.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Yakin hapus produk ini?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#89CFF0',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect ke URL penghapusan
            window.location.href = `index.php?action=dashboard&page=hapus_produk&id=${id}`;
        }
    })
}
</script>
    </table>
</div>