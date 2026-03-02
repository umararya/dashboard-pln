<?php
// pages/data-server.content.php
?>

<style>
.table-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
.btn-group-top { display: flex; gap: 10px; flex-wrap: wrap; }
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

/* Badge status server */
.badge-server-hidup {
    display: inline-block; padding: 3px 10px; border-radius: 20px;
    background: #d1fae5; color: #065f46;
    font-size: 11px; font-weight: 700; white-space: nowrap;
}
.badge-server-mati {
    display: inline-block; padding: 3px 10px; border-radius: 20px;
    background: #fee2e2; color: #991b1b;
    font-size: 11px; font-weight: 700; white-space: nowrap;
}
/* Toggle status inline */
.status-toggle-btn {
    cursor: pointer; border: none; background: none; padding: 0;
    display: inline-block;
}
.status-toggle-btn:hover .badge-server-hidup { background: #a7f3d0; }
.status-toggle-btn:hover .badge-server-mati  { background: #fecaca; }
.status-toggle-btn .toggle-hint {
    display: block; font-size: 10px; color: #94a3b8; margin-top: 2px; text-align: center;
}

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

/* ===================== SEARCH BOX ===================== */
.search-wrapper {
    position: relative;
    margin-bottom: 15px;
}
.search-wrapper .search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 15px;
    color: #94a3b8;
    pointer-events: none;
}
#serverSearch {
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
#serverSearch:focus {
    border-color: #3b82f6;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
}
#serverSearch::placeholder { color: #94a3b8; }
.search-clear-btn {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    font-size: 16px;
    color: #94a3b8;
    display: none;
    padding: 0 4px;
    line-height: 1;
}
.search-clear-btn:hover { color: #475569; }
.search-no-result {
    display: none;
    text-align: center;
    padding: 40px 20px;
    color: #94a3b8;
    font-size: 14px;
}
/* ====================================================== */
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
        <p>Total: <strong><?= $total_count ?></strong> server | Halaman <?= $page ?> dari <?= max(1, $total_pages) ?></p>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success" style="margin: 15px 25px 0;">✅ <?= h($success) ?></div>
    <?php endif; ?>

    <div style="padding: 25px;">
        <div class="table-controls">
            <button type="button" class="btn-toggle-view" onclick="toggleTableView()">
                <span id="toggleText">📖 Luaskan Tabel</span>
            </button>
            <div class="btn-group-top">
                <a href="<?= base_url('pages/data-server-input.php') ?>" class="btn-add-data">➕ Input Data</a>
            </div>
        </div>

        <?php if (empty($servers)): ?>
            <p style="text-align: center; padding: 60px 20px; color: #94a3b8; font-size: 15px;">
                📦 Belum ada data server.<br>
                <span style="font-size: 13px;">Klik tombol <strong>"Input Data"</strong> untuk menambahkan server baru.</span>
            </p>
        <?php else: ?>

            <!-- ===================== SEARCH INPUT ===================== -->
            <div class="search-wrapper">
                <span class="search-icon">🔍</span>
                <input
                    type="text"
                    id="serverSearch"
                    placeholder="Cari server... (IND, Fungsi, IP, Merk, Type, OS, Processor, Status)"
                    autocomplete="off"
                >
                <button class="search-clear-btn" id="searchClearBtn" title="Hapus pencarian">✕</button>
            </div>
            <div class="search-no-result" id="searchNoResult">
                🔎 Tidak ada data yang cocok dengan kata kunci "<span id="searchKeywordDisplay"></span>"
            </div>
            <!-- ======================================================= -->

            <div class="table-wrapper">
                <table class="data-table compact" id="serverTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>IND</th>
                            <th>Fungsi Server</th>
                            <th>IP Address</th>
                            <th>Detail</th>

                            <!-- Extended columns (hidden by default) -->
                            <th class="extended-col">Merk</th>
                            <th class="extended-col">Type</th>
                            <th class="extended-col">OS</th>
                            <th class="extended-col">Processor</th>
                            <th class="extended-col">RAM</th>
                            <th class="extended-col">Storage</th>
                            <th class="extended-col">Server Fisik</th>

                            <!-- Status Server selalu tampil, posisi setelah Detail/extended, sebelum Gambar -->
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

                                <!-- Kolom Status Server — posisi setelah extended, sebelum Gambar -->
                                <td>
                                    <?php $isHidup = ($server['status_server'] ?? 'HIDUP') === 'HIDUP'; ?>
                                    <?php if (is_admin()): ?>
                                        <form method="post" action="<?= base_url('pages/data-server.php') ?>" style="margin:0;display:inline;">
                                            <input type="hidden" name="action" value="toggle_status_server">
                                            <input type="hidden" name="server_id" value="<?= $server['id'] ?>">
                                            <input type="hidden" name="current_page" value="<?= $page ?>">
                                            <input type="hidden" name="redirect_page" value="<?= $page ?>">
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

                                <!-- Kolom Gambar — posisi sebelum Aksi -->
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
                                           class="btn btn-sm"
                                           style="background:#10b981;color:white;padding:6px 10px;font-size:12px;text-decoration:none;display:inline-block;border-radius:6px;">
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
                    <?php if ($page > 1): ?>
                        <a href="?page=1">«« First</a>
                        <a href="?page=<?= $page - 1 ?>">‹ Prev</a>
                    <?php endif; ?>
                    <?php for ($i = max(1,$page-2); $i <= min($total_pages,$page+2); $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="active"><?= $i ?></span>
                        <?php else: ?>
                            <a href="?page=<?= $i ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 ?>">Next ›</a>
                        <a href="?page=<?= $total_pages ?>">Last »»</a>
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

/* ===================== REAL-TIME SEARCH =====================
 * Kolom yang di-search (index td dalam <tr>):
 *   1  → IND
 *   2  → Fungsi Server
 *   3  → IP Address
 *   5  → Merk         (extended-col)
 *   6  → Type         (extended-col)
 *   7  → OS           (extended-col)
 *   8  → Processor    (extended-col)
 *   12 → Status Server
 * ============================================================ */
(function () {
    const searchInput    = document.getElementById('serverSearch');
    const clearBtn       = document.getElementById('searchClearBtn');
    const noResultEl     = document.getElementById('searchNoResult');
    const keywordDisplay = document.getElementById('searchKeywordDisplay');

    // Kolom index yang akan di-search
    const SEARCH_COLS = [1, 2, 3, 5, 6, 7, 8, 12];

    if (!searchInput) return; // guard: kalau tabel kosong / elemen tidak ada

    function doSearch() {
        const keyword = searchInput.value.trim().toLowerCase();
        const tbody   = document.querySelector('#serverTable tbody');

        if (!tbody) return;

        const rows       = tbody.querySelectorAll('tr');
        let visibleCount = 0;

        rows.forEach(function (row) {
            if (!keyword) {
                // Keyword kosong → tampilkan semua
                row.style.display = '';
                visibleCount++;
                return;
            }

            const cells  = row.querySelectorAll('td');
            let   match  = false;

            SEARCH_COLS.forEach(function (colIdx) {
                if (cells[colIdx]) {
                    const cellText = cells[colIdx].innerText || cells[colIdx].textContent || '';
                    if (cellText.toLowerCase().indexOf(keyword) !== -1) {
                        match = true;
                    }
                }
            });

            row.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        // Tampilkan / sembunyikan tombol clear
        clearBtn.style.display = keyword ? 'block' : 'none';

        // Tampilkan pesan "tidak ada hasil"
        if (keyword && visibleCount === 0) {
            noResultEl.style.display  = 'block';
            keywordDisplay.textContent = keyword;
        } else {
            noResultEl.style.display  = 'none';
            keywordDisplay.textContent = '';
        }
    }

    // Event: ketik real-time
    searchInput.addEventListener('input', doSearch);

    // Event: tombol clear (✕)
    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        doSearch();
        searchInput.focus();
    });
})();
/* =========================================================== */
</script>