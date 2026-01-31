<?php // pages/data-server-detail.content.php ?>
<style>
.detail-grid { display: grid; grid-template-columns: 200px 1fr; gap: 14px; margin-bottom: 14px; }
.detail-label { font-weight: 700; color: #475569; }
.detail-value { color: #1e293b; }
.detail-separator { border-bottom: 1px solid #e5e7eb; margin: 20px 0; }
.history-card { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 10px; padding: 16px; margin-bottom: 12px; }
.status-badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; }
.status-aman { background: #d1fae5; color: #065f46; }
.status-problem { background: #fee2e2; color: #991b1b; }
.kondisi-hidup { background: #d1fae5; color: #065f46; }
.kondisi-mati { background: #fee2e2; color: #991b1b; }
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
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2>🔧 History Pemeliharaan</h2>
            <p>Riwayat pemeliharaan server</p>
        </div>
        <a href="<?= base_url('pages/maintenance-input.php?server_id=' . $server_id) ?>" class="btn btn-primary">
            ➕ Input History Pemeliharaan
        </a>
    </div>

    <div style="padding: 25px;">
        <?php if (empty($maintenance_history)): ?>
            <p style="text-align: center; padding: 40px 20px; color: #94a3b8; font-size: 14px;">
                📋 Belum menginputkan data history pemeliharaan.<br>
                <span style="font-size: 13px;">Klik tombol <strong>"Input History Pemeliharaan"</strong> untuk menambahkan data baru.</span>
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

                    <?php if (is_admin()): ?>
                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                            <a href="<?= base_url('pages/maintenance-edit.php?id=' . $m['id']) ?>" class="btn btn-sm btn-edit">✏️ Edit</a>
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