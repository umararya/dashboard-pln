<?php // maintenance-edit.content.php ?>
<style>
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-weight: 600; margin-bottom: 6px; color: #374151; font-size: 14px; }
.form-group label .required { color: #ef4444; }
.form-group input, .form-group textarea, .form-group select {
    width: 100%; padding: 10px 12px; border: 1px solid #d1d5db;
    border-radius: 8px; font-size: 14px; outline: none;
}
.form-group input:focus, .form-group textarea:focus, .form-group select:focus {
    border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
}
.image-upload-area {
    border: 2px dashed #d1d5db; border-radius: 10px; padding: 24px;
    text-align: center; cursor: pointer; transition: all 0.2s;
    background: #f9fafb; position: relative;
}
.image-upload-area:hover { border-color: #3b82f6; background: #eff6ff; }
.image-upload-area input[type="file"] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer;
    width: 100%; height: 100%; padding: 0; border: none;
}
.image-preview { display: none; margin-top: 12px; text-align: center; }
.image-preview img { max-width: 100%; max-height: 200px; border-radius: 8px; border: 1px solid #e5e7eb; object-fit: contain; }
.current-image-box {
    background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 10px;
    padding: 16px; text-align: center; margin-bottom: 12px;
}
.current-image-box img { max-width: 100%; max-height: 220px; border-radius: 8px; object-fit: contain; border: 1px solid #e5e7eb; }
</style>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2>✏️ Edit History Pemeliharaan</h2>
            <p>Server: <strong><?= h($maintenance['ind']) ?></strong> - <?= h($maintenance['fungsi_server']) ?></p>
        </div>
        <a href="<?= base_url('pages/data-server-detail.php?id=' . $server_id) ?>" class="btn btn-secondary">← Kembali</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error" style="margin: 15px 25px 0;">
            <strong>⚠ Perbaiki input berikut:</strong>
            <ul style="margin: 8px 0 0 20px;"><?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <div style="padding: 25px;">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit_maintenance">

            <div class="form-group">
                <label>Waktu Pemeliharaan <span class="required">*</span></label>
                <input type="text" name="waktu_pemeliharaan" value="<?= h($waktu_pemeliharaan) ?>" required>
            </div>

            <div class="form-group">
                <label>Temuan <span class="required">*</span></label>
                <textarea name="temuan" rows="5" required><?= h($temuan) ?></textarea>
            </div>

            <div class="form-group">
                <label>Dicek Terakhir Oleh <span class="required">*</span></label>
                <input type="text" name="dicek_oleh" value="<?= h($dicek_oleh) ?>" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label>Kondisi <span class="required">*</span></label>
                    <select name="kondisi" required>
                        <option value="HIDUP" <?= $kondisi === 'HIDUP' ? 'selected' : '' ?>>🟢 HIDUP</option>
                        <option value="MATI"  <?= $kondisi === 'MATI'  ? 'selected' : '' ?>>🔴 MATI</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status <span class="required">*</span></label>
                    <select name="status" required>
                        <option value="AMAN"    <?= $status === 'AMAN'    ? 'selected' : '' ?>>✅ AMAN</option>
                        <option value="PROBLEM" <?= $status === 'PROBLEM' ? 'selected' : '' ?>>⚠️ PROBLEM</option>
                    </select>
                </div>
            </div>

            <!-- Gambar History -->
            <div class="form-group" style="margin-top: 8px;">
                <label>📷 Gambar Pemeliharaan</label>

                <?php if (!empty($maintenance['gambar'])): ?>
                    <div class="current-image-box">
                        <img src="<?= base_url('uploads/maintenance_images/' . h($maintenance['gambar'])) ?>" alt="Gambar Pemeliharaan">
                        <div style="margin-top: 10px; display: flex; justify-content: center; align-items: center; gap: 10px;">
                            <span style="font-size: 13px; color: #64748b;"><?= h($maintenance['gambar']) ?></span>
                            <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500; color: #dc2626; cursor: pointer; margin: 0;">
                                <input type="checkbox" name="hapus_gambar" value="1" id="hapusGambar" style="width: auto; padding: 0;">
                                Hapus gambar ini
                            </label>
                        </div>
                    </div>
                    <label style="margin-top: 8px; display: block; font-weight: 600; font-size: 13px; color: #374151;">
                        Ganti dengan Gambar Baru <small style="font-weight: 400; color: #64748b;">(JPEG/JPG, maks. 2MB)</small>
                    </label>
                <?php else: ?>
                    <label style="display: block; font-weight: 600; font-size: 13px; color: #374151; margin-bottom: 6px;">
                        Upload Gambar <small style="font-weight: 400; color: #64748b;">(Opsional — JPEG/JPG, maks. 2MB)</small>
                    </label>
                <?php endif; ?>

                <div class="image-upload-area" id="uploadArea">
                    <input type="file" name="gambar" id="gambarInput" accept=".jpg,.jpeg,image/jpeg">
                    <div id="uploadPlaceholder">
                        <div style="font-size: 32px; margin-bottom: 8px;">📷</div>
                        <div style="font-weight: 600; color: #374151; margin-bottom: 4px;">Klik atau drag gambar ke sini</div>
                        <div style="font-size: 13px; color: #94a3b8;">Format: JPEG/JPG &bull; Maks. 2MB</div>
                    </div>
                </div>
                <div class="image-preview" id="imagePreview">
                    <img id="previewImg" src="" alt="Preview">
                    <div style="margin-top: 8px; font-size: 13px; color: #64748b;" id="previewFilename"></div>
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 20px; padding-top: 20px; border-top: 2px solid #e5e7eb;">
                <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                <a href="<?= base_url('pages/data-server-detail.php?id=' . $server_id) ?>" class="btn btn-secondary">❌ Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
const gambarInput       = document.getElementById('gambarInput');
const imagePreview      = document.getElementById('imagePreview');
const previewImg        = document.getElementById('previewImg');
const previewFname      = document.getElementById('previewFilename');
const uploadPlaceholder = document.getElementById('uploadPlaceholder');

gambarInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            previewImg.src = e.target.result;
            previewFname.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            imagePreview.style.display = 'block';
            uploadPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});
</script>