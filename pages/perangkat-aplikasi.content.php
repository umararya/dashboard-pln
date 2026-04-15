<?php
// pages/perangkat-aplikasi.content.php
?>

<style>
/* ── Filter Bar ─────────────────────────────────────── */
.pa-filter-bar {
    padding: 16px 25px;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}
.pa-filter-title {
    font-size: 12px; font-weight: 700; color: #64748b;
    text-transform: uppercase; letter-spacing: 0.4px;
    margin-bottom: 10px;
}
.pa-filter-row {
    display: flex;
    gap: 10px;
    align-items: flex-end;
    flex-wrap: wrap;
}
.pa-filter-field { display: flex; flex-direction: column; gap: 5px; }
.pa-filter-field label {
    font-size: 11px; font-weight: 700; color: #64748b;
    text-transform: uppercase; letter-spacing: 0.4px;
}
.pa-filter-field select {
    min-width: 220px;
    padding: 9px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 13px;
    background: #fff;
    outline: none;
    transition: border-color 0.2s;
    cursor: pointer;
}
.pa-filter-field select:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.1); }
.btn-filter-apply {
    padding: 9px 18px; background: #3b82f6; color: #fff;
    border: none; border-radius: 8px; font-size: 13px; font-weight: 600;
    cursor: pointer; white-space: nowrap; transition: background .2s;
}
.btn-filter-apply:hover { background: #2563eb; }
.btn-filter-reset {
    padding: 9px 14px; background: #f1f5f9; color: #475569;
    border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; font-weight: 600;
    cursor: pointer; text-decoration: none; display: inline-flex; align-items: center;
    white-space: nowrap; transition: background .2s;
}
.btn-filter-reset:hover { background: #e2e8f0; }
.filter-active-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: #dbeafe; color: #1d4ed8; border-radius: 20px;
    padding: 3px 12px; font-size: 12px; font-weight: 700; margin-left: 8px;
}

/* ── Search Box ─────────────────────────────────────── */
.search-wrapper {
    position: relative; margin-bottom: 15px;
}
.search-wrapper .search-icon {
    position: absolute; left: 13px; top: 50%;
    transform: translateY(-50%);
    font-size: 15px; color: #94a3b8; pointer-events: none;
}
#paSearch {
    width: 100%; padding: 10px 42px 10px 40px;
    border: 1.5px solid #e2e8f0; border-radius: 8px;
    font-size: 13px; color: #334155; background: #f8fafc; outline: none;
    transition: border-color .2s, box-shadow .2s; box-sizing: border-box;
}
#paSearch:focus {
    border-color: #3b82f6; background: #fff;
    box-shadow: 0 0 0 3px rgba(59,130,246,.1);
}
#paSearch::placeholder { color: #94a3b8; }
.search-clear-btn {
    position: absolute; right: 11px; top: 50%;
    transform: translateY(-50%);
    background: none; border: none; cursor: pointer;
    font-size: 16px; color: #94a3b8; display: none; padding: 0 4px; line-height: 1;
}
.search-clear-btn:hover { color: #475569; }
.search-no-result {
    display: none; text-align: center; padding: 40px 20px;
    color: #94a3b8; font-size: 14px;
}

/* ── Patch Status Badges ────────────────────────────── */
.patch-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 700; white-space: nowrap;
}
.patch-ok       { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
.patch-not      { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
.patch-na       { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }
.patch-pending  { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }

/* ── Table ──────────────────────────────────────────── */
.table-wrapper { overflow-x: auto; border-radius: 10px; border: 1px solid #e5e7eb; }
.data-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.data-table thead th {
    background: #f8fafc; padding: 12px 14px;
    text-align: left; font-weight: 700; color: #475569;
    border-bottom: 2px solid #e5e7eb; white-space: nowrap;
}
.data-table tbody td {
    padding: 10px 14px; border-bottom: 1px solid #e5e7eb; vertical-align: middle;
}
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

/* ── Misc badges ────────────────────────────────────── */
.badge-lokasi {
    display: inline-block; padding: 3px 8px; border-radius: 20px;
    font-size: 11px; font-weight: 700;
    background: #e0f2fe; color: #0369a1; white-space: nowrap;
}
.badge-bidang {
    display: inline-block; padding: 3px 8px; border-radius: 20px;
    font-size: 11px; font-weight: 700;
    background: #ede9fe; color: #6d28d9; white-space: nowrap;
}
.btn-export {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 14px; background: #16a34a; color: #fff;
    border: none; border-radius: 8px; font-size: 13px; font-weight: 600;
    cursor: pointer; text-decoration: none; transition: background 0.2s; white-space: nowrap;
}
.btn-export:hover { background: #15803d; }
</style>

<?php
function patch_badge_class(string $val): string {
    return match($val) {
        '✅'  => 'patch-ok',
        '❌'  => 'patch-not',
        '–'   => 'patch-na',
        default => 'patch-pending',
    };
}

// Build pagination URL helper (preserve filter params)
function pa_page_url(int $p): string {
    global $filter_jenis, $filter_msb;
    $q = http_build_query(array_filter([
        'filter_jenis' => $filter_jenis,
        'filter_msb'   => $filter_msb,
        'page'         => $p,
    ]));
    return base_url('pages/perangkat-aplikasi.php') . ($q ? '?' . $q : '');
}
?>

<div class="card">
    <!-- HEADER -->
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
        <div>
            <h2>🗂️ Perangkat Aplikasi</h2>
            <p>
                Total: <strong><?= $total_count ?></strong> perangkat | Halaman <?= $page ?> dari <?= max(1,$total_pages) ?>
                <?php if ($is_filtered): ?>
                    <span class="filter-active-badge">🔍 Filter aktif</span>
                <?php endif; ?>
            </p>
        </div>
        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
            <a class="btn-export" href="<?= base_url('pages/export-perangkat-aplikasi.php') ?>">
                📥 Export Excel
            </a>
            <a class="btn btn-primary btn-sm" href="<?= base_url('pages/perangkat-aplikasi-input.php') ?>">
                ➕ Input Data
            </a>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success" style="margin:15px 25px 0;">✅ <?= h($success) ?></div>
    <?php endif; ?>

    <!-- FILTER BAR -->
    <form method="get" action="">
        <div class="pa-filter-bar">
            <div class="pa-filter-title">🔍 Filter Data</div>
            <div class="pa-filter-row">
                <div class="pa-filter-field">
                    <label>Jenis Perangkat</label>
                    <select name="filter_jenis">
                        <option value="">— Semua Jenis —</option>
                        <?php foreach ($jenis_options as $opt): ?>
                            <option value="<?= h($opt) ?>" <?= $filter_jenis === $opt ? 'selected' : '' ?>>
                                <?= h($opt) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="pa-filter-field">
                    <label>MSB / Sub Bidang</label>
                    <select name="filter_msb">
                        <option value="">— Semua MSB —</option>
                        <?php foreach ($msb_options as $opt): ?>
                            <option value="<?= h($opt) ?>" <?= $filter_msb === $opt ? 'selected' : '' ?>>
                                <?= h($opt) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn-filter-apply">🔍 Filter</button>
                <?php if ($is_filtered): ?>
                    <a href="<?= base_url('pages/perangkat-aplikasi.php') ?>" class="btn-filter-reset">✕ Reset</a>
                <?php endif; ?>
            </div>
        </div>
    </form>

    <div style="padding:25px;">

        <?php if (empty($rows) && $page === 1 && !$is_filtered): ?>
            <p style="text-align:center;padding:60px 20px;color:#94a3b8;font-size:15px;">
                🗂️ Belum ada data perangkat aplikasi.<br>
                <span style="font-size:13px;">Klik tombol <strong>"Input Data"</strong> untuk menambahkan.</span>
            </p>
        <?php elseif (empty($rows)): ?>
            <p style="text-align:center;padding:40px 20px;color:#94a3b8;font-size:14px;">
                😕 Tidak ada data yang sesuai filter.
            </p>
        <?php else: ?>

            <!-- SEARCH -->
            <div class="search-wrapper">
                <span class="search-icon">🔍</span>
                <input type="text" id="paSearch"
                    placeholder="Cari... (Jenis Perangkat, URL, IP, Lokasi, Bidang, MSB, Pemilik)"
                    autocomplete="off">
                <button class="search-clear-btn" id="paClearBtn" title="Hapus pencarian">✕</button>
            </div>
            <div class="search-no-result" id="paNoResult">
                🔎 Tidak ada data yang cocok dengan kata kunci "<span id="paKeyword"></span>"
            </div>

            <!-- TABLE -->
            <div class="table-wrapper">
                <table class="data-table" id="paTable">
                    <thead>
                        <tr>
                            <th style="width:48px;">No</th>
                            <th>Jenis Perangkat</th>
                            <th>URL</th>
                            <th>IP</th>
                            <th>Lokasi</th>
                            <th>Bidang</th>
                            <th>MSB / Sub Bidang</th>
                            <th style="width:110px;">Firmware Patch</th>
                            <th style="width:120px;">Network Device Patch</th>
                            <th>Pemilik Aset</th>
                            <th style="width:56px;">Oleh</th>
                            <th style="width:130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = $offset + 1; foreach ($rows as $r): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= h($r['jenis_perangkat']) ?></strong></td>
                                <td>
                                    <?php if (!empty($r['url'])): ?>
                                        <a href="<?= h($r['url']) ?>" target="_blank"
                                           style="color:#3b82f6;font-size:12px;word-break:break-all;">
                                            <?= h(mb_strimwidth($r['url'], 0, 40, '…')) ?>
                                        </a>
                                    <?php else: ?>
                                        <span style="color:#cbd5e1;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($r['ip'])): ?>
                                        <code style="background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:12px;">
                                            <?= h($r['ip']) ?>
                                        </code>
                                    <?php else: ?>
                                        <span style="color:#cbd5e1;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($r['lokasi'])): ?>
                                        <span class="badge-lokasi"><?= h($r['lokasi']) ?></span>
                                    <?php else: ?>—<?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($r['bidang'])): ?>
                                        <span class="badge-bidang"><?= h($r['bidang']) ?></span>
                                    <?php else: ?>—<?php endif; ?>
                                </td>
                                <td><?= h($r['msb_sub_bidang'] ?: '—') ?></td>
                                <td>
                                    <span class="patch-badge <?= patch_badge_class($r['firmware_patch'] ?? '⌛') ?>">
                                        <?= h($r['firmware_patch'] ?? '⌛') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="patch-badge <?= patch_badge_class($r['network_device_patch'] ?? '⌛') ?>">
                                        <?= h($r['network_device_patch'] ?? '⌛') ?>
                                    </span>
                                </td>
                                <td><?= h($r['pemilik_aset'] ?: '—') ?></td>
                                <td style="font-size:12px;color:#64748b;">
                                    <?= h($r['created_by_name'] ?? '—') ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= base_url('pages/perangkat-aplikasi-edit.php?id=' . $r['id']) ?>"
                                           class="btn btn-sm btn-edit">✏️ Edit</a>
                                        <?php if (is_admin()): ?>
                                            <form method="post" style="display:inline;margin:0;"
                                                  onsubmit="return confirm('Yakin hapus data ini?')">
                                                <input type="hidden" name="action"  value="delete_perangkat_aplikasi">
                                                <input type="hidden" name="item_id" value="<?= $r['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="<?= pa_page_url(1) ?>">«« First</a>
                        <a href="<?= pa_page_url($page - 1) ?>">‹ Prev</a>
                    <?php endif; ?>
                    <?php for ($i = max(1,$page-2); $i <= min($total_pages,$page+2); $i++): ?>
                        <?php if ($i === $page): ?>
                            <span class="active"><?= $i ?></span>
                        <?php else: ?>
                            <a href="<?= pa_page_url($i) ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <a href="<?= pa_page_url($page + 1) ?>">Next ›</a>
                        <a href="<?= pa_page_url($total_pages) ?>">Last »»</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</div>

<script>
(function () {
    const input    = document.getElementById('paSearch');
    const clearBtn = document.getElementById('paClearBtn');
    const noResult = document.getElementById('paNoResult');
    const kwEl     = document.getElementById('paKeyword');
    // Cols: 1=Jenis, 2=URL, 3=IP, 4=Lokasi, 5=Bidang, 6=MSB, 9=Pemilik
    const COLS = [1, 2, 3, 4, 5, 6, 9];

    if (!input) return;

    function doSearch() {
        const kw    = input.value.trim().toLowerCase();
        const tbody = document.querySelector('#paTable tbody');
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
            noResult.style.display = 'block';
            kwEl.textContent       = kw;
        } else {
            noResult.style.display = 'none';
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