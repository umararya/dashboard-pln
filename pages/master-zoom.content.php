<?php
// pages/master-zoom.content.php
?>

<style>
/* ── Grid 2 kolom ────────────────────────────────────── */
.mz-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    align-items: start;
}
@media (max-width: 900px) {
    .mz-grid { grid-template-columns: 1fr; }
}

/* ── Card ────────────────────────────────────────────── */
.mz-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,.07);
    overflow: hidden;
}
.mz-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    background: #f8fafc;
}
.mz-card-header h3 {
    margin: 0;
    font-size: 17px;
    font-weight: 800;
    color: #0f172a;
}
.mz-card-header p {
    margin: 4px 0 0;
    font-size: 13px;
    color: #64748b;
}
.mz-count-badge {
    background: #dbeafe;
    color: #1e40af;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
    white-space: nowrap;
}

/* ── Add Form ─────────────────────────────────────────── */
.mz-add-form {
    padding: 16px 22px;
    background: #f0fdf4;
    border-bottom: 1px solid #bbf7d0;
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}
.mz-add-form input[type="text"],
.mz-add-form input[type="email"] {
    flex: 1;
    min-width: 200px;
    padding: 10px 14px;
    border: 1.5px solid #d1d5db;
    border-radius: 9px;
    font-size: 14px;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
    font-family: inherit;
}
.mz-add-form input:focus {
    border-color: #22c55e;
    box-shadow: 0 0 0 3px rgba(34,197,94,.12);
}
.btn-add-item {
    padding: 10px 18px;
    background: #22c55e;
    color: #fff;
    border: none;
    border-radius: 9px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    transition: background .2s;
}
.btn-add-item:hover { background: #16a34a; }

/* ── Table ────────────────────────────────────────────── */
.mz-table-wrap { overflow-x: auto; }
.mz-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}
.mz-table thead th {
    background: #f8fafc;
    padding: 12px 16px;
    text-align: left;
    font-weight: 700;
    color: #475569;
    border-bottom: 2px solid #e5e7eb;
    white-space: nowrap;
}
.mz-table tbody td {
    padding: 11px 16px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}
