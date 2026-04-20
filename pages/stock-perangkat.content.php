<?php
// pages/stock-perangkat.content.php
?>

<style>
/* ── Search Form ─────────────────────────────────────── */
.search-form-wrapper {
    display: flex; gap: 8px; align-items: center; margin-bottom: 15px;
}
.search-form-wrapper .search-input-wrap { position: relative; flex: 1; }
.search-form-wrapper .search-icon {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    font-size: 15px; color: #94a3b8; pointer-events: none;
}
.search-form-wrapper input[type="text"] {
    width: 100%; padding: 10px 40px 10px 38px;
    border: 1.5px solid #e2e8f0; border-radius: 8px;
    font-size: 13px; color: #334155; background: #f8fafc; outline: none;
    transition: border-color 0.2s, box-shadow 0.2s; box-sizing: border-box;
}
.search-form-wrapper input[type="text"]:focus {
    border-color: #3b82f6; background: #fff;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
.search-form-wrapper input[type="text"]::placeholder { color: #94a3b8; }
.btn-search { padding: 10px 18px; background: #3b82f6; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; transition: background 0.2s; }
.btn-search:hover { background: #2563eb; }
.btn-search-reset { padding: 10px 14px; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; text-decoration: none; display: inline-flex; align-items: center; transition: background 0.2s; }
.btn-search-reset:hover { background: #e2e8f0; }
.search-result-info { font-size: 12px; color: #64748b; margin-bottom: 10px; padding: 6px 10px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; display: inline-block; }

/* ── Kondisi Badges ─────────────────────────────────── */
.badge-kondisi-baik { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; white-space: nowrap; background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
.badge-kondisi-rusak { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; white-space: nowrap; background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
.badge-kondisi-perlu-service { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; white-space: nowrap; background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }

/* ── Thumbnail ──────────────────────────────────────── */
.perangkat-thumb { width: 56px; height: 44px; object-fit: cover; border-radius: 6px; border: 1px solid #e5e7eb; cursor: pointer; transition: transform 0.15s; display: block; }
.perangkat-thumb:hover { transform: scale(1.08); }
.no-thumb { width: 56px; height: 44px; background: #f1f5f9; border-radius: 6px; border: 1px dashed #cbd5e1; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #94a3b8; }

/* ── Image Preview Modal ─────────────────────────────── */
.img-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75); z-index: 9999; align-items: center; justify-content: center; }
.img-modal-overlay.active { display: flex; }
.img-modal-box { background: white; border-radius: 12px; padding: 16px; max-width: 90vw; max-height: 90vh; position: relative; text-align: center; }
.img-modal-box img { max-width: 80vw; max-height: 75vh; border-radius: 8px; object-fit: contain; }
.img-modal-close { position: absolute; top: 10px; right: 12px; font-size: 22px; cursor: pointer; color: #64748b; background: none; border: none; }
.img-modal-title { font-weight: 700; margin-bottom: 10px; color: #1e293b; font-size: 14px; }

/* ── Table ──────────────────────────────────────────── */
.table-wrapper { overflow-x: auto; border-radius: 10px; border: 1px solid #e5e7eb; }
.data-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.data-table thead th { background: #f8fafc; padding: 12px 14px; text-align: left; font-weight: 700; color: #475569; border-bottom: 2px solid #e5e7eb; white-space: nowrap; }
.data-table tbody td { padding: 10px 14px; border-bottom: 1px solid #e5e7eb; vertical-align: middle; }
.data-table tbody tr:hover { background: #f8fafc; }

/* ── Pagination ─────────────────────────────────────── */
.pagination { display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 20px; flex-wrap: wrap; }
.pagination a, .pagination span { padding: 8px 14px; border: 1px solid #d1d5db; border-radius: 6px; text-decoration: none; color: #374151; font-size: 13px; font-weight: 600; }
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
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div>
            <h2>📦 Stock Perangkat IT</h2>
            <p>
                Total: <strong><?= $total_count ?></strong> perangkat
                <?php if ($search === ''): ?>
                    | Halaman <?= $page ?> dari <?= max(1, $total_pages) ?>
                <?php endif; ?>
            </p>
        </div>
        <div>
            <a class="btn btn-primary btn-sm" href="<?= base_url('pages/stock-perangkat-input.php') ?>">
                ➕ Input Data
            </a>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success" style="margin: 15px 25px 0;">✅ <?= h($success) ?></div>
    <?php endif; ?>

    <div style="padding: 25px;">

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
                        placeholder="Cari perangkat... (Nama, Type, Supplai, Kondisi, Keterangan)"
                        autocomplete="off"
                    >
                </div>
                <button type="submit" class="btn-search">Cari</button>
                <?php if ($search !== ''): ?>
                    <a href="<?= base_url('pages/stock-perangkat.php') ?>" class="btn-search-reset">✕ Reset</a>
                <?php endif; ?>
            </div>
        </form>

        <?php if ($search !== '' && $total_count > 0): ?>
            <div class="search-result-info">
                🔍 Menampilkan <strong><?= $total_count ?></strong> hasil untuk "<strong><?= h($search) ?></strong>"
            </div>
        <?php elseif ($search !== '' && $total_count === 0): ?>
            <div style="text-align:center;padding:40px 20px;color:#94a3b8;font-size:14px;">
                🔎 Tidak ada perangkat yang cocok dengan "<strong><?= h($search) ?></strong>"
                &nbsp;·&nbsp; <a href="<?= base_url('pages/stock-perangkat.php') ?>" style="color:#3b82f6;">Reset pencarian</a>
            </div>
        <?php endif; ?>
        <!-- ─────────────────────────────────────────────────────── -->

        <?php if (empty($perangkat_list) && $search === '' && $page === 1): ?>
            <p style="text-align:center;padding:60px 20px;color:#94a3b8;font-size:15px;">
                📦 Belum ada data perangkat.<br>
                <span style="font-size:13px;">Klik tombol <strong>"Input Data"</strong> untuk menambahkan.</span>
            </p>
        <?php elseif (!empty($perangkat_list)): ?>

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

            <?php if ($total_pages > 1): ?>
                <?php $base_q = $search !== '' ? '&q=' . urlencode($search) : ''; ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=1<?= $base_q ?>">«« First</a>
                        <a href="?page=<?= $page - 1 ?><?= $base_q ?>">‹ Prev</a>
                    <?php endif; ?>
                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <?php if ($i === $page): ?>
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
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeImgModal(); });

// Auto-submit setelah 500ms berhenti mengetik
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