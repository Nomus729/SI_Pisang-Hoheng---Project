<?php
// Ambil data settings yang dikirim dari controller
// $data adalah array ['key' => 'value']
?>

<div class="card-box">
    <div class="header-title">
        <i class="fas fa-info-circle"></i> Pengaturan Informasi Website
    </div>

    <form action="index.php?action=dashboard&action_post=update_settings" method="POST">

        <div class="form-section">
            <h4 style="border-bottom: 2px solid #ddd; padding-bottom: 10px; margin-bottom: 20px; color: #555;">
                <i class="fas fa-store"></i> Kontak & Lokasi
            </h4>

            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="address" class="form-control" rows="3" required><?= isset($data['address']) ? $data['address'] : '' ?></textarea>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Nomor Telepon / WhatsApp</label>
                        <input type="text" name="phone" class="form-control" value="<?= isset($data['phone']) ? $data['phone'] : '' ?>" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Email Bisnis</label>
                        <input type="email" name="email" class="form-control" value="<?= isset($data['email']) ? $data['email'] : '' ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section" style="margin-top: 30px;">
            <h4 style="border-bottom: 2px solid #ddd; padding-bottom: 10px; margin-bottom: 20px; color: #555;">
                <i class="fas fa-clock"></i> Jam Operasional
            </h4>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Senin - Jumat</label>
                        <input type="text" name="hours_mon_fri" class="form-control" value="<?= isset($data['hours_mon_fri']) ? $data['hours_mon_fri'] : '' ?>" placeholder="Contoh: 10.00 - 22.00">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Sabtu - Minggu</label>
                        <input type="text" name="hours_sat_sun" class="form-control" value="<?= isset($data['hours_sat_sun']) ? $data['hours_sat_sun'] : '' ?>" placeholder="Contoh: 09.00 - 23.00">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section" style="margin-top: 30px;">
            <h4 style="border-bottom: 2px solid #ddd; padding-bottom: 10px; margin-bottom: 20px; color: #555;">
                <i class="fas fa-share-alt"></i> Media Sosial (Link Lengkap)
            </h4>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label><i class="fab fa-instagram"></i> Instagram</label>
                        <input type="url" name="social_ig" class="form-control" value="<?= isset($data['social_ig']) ? $data['social_ig'] : '' ?>">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label><i class="fab fa-facebook"></i> Facebook</label>
                        <input type="url" name="social_fb" class="form-control" value="<?= isset($data['social_fb']) ? $data['social_fb'] : '' ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label><i class="fab fa-whatsapp"></i> WhatsApp Link (wa.me/...)</label>
                        <input type="url" name="social_wa" class="form-control" value="<?= isset($data['social_wa']) ? $data['social_wa'] : '' ?>">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label><i class="fab fa-tiktok"></i> TikTok</label>
                        <input type="url" name="social_tt" class="form-control" value="<?= isset($data['social_tt']) ? $data['social_tt'] : '' ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section" style="margin-top: 30px;">
            <h4 style="border-bottom: 2px solid #ddd; padding-bottom: 10px; margin-bottom: 20px; color: #555;">
                <i class="fas fa-map-marked"></i> Google Maps Embed Code
            </h4>
            <div class="form-group">
                <label>Kode Embed (iframe)</label>
                <textarea name="map_embed" class="form-control" rows="5" placeholder='<iframe src="...">'><?= isset($data['map_embed']) ? $data['map_embed'] : '' ?></textarea>
                <small style="color:red;">*Pastikan copy kode "Embed a map" dari Google Maps.</small>
            </div>
        </div>

        <div class="form-actions" style="margin-top: 30px; text-align: right;">
            <button type="submit" class="btn-simpan">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>

    </form>
</div>

<style>
    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-top: 5px;
    }

    .row {
        display: flex;
        gap: 20px;
    }

    .col-6 {
        flex: 1;
    }

    .btn-simpan {
        background: #27ae60;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-simpan:hover {
        background: #219150;
    }

    @media (max-width: 768px) {
        .row {
            flex-direction: column;
            gap: 0;
        }

        .col-6 {
            width: 100%;
        }
    }
</style>