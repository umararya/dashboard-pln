<?php
// pages/data-server-edit.content.php
// NOTE: This uses the same styles and structure as input page
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
</style>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2>✏️ Edit Data Server</h2>
            <p>Update informasi server <strong><?= h($server['ind']) ?></strong></p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="<?= base_url('pages/data-server-detail.php?id=' . $server_id) ?>" class="btn btn-secondary">
                👁️ Lihat Detail
            </a>
            <a href="<?= base_url('pages/data-server.php') ?>" class="btn btn-secondary">
                ← Kembali ke Daftar
            </a>
        </div>
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
        <form method="post">
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

            <!-- Additional Info -->
            <div class="form-section-title">📋 Informasi Tambahan</div>
            <div class="form-group">
                <label>Server Fisik</label>
                <input type="text" name="server_fisik" value="<?= h($server_fisik) ?>">
            </div>

            <div class="form-group">
                <label>Keterangan Tambahan</label>
                <textarea name="keterangan_tambahan" rows="3"><?= h($keterangan_tambahan) ?></textarea>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 25px; padding-top: 20px; border-top: 2px solid #e5e7eb;">
                <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                <a href="<?= base_url('pages/data-server-detail.php?id=' . $server_id) ?>" class="btn btn-secondary">❌ Batal</a>
            </div>
        </form>
    </div>
</div>