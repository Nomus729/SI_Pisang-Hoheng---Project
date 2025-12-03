<div class="card-box">
    <h3><?= isset($data['id']) ? 'Edit Produk' : 'Tambah Produk Baru' ?></h3>
    
<form id="formProduk" action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
    
    <?php if(isset($data['id'])): ?>
        <input type="hidden" name="id" value="<?= $data['id'] ?>">
    <?php endif; ?>
        
        <div class="row-2">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama" value="<?= $data['nama_produk'] ?? '' ?>" 
                       pattern="[a-zA-Z\s]+" title="Hanya boleh huruf dan spasi" required>
            </div>
            <div class="form-group">
                <label>Kode Barang (ID)</label>
                <input type="text" name="kode" value="<?= $data['kode_barang'] ?? 'BRG'.rand(1000,9999) ?>" 
                       style="background-color: #eee; cursor: not-allowed;" readonly>
            </div>
        </div>

        <div class="row-2">
            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="text" id="hargaInput" name="harga_display" 
                       value="<?= isset($data['harga']) ? 'Rp ' . number_format($data['harga'], 0, ',', '.') : '' ?>" 
                       placeholder="Rp 0" required>
                
                <input type="hidden" id="hargaReal" name="harga" value="<?= $data['harga'] ?? '' ?>">
            </div>
            
            <div class="form-group">
                <label>Stok Awal</label>
                <input type="text" name="stok" 
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                       value="<?= $data['stok'] ?? '' ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>Detail / Komposisi</label>
            <div class="detail-input-wrapper">
                <textarea name="detail" id="detailInput" rows="5" placeholder="- Pisang&#10;- Keju&#10;- Coklat" required><?= $data['deskripsi'] ?? '' ?></textarea>
                <small style="color: #888;">Tekan Enter untuk menambah poin baru.</small>
            </div>
        </div>

        <div class="form-group">
            <label>Upload Gambar</label>
            <input type="file" name="gambar" id="imgInp" accept="image/*" <?= isset($data['gambar']) ? '' : 'required' ?>>
            
            <div style="margin-top: 10px;">
                <img id="blah" src="<?= isset($data['gambar']) ? 'uploads/'.$data['gambar'] : 'https://placehold.co/150x150?text=Preview' ?>" 
                     width="150" height="150" style="object-fit: cover; border-radius: 10px; border: 1px solid #ddd;">
            </div>
        </div>

        <button type="submit" class="btn-signin" style="width:100%; margin-top:20px;">Simpan Data</button>
    </form>
</div>

<script>
    // 1. Preview Gambar saat dipilih
    imgInp.onchange = evt => {
        const [file] = imgInp.files
        if (file) {
            blah.src = URL.createObjectURL(file)
        }
    }

    // 2. Auto Bullet Point pada Textarea
    const detailInput = document.getElementById('detailInput');
    
    // Saat user mengetik
    detailInput.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            const cursor = this.selectionStart;
            const value = this.value;
            
            // REVISI: Gunakan "- " (Strip) bukan bullet
            this.value = value.substring(0, cursor) + "- " + value.substring(cursor);
            
            this.selectionStart = this.selectionEnd = cursor + 2; 
        }
    });

    // Saat pertama kali fokus (jika kosong)
    detadetailInput.addEventListener('focus', function() {
        if (this.value === '') {
            this.value = '- '; // Awal otomatis strip
        }
    });

    // 3. Validasi Form Sebelum Submit
    function validateForm() {
        const harga = document.querySelector('input[name="harga"]').value;
        const stok = document.querySelector('input[name="stok"]').value;
        
        if (isNaN(harga) || harga <= 0) {
            alert("Harga harus berupa angka dan lebih dari 0!");
            return false;
        }
        if (isNaN(stok) || stok < 0) {
            alert("Stok harus berupa angka positif!");
            return false;
        }
        return true;
    }
</script>

<script>
    const hargaInput = document.getElementById('hargaInput');
    const hargaReal = document.getElementById('hargaReal');

    hargaInput.addEventListener('keyup', function(e) {
        // Ambil value, hapus semua karakter selain angka
        let value = this.value.replace(/[^0-9]/g, '');
        
        // Simpan nilai asli (angka) ke input hidden untuk dikirim ke DB
        hargaReal.value = value;

        // Format tampilan jadi Rp xx.xxx
        if (value) {
            this.value = formatRupiah(value, 'Rp ');
        } else {
            this.value = '';
        }
    });

    function formatRupiah(angka, prefix) {
        var number_string = angka.toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
    }
</script>