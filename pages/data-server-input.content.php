<?php
// pages/data-server-input.content.php
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

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #374151;
    font-size: 14px;
}

.form-group label .required {
    color: #ef4444;
    margin-left: 2px;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    outline: none;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-section-title {
    font-size: 15px;
    font-weight: 700;
    color: #1e293b;
    margin: 20px 0 12px 0;
    padding-bottom: 8px;
    border-bottom: 2px solid #e5e7eb;
}

/* Image Upload */
.image-upload-area {
    border: 2px dashed #d1d5db;
    border-radius: 10px;
    padding: 24px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    background: #f9fafb;
    position: relative;
}
.image-upload-area:hover {
    border-color: #3b82f6;
    background: #eff6ff;
}
.image-upload-area input[type="file"] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    width: 100%;
    height: 100%;
    padding: 0;
    border: none;
}
.image-preview {
    display: none;
    margin-top: 12px;
    text-align: center;
}
.image-preview img {
    max-width: 100%;
    max-height: 200px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    object-fit: contain;
}
</style>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2>➕ Input Data Server Baru</h2>
            <p>Tambahkan informasi server baru ke database</p>
        </div>
        <a href="<?= base_url('pages/data-server.php') ?>" class="btn btn-secondary">
            ← Kembali ke Daftar
        </a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error" style="margin: 15px 25px 0;">
            <strong>⚠ Perbaiki input berikut:</strong>
            <ul style="margin: 8px 0 0 20px;">
                <?php foreach ($errors as $e): ?>
                    <li><?= h($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div style="padding: 25px;">
        <form method="post" id="serverForm" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add_server">
            
            <!-- Basic Info -->
            <div class="form-grid">
                <div class="form-group">
                    <label>IND <span class="required">*</span></label>
                    <input type="text" name="ind" value="<?= h($ind) ?>" placeholder="Contoh: SRV-001" required>
                </div>
                
                <div class="form-group">
                    <label>Fungsi Server <span class="required">*</span></label>
                    <input type="text" name="fungsi_server" value="<?= h($fungsi_server) ?>" placeholder="Contoh: Database Server" required>
                </div>
                
                <div class="form-group">
                    <label>IP Address <span class="required">*</span></label>
                    <input type="text" name="ip" value="<?= h($ip) ?>" placeholder="192.168.1.1" required>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Merk</label>
                    <input type="text" name="merk" value="<?= h($merk) ?>" placeholder="Contoh: Dell, HP, Lenovo">
                </div>
                
                <div class="form-group">
                    <label>Type</label>
                    <input type="text" name="type" value="<?= h($type) ?>" placeholder="Contoh: PowerEdge R740">
                </div>
                
                <div class="form-group">
                    <label>System Operasi</label>
                    <input type="text" name="system_operasi" value="<?= h($system_operasi) ?>" placeholder="Contoh: Ubuntu 20.04, Windows Server 2019">
                </div>
            </div>

            <div class="form-group">
                <label>Detail</label>
                <textarea name="detail" rows="3" placeholder="Deskripsi singkat tentang server ini"><?= h($detail) ?></textarea>
            </div>

            <!-- Processor -->
            <div class="form-section-title">⚙️ Spesifikasi Processor</div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Merk Processor</label>
                    <input type="text" name="processor_merk" value="<?= h($processor_merk) ?>" placeholder="Intel, AMD">
                </div>
                
                <div class="form-group">
                    <label>Type Processor</label>
                    <input type="text" name="processor_type" value="<?= h($processor_type) ?>" placeholder="Xeon Gold 6140">
                </div>
            </div>

            <div class="form-grid-3">
                <div class="form-group">
                    <label>Kecepatan (GHz)</label>
                    <input type="text" name="processor_kecepatan" value="<?= h($processor_kecepatan) ?>" placeholder="2.3">
                </div>
                
                <div class="form-group">
                    <label>Jumlah Keping</label>
                    <input type="number" name="processor_keping" value="<?= h($processor_keping) ?>" min="0" placeholder="2">
                </div>
                
                <div class="form-group">
                    <label>Jumlah Core</label>
                    <input type="number" name="processor_core" value="<?= h($processor_core) ?>" min="0" placeholder="18">
                </div>
            </div>

            <!-- RAM -->
            <div class="form-section-title">💾 Spesifikasi RAM</div>
            <div class="form-grid-3">
                <div class="form-group">
                    <label>Jenis RAM</label>
                    <input type="text" name="ram_jenis" value="<?= h($ram_jenis) ?>" placeholder="DDR4, DDR5">
                </div>
                
                <div class="form-group">
                    <label>Kapasitas</label>
                    <input type="text" name="ram_kapasitas" value="<?= h($ram_kapasitas) ?>" placeholder="16GB, 32GB">
                </div>
                
                <div class="form-group">
                    <label>Jumlah Keping</label>
                    <input type="number" name="ram_jumlah_keping" value="<?= h($ram_jumlah_keping) ?>" min="0" placeholder="4">
                </div>
            </div>

            <!-- Storage -->
            <div class="form-section-title">💿 Spesifikasi Storage</div>
            <div class="form-grid-3">
                <div class="form-group">
                    <label>Jenis Storage</label>
                    <input type="text" name="storage_jenis" value="<?= h($storage_jenis) ?>" placeholder="HDD, SSD, NVMe">
                </div>
                
                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" name="storage_jumlah" value="<?= h($storage_jumlah) ?>" min="0" placeholder="4">
                </div>
                
                <div class="form-group">
                    <label>Kapasitas Total</label>
                    <input type="text" name="storage_kapasitas_total" value="<?= h($storage_kapasitas_total) ?>" placeholder="2TB, 4TB">
                </div>
            </div>

            <!-- Additional Info -->
            <div class="form-section-title">📋 Informasi Tambahan</div>
            <div class="form-group">
                <label>Server Fisik</label>
                <input type="text" name="server_fisik" value="<?= h($server_fisik) ?>" placeholder="Lokasi fisik server (Contoh: Rack A1 - Row 3)">
            </div>

            <div class="form-group">
                <label>Keterangan Tambahan</label>
                <textarea name="keterangan_tambahan" rows="3" placeholder="Catatan atau informasi tambahan lainnya"><?= h($keterangan_tambahan) ?></textarea>
            </div>

            <div class="form-group">
                <label>Status Server <span class="required">*</span></label>
                <div style="display: flex; gap: 16px; margin-top: 4px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 500; cursor: pointer; color: #15803d;">
                        <input type="radio" name="status_server" value="HIDUP"
                               <?= ($status_server ?? 'HIDUP') === 'HIDUP' ? 'checked' : '' ?>
                               style="width: auto; padding: 0; margin: 0; accent-color: #22c55e;">
                        🟢 HIDUP
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 500; cursor: pointer; color: #dc2626;">
                        <input type="radio" name="status_server" value="MATI"
                               <?= ($status_server ?? 'HIDUP') === 'MATI' ? 'checked' : '' ?>
                               style="width: auto; padding: 0; margin: 0; accent-color: #ef4444;">
                        🔴 MATI
                    </label>
                </div>
            </div>

            <!-- Gambar -->
            <div class="form-section-title">🖼️ Gambar Server</div>
            <div class="form-group">
                <label>Upload Gambar <small style="font-weight: 400; color: #64748b;">(Opsional — format JPEG/JPG, maks. 2MB)</small></label>
                <div class="image-upload-area" id="uploadArea">
                    <input type="file" name="gambar" id="gambarInput" accept=".jpg,.jpeg,image/jpeg">
                    <div id="uploadPlaceholder">
                        <div style="font-size: 32px; margin-bottom: 8px;">📷</div>
                        <div style="font-weight: 600; color: #374151; margin-bottom: 4px;">Klik atau drag gambar ke sini</div>
                        <div style="font-size: 13px; color: #94a3b8;">Format: JPEG/JPG &bull; Maks. 2MB</div>
                    </div>
                </div>
                <div class="image-preview" id="imagePreview">
                    <img id="previewImg" src="" alt="Preview Gambar">
                    <div style="margin-top: 8px; font-size: 13px; color: #64748b;" id="previewFilename"></div>
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 25px; padding-top: 20px; border-top: 2px solid #e5e7eb;">
                <button type="submit" class="btn btn-primary">💾 Simpan Data Server</button>
                <button type="reset" class="btn btn-secondary" onclick="resetPreview()">🔄 Reset Form</button>
                <a href="<?= base_url('pages/data-server.php') ?>" class="btn btn-secondary">❌ Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
const gambarInput = document.getElementById('gambarInput');
const imagePreview = document.getElementById('imagePreview');
const previewImg = document.getElementById('previewImg');
const previewFilename = document.getElementById('previewFilename');
const uploadPlaceholder = document.getElementById('uploadPlaceholder');

gambarInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewFilename.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            imagePreview.style.display = 'block';
            uploadPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});

function resetPreview() {
    imagePreview.style.display = 'none';
    uploadPlaceholder.style.display = 'block';
    previewImg.src = '';
}
</script>