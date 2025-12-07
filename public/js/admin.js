document.addEventListener("DOMContentLoaded", function () {
    const notifBtn = document.getElementById('notifBtn');
    const notifDropdown = document.getElementById('notifDropdown');
    const notifCount = document.getElementById('notifCount');
    const notifList = document.getElementById('notifList');
    const sound = document.getElementById('notifSound');

    // Variabel untuk melacak jumlah notif sebelumnya (agar suara tidak bunyi terus)
    let previousCount = 0;

    // 1. Toggle Dropdown Notifikasi
    if (notifBtn) {
        notifBtn.addEventListener('click', () => {
            notifDropdown.classList.toggle('hidden');
            // Saat dibuka, jangan sembunyikan badge, biarkan admin melihat jumlahnya
            // notifCount.classList.add('hidden'); 
        });
    }

    // 2. FUNGSI CEK ORDER DARI DATABASE (Realtime Polling)
    function checkNotifications() {
        fetch('index.php?action=dashboard&action_ajax=check_notif')
            .then(response => response.json())
            .then(data => {
                const count = data.count;
                const orders = data.orders;

                // Update Badge Angka
                if (count > 0) {
                    notifCount.classList.remove('hidden');
                    notifCount.innerText = count;
                } else {
                    notifCount.classList.add('hidden');
                }

                // Cek apakah ada pesanan baru masuk? (Jika jumlah bertambah)
                if (count > previousCount) {
                    // Mainkan Suara "Ting!"
                    if(sound) {
                        sound.play().catch(error => console.log('Audio dicegah browser, perlu interaksi user.'));
                    }
                    
                    // Tampilkan SweetAlert Kecil (Toast) di pojok
                    const Toast = Swal.mixin({
                        toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
                    });
                    Toast.fire({ icon: 'info', title: 'Ada Pesanan Baru Masuk!' });
                }

                // Update isi Dropdown
                if (count > 0) {
                    notifList.innerHTML = ''; // Bersihkan list lama
                    
                    orders.forEach(order => {
                        const item = document.createElement('div');
                        item.className = 'notif-item';
                        // Format Rupiah
                        let harga = new Intl.NumberFormat('id-ID').format(order.total_harga);
                        
                        item.innerHTML = `
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <b>${order.nama_user}</b><br>
                                    <small>Total: Rp ${harga}</small>
                                </div>
                                <a href="index.php?action=dashboard&page=pesanan" style="font-size:0.8rem; color:#89CFF0;">Lihat</a>
                            </div>
                        `;
                        notifList.appendChild(item);
                    });
                } else {
                    notifList.innerHTML = '<p class="empty-notif">Tidak ada pesanan baru</p>';
                }

                // Simpan jumlah terakhir
                previousCount = count;
            })
            .catch(err => console.error("Gagal cek notif:", err));
    }

    // Jalankan pengecekan pertama kali saat halaman dimuat
    checkNotifications();

    // Jalankan pengecekan setiap 5 detik (5000 ms)
    setInterval(checkNotifications, 5000);


    // ============================================
    // BAGIAN SWEETALERT (FLASH DATA)
    // ============================================
    const flashData = document.getElementById('flash-data');
    if (flashData) {
        const icon = flashData.getAttribute('data-icon');
        const title = flashData.getAttribute('data-title');
        const text = flashData.getAttribute('data-text');

        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            confirmButtonColor: '#89CFF0',
            confirmButtonText: 'Oke Siap!'
        });
    }
    
});

// =========================================
// 4. MANAJEMEN PESANAN (AKSI ADMIN)
// =========================================

// A. Lihat Detail Pesanan (Modal)
function viewDetail(orderId) {
    const modal = document.getElementById('detailModalAdmin');
    
    // Tampilkan Loading dulu
    Swal.fire({ title: 'Memuat Data...', didOpen: () => Swal.showLoading() });

    // Ambil data dari server
    fetch(`index.php?action=dashboard&action_ajax=get_detail&id=${orderId}`)
        .then(res => res.json())
        .then(data => {
            Swal.close(); // Tutup loading
            
            const order = data.order;
            const items = data.items;

            // Isi Data ke Modal
            document.getElementById('modalKode').innerText = '#' + order.kode_pesanan;
            document.getElementById('modalNama').innerText = order.nama_user;
            document.getElementById('modalTanggal').innerText = order.tanggal;
            
            // Format Status Badge
            const badgeClass = `status-${order.status}`;
            document.getElementById('modalStatus').innerHTML = `<span class="status-badge ${badgeClass}">${order.status.toUpperCase()}</span>`;
            
            document.getElementById('modalMetode').innerText = order.metode_bayar.toUpperCase() + ' - ' + order.metode_kirim.toUpperCase();
            document.getElementById('modalTotal').innerText = 'Rp ' + parseInt(order.total_harga).toLocaleString('id-ID');

            // Alamat (Tampilkan jika delivery)
            const alamatBox = document.getElementById('modalAlamatBox');
            if(order.metode_kirim === 'delivery') {
                alamatBox.style.display = 'block';
                document.getElementById('modalAlamat').innerText = order.alamat_kirim;
            } else {
                alamatBox.style.display = 'none';
            }

            const catatanText = order.catatan ? order.catatan : "Tidak ada catatan";
            document.getElementById('modalCatatan').innerText = catatanText;

            // Loop Barang Belanjaan
            let htmlItems = '';
            items.forEach(item => {
                htmlItems += `
                    <tr>
                        <td>
                            <b>${item.product_name}</b><br>
                        </td>
                        <td class="text-center">${item.qty}</td>
                        <td class="text-right">Rp ${parseInt(item.subtotal).toLocaleString('id-ID')}</td>
                    </tr>
                `;
            });
            document.getElementById('modalItems').innerHTML = htmlItems;

            // Buka Modal
            modal.style.display = 'flex';
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error', 'Gagal memuat detail pesanan.', 'error');
        });
}

// B. Update Status Pesanan (Terima / Tolak / Selesai)
function updateStatus(orderId, newStatus) {
    let confirmText = "";
    let btnColor = "";
    let btnText = "";

    // Tentukan pesan konfirmasi berdasarkan status baru
    if (newStatus === 'proses') {
        confirmText = "Pesanan akan diproses?";
        btnColor = "#28a745"; // Hijau
        btnText = "Ya, Proses";
    } else if (newStatus === 'selesai') {
        confirmText = "Pesanan sudah selesai dan diterima?";
        btnColor = "#007bff"; // Biru
        btnText = "Ya, Selesai";
    } else if (newStatus === 'batal') {
        confirmText = "Yakin ingin menolak pesanan ini?";
        btnColor = "#d33"; // Merah
        btnText = "Ya, Tolak";
    }

    Swal.fire({
        title: 'Update Status?',
        text: confirmText,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: btnColor,
        cancelButtonColor: '#6c757d',
        confirmButtonText: btnText
    }).then((result) => {
        if (result.isConfirmed) {
            
            // Kirim Request ke Server
            fetch('index.php?action=dashboard&action_ajax=update_status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: orderId, status: newStatus })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Berhasil!', 'Status pesanan diperbarui.', 'success')
                    .then(() => location.reload()); // Reload halaman agar tabel update
                } else {
                    Swal.fire('Gagal', 'Gagal update status.', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
            });
        }
    });
}

// Tutup Modal Detail saat tombol X diklik
document.querySelectorAll('.close-modal-admin').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('detailModalAdmin').style.display = 'none';
    });
});