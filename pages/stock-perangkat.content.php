<?php
// pages/stock-perangkat.content.php
?>

<style>
/* ── Table Controls ─────────────────────────────────── */
.table-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 10px;
}

/* ── Search Box ─────────────────────────────────────── */
.search-wrapper {
    position: relative;
    margin-bottom: 15px;
}
.search-wrapper .search-icon {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 15px;
    color: #94a3b8;
    pointer-events: none;
}
#stockSearch {
    width: 100%;
    padding: 10px 42px 10px 40px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: 13px;
    color: #334155;
    background: #f8fafc;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    box-sizing: border-box;
}
#stockSearch:focus {
    border-color: #3b82f6;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
#stockSearch::placeholder { color: #94a3b8; }
.search-clear-btn {
    position: absolute;
    right: 11px;
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

/* ── Kondisi Badges ─────────────────────────────────── */
.badge-kondisi-baik {
    display: inline-block; padding: 4px 12px; border-radius: 20px;
    font-size: 12px; font-weight: 700; white-space: nowrap;
    background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7;
}
.badge-kondisi-rusak {
    display: inline-block; padding: 4px 12px; border-radius: 20px;
    font-size: 12px; font-weight: 700; white-space: nowrap;
    background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;
}
.badge-kondisi-perlu-service {
    display: inline-block; padding: 4px 12px; border-radius: 20px;
    font-size: 12px; font-weight: 700; white-space: nowrap;
    background: #fef3c7; color: #92400e; border: 1px solid #fcd34d;
}

/* ── Thumbnail ──────────────────────────────────────── */
.perangkat-thumb {
    width: 56px; height: 44px; object-fit: cover;
    border-radius: 6px; border: 1px solid #e5e7eb;
    cursor: pointer; transition: transform 0.15s; display: block;
}
.perangkat-thumb:hover { transform: scale(1.08); }
.no-thumb {
    width: 56px; height: 44px; background: #f1f5f9;
    border-radius: 6px; border: 1px dashed #cbd5e1;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: #94a3b8;
}

/* ── Image Preview Modal ─────────────────────────────── */
.img-modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.75); z-index: 9999;
    align-items: center; justify-content: center;
}
.img-modal-overlay.active { display: flex; }
.img-modal-box {
    background: white; border-radius: 12px; padding: 16px;
    max-width: 90vw; max-height: 90vh; position: relative; text-align: center;
}
.img-modal-box img { max-width: 80vw; max-height: 75vh; border-radius: 8px; object-fit: contain; }
.img-modal-close {
    position: absolute; top: 10px; right: 12px;
    font-size: 22px; cursor: pointer; color: #64748b;
    background: none; border: none;
}
.img-modal-title { font-weight: 700; margin-bottom: 10px; color: #1e293b; font-size: 14px; }

/* ── Table ──────────────────────────────────────────── */
.table-wrapper { overflow-x: auto; border-radius: 10px; border: 1px solid #e5e7eb; }
.data-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.data-table thead th {
    background: #f8fafc; padding: 12px 14px;
    text-align: left; font-weight: 700; color: #475569;
    border-bottom: 2px solid #e5e7eb; white-space: nowrap;
}
.data-table tbody td { padding: 10px 14px; border-bottom: 1px solid #e5e7eb; vertical-align: middle; }
.data-table tbody tr:hover { background: #f8fafc; }

/* ── Pagination ─────────────────────────────────────── */
.pagination {
    display: flex; justify-content: center; align-items: center;
    gap: 8px; margin-top: 20px; flex-wrap: wrap;
}
.pagination a, .pagination span {
    padding: 8px 14px; border: 1px solid #d1d5db; border-radius: 6px;
    text-decoration: none; color: #374151; font-size: 13px; font-weight: 600;
}
.pagination a:hover { background: #f3f4f6; border-color: #9ca3af; }
.pagination .active { background: #3b82f6; color: white; border-color: #3b82f6; }
</style>

<!-- Image Preview Modal -->
<div class="img-modal-overlay" id="imgModal" onclick="closeImgModal(event)">
    <div class="img-modal-box">
        <button class="img-modal-close" onclick="closeImgModal()">✕</button>
        <div class="img-modal-title" id="imgModalTitle"></div>
        <img id="imgModalImg" src="" alt="Foto Perangkat">
    </div>
</div>

<div class="card">
    <!-- HEADER -->
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div>
            <h2>📦 Stock Perangkat IT</h2>
            <p>Total: <strong><?= $total_count ?></strong> perangkat | Halaman <?= $page ?> dari <?= max(1, $total_pages) ?></p>
        </div>
        <div>
            <a class="btn btn-primary btn-sm" href="<?= base_url('pages/stock-perangkat-input.php') ?>">
                ➕ Input Data
            </a>
        </div>
    </div>

    <!-- ALERT -->
    <?php if ($success): ?>
        <div class="alert alert-success" style="margin: 15px 25px 0;">✅ <?= h($success) ?></div>
    <?php endif; ?>

    <div style="padding: 25px;">

        <?php if (empty($perangkat_list) && $page === 1): ?>
            <p style="text-align:center;padding:60px 20px;color:#94a3b8;font-size:15px;">
                📦 Belum ada data perangkat.<br>
                <span style="font-size:13px;">Klik tombol <strong>"Input Data"</strong> untuk menambahkan perangkat baru.</span>
            </p>
        <?php else: ?>

            <!-- SEARCH -->
            <div class="search-wrapper">
                <span class="search-icon">🔍</span>
                <input
                    type="text"
                    id="stockSearch"
                    placeholder="Cari perangkat... (Nama, Type, Supplai, Kondisi, Keterangan)"
                    autocomplete="off"
                >
                <button class="search-clear-btn" id="stockClearBtn" title="Hapus pencarian">✕</button>
            </div>
            <div class="search-no-result" id="stockNoResult">
                🔎 Tidak ada data yang cocok dengan kata kunci "<span id="stockKeyword"></span>"
            </div>

            <!-- TABLE -->
            <div class="table-wrapper">
                <table class="data-table" id="stockTable">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>Nama Barang</th>
                            <th>Type Barang</th>
                            <th>Supplai</th>
                            <th style="width:130px;">Kondisi</th>
                            <th>Keterangan</th>
                            <th style="width:72px;">Foto</th>
                            <th style="width:56px;">Oleh</th>
                            <?php if (is_admin()): ?>
                                <th style="width:130px;">Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = $offset + 1; foreach ($perangkat_list as $p): ?>
                            <?php
                            $kondisi = $p['kondisi'] ?? 'BAIK';
                            $kondisiClass = match($kondisi) {
                                'BAIK'          => 'badge-kondisi-baik',
                                'RUSAK'         => 'badge-kondisi-rusak',
                                'PERLU SERVICE' => 'badge-kondisi-perlu-service',
                                default         => 'badge-kondisi-baik',
                            };
                            $kondisiIcon = match($kondisi) {
                                'BAIK'          => '🟢',
                                'RUSAK'         => '🔴',
                                'PERLU SERVICE' => '🟡',
                                default         => '🟢',
                            };
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= h($p['nama_barang']) ?></strong></td>
                                <td><?= h($p['type_barang'] ?: '—') ?></td>
                                <td><?= h($p['supplai'] ?: '—') ?></td>
                                <td>
                                    <span class="<?= $kondisiClass ?>">
                                        <?= $kondisiIcon ?> <?= h($kondisi) ?>
                                    </span>
                                </td>
                                <td style="max-width:220px;word-break:break-word;">
                                    <?= h(mb_strimwidth($p['keterangan'] ?? '', 0, 80, '…')) ?>
                                </td>
                                <td>
                                    <?php if (!empty($p['foto'])): ?>
                                        <img
                                            src="<?= base_url('uploads/stock_perangkat/' . h($p['foto'])) ?>"
                                            alt="<?= h($p['nama_barang']) ?>"
                                            class="perangkat-thumb"
                                            onclick="openImgModal(
                                                '<?= base_url('uploads/stock_perangkat/' . h($p['foto'])) ?>',
                                                '<?= h(addslashes($p['nama_barang'])) ?>'
                                            )"
                                        >
                                    <?php else: ?>
                                        <div class="no-thumb" title="Belum ada foto">📷</div>
                                    <?php endif; ?>
                                </td>
                                <td style="font-size:12px;color:#64748b;">
                                    <?= h($p['created_by_name'] ?? '—') ?>
                                </td>
                                <?php if (is_admin()): ?>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= base_url('pages/stock-perangkat-edit.php?id=' . $p['id']) ?>"
                                               class="btn btn-sm btn-edit">✏️ Edit</a>

                                            <form method="post" style="display:inline;margin:0;"
                                                  onsubmit="return confirm('Yakin hapus perangkat ini?')">
                                                <input type="hidden" name="action"       value="delete_perangkat">
                                                <input type="hidden" name="perangkat_id" value="<?= $p['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                            </form>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=1">«« First</a>
                        <a href="?page=<?= $page - 1 ?>">‹ Prev</a>
                    <?php endif; ?>
                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <?php if ($i === $page): ?>
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
/* ── Image Modal ──────────────────────────────────────── */
function openImgModal(src, title) {
    document.getElementById('imgModalImg').src   = src;
    document.getElementById('imgModalTitle').textContent = title;
    document.getElementById('imgModal').classList.add('active');
}
function closeImgModal(e) {
    if (!e || e.target === document.getElementById('imgModal') || e.currentTarget.tagName === 'BUTTON') {
        document.getElementById('imgModal').classList.remove('active');
    }
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeImgModal();
});

/* ── Real-time Search ─────────────────────────────────── */
(function () {
    const input    = document.getElementById('stockSearch');
    const clearBtn = document.getElementById('stockClearBtn');
    const noResult = document.getElementById('stockNoResult');
    const keyword  = document.getElementById('stockKeyword');

    // Column indices: 1=Nama, 2=Type, 3=Supplai, 4=Kondisi, 5=Keterangan
    const COLS = [1, 2, 3, 4, 5];

    if (!input) return;

    function doSearch() {
        const kw    = input.value.trim().toLowerCase();
        const tbody = document.querySelector('#stockTable tbody');
        if (!tbody) return;

        let visible = 0;
        tbody.querySelectorAll('tr').forEach(function (row) {
            if (!kw) { row.style.display = ''; visible++; return; }

            const cells = row.querySelectorAll('td');
            let match   = false;
            COLS.forEach(function (ci) {
                if (cells[ci]) {
                    const txt = (cells[ci].innerText || cells[ci].textContent || '').toLowerCase();
                    if (txt.indexOf(kw) !== -1) match = true;
                }
            });
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        clearBtn.style.display = kw ? 'block' : 'none';

        if (kw && visible === 0) {
            noResult.style.display  = 'block';
            keyword.textContent     = kw;
        } else {
            noResult.style.display  = 'none';
            keyword.textContent     = '';
        }
    }

    input.addEventListener('input', doSearch);
    clearBtn.addEventListener('click', function () {
        input.value = '';
        doSearch();
        input.focus();
    });
})();
</script>