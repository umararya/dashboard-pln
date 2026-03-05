<?php
// pages/it-support-jateng-edit.content.php
?>

<style>
.its-form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 18px;
    margin-bottom: 20px;
}
.form-group { margin-bottom: 18px; }
.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 7px;
    color: #374151;
    font-size: 14px;
}
.form-group label .req { color: #ef4444; margin-left: 2px; }
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 11px 13px;
    border: 1px solid #d1d5db;
    border-radius: 9px;
    font-size: 14px;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    box-sizing: border-box;
    font-family: inherit;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
.form-divider {
    border: none;
    border-top: 2px solid #e5e7eb;
    margin: 24px 0 20px;
}
</style>

<div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div>
            <h2>✏️ Edit IT Support Jateng</h2>
            <p>Update data personil: <strong><?= h($person['nama']) ?></strong></p>
        </div>
        <a href="<?= base_url('pages/it-support-jateng.php') ?>" class="btn btn-secondary btn-sm">← Kembali</a>
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
        <form method="post">
            <input type="hidden" name="action" value="edit_person">

            <div class="its-form-grid">
                <!-- Nama -->
                <div class="form-group">
                    <label>Nama <span class="req">*</span></label>
                    <input type="text"
                           name="nama"
                           value="<?= h($nama) ?>"
                           placeholder="Nama lengkap"
                           required
                           autofocus>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label>Email</label>
                    <input type="email"
                           name="email"
                           value="<?= h($email) ?>"
                           placeholder="contoh@pln.co.id">
                </div>
            </div>

            <div class="its-form-grid">
                <!-- No. HP -->
                <div class="form-group">
                    <label>No. HP</label>
                    <input type="text"
                           name="no_hp"
                           value="<?= h($no_hp) ?>"
                           placeholder="08xxxxxxxxxx">
                </div>

                <!-- Penempatan -->
                <div class="form-group">
                    <label>Penempatan</label>
                    <input type="text"
                           name="penempatan"
                           value="<?= h($penempatan) ?>"
                           placeholder="Contoh: UP3 Semarang, UID Jateng">
                </div>
            </div>

            <!-- OPS STI -->
            <div class="form-group" style="max-width: 460px;">
                <label>OPS STI</label>
                <input type="text"
                       name="ops_sti"
                       value="<?= h($ops_sti) ?>"
                       placeholder="Contoh: OPS-001, Operator Senior">
            </div>

            <hr class="form-divider">

            <div style="display:flex;gap:12px;align-items:center;">
                <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                <a href="<?= base_url('pages/it-support-jateng.php') ?>" class="btn btn-secondary">❌ Batal</a>
            </div>
        </form>
    </div>
</div>