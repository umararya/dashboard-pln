<?php // pages/data-server-detail.content.php ?>
<style>
.detail-grid { display: grid; grid-template-columns: 200px 1fr; gap: 14px; margin-bottom: 14px; }
.detail-label { font-weight: 700; color: #475569; }
.detail-value { color: #1e293b; display: flex; align-items: center; }
.detail-separator { border-bottom: 1px solid #e5e7eb; margin: 20px 0; }
.history-card { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 10px; padding: 16px; margin-bottom: 12px; }
.status-badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; }
.status-aman { background: #d1fae5; color: #065f46; }
.status-problem { background: #fee2e2; color: #991b1b; }
.kondisi-hidup { background: #d1fae5; color: #065f46; }
.kondisi-mati { background: #fee2e2; color: #991b1b; }

/* Toggle Switch */
.toggle-wrapper { display: flex; align-items: center; gap: 10px; }
.toggle-switch {
    position: relative;
    width: 52px;
    height: 28px;
    cursor: pointer;
}
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    border-radius: 28px;
    transition: 0.3s;
    background: #ef4444;
}
.toggle-slider:before {
    content: '';
    position: absolute;
    width: 22px; height: 22px;
    left: 3px; bottom: 3px;
    background: white;
    border-radius: 50%;
    transition: 0.3s;
}
input:checked + .toggle-slider { background: #22c55e; }
input:checked + .toggle-slider:before { transform: translateX(24px); }
.toggle-label-hidup { color: #15803d; font-weight: 700; font-size: 14px; }
.toggle-label-mati { color: #dc2626; font-weight: 700; font-size: 14px; }

/* Server status banner */
.server-mati-banner {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 8px;
    padding: 12px 16px;
    color: #991b1b;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
}
</style>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2>🖥️ Detail Server: <?= h($server['ind']) ?></h2>
            <p><?= h($server['fungsi_server']) ?> | IP: <code><?= h($server['ip']) ?></code></p>
        </div>
        <div style="display: flex; gap: 10px;">
            <?php if (is_admin()): ?>
                <a href="<?= base_url('pages/data-server-edit.php?id=' . $server_id) ?>" class="btn btn-edit">✏️ Edit Server</a>
            <?php endif; ?>
            <a href="<?= base_url('pages/data-server.php') ?>" class="btn btn-secondary">← Kembali</a>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success" style="margin: 15px 25px 0;">✅ <?= h($success) ?></div>
    <?php endif; ?>

    <div style="padding: 25px;">
        <h3 style="margin: 0 0 16px 0; font-size: 16px; font-weight: 700;">📋 Informasi Server</h3>
        
        <div class="detail-grid">
            <div class="detail-label">IND:</div>
            <div class="detail-value"><strong><?= h($server['ind']) ?></strong></div>
            
            <div class="detail-label">Fungsi Server:</div>
            <div class="detail-value"><?= h($server['fungsi_server']) ?></div>
            
            <div class="detail-label">IP Address:</div>
            <div class="detail-value"><code style="background: #f3f4f6; padding: 4px 8px; border-radius: 4px;"><?= h($server['ip']) ?></code></div>
            
            <div class="detail-label">Detail:</div>
            <div class="detail-value"><?= h($server['detail'] ?: '-') ?></div>

            <div class="detail-label">Gambar:</div>
            <div class="detail-value">
                <?php if (!empty($server['gambar'])): ?>
                    <a href="<?= base_url('uploads/server_images/' . h($server['gambar'])) ?>" target="_blank" title="Lihat gambar penuh">
                        <img 
                            src="<?= base_url('uploads/server_images/' . h($server['gambar'])) ?>" 
                            alt="Gambar <?= h($server['ind']) ?>"
                            style="max-width: 260px; max-height: 180px; border-radius: 8px; border: 1px solid #e5e7eb; object-fit: contain; cursor: zoom-in;"
                        >
                    </a>
                <?php else: ?>
                    <span style="color: #94a3b8; font-style: italic;">Belum ada gambar</span>
                <?php endif; ?>
            </div>

            <div class="detail-label">Status Server:</div>
            <div class="detail-value">
                <?php $isHidup = ($server['status_server'] ?? 'HIDUP') === 'HIDUP'; ?>
                <?php if (is_admin()): ?>
                    <!-- Toggle switch untuk admin -->
                    <form method="post" style="margin: 0;" id="toggleStatusForm">
                        <input type="hidden" name="action" value="toggle_status_server">
                        <div class="toggle-wrapper">
                            <label class="toggle-switch" title="Klik untuk mengubah status server">
                                <input type="checkbox" <?= $isHidup ? 'checked' : '' ?> onchange="document.getElementById('toggleStatusForm').submit()">
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="<?= $isHidup ? 'toggle-label-hidup' : 'toggle-label-mati' ?>">
                                <?= $isHidup ? '🟢 HIDUP' : '🔴 MATI' ?>
                            </span>
                        </div>
                    </form>
                <?php else: ?>
                    <!-- Non-admin: tampilkan badge saja -->
                    <span class="status-badge <?= $isHidup ? 'kondisi-hidup' : 'kondisi-mati' ?>">
                        <?= $isHidup ? '🟢 HIDUP' : '🔴 MATI' ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="detail-separator"></div>
        <h4 style="margin: 0 0 12px 0; color: #1e293b;">Hardware</h4>
        <div class="detail-grid">
            <div class="detail-label">Merk:</div>
            <div class="detail-value"><?= h($server['merk'] ?: '-') ?></div>
            
            <div class="detail-label">Type:</div>
            <div class="detail-value"><?= h($server['type'] ?: '-') ?></div>
            
            <div class="detail-label">System Operasi:</div>
            <div class="detail-value"><?= h($server['system_operasi'] ?: '-') ?></div>
        </div>

        <div class="detail-separator"></div>
        <h4 style="margin: 0 0 12px 0; color: #1e293b;">Processor</h4>
        <div class="detail-grid">
            <div class="detail-label">Merk & Type:</div>
            <div class="detail-value"><?= h($server['processor_merk'] ?: '-') ?> <?= h($server['processor_type'] ?: '') ?></div>
            
            <div class="detail-label">Kecepatan:</div>
            <div class="detail-value"><?= h($server['processor_kecepatan'] ?: '-') ?> GHz</div>
            
            <div class="detail-label">Keping & Core:</div>
            <div class="detail-value"><?= $server['processor_keping'] ?: 0 ?> keping, <?= $server['processor_core'] ?: 0 ?> cores</div>
        </div>

        <div class="detail-separator"></div>
        <h4 style="margin: 0 0 12px 0; color: #1e293b;">RAM</h4>
        <div class="detail-grid">
            <div class="detail-label">Jenis & Kapasitas:</div>
            <div class="detail-value"><?= h($server['ram_jenis'] ?: '-') ?> <?= h($server['ram_kapasitas'] ?: '') ?></div>
            
            <div class="detail-label">Jumlah Keping:</div>
            <div class="detail-value"><?= $server['ram_jumlah_keping'] ?: 0 ?> keping</div>
        </div>

        <div class="detail-separator"></div>
        <h4 style="margin: 0 0 12px 0; color: #1e293b;">Storage</h4>
        <div class="detail-grid">
            <div class="detail-label">Jenis:</div>
            <div class="detail-value"><?= h($server['storage_jenis'] ?: '-') ?></div>
            
            <div class="detail-label">Jumlah:</div>
            <div class="detail-value"><?= $server['storage_jumlah'] ?: 0 ?> unit</div>
            
            <div class="detail-label">Kapasitas Total:</div>
            <div class="detail-value"><?= h($server['storage_kapasitas_total'] ?: '-') ?></div>
        </div>

        <div class="detail-separator"></div>
        <h4 style="margin: 0 0 12px 0; color: #1e293b;">Informasi Tambahan</h4>
        <div class="detail-grid">
            <div class="detail-label">Server Fisik:</div>
            <div class="detail-value"><?= h($server['server_fisik'] ?: '-') ?></div>
            
            <div class="detail-label">Keterangan:</div>
            <div class="detail-value"><?= h($server['keterangan_tambahan'] ?: '-') ?></div>
            
            <div class="detail-label">Dibuat Pada:</div>
            <div class="detail-value"><?= h($server['created_at']) ?></div>
        </div>
    </div>
</div>

<!-- Maintenance History -->
<?php $serverHidup = ($server['status_server'] ?? 'HIDUP') === 'HIDUP'; ?>
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2>🔧 History Pemeliharaan</h2>
            <p>Riwayat pemeliharaan server</p>
        </div>
        <?php if ($serverHidup): ?>
            <a href="<?= base_url('pages/maintenance-input.php?server_id=' . $server_id) ?>" class="btn btn-primary">
                ➕ Input History Pemeliharaan
            </a>
        <?php else: ?>
            <span style="background: #fee2e2; color: #991b1b; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; border: 1px solid #fecaca;">
                🔴 Server Mati — Input Dinonaktifkan
            </span>
        <?php endif; ?>
    </div>

    <div style="padding: 25px;">
        <?php if (!$serverHidup): ?>
            <div class="server-mati-banner">
                🚫 Server dalam keadaan <strong>MATI</strong>. Input dan edit history pemeliharaan tidak dapat dilakukan.
            </div>
        <?php endif; ?>

        <?php if (empty($maintenance_history)): ?>
            <p style="text-align: center; padding: 40px 20px; color: #94a3b8; font-size: 14px;">
                📋 Belum menginputkan data history pemeliharaan.<br>
                <?php if ($serverHidup): ?>
                    <span style="font-size: 13px;">Klik tombol <strong>"Input History Pemeliharaan"</strong> untuk menambahkan data baru.</span>
                <?php endif; ?>
            </p>
        <?php else: ?>
            <?php foreach ($maintenance_history as $m): ?>
                <div class="history-card">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                        <div>
                            <div style="font-weight: 700; color: #1e293b; margin-bottom: 4px;">
                                🕒 <?= h($m['waktu_pemeliharaan']) ?>
                            </div>
                            <div style="font-size: 12px; color: #64748b;">
                                Dicek oleh: <strong><?= h($m['dicek_oleh']) ?></strong> | 
                                Input oleh: <?= h($m['created_by_name'] ?: 'System') ?>
                            </div>
                        </div>
                        <div style="display: flex; gap: 6px;">
                            <span class="status-badge kondisi-<?= strtolower($m['kondisi']) ?>">
                                <?= $m['kondisi'] === 'HIDUP' ? '🟢' : '🔴' ?> <?= h($m['kondisi']) ?>
                            </span>
                            <span class="status-badge status-<?= strtolower($m['status']) ?>">
                                <?= $m['status'] === 'AMAN' ? '✅' : '⚠️' ?> <?= h($m['status']) ?>
                            </span>
                        </div>
                    </div>
                    
                    <div style="background: white; padding: 12px; border-radius: 6px; margin-bottom: 10px;">
                        <div style="font-size: 13px; color: #475569; font-weight: 600; margin-bottom: 4px;">Temuan:</div>
                        <div style="color: #1e293b; font-size: 14px;"><?= nl2br(h($m['temuan'])) ?></div>
                    </div>

                    <?php if (!empty($m['gambar'])): ?>
                        <div style="background: white; padding: 12px; border-radius: 6px; margin-bottom: 10px;">
                            <div style="font-size: 13px; color: #475569; font-weight: 600; margin-bottom: 8px;">📷 Gambar Pemeliharaan:</div>
                            <a href="<?= base_url('uploads/maintenance_images/' . h($m['gambar'])) ?>" target="_blank" title="Lihat gambar penuh">
                                <img 
                                    src="<?= base_url('uploads/maintenance_images/' . h($m['gambar'])) ?>"
                                    alt="Gambar pemeliharaan"
                                    style="max-width: 100%; max-height: 220px; border-radius: 8px; border: 1px solid #e5e7eb; object-fit: contain; cursor: zoom-in; display: block;"
                                >
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if (is_admin()): ?>
                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                            <?php if ($serverHidup): ?>
                                <a href="<?= base_url('pages/maintenance-edit.php?id=' . $m['id']) ?>" class="btn btn-sm btn-edit">✏️ Edit</a>
                            <?php else: ?>
                                <span style="color: #94a3b8; font-size: 12px; font-style: italic; padding: 4px 8px;">✏️ Edit dinonaktifkan</span>
                            <?php endif; ?>
                            <form method="post" style="display: inline; margin: 0;" onsubmit="return confirm('Yakin hapus history ini?')">
                                <input type="hidden" name="action" value="delete_maintenance">
                                <input type="hidden" name="maintenance_id" value="<?= $m['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">🗑️ Hapus</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>