<?php
// pages/data-server.content.php
?>

<style>
.table-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
.btn-toggle-view { padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-block; }
.btn-toggle-view:hover { background: #2563eb; }
.btn-add-data { padding: 8px 16px; background: #10b981; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-block; }
.btn-add-data:hover { background: #059669; }
.table-wrapper { overflow-x: auto; border-radius: 10px; border: 1px solid #e5e7eb; }
.data-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.data-table thead th { background: #f8fafc; padding: 12px 14px; text-align: left; font-weight: 700; color: #475569; border-bottom: 2px solid #e5e7eb; white-space: nowrap; }
.data-table tbody td { padding: 10px 14px; border-bottom: 1px solid #e5e7eb; vertical-align: middle; }
.data-table tbody tr:hover { background: #f8fafc; }
.data-table.compact .extended-col { display: none; }
.data-table.full .extended-col { display: table-cell; }

.badge-server-hidup { display: inline-block; padding: 3px 10px; border-radius: 20px; background: #d1fae5; color: #065f46; font-size: 11px; font-weight: 700; white-space: nowrap; }
.badge-server-mati  { display: inline-block; padding: 3px 10px; border-radius: 20px; background: #fee2e2; color: #991b1b; font-size: 11px; font-weight: 700; white-space: nowrap; }
.status-toggle-btn { cursor: pointer; border: none; background: none; padding: 0; display: inline-block; }
.status-toggle-btn:hover .badge-server-hidup { background: #a7f3d0; }
.status-toggle-btn:hover .badge-server-mati  { background: #fecaca; }
.status-toggle-btn .toggle-hint { display: block; font-size: 10px; color: #94a3b8; margin-top: 2px; text-align: center; }

.server-thumb { width: 56px; height: 42px; object-fit: cover; border-radius: 6px; border: 1px solid #e5e7eb; cursor: pointer; transition: transform 0.15s; display: block; }
.server-thumb:hover { transform: scale(1.08); }
.no-thumb { width: 56px; height: 42px; background: #f1f5f9; border-radius: 6px; border: 1px dashed #cbd5e1; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #94a3b8; }

.img-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75); z-index: 9999; align-items: center; justify-content: center; }
.img-modal-overlay.active { display: flex; }
.img-modal-box { background: white; border-radius: 12px; padding: 16px; max-width: 90vw; max-height: 90vh; position: relative; text-align: center; }
.img-modal-box img { max-width: 80vw; max-height: 75vh; border-radius: 8px; object-fit: contain; }
.img-modal-close { position: absolute; top: 10px; right: 12px; font-size: 22px; cursor: pointer; color: #64748b; background: none; border: none; }
.img-modal-title { font-weight: 700; margin-bottom: 10px; color: #1e293b; font-size: 14px; }

.pagination { display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 20px; flex-wrap: wrap; }
.pagination a, .pagination span { padding: 8px 14px; border: 1px solid #d1d5db; border-radius: 6px; text-decoration: none; color: #374151; font-size: 13px; font-weight: 600; }
.pagination a:hover { background: #f3f4f6; border-color: #9ca3af; }
.pagination .active { background: #3b82f6; color: white; border-color: #3b82f6; }

/* ── Server-side Search Form ────────────────────────────────────── */
.search-form-wrapper {
    display: flex;
    gap: 8px;
    align-items: center;
    margin-bottom: 15px;
}
.search-form-wrapper .search-input-wrap {
    position: relative;
    flex: 1;
}
.search-form-wrapper .search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 15px;
    color: #94a3b8;
    pointer-events: none;
}
.search-form-wrapper input[type="text"] {
    width: 100%;
    padding: 10px 40px 10px 38px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: 13px;
    color: #334155;
    background: #f8fafc;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    box-sizing: border-box;
}
.search-form-wrapper input[type="text"]:focus {
    border-color: #3b82f6;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
.search-form-wrapper input[type="text"]::placeholder { color: #94a3b8; }
.btn-search {
    padding: 10px 18px;
    background: #3b82f6;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: background 0.2s;
}
.btn-search:hover { background: #2563eb; }
.btn-search-reset {
    padding: 10px 14px;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: background 0.2s;
}
.btn-search-reset:hover { background: #e2e8f0; }
.search-result-info {
    font-size: 12px;
    color: #64748b;
    margin-bottom: 10px;
    padding: 6px 10px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 6px;
    display: inline-block;
}
</style>

<!-- Modal Preview Gambar -->
<div class="img-modal-overlay" id="imgModal" onclick="closeImgModal(event)">
    <div class="img-modal-box">
        <button class="img-modal-close" onclick="closeImgModal()">✕</button>
        <div class="img-modal-title" id="imgModalTitle"></div>
        <img id="imgModalImg" src="" alt="Gambar Server">
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>🖥️ Daftar Server</h2>
        <p>
            Total: <strong><?= $total_count ?></strong> server
            <?php if ($search !== ''): ?>
                | Hasil pencarian: <strong><?= $total_count ?></strong> ditemukan
            <?php else: ?>
                | Halaman <?= $page ?> dari <?= max(1, $total_pages) ?>
            <?php endif; ?>
        </p>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success" style="margin: 15px 25px 0;">✅ <?= h($success) ?></div>
    <?php endif; ?>

    <div style="padding: 25px;">
        <div class="table-controls">
            <button type="button" class="btn-toggle-view" onclick="toggleTableView()">
                <span id="toggleText">📖 Luaskan Tabel</span>
            </button>
            <div>
                <a href="<?= base_url('pages/data-server-input.php') ?>" class="btn-add-data">➕ Input Data</a>
            </div>
        </div>

        <!-- ── SERVER-SIDE SEARCH FORM ─────────────────────────── -->
        <form method="get" action="" id="searchForm">
            <div class="search-form-wrapper">
                <div class="search-input-wrap">
                    <span class="search-icon">🔍</span>
                    <input
                        type="text"
                        name="q"
                        id="searchInput"
                        value="<?= h($search) ?>"
                        placeholder="Cari server... (IND, Fungsi, IP, Merk, Type, OS, Processor, Status)"
                        autocomplete="off"
                    >
                </div>
                <button type="submit" class="btn-search">Cari</button>
                <?php if ($search !== ''): ?>
                    <a href="<?= base_url('pages/data-server.php') ?>" class="btn-search-reset">✕ Reset</a>
                <?php endif; ?>
            </div>
        </form>

        <?php if ($search !== '' && $total_count > 0): ?>
            <div class="search-result-info">
                🔍 Menampilkan <strong><?= $total_count ?></strong> hasil untuk "<strong><?= h($search) ?></strong>"
            </div>
        <?php elseif ($search !== '' && $total_count === 0): ?>
            <div style="text-align:center;padding:40px 20px;color:#94a3b8;font-size:14px;">
                🔎 Tidak ada server yang cocok dengan "<strong><?= h($search) ?></strong>"
                &nbsp;·&nbsp; <a href="<?= base_url('pages/data-server.php') ?>" style="color:#3b82f6;">Reset pencarian</a>
            </div>
        <?php endif; ?>
        <!-- ─────────────────────────────────────────────────────── -->

        <?php if (empty($servers) && $search === ''): ?>
            <p style="text-align: center; padding: 60px 20px; color: #94a3b8; font-size: 15px;">
                📦 Belum ada data server.<br>
                <span style="font-size: 13px;">Klik tombol <strong>"Input Data"</strong> untuk menambahkan server baru.</span>
            </p>
        <?php elseif (!empty($servers)): ?>
            <div class="table-wrapper">
                <table class="data-table compact" id="serverTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>IND</th>
                            <th>Fungsi Server</th>
                            <th>IP Address</th>
                            <th>Detail</th>
                            <th class="extended-col">Merk</th>
                            <th class="extended-col">Type</th>
                            <th class="extended-col">OS</th>
                            <th class="extended-col">Processor</th>
                            <th class="extended-col">RAM</th>
                            <th class="extended-col">Storage</th>
                            <th class="extended-col">Server Fisik</th>
                            <th style="width: 100px;">Status Server</th>
                            <th style="width: 72px;">Gambar</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = $offset + 1; foreach ($servers as $server): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= h($server['ind']) ?></strong></td>
                                <td><?= h($server['fungsi_server']) ?></td>
                                <td><code style="background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:12px;"><?= h($server['ip']) ?></code></td>
                                <td><?= h(substr($server['detail'] ?? '', 0, 50)) ?><?= strlen($server['detail'] ?? '') > 50 ? '...' : '' ?></td>
                                <td class="extended-col"><?= h($server['merk'] ?: '-') ?></td>
                                <td class="extended-col"><?= h($server['type'] ?: '-') ?></td>
                                <td class="extended-col"><?= h($server['system_operasi'] ?: '-') ?></td>
                                <td class="extended-col">
                                    <?php if ($server['processor_merk']): ?>
                                        <?= h($server['processor_merk']) ?> <?= h($server['processor_type']) ?>
                                        <br><small><?= h($server['processor_kecepatan']) ?> GHz, <?= h($server['processor_core']) ?> cores</small>
                                    <?php else: ?>-<?php endif; ?>
                                </td>
                                <td class="extended-col">
                                    <?php if ($server['ram_jenis']): ?>
                                        <?= h($server['ram_jenis']) ?> <?= h($server['ram_kapasitas']) ?>
                                        <br><small><?= h($server['ram_jumlah_keping']) ?> keping</small>
                                    <?php else: ?>-<?php endif; ?>
                                </td>
                                <td class="extended-col">
                                    <?php if ($server['storage_jenis']): ?>
                                        <?= h($server['storage_jenis']) ?> <?= h($server['storage_kapasitas_total']) ?>
                                        <br><small><?= h($server['storage_jumlah']) ?> unit</small>
                                    <?php else: ?>-<?php endif; ?>
                                </td>
                                <td class="extended-col"><?= h($server['server_fisik'] ?: '-') ?></td>
                                <td>
                                    <?php $isHidup = ($server['status_server'] ?? 'HIDUP') === 'HIDUP'; ?>
                                    <?php if (is_admin()): ?>
                                        <form method="post" action="<?= base_url('pages/data-server.php') ?>" style="margin:0;display:inline;">
                                            <input type="hidden" name="action" value="toggle_status_server">
                                            <input type="hidden" name="server_id" value="<?= $server['id'] ?>">
                                            <input type="hidden" name="current_page" value="<?= $page ?>">
                                            <input type="hidden" name="q" value="<?= h($search) ?>">
                                            <button type="submit" class="status-toggle-btn" title="Klik untuk ubah status">
                                                <span class="<?= $isHidup ? 'badge-server-hidup' : 'badge-server-mati' ?>">
                                                    <?= $isHidup ? '🟢 HIDUP' : '🔴 MATI' ?>
                                                </span>
                                                <span class="toggle-hint">klik ubah</span>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="<?= $isHidup ? 'badge-server-hidup' : 'badge-server-mati' ?>">
                                            <?= $isHidup ? '🟢 HIDUP' : '🔴 MATI' ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($server['gambar'])): ?>
                                        <img
                                            src="<?= base_url('uploads/server_images/' . h($server['gambar'])) ?>"
                                            alt="<?= h($server['ind']) ?>"
                                            class="server-thumb"
                                            onclick="openImgModal('<?= base_url('uploads/server_images/' . h($server['gambar'])) ?>', '<?= h($server['ind']) ?> — <?= h($server['fungsi_server']) ?>')"
                                        >
                                    <?php else: ?>
                                        <div class="no-thumb" title="Belum ada gambar">📷</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= base_url('pages/data-server-detail.php?id=' . $server['id']) ?>"
                                           class="btn btn-sm" style="background:#10b981;color:white;padding:6px 10px;font-size:12px;text-decoration:none;display:inline-block;border-radius:6px;">
                                            👁️ Detail
                                        </a>
                                        <?php if (is_admin()): ?>
                                            <a href="<?= base_url('pages/data-server-edit.php?id=' . $server['id']) ?>" class="btn btn-sm btn-edit">✏️ Edit</a>
                                            <form method="post" style="display:inline;margin:0;" onsubmit="return confirm('Yakin hapus server ini?\n\nSemua history pemeliharaan juga akan terhapus.')">
                                                <input type="hidden" name="action" value="delete_server">
                                                <input type="hidden" name="server_id" value="<?= $server['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">🗑️ Hapus</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php
                    // Build pagination URL (preserve search)
                    $base_q = $search !== '' ? '&q=' . urlencode($search) : '';
                    ?>
                    <?php if ($page > 1): ?>
                        <a href="?page=1<?= $base_q ?>">«« First</a>
                        <a href="?page=<?= $page - 1 ?><?= $base_q ?>">‹ Prev</a>
                    <?php endif; ?>
                    <?php for ($i = max(1,$page-2); $i <= min($total_pages,$page+2); $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="active"><?= $i ?></span>
                        <?php else: ?>
                            <a href="?page=<?= $i ?><?= $base_q ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 ?><?= $base_q ?>">Next ›</a>
                        <a href="?page=<?= $total_pages ?><?= $base_q ?>">Last »»</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
let isExpanded = false;
function toggleTableView() {
    const table = document.getElementById('serverTable');
    const toggleText = document.getElementById('toggleText');
    isExpanded = !isExpanded;
    if (isExpanded) {
        table.classList.replace('compact', 'full');
        toggleText.textContent = '📕 Ringkaskan Tabel';
    } else {
        table.classList.replace('full', 'compact');
        toggleText.textContent = '📖 Luaskan Tabel';
    }
}

function openImgModal(src, title) {
    document.getElementById('imgModalImg').src = src;
    document.getElementById('imgModalTitle').textContent = title;
    document.getElementById('imgModal').classList.add('active');
}
function closeImgModal(e) {
    if (!e || e.target === document.getElementById('imgModal') || e.currentTarget.tagName === 'BUTTON') {
        document.getElementById('imgModal').classList.remove('active');
    }
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeImgModal(); });

// Auto-submit form setelah user berhenti mengetik 500ms
(function () {
    const input = document.getElementById('searchInput');
    if (!input) return;
    let timer;
    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(function () {
            document.getElementById('searchForm').submit();
        }, 500);
    });
})();
</script>