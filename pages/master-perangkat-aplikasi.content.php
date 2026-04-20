<?php
// pages/master-perangkat-aplikasi.content.php
$TAB_META = [
    'jenis'  => ['icon' => '🗂️',  'label' => 'Jenis Perangkat',  'placeholder' => 'Contoh: Aplikasi Web, ERP, SCADA'],
    'brand'  => ['icon' => '🏷️',  'label' => 'Brand',            'placeholder' => 'Contoh: Microsoft, Oracle, Custom / In-house'],
    'lokasi' => ['icon' => '📍',  'label' => 'Lokasi',           'placeholder' => 'Contoh: UP3 Semarang, Data Center Utama'],
    'bidang' => ['icon' => '🏢',  'label' => 'Bidang',           'placeholder' => 'Contoh: STI, Niaga, Keuangan'],
    'msb'    => ['icon' => '📂',  'label' => 'MSB / Sub Bidang', 'placeholder' => 'Contoh: Sistem & Infrastruktur'],
];
?>

<style>
.mpa-tabs { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 0; border-bottom: 2px solid #e5e7eb; padding-bottom: 0; }
.mpa-tab-btn { padding: 11px 20px; border: none; background: none; font-size: 14px; font-weight: 600; color: #64748b; cursor: pointer; border-bottom: 3px solid transparent; margin-bottom: -2px; transition: color .2s, border-color .2s; white-space: nowrap; display: flex; align-items: center; gap: 7px; text-decoration: none; }
.mpa-tab-btn:hover { color: #1e293b; }
.mpa-tab-btn.active { color: #3b82f6; border-bottom-color: #3b82f6; }
.mpa-panel { display: none; }
.mpa-panel.active { display: block; }
.mpa-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,.07); overflow: hidden; margin-bottom: 20px; }
.mpa-card-header { padding: 18px 22px; background: #f8fafc; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.mpa-card-header h3 { margin: 0; font-size: 17px; font-weight: 800; color: #0f172a; }
.mpa-card-header p  { margin: 4px 0 0; font-size: 13px; color: #64748b; }
.count-badge { background: #dbeafe; color: #1e40af; padding: 4px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; white-space: nowrap; }
.mpa-add-form { padding: 16px 22px; background: #f0fdf4; border-bottom: 1px solid #bbf7d0; display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
.mpa-add-form input[type="text"] { flex: 1; min-width: 240px; padding: 10px 14px; border: 1.5px solid #d1d5db; border-radius: 9px; font-size: 14px; outline: none; transition: border-color .2s, box-shadow .2s; font-family: inherit; }
.mpa-add-form input[type="text"]:focus { border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,.12); }
.btn-add { padding: 10px 18px; background: #22c55e; color: #fff; border: none; border-radius: 9px; font-size: 14px; font-weight: 700; cursor: pointer; white-space: nowrap; transition: background .2s; }
.btn-add:hover { background: #16a34a; }
.mpa-table-wrap { overflow-x: auto; }
.mpa-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.mpa-table thead th { background: #f8fafc; padding: 12px 16px; text-align: left; font-weight: 700; color: #475569; border-bottom: 2px solid #e5e7eb; white-space: nowrap; }
.mpa-table tbody td { padding: 11px 16px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
.mpa-table tbody tr:last-child td { border-bottom: none; }
.mpa-table tbody tr:hover { background: #f8fafc; }
.s-on  { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; background: #d1fae5; color: #065f46; white-space: nowrap; }
.s-off { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; background: #fee2e2; color: #991b1b; white-space: nowrap; }
.mpa-btn-group { display: flex; gap: 6px; align-items: center; }
.btn-mpa { padding: 6px 12px; border: none; border-radius: 7px; font-size: 12px; font-weight: 700; cursor: pointer; white-space: nowrap; transition: opacity .15s; }
.btn-mpa:hover { opacity: .82; }
.btn-mpa-edit   { background: #3b82f6; color: #fff; }
.btn-mpa-toggle { background: #f59e0b; color: #fff; }
.btn-mpa-del    { background: #ef4444; color: #fff; }
.mpa-empty { text-align: center; padding: 36px 20px; color: #94a3b8; font-size: 14px; }
.mpa-footer-note { padding: 12px 18px; background: #f8fafc; border-top: 1px solid #e5e7eb; font-size: 12px; color: #64748b; }

/* Pagination */
.mpa-pagination { display: flex; justify-content: center; align-items: center; gap: 8px; padding: 16px 22px; flex-wrap: wrap; border-top: 1px solid #f1f5f9; }
.mpa-pagination a, .mpa-pagination span { padding: 8px 14px; border: 1px solid #d1d5db; border-radius: 6px; text-decoration: none; color: #374151; font-size: 13px; font-weight: 600; }
.mpa-pagination a:hover { background: #f3f4f6; border-color: #9ca3af; }
.mpa-pagination .active { background: #3b82f6; color: white; border-color: #3b82f6; }

.mpa-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.48); z-index: 3000; align-items: center; justify-content: center; }
.mpa-modal.show { display: flex; }
.mpa-modal-box { background: #fff; border-radius: 14px; width: 92%; max-width: 440px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,.25); animation: mpaPop .2s ease; }
@keyframes mpaPop { from { transform: scale(.94); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.mpa-modal-header { padding: 18px 22px; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; }
.mpa-modal-header h3 { margin: 0; font-size: 17px; font-weight: 800; color: #0f172a; }
.mpa-modal-close { background: none; border: none; font-size: 26px; cursor: pointer; color: #94a3b8; line-height: 1; }
.mpa-modal-close:hover { color: #475569; }
.mpa-modal-body { padding: 22px; }
.mpa-fg { margin-bottom: 0; }
.mpa-fg label { display: block; font-weight: 700; margin-bottom: 8px; color: #374151; font-size: 14px; }
.mpa-fg input { width: 100%; padding: 11px 13px; border: 1.5px solid #d1d5db; border-radius: 9px; font-size: 14px; outline: none; transition: border-color .2s, box-shadow .2s; box-sizing: border-box; font-family: inherit; }
.mpa-fg input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.12); }
.mpa-modal-footer { display: flex; gap: 10px; justify-content: flex-end; padding: 16px 22px; border-top: 1px solid #e5e7eb; background: #f8fafc; }
.btn-mpa-save   { padding: 10px 22px; background: #3b82f6; color: #fff; border: none; border-radius: 9px; font-size: 14px; font-weight: 700; cursor: pointer; }
.btn-mpa-save:hover { background: #2563eb; }
.btn-mpa-cancel { padding: 10px 18px; background: #e5e7eb; color: #374151; border: none; border-radius: 9px; font-size: 14px; font-weight: 600; cursor: pointer; }
</style>

<!-- Edit Modal -->
<div class="mpa-modal" id="mpaEditModal">
    <div class="mpa-modal-box">
        <div class="mpa-modal-header">
            <h3 id="mpaModalTitle">✏️ Edit Item</h3>
            <button class="mpa-modal-close" onclick="closeMpaModal()">&times;</button>
        </div>
        <form method="post" id="mpaEditForm">
            <input type="hidden" name="action"    value="edit_item">
            <input type="hidden" name="tab_key"   id="mpaEditTab">
            <input type="hidden" name="item_id"   id="mpaEditId">
            <div class="mpa-modal-body">
                <div class="mpa-fg">
                    <label id="mpaEditLabel">Nama</label>
                    <input type="text" name="item_name" id="mpaEditName" required>
                </div>
            </div>
            <div class="mpa-modal-footer">
                <button type="button" class="btn-mpa-cancel" onclick="closeMpaModal()">Batal</button>
                <button type="submit" class="btn-mpa-save">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- HEADER CARD -->
<div class="mpa-card" style="margin-bottom:22px;">
    <div class="mpa-card-header">
        <div>
            <h3>📋 Master Perangkat Aplikasi</h3>
            <p>Kelola opsi dropdown pada form Input &amp; Edit Perangkat Aplikasi</p>
        </div>
    </div>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success" style="margin:14px 22px 0;">✅ <?= h($success ?: 'Perubahan berhasil disimpan.') ?></div>
    <?php elseif (!empty($success)): ?>
        <div class="alert alert-success" style="margin:14px 22px 0;">✅ <?= h($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error" style="margin:14px 22px 0;">
            <strong>⚠ Error:</strong>
            <ul style="margin:8px 0 0 18px;">
                <?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- TAB NAV -->
    <div style="padding:18px 22px 0;">
        <div class="mpa-tabs">
            <?php foreach ($TAB_META as $key => $meta): ?>
                <a
                    href="<?= base_url('pages/master-perangkat-aplikasi.php?tab=' . $key) ?>"
                    class="mpa-tab-btn <?= $active_tab === $key ? 'active' : '' ?>"
                >
                    <?= $meta['icon'] ?> <?= h($meta['label']) ?>
                    <span style="background:#e2e8f0;color:#475569;border-radius:20px;padding:1px 8px;font-size:11px;font-weight:700;">
                        <?= $tab_totals[$key] ?>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- TAB PANELS -->
    <div style="padding:0 0 4px;">
        <?php foreach ($TAB_META as $key => $meta): ?>
            <div class="mpa-panel <?= $active_tab === $key ? 'active' : '' ?>">

                <?php if ($active_tab === $key): ?>

                    <form method="post" class="mpa-add-form">
                        <input type="hidden" name="action"    value="add_item">
                        <input type="hidden" name="tab_key"   value="<?= $key ?>">
                        <input type="text"   name="item_name" placeholder="<?= h($meta['placeholder']) ?>" required>
                        <button type="submit" class="btn-add">➕ Tambah <?= h($meta['label']) ?></button>
                    </form>

                    <div style="padding:10px 22px 0; font-size:13px; color:#64748b;">
                        Total: <strong><?= $total_count ?></strong> item | Halaman <strong><?= $page ?></strong> dari <strong><?= max(1, $total_pages) ?></strong>
                    </div>

                    <div class="mpa-table-wrap">
                        <table class="mpa-table">
                            <thead>
                                <tr>
                                    <th style="width:50px;">No</th>
                                    <th><?= h($meta['label']) ?></th>
                                    <th style="width:80px;">Status</th>
                                    <th style="width:150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($data[$key])): ?>
                                    <tr><td colspan="4" class="mpa-empty">Belum ada data <?= h($meta['label']) ?></td></tr>
                                <?php else: ?>
                                    <?php $no = $offset + 1; foreach ($data[$key] as $item): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td>
                                                <span style="<?= !(int)$item['is_active'] ? 'color:#94a3b8;text-decoration:line-through;' : 'font-weight:600;' ?>">
                                                    <?= h($item['name']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ((int)$item['is_active']): ?>
                                                    <span class="s-on">🟢 Aktif</span>
                                                <?php else: ?>
                                                    <span class="s-off">🔴 Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="mpa-btn-group">
                                                    <button type="button" class="btn-mpa btn-mpa-edit"
                                                        onclick='openMpaEdit("<?= $key ?>", <?= (int)$item['id'] ?>, <?= json_encode($item['name']) ?>, "<?= h($meta['label']) ?>")'>✏️</button>

                                                    <form method="post" style="margin:0;">
                                                        <input type="hidden" name="action"  value="toggle_item">
                                                        <input type="hidden" name="tab_key" value="<?= $key ?>">
                                                        <input type="hidden" name="item_id" value="<?= (int)$item['id'] ?>">
                                                        <button type="submit" class="btn-mpa btn-mpa-toggle"
                                                            title="<?= (int)$item['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                                            <?= (int)$item['is_active'] ? '🔕' : '🔔' ?>
                                                        </button>
                                                    </form>

                                                    <form method="post" style="margin:0;"
                                                        onsubmit="return confirm('Yakin hapus \'<?= h(addslashes($item['name'])) ?>\'?')">
                                                        <input type="hidden" name="action"  value="delete_item">
                                                        <input type="hidden" name="tab_key" value="<?= $key ?>">
                                                        <input type="hidden" name="item_id" value="<?= (int)$item['id'] ?>">
                                                        <button type="submit" class="btn-mpa btn-mpa-del">🗑️</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($total_pages > 1): ?>
                        <div class="mpa-pagination">
                            <?php if ($page > 1): ?>
                                <a href="?tab=<?= $key ?>&page=1">«« First</a>
                                <a href="?tab=<?= $key ?>&page=<?= $page - 1 ?>">‹ Prev</a>
                            <?php endif; ?>
                            <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                                <?php if ($i === $page): ?>
                                    <span class="active"><?= $i ?></span>
                                <?php else: ?>
                                    <a href="?tab=<?= $key ?>&page=<?= $i ?>"><?= $i ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <?php if ($page < $total_pages): ?>
                                <a href="?tab=<?= $key ?>&page=<?= $page + 1 ?>">Next ›</a>
                                <a href="?tab=<?= $key ?>&page=<?= $total_pages ?>">Last »»</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="mpa-footer-note">
                        💡 Item dengan status <strong>Nonaktif</strong> tidak akan muncul di dropdown form Input &amp; Edit.
                    </div>

                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function openMpaEdit(tabKey, id, name, label) {
    document.getElementById('mpaModalTitle').textContent = '✏️ Edit ' + label;
    document.getElementById('mpaEditLabel').textContent  = 'Nama ' + label;
    document.getElementById('mpaEditTab').value  = tabKey;
    document.getElementById('mpaEditId').value   = id;
    document.getElementById('mpaEditName').value = name;
    document.getElementById('mpaEditModal').classList.add('show');
    setTimeout(function() { document.getElementById('mpaEditName').focus(); }, 150);
}
function closeMpaModal() { document.getElementById('mpaEditModal').classList.remove('show'); }
document.addEventListener('click', function (e) { if (e.target && e.target.id === 'mpaEditModal') closeMpaModal(); });
document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeMpaModal(); });
</script>