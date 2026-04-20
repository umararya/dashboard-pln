<?php
// pages/it-support-jateng.content.php
?>

<style>
/* ── Search Form ─────────────────────────────────────── */
.search-form-wrapper {
    display: flex; gap: 8px; align-items: center; margin-bottom: 16px;
}
.search-form-wrapper .search-input-wrap { position: relative; flex: 1; }
.search-form-wrapper .search-icon {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    font-size: 15px; color: #94a3b8; pointer-events: none;
}
.search-form-wrapper input[type="text"] {
    width: 100%; padding: 11px 42px 11px 40px;
    border: 1.5px solid #e2e8f0; border-radius: 9px;
    font-size: 13px; color: #334155; background: #f8fafc; outline: none;
    transition: border-color 0.2s, box-shadow 0.2s; box-sizing: border-box;
}
.search-form-wrapper input[type="text"]:focus {
    border-color: #3b82f6; background: #fff;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
.search-form-wrapper input[type="text"]::placeholder { color: #94a3b8; }
.btn-search { padding: 10px 18px; background: #3b82f6; color: #fff; border: none; border-radius: 9px; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; transition: background 0.2s; }
.btn-search:hover { background: #2563eb; }
.btn-search-reset { padding: 10px 14px; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 9px; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; text-decoration: none; display: inline-flex; align-items: center; transition: background 0.2s; }
.btn-search-reset:hover { background: #e2e8f0; }
.search-result-info { font-size: 12px; color: #64748b; margin-bottom: 10px; padding: 6px 10px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; display: inline-block; }

/* ── Pagination ─────────────────────────────────────── */
.pagination { display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 20px; flex-wrap: wrap; }
.pagination a, .pagination span { padding: 8px 14px; border: 1px solid #d1d5db; border-radius: 6px; text-decoration: none; color: #374151; font-size: 13px; font-weight: 600; }
.pagination a:hover { background: #f3f4f6; border-color: #9ca3af; }
.pagination .active { background: #3b82f6; color: white; border-color: #3b82f6; }

/* ── Badges ─────────────────────────────────────────── */
.badge-penempatan { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; white-space: nowrap; }
.badge-ops { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; white-space: nowrap; }

.btn-group { display: flex; gap: 6px; align-items: center; }
</style>

<div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div>
            <h2>👨‍💻 IT Support Jateng</h2>
            <p>Total: <strong><?= $total_count ?></strong> personil | Halaman <?= $page ?> dari <?= max(1, $total_pages) ?></p>
        </div>
        <div>
            <a class="btn btn-primary btn-sm" href="<?= base_url('pages/it-support-jateng-input.php') ?>">
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
                        placeholder="Cari... (Nama, Email, No. HP, Penempatan, OPS STI)"
                        autocomplete="off"
                    >
                </div>
                <button type="submit" class="btn-search">Cari</button>
                <?php if ($search !== ''): ?>
                    <a href="<?= base_url('pages/it-support-jateng.php') ?>" class="btn-search-reset">✕ Reset</a>
                <?php endif; ?>
            </div>
        </form>

        <?php if ($search !== '' && $total_count > 0): ?>
            <div class="search-result-info">
                🔍 Menampilkan <strong><?= $total_count ?></strong> hasil untuk "<strong><?= h($search) ?></strong>"
            </div>
        <?php elseif ($search !== '' && $total_count === 0): ?>
            <div style="text-align:center;padding:40px 20px;color:#94a3b8;font-size:14px;">
                🔎 Tidak ada data yang cocok dengan "<strong><?= h($search) ?></strong>"
                &nbsp;·&nbsp; <a href="<?= base_url('pages/it-support-jateng.php') ?>" style="color:#3b82f6;">Reset pencarian</a>
            </div>
        <?php endif; ?>
        <!-- ─────────────────────────────────────────────────────── -->

        <?php if (empty($people) && $search === ''): ?>
            <p style="text-align:center;padding:60px 20px;color:#94a3b8;font-size:15px;">
                📋 Belum ada data IT Support.<br>
                <span style="font-size:13px;">Klik tombol <strong>"Input Data"</strong> untuk menambahkan.</span>
            </p>
        <?php elseif (!empty($people)): ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:56px;">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Penempatan</th>
                            <th>OPS STI</th>
                            <th style="width:170px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($people as $p): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= h($p['nama']) ?></strong></td>
                                <td>
                                    <?php if (!empty($p['email'])): ?>
                                        <a href="mailto:<?= h($p['email']) ?>" style="color:#3b82f6;text-decoration:none;">
                                            <?= h($p['email']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span style="color:#cbd5e1;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($p['no_hp'])): ?>
                                        <a href="tel:<?= h($p['no_hp']) ?>" style="color:#1e293b;text-decoration:none;font-family:monospace;">
                                            <?= h($p['no_hp']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span style="color:#cbd5e1;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($p['penempatan'])): ?>
                                        <span class="badge-penempatan"><?= h($p['penempatan']) ?></span>
                                    <?php else: ?>
                                        <span style="color:#cbd5e1;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($p['ops_sti'])): ?>
                                        <span class="badge-ops"><?= h($p['ops_sti']) ?></span>
                                    <?php else: ?>
                                        <span style="color:#cbd5e1;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= base_url('pages/it-support-jateng-edit.php?id=' . $p['id']) ?>"
                                           class="btn btn-sm btn-edit">✏️ Edit</a>
                                        <?php if (is_admin()): ?>
                                            <form method="post" style="display:inline;margin:0;"
                                                  onsubmit="return confirm('Yakin hapus data <?= h(addslashes($p['nama'])) ?>?')">
                                                <input type="hidden" name="action"    value="delete_person">
                                                <input type="hidden" name="person_id" value="<?= $p['id'] ?>">
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
        <?php endif; ?>
    </div>
</div>

<?php if ($total_pages > 1): ?>
    <?php $base_q = http_build_query(array_filter(['q' => $search])); $base_q = $base_q ? '&' . $base_q : ''; ?>
    <div class="pagination" style="margin-top:0;margin-bottom:20px;">
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

<script>
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