.mz-table tbody tr:last-child td { border-bottom: none; }
.mz-table tbody tr:hover { background: #f8fafc; }

/* ── Status Badge ──────────────────────────────────────── */
.status-on  { background: #d1fae5; color: #065f46; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; white-space: nowrap; display: inline-block; }
.status-off { background: #fee2e2; color: #991b1b; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; white-space: nowrap; display: inline-block; }

/* ── Action buttons ───────────────────────────────────── */
.mz-btn-group { display: flex; gap: 6px; align-items: center; flex-wrap: nowrap; }
.btn-mz {
    padding: 6px 12px;
    border: none;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    transition: opacity .15s;
}
.btn-mz:hover { opacity: .82; }
.btn-mz-edit    { background: #3b82f6; color: #fff; }
.btn-mz-toggle  { background: #f59e0b; color: #fff; }
.btn-mz-del     { background: #ef4444; color: #fff; }
.btn-mz-link    { background: none; border: none; padding: 0; cursor: pointer; font-size: 13px; text-decoration: none; }

/* ── Empty State ──────────────────────────────────────── */
.mz-empty {
    text-align: center;
    padding: 36px 20px;
    color: #94a3b8;
    font-size: 14px;
}

/* ── Modal ────────────────────────────────────────────── */
.mz-modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.48);
    z-index: 3000;
    align-items: center;
    justify-content: center;
}
.mz-modal.show { display: flex; }
.mz-modal-box {
    background: #fff;
    border-radius: 14px;
    width: 92%;
    max-width: 460px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(0,0,0,.25);
    animation: mzPop .2s ease;
}
@keyframes mzPop {
    from { transform: scale(.94); opacity: 0; }
    to   { transform: scale(1);   opacity: 1; }
}
.mz-modal-header {
    padding: 18px 22px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.mz-modal-header h3 { margin: 0; font-size: 17px; font-weight: 800; color: #0f172a; }
.mz-modal-close {
    background: none;
    border: none;
    font-size: 26px;
    cursor: pointer;
    color: #94a3b8;
    line-height: 1;
    padding: 0;
}
.mz-modal-close:hover { color: #475569; }
.mz-modal-body { padding: 22px; }
.mz-form-group { margin-bottom: 16px; }
.mz-form-group label {
    display: block;
    font-weight: 700;
    margin-bottom: 7px;
    color: #374151;
    font-size: 14px;
}
.mz-form-group input {
    width: 100%;
    padding: 11px 13px;
    border: 1.5px solid #d1d5db;
    border-radius: 9px;
    font-size: 14px;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
    box-sizing: border-box;
    font-family: inherit;
}
.mz-form-group input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.12);
}
.mz-modal-footer {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    padding: 16px 22px;
    border-top: 1px solid #e5e7eb;
    background: #f8fafc;
}
.btn-mz-save   { padding: 10px 22px; background: #3b82f6; color: #fff; border: none; border-radius: 9px; font-size: 14px; font-weight: 700; cursor: pointer; transition: background .2s; }
.btn-mz-save:hover { background: #2563eb; }
.btn-mz-cancel { padding: 10px 18px; background: #e5e7eb; color: #374151; border: none; border-radius: 9px; font-size: 14px; font-weight: 600; cursor: pointer; }
</style>

<!-- Edit Unit Modal -->
<div class="mz-modal" id="modalEditUnit">
    <div class="mz-modal-box">
        <div class="mz-modal-header">
            <h3>✏️ Edit Unit</h3>
            <button class="mz-modal-close" onclick="closeMzModal('modalEditUnit')">&times;</button>
        </div>
        <form method="post">
            <input type="hidden" name="action"  value="edit_unit">
            <input type="hidden" name="unit_id" id="editUnitId">
            <div class="mz-modal-body">
                <div class="mz-form-group">
                    <label>Nama Unit</label>
                    <input type="text" name="unit_name" id="editUnitName" required placeholder="Nama unit / bagian">
                </div>
            </div>
            <div class="mz-modal-footer">
                <button type="button" class="btn-mz-cancel" onclick="closeMzModal('modalEditUnit')">Batal</button>
                <button type="submit" class="btn-mz-save">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Link Modal -->
<div class="mz-modal" id="modalEditLink">
    <div class="mz-modal-box">
        <div class="mz-modal-header">
            <h3>✏️ Edit Link Zoom</h3>
            <button class="mz-modal-close" onclick="closeMzModal('modalEditLink')">&times;</button>
        </div>
        <form method="post">
            <input type="hidden" name="action"  value="edit_link">
            <input type="hidden" name="link_id" id="editLinkId">
            <div class="mz-modal-body">
                <div class="mz-form-group">
                    <label>Email Akun Zoom</label>
                    <input type="email" name="link_email" id="editLinkEmail" required placeholder="contoh@gmail.com">
                </div>
            </div>
            <div class="mz-modal-footer">
                <button type="button" class="btn-mz-cancel" onclick="closeMzModal('modalEditLink')">Batal</button>
                <button type="submit" class="btn-mz-save">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- PAGE HEADER CARD -->
<div class="card" style="margin-bottom:22px;">
    <div class="card-header">
        <h2>🎥 Master Zoom</h2>
        <p>Kelola opsi dropdown <strong>Unit</strong> dan <strong>Link Zoom</strong> yang muncul pada form Booking Jadwal Zoom.</p>
    </div>

    <?php if (!empty($success)): ?>
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
</div>

<!-- 2-COLUMN GRID -->
<div class="mz-grid">

    <!-- ══ KOLOM KIRI: UNIT ══════════════════════════════ -->
    <div class="mz-card">
        <div class="mz-card-header">
            <div>
                <h3>🏢 Daftar Unit</h3>
                <p>Unit / bagian yang muncul di dropdown form booking</p>
            </div>
            <span class="mz-count-badge"><?= count($unit_list) ?> unit</span>
        </div>

        <!-- Add Form -->
        <form method="post" class="mz-add-form">
            <input type="hidden" name="action" value="add_unit">
            <input type="text"
                   name="unit_name"
                   placeholder="Nama unit baru (contoh: UP3 Demak)"
                   required>
            <button type="submit" class="btn-add-item">➕ Tambah</button>
        </form>

        <!-- Table -->
        <div class="mz-table-wrap">
            <table class="mz-table">
                <thead>
                    <tr>
                        <th style="width:46px;">No</th>
                        <th>Nama Unit</th>
                        <th style="width:70px;">Status</th>
                        <th style="width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($unit_list)): ?>
                        <tr><td colspan="4" class="mz-empty">Belum ada data unit</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($unit_list as $u): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <strong><?= h($u['name']) ?></strong>
                                </td>
                                <td>
                                    <?php if ((int)$u['is_active']): ?>
                                        <span class="status-on">🟢 Aktif</span>
                                    <?php else: ?>
                                        <span class="status-off">🔴 Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="mz-btn-group">
                                        <!-- Edit -->
                                        <button type="button"
                                                class="btn-mz btn-mz-edit"
                                                onclick='openEditUnit(<?= (int)$u["id"] ?>, <?= json_encode($u["name"]) ?>)'>
                                            ✏️
                                        </button>

                                        <!-- Toggle Aktif -->
                                        <form method="post" style="margin:0;">
                                            <input type="hidden" name="action"  value="toggle_unit">
                                            <input type="hidden" name="unit_id" value="<?= (int)$u['id'] ?>">
                                            <button type="submit"
                                                    class="btn-mz btn-mz-toggle"
                                                    title="<?= (int)$u['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                                <?= (int)$u['is_active'] ? '🔕' : '🔔' ?>
                                            </button>
                                        </form>

                                        <!-- Hapus -->
                                        <form method="post" style="margin:0;"
                                              onsubmit="return confirm('Yakin hapus unit \'<?= h(addslashes($u['name'])) ?>\'?')">
                                            <input type="hidden" name="action"  value="delete_unit">
                                            <input type="hidden" name="unit_id" value="<?= (int)$u['id'] ?>">
                                            <button type="submit" class="btn-mz btn-mz-del" title="Hapus">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Info footer -->
        <div style="padding:12px 18px;background:#f8fafc;border-top:1px solid #e5e7eb;font-size:12px;color:#64748b;">
            💡 Unit dengan status <strong>Nonaktif</strong> tidak akan muncul di dropdown form booking.
        </div>
    </div>

    <!-- ══ KOLOM KANAN: LINK ZOOM ════════════════════════ -->
    <div class="mz-card">
        <div class="mz-card-header">
            <div>
                <h3>🔗 Daftar Link Zoom</h3>
                <p>Akun Zoom yang muncul di dropdown form booking</p>
            </div>
            <span class="mz-count-badge"><?= count($link_list) ?> akun</span>
        </div>

        <!-- Add Form -->
        <form method="post" class="mz-add-form" style="background:#eff6ff;border-bottom-color:#bfdbfe;">
            <input type="hidden" name="action" value="add_link">
            <input type="email"
                   name="link_email"
                   placeholder="Email akun Zoom baru"
                   required
                   style="border-color:#bfdbfe;">
            <button type="submit" class="btn-add-item" style="background:#3b82f6;">➕ Tambah</button>
        </form>

        <!-- Table -->
        <div class="mz-table-wrap">
            <table class="mz-table">
                <thead>
                    <tr>
                        <th style="width:46px;">No</th>
                        <th>Email Akun Zoom</th>
                        <th style="width:70px;">Status</th>
                        <th style="width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($link_list)): ?>
                        <tr><td colspan="4" class="mz-empty">Belum ada data link Zoom</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($link_list as $lk): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <span style="font-family:monospace;font-size:13px;background:#f0f9ff;padding:3px 8px;border-radius:5px;color:#0369a1;">
                                        <?= h($lk['email']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ((int)$lk['is_active']): ?>
                                        <span class="status-on">🟢 Aktif</span>
                                    <?php else: ?>
                                        <span class="status-off">🔴 Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="mz-btn-group">
                                        <!-- Edit -->
                                        <button type="button"
                                                class="btn-mz btn-mz-edit"
                                                onclick='openEditLink(<?= (int)$lk["id"] ?>, <?= json_encode($lk["email"]) ?>)'>
                                            ✏️
                                        </button>

                                        <!-- Toggle Aktif -->
                                        <form method="post" style="margin:0;">
                                            <input type="hidden" name="action"  value="toggle_link">
                                            <input type="hidden" name="link_id" value="<?= (int)$lk['id'] ?>">
                                            <button type="submit"
                                                    class="btn-mz btn-mz-toggle"
                                                    title="<?= (int)$lk['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                                <?= (int)$lk['is_active'] ? '🔕' : '🔔' ?>
                                            </button>
                                        </form>

                                        <!-- Hapus -->
                                        <form method="post" style="margin:0;"
                                              onsubmit="return confirm('Yakin hapus link Zoom ini?')">
                                            <input type="hidden" name="action"  value="delete_link">
                                            <input type="hidden" name="link_id" value="<?= (int)$lk['id'] ?>">
                                            <button type="submit" class="btn-mz btn-mz-del" title="Hapus">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Info footer -->
        <div style="padding:12px 18px;background:#f8fafc;border-top:1px solid #e5e7eb;font-size:12px;color:#64748b;">
            💡 Akun dengan status <strong>Nonaktif</strong> tidak akan muncul di dropdown form booking.
        </div>
    </div>

</div><!-- end .mz-grid -->

<script>
/* ── Modal helpers ──────────────────────────────────────── */
function openEditUnit(id, name) {
    document.getElementById('editUnitId').value   = id;
    document.getElementById('editUnitName').value = name;
    document.getElementById('modalEditUnit').classList.add('show');
}

function openEditLink(id, email) {
    document.getElementById('editLinkId').value    = id;
    document.getElementById('editLinkEmail').value = email;
    document.getElementById('modalEditLink').classList.add('show');
}

function closeMzModal(modalId) {
    document.getElementById(modalId).classList.remove('show');
}

/* Tutup saat klik backdrop */
document.addEventListener('click', function (e) {
    if (e.target && e.target.classList && e.target.classList.contains('mz-modal')) {
        e.target.classList.remove('show');
    }
});

/* Tutup saat tekan Escape */
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.mz-modal.show').forEach(function (m) {
            m.classList.remove('show');
        });
    }
});
</script>