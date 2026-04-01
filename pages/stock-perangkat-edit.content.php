<?php
// pages/stock-perangkat-edit.content.php
?>

<style>
.sp-form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 18px;
    margin-bottom: 20px;
}
.form-group { margin-bottom: 18px; }
.form-group label {
    display: block; font-weight: 600; margin-bottom: 7px;
    color: #374151; font-size: 14px;
}
.form-group label .req { color: #ef4444; margin-left: 2px; }
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%; padding: 11px 13px;
    border: 1px solid #d1d5db; border-radius: 9px;
    font-size: 14px; outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    box-sizing: border-box; font-family: inherit;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
.form-divider { border: none; border-top: 2px solid #e5e7eb; margin: 24px 0 20px; }

.kondisi-radio-group { display: flex; gap: 14px; flex-wrap: wrap; margin-top: 6px; }
.kondisi-radio-opt {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 18px; border-radius: 8px; border: 2px solid #e5e7eb;
    cursor: pointer; font-weight: 600; font-size: 14px; transition: all 0.15s;
    user-select: none;
}
.kondisi-radio-opt input[type="radio"] { width: auto; padding: 0; margin: 0; }
.kondisi-radio-opt.baik          { color: #065f46; }
.kondisi-radio-opt.rusak         { color: #991b1b; }
.kondisi-radio-opt.perlu-service { color: #92400e; }
.kondisi-radio-opt:has(input:checked).baik          { background: #d1fae5; border-color: #34d399; }
.kondisi-radio-opt:has(input:checked).rusak         { background: #fee2e2; border-color: #f87171; }
.kondisi-radio-opt:has(input:checked).perlu-service { background: #fef3c7; border-color: #fbbf24; }

.image-upload-area {
    border: 2px dashed #d1d5db; border-radius: 10px; padding: 28px;
    text-align: center; cursor: pointer; transition: all 0.2s;
    background: #f9fafb; position: relative;
}
.image-upload-area:hover { border-color: #3b82f6; background: #eff6ff; }
.image-upload-area input[type="file"] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer;
    width: 100%; height: 100%; padding: 0; border: none;
}
.image-preview { display: none; margin-top: 14px; text-align: center; }
.image-preview img {
    max-width: 100%; max-height: 220px; border-radius: 8px;
    border: 1px solid #e5e7eb; object-fit: contain;
}
.current-image-box {
    background: #f8fafc; border: 1px solid #e5e7eb;
    border-radius: 10px; padding: 16px; text-align: center; margin-bottom: 12px;
}
.current-image-box img {
    max-width: 100%; max-height: 220px; border-radius: 8px;
    object-fit: contain; border: 1px solid #e5e7eb;
}
</style>

<div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div>
            <h2>✏️ Edit Stock Perangkat IT</h2>
            <p>Update data: <strong><?= h($perangkat['nama_barang']) ?></strong></p>
        </div>
        <a href="<?= base_url('pages/stock-perangkat.php') ?>" class="btn btn-secondary btn-sm">← Kembali</a>
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
            <input type="hidden" name="action" value="edit_perangkat">

            <!-- Nama & Type -->
            <div class="sp-form-grid">
                <div class="form-group">
                    <label>Nama Barang <span class="req">*</span></label>
                    <input type="text" name="nama_barang" value="<?= h($nama_barang) ?>" required>
                </div>
                <div class="form-group">
                    <label>Type Barang</label>
                    <input type="text" name="type_barang" value="<?= h($type_barang) ?>">
                </div>
            </div>

            <!-- Supplai -->
            <div class="form-group" style="max-width:500px;">
                <label>Supplai</label>
                <input type="text" name="supplai" value="<?= h($supplai) ?>">
            </div>

            <!-- Kondisi -->
            <div class="form-group">
                <label>Kondisi <span class="req">*</span></label>
                <div class="kondisi-radio-group">
                    <label class="kondisi-radio-opt baik">
                        <input type="radio" name="kondisi" value="BAIK"
                               <?= ($kondisi === 'BAIK') ? 'checked' : '' ?>>
                        🟢 BAIK
                    </label>
                    <label class="kondisi-radio-opt perlu-service">
                        <input type="radio" name="kondisi" value="PERLU SERVICE"
                               <?= ($kondisi === 'PERLU SERVICE') ? 'checked' : '' ?>>
                        🟡 PERLU SERVICE
                    </label>
                    <label class="kondisi-radio-opt rusak">
                        <input type="radio" name="kondisi" value="RUSAK"
                               <?= ($kondisi === 'RUSAK') ? 'checked' : '' ?>>
                        🔴 RUSAK
                    </label>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" rows="4"><?= h($keterangan) ?></textarea>
            </div>

            <!-- Foto -->
            <div class="form-group">
                <label>📷 Foto Perangkat</label>

                <?php if (!empty($perangkat['foto'])): ?>
                    <div class="current-image-box">
                        <img src="<?= base_url('uploads/stock_perangkat/' . h($perangkat['foto'])) ?>"
                             alt="Foto saat ini">
                        <div style="margin-top:10px;display:flex;justify-content:center;align-items:center;gap:10px;">
                            <span style="font-size:13px;color:#64748b;"><?= h($perangkat['foto']) ?></span>
                            <label style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:500;color:#dc2626;cursor:pointer;margin:0;">
                                <input type="checkbox" name="hapus_foto" value="1" style="width:auto;padding:0;">
                                Hapus foto ini
                            </label>
                        </div>
                    </div>
                    <label style="display:block;font-weight:600;font-size:13px;color:#374151;margin-bottom:6px;">
                        Ganti dengan Foto Baru
                        <small style="font-weight:400;color:#64748b;">(Opsional — JPEG/PNG/WebP, maks. 2MB)</small>
                    </label>
                <?php else: ?>
                    <label style="display:block;font-size:13px;color:#374151;margin-bottom:6px;font-weight:400;">
                        Upload Foto <small style="color:#64748b;">(Opsional — JPEG/PNG/WebP, maks. 2MB)</small>
                    </label>
                <?php endif; ?>

                <div class="image-upload-area">
                    <input type="file" name="foto" id="fotoInput" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                    <div id="uploadPlaceholder">
                        <div style="font-size:32px;margin-bottom:8px;">📷</div>
                        <div style="font-weight:600;color:#374151;margin-bottom:4px;">Klik atau drag foto ke sini</div>
                        <div style="font-size:13px;color:#94a3b8;">Format: JPEG / PNG / WebP &bull; Maks. 2MB</div>
                    </div>
                </div>
                <div class="image-preview" id="imagePreview">
                    <img id="previewImg" src="" alt="Preview">
                    <div style="margin-top:8px;font-size:13px;color:#64748b;" id="previewFilename"></div>
                </div>
            </div>

            <hr class="form-divider">

            <div style="display:flex;gap:12px;align-items:center;">
                <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                <a href="<?= base_url('pages/stock-perangkat.php') ?>" class="btn btn-secondary">❌ Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
const fotoInput       = document.getElementById('fotoInput');
const imagePreview    = document.getElementById('imagePreview');
const previewImg      = document.getElementById('previewImg');
const previewFilename = document.getElementById('previewFilename');
const uploadPlaceholder = document.getElementById('uploadPlaceholder');

fotoInput.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src              = e.target.result;
            previewFilename.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            imagePreview.style.display  = 'block';
            uploadPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});
</script>