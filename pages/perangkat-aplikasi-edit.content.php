<?php
// pages/perangkat-aplikasi-edit.content.php
?>

<style>
.pa-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 18px;
    margin-bottom: 20px;
}
.pa-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
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
    transition: border-color .2s, box-shadow .2s;
    box-sizing: border-box; font-family: inherit;
    background: #fff;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.1);
}
.form-section-title {
    font-size: 15px; font-weight: 700; color: #1e293b;
    margin: 24px 0 14px; padding-bottom: 8px;
    border-bottom: 2px solid #e5e7eb;
}
.form-divider { border: none; border-top: 2px solid #e5e7eb; margin: 24px 0 20px; }
.patch-legend {
    background: #f8fafc; border: 1px solid #e5e7eb;
    border-radius: 10px; padding: 14px 18px;
    margin-bottom: 20px; font-size: 13px; color: #475569;
}
.patch-legend strong { display: block; margin-bottom: 8px; font-size: 13px; color: #1e293b; }
.patch-legend-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px,1fr)); gap: 6px; }
.patch-legend-item {
    display: flex; align-items: center; gap: 8px;
    padding: 6px 10px; border-radius: 6px;
    font-size: 12px; font-weight: 600;
}
.pl-ok      { background: #d1fae5; color: #065f46; }
.pl-not     { background: #fee2e2; color: #991b1b; }
.pl-na      { background: #f1f5f9; color: #64748b; }
.pl-pending { background: #fef3c7; color: #92400e; }
</style>

<div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div>
            <h2>✏️ Edit Perangkat Aplikasi</h2>
            <p>Update data: <strong><?= h($record['nama_perangkat']) ?></strong></p>
        </div>
        <a href="<?= base_url('pages/perangkat-aplikasi.php') ?>" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error" style="margin:15px 25px 0;">
            <strong>⚠ Perbaiki input berikut:</strong>
            <ul style="margin:8px 0 0 20px;">
                <?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div style="padding:25px;">
        <form method="post">
            <input type="hidden" name="action" value="edit_perangkat_aplikasi">

            <!-- ── IDENTITAS PERANGKAT ─────────────────────── -->
            <div class="form-section-title">📋 Identitas Perangkat</div>

            <div class="pa-grid">
                <div class="form-group">
                    <label>Nama Perangkat <span class="req">*</span></label>
                    <select name="nama_perangkat" required>
                        <option value="">-- Pilih Nama Perangkat --</option>
                        <?php foreach ($NAMA_PERANGKAT_OPTIONS as $opt): ?>
                            <option value="<?= h($opt) ?>" <?= ($nama_perangkat === $opt) ? 'selected' : '' ?>>
                                <?= h($opt) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Brand</label>
                    <select name="brand">
                        <option value="">-- Pilih Brand --</option>
                        <?php foreach ($BRAND_OPTIONS as $opt): ?>
                            <option value="<?= h($opt) ?>" <?= ($brand === $opt) ? 'selected' : '' ?>>
                                <?= h($opt) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="pa-grid">
                <div class="form-group">
                    <label>URL</label>
                    <input type="text" name="url" value="<?= h($url) ?>"
                           placeholder="https://contoh.pln.co.id">
                </div>
                <div class="form-group">
                    <label>IP Address</label>
                    <input type="text" name="ip" value="<?= h($ip) ?>"
                           placeholder="192.168.1.100">
                </div>
            </div>

            <div class="pa-grid">
                <div class="form-group">
                    <label>Type</label>
                    <input type="text" name="type" value="<?= h($type) ?>"
                           placeholder="Web Application, REST API, Desktop App">
                </div>
                <div class="form-group">
                    <label>Server</label>
                    <input type="text" name="server" value="<?= h($server) ?>"
                           placeholder="Nama / IP server tempat aplikasi berjalan">
                </div>
            </div>

            <div class="form-group">
                <label>OS (Sistem Operasi Server)</label>
                <input type="text" name="os" value="<?= h($os) ?>"
                       placeholder="Ubuntu 22.04, Windows Server 2019, RHEL 8">
            </div>

            <!-- ── PENEMPATAN ──────────────────────────────── -->
            <div class="form-section-title">🏢 Penempatan</div>

            <div class="pa-grid">
                <div class="form-group">
                    <label>Lokasi</label>
                    <select name="lokasi">
                        <option value="">-- Pilih Lokasi --</option>
                        <?php foreach ($LOKASI_OPTIONS as $opt): ?>
                            <option value="<?= h($opt) ?>" <?= ($lokasi === $opt) ? 'selected' : '' ?>>
                                <?= h($opt) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Bidang</label>
                    <select name="bidang">
                        <option value="">-- Pilih Bidang --</option>
                        <?php foreach ($BIDANG_OPTIONS as $opt): ?>
                            <option value="<?= h($opt) ?>" <?= ($bidang === $opt) ? 'selected' : '' ?>>
                                <?= h($opt) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="pa-grid">
                <div class="form-group">
                    <label>MSB / Sub Bidang</label>
                    <select name="msb_sub_bidang">
                        <option value="">-- Pilih MSB / Sub Bidang --</option>
                        <?php foreach ($MSB_OPTIONS as $opt): ?>
                            <option value="<?= h($opt) ?>" <?= ($msb_sub_bidang === $opt) ? 'selected' : '' ?>>
                                <?= h($opt) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Pemilik Aset</label>
                    <input type="text" name="pemilik_aset" value="<?= h($pemilik_aset) ?>"
                           placeholder="Nama pemilik / penanggung jawab aset">
                </div>
            </div>

            <!-- ── STATUS PATCH ────────────────────────────── -->
            <div class="form-section-title">🔐 Status Patch</div>

            <div class="patch-legend">
                <strong>Keterangan Status Patch:</strong>
                <div class="patch-legend-grid">
                    <span class="patch-legend-item pl-ok">     ✅  Up-to-date</span>
                    <span class="patch-legend-item pl-not">    ❌  Belum Up-to-date</span>
                    <span class="patch-legend-item pl-na">     –   Tidak relevan / tidak ada patch</span>
                    <span class="patch-legend-item pl-pending"> ⌛  Belum Konfirmasi</span>
                </div>
            </div>

            <div class="pa-grid-2">
                <div class="form-group">
                    <label>Firmware Patch</label>
                    <select name="firmware_patch">
                        <?php foreach ($PATCH_OPTIONS as $val => $label): ?>
                            <option value="<?= h($val) ?>" <?= ($firmware_patch === $val) ? 'selected' : '' ?>>
                                <?= h($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Network Device Patch</label>
                    <select name="network_device_patch">
                        <?php foreach ($PATCH_OPTIONS as $val => $label): ?>
                            <option value="<?= h($val) ?>" <?= ($network_device_patch === $val) ? 'selected' : '' ?>>
                                <?= h($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <hr class="form-divider">

            <div style="display:flex;gap:12px;align-items:center;">
                <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                <a href="<?= base_url('pages/perangkat-aplikasi.php') ?>" class="btn btn-secondary">❌ Batal</a>
            </div>
        </form>
    </div>
</div>