<?php // maintenance-edit.content.php ?>
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
        <form method="post">
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
                        <option value="MATI" <?= $kondisi === 'MATI' ? 'selected' : '' ?>>🔴 MATI</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status <span class="required">*</span></label>
                    <select name="status" required>
                        <option value="AMAN" <?= $status === 'AMAN' ? 'selected' : '' ?>>✅ AMAN</option>
                        <option value="PROBLEM" <?= $status === 'PROBLEM' ? 'selected' : '' ?>>⚠️ PROBLEM</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 20px; padding-top: 20px; border-top: 2px solid #e5e7eb;">
                <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                <a href="<?= base_url('pages/data-server-detail.php?id=' . $server_id) ?>" class="btn btn-secondary">❌ Batal</a>
            </div>
        </form>
    </div>
</div>

<style>
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-weight: 600; margin-bottom: 6px; color: #374151; font-size: 14px; }
.form-group label .required { color: #ef4444; }
.form-group input, .form-group textarea, .form-group select {
    width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; outline: none;
}
.form-group input:focus, .form-group textarea:focus, .form-group select:focus {
    border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>