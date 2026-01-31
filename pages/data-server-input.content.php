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
        <form method="post" id="serverForm">
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

            <div style="display: flex; gap: 12px; margin-top: 25px; padding-top: 20px; border-top: 2px solid #e5e7eb;">
                <button type="submit" class="btn btn-primary">💾 Simpan Data Server</button>
                <button type="reset" class="btn btn-secondary">🔄 Reset Form</button>
                <a href="<?= base_url('pages/data-server.php') ?>" class="btn btn-secondary">❌ Batal</a>
            </div>
        </form>
    </div>
</div>