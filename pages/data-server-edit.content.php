<?php
// pages/data-server-edit.content.php
?>

<style>
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 18px;
    margin-bottom: 20px;
}
.form-grid-3 {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
}
.form-group { margin-bottom: 16px; }
.form-group label {
    display: block; font-weight: 600; margin-bottom: 6px;
    color: #374151; font-size: 14px;
}
.form-group label .required { color: #ef4444; margin-left: 2px; }
.form-group input,
.form-group textarea,
.form-group select {
    width: 100%; padding: 10px 12px; border: 1px solid #d1d5db;
    border-radius: 8px; font-size: 14px; outline: none;
}
.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
}
.form-section-title {
    font-size: 15px; font-weight: 700; color: #1e293b;
    margin: 20px 0 12px 0; padding-bottom: 8px;
    border-bottom: 2px solid #e5e7eb;
}
/* Status radio */
.status-radio-group {
    display: flex; gap: 16px; margin-top: 6px;
}
.status-radio-option {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 18px; border-radius: 8px; border: 2px solid #e5e7eb;
    cursor: pointer; font-weight: 600; font-size: 14px; transition: all 0.15s;
    user-select: none;
}
.status-radio-option.hidup { color: #15803d; }
.status-radio-option.mati  { color: #dc2626; }
.status-radio-option input[type="radio"] { width: auto; padding: 0; margin: 0; accent-color: currentColor; }
.status-radio-option:has(input:checked).hidup { background: #dcfce7; border-color: #22c55e; }
.status-radio-option:has(input:checked).mati  { background: #fee2e2; border-color: #ef4444; }
/* Image upload */
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
.image-preview img {
    max-width: 100%; max-height: 200px; border-radius: 8px;
    border: 1px solid #e5e7eb; object-fit: contain;
}
.current-image-box {
    background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 10px;
    padding: 16px; text-align: center; margin-bottom: 12px;
}
.current-image-box img {
    max-width: 100%; max-height: 220px; border-radius: 8px;
    object-fit: contain; border: 1px solid #e5e7eb;
}
</style>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2>✏️ Edit Data Server</h2>
            <p>Update informasi server <strong><?= h($server['ind']) ?></strong></p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="<?= base_url('pages/data-server-detail.php?id=' . $server_id) ?>" class="btn btn-secondary">← Detail</a>
            <a href="<?= base_url('pages/data-server.php') ?>" class="btn btn-secondary">← Daftar</a>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error" style="margin: 15px 25px 0;">
            <strong>⚠ Perbaiki input berikut:</strong>
            <ul style="margin: 8px 0 0 20px;">
                <?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div style="padding: 25px;">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit_server">

            <!-- Basic Info -->
            <div class="form-grid">
                <div class="form-group">
                    <label>IND <span class="required">*</span></label>
                    <input type="text" name="ind" value="<?= h($ind) ?>" required>
                </div>
                <div class="form-group">
                    <label>Fungsi Server <span class="required">*</span></label>
                    <input type="text" name="fungsi_server" value="<?= h($fungsi_server) ?>" required>
                </div>
                <div class="form-group">
                    <label>IP Address <span class="required">*</span></label>
                    <input type="text" name="ip" value="<?= h($ip) ?>" required>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Merk</label>
                    <input type="text" name="merk" value="<?= h($merk) ?>">
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <input type="text" name="type" value="<?= h($type) ?>">
                </div>
                <div class="form-group">
                    <label>System Operasi</label>
                    <input type="text" name="system_operasi" value="<?= h($system_operasi) ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Detail</label>
                <textarea name="detail" rows="3"><?= h($detail) ?></textarea>
            </div>

            <!-- Processor -->
            <div class="form-section-title">⚙️ Spesifikasi Processor</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Merk Processor</label>
                    <input type="text" name="processor_merk" value="<?= h($processor_merk) ?>">
                </div>
                <div class="form-group">
                    <label>Type Processor</label>
                    <input type="text" name="processor_type" value="<?= h($processor_type) ?>">
                </div>
            </div>
            <div class="form-grid-3">
                <div class="form-group">
                    <label>Kecepatan (GHz)</label>
                    <input type="text" name="processor_kecepatan" value="<?= h($processor_kecepatan) ?>">
                </div>
                <div class="form-group">
                    <label>Jumlah Keping</label>
                    <input type="number" name="processor_keping" value="<?= h($processor_keping) ?>" min="0">
                </div>
                <div class="form-group">
                    <label>Jumlah Core</label>
                    <input type="number" name="processor_core" value="<?= h($processor_core) ?>" min="0">
                </div>
            </div>

            <!-- RAM -->
            <div class="form-section-title">💾 Spesifikasi RAM</div>
            <div class="form-grid-3">
                <div class="form-group">
                    <label>Jenis RAM</label>
                    <input type="text" name="ram_jenis" value="<?= h($ram_jenis) ?>">
                </div>
                <div class="form-group">
                    <label>Kapasitas</label>
                    <input type="text" name="ram_kapasitas" value="<?= h($ram_kapasitas) ?>">
                </div>
                <div class="form-group">
                    <label>Jumlah Keping</label>
                    <input type="number" name="ram_jumlah_keping" value="<?= h($ram_jumlah_keping) ?>" min="0">
                </div>
            </div>

            <!-- Storage -->
            <div class="form-section-title">💿 Spesifikasi Storage</div>
            <div class="form-grid-3">
                <div class="form-group">
                    <label>Jenis Storage</label>
                    <input type="text" name="storage_jenis" value="<?= h($storage_jenis) ?>">
                </div>
                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" name="storage_jumlah" value="<?= h($storage_jumlah) ?>" min="0">
                </div>
                <div class="form-group">
                    <label>Kapasitas Total</label>
                    <input type="text" name="storage_kapasitas_total" value="<?= h($storage_kapasitas_total) ?>">
                </div>
            </div>

            <!-- Informasi Tambahan -->
            <div class="form-section-title">📋 Informasi Tambahan</div>
            <div class="form-group">
                <label>Server Fisik</label>
                <input type="text" name="server_fisik" value="<?= h($server_fisik) ?>">
            </div>
            <div class="form-group">
                <label>Keterangan Tambahan</label>
                <textarea name="keterangan_tambahan" rows="3"><?= h($keterangan_tambahan) ?></textarea>
            </div>

            <!-- Status Server -->
            <div class="form-section-title">🔌 Status Server</div>
            <div class="form-group">
                <label>Status Server <span class="required">*</span></label>
                <div class="status-radio-group">
                    <label class="status-radio-option hidup">
                        <input type="radio" name="status_server" value="HIDUP"
                               <?= ($status_server ?? 'HIDUP') === 'HIDUP' ? 'checked' : '' ?>>
                        🟢 HIDUP
                    </label>
                    <label class="status-radio-option mati">
                        <input type="radio" name="status_server" value="MATI"
                               <?= ($status_server ?? 'HIDUP') === 'MATI' ? 'checked' : '' ?>>
                        🔴 MATI
                    </label>
                </div>
            </div>

            <!-- Gambar -->
            <div class="form-section-title">🖼️ Gambar Server</div>
            <div class="form-group">
                <?php if (!empty($server['gambar'])): ?>
                    <label>Gambar Saat Ini</label>
                    <div class="current-image-box">
                        <img src="<?= base_url('uploads/server_images/' . h($server['gambar'])) ?>" alt="Gambar Server">
                        <div style="margin-top: 10px; display: flex; justify-content: center; align-items: center; gap: 10px;">
                            <span style="font-size: 13px; color: #64748b;"><?= h($server['gambar']) ?></span>
                            <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500; color: #dc2626; cursor: pointer; margin: 0;">
                                <input type="checkbox" name="hapus_gambar" value="1" style="width: auto; padding: 0;">
                                Hapus gambar ini
                            </label>
                        </div>
                    </div>
                    <label style="display: block; font-weight: 600; font-size: 13px; color: #374151; margin-bottom: 6px;">
                        Ganti dengan Gambar Baru
                        <small style="font-weight: 400; color: #64748b;">(Opsional — JPEG/JPG, maks. 2MB)</small>
                    </label>
                <?php else: ?>
                    <label>Upload Gambar
                        <small style="font-weight: 400; color: #64748b;">(Opsional — JPEG/JPG, maks. 2MB)</small>
                    </label>
                <?php endif; ?>

                <div class="image-upload-area">
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

            <div style="display: flex; gap: 12px; margin-top: 25px; padding-top: 20px; border-top: 2px solid #e5e7eb;">
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
const previewFilename   = document.getElementById('previewFilename');
const uploadPlaceholder = document.getElementById('uploadPlaceholder');

gambarInput.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            previewImg.src = e.target.result;
            previewFilename.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            imagePreview.style.display = 'block';
            uploadPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});
</script>