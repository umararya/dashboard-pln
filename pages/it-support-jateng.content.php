<?php
// pages/it-support-jateng.content.php
?>

<style>
/* ── Search Box ─────────────────────────────────────── */
.search-wrapper {
    position: relative;
    margin-bottom: 16px;
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
#itsSearch {
    width: 100%;
    padding: 11px 42px 11px 40px;
    border: 1.5px solid #e2e8f0;
    border-radius: 9px;
    font-size: 13px;
    color: #334155;
    background: #f8fafc;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    box-sizing: border-box;
}
#itsSearch:focus {
    border-color: #3b82f6;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
#itsSearch::placeholder { color: #94a3b8; }
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

/* ── Badges ─────────────────────────────────────────── */
.badge-penempatan {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
    white-space: nowrap;
}
.badge-ops {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    white-space: nowrap;
}

/* ── Action Buttons ─────────────────────────────────── */
.btn-group { display: flex; gap: 6px; align-items: center; }
</style>

<div class="card">
    <!-- HEADER -->
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div>
            <h2>👨‍💻 IT Support Jateng</h2>
            <p>Total: <strong><?= count($people) ?></strong> personil</p>
        </div>
        <div>
            <a class="btn btn-primary btn-sm" href="<?= base_url('pages/it-support-jateng-input.php') ?>">
                ➕ Input Data
            </a>
        </div>
    </div>

    <!-- SUCCESS ALERT -->
    <?php if ($success): ?>
        <div class="alert alert-success" style="margin: 15px 25px 0;">✅ <?= h($success) ?></div>
    <?php endif; ?>

    <div style="padding: 25px;">

        <?php if (empty($people)): ?>
            <p style="text-align:center;padding:60px 20px;color:#94a3b8;font-size:15px;">
                📋 Belum ada data IT Support.<br>
                <span style="font-size:13px;">Klik tombol <strong>"Input Data"</strong> untuk menambahkan.</span>
            </p>
        <?php else: ?>

            <!-- SEARCH -->
            <div class="search-wrapper">
                <span class="search-icon">🔍</span>
                <input
                    type="text"
                    id="itsSearch"
                    placeholder="Cari... (Nama, Email, No. HP, Penempatan, OPS STI)"
                    autocomplete="off"
                >
                <button class="search-clear-btn" id="itsClearBtn" title="Hapus pencarian">✕</button>
            </div>
            <div class="search-no-result" id="itsNoResult">
                🔎 Tidak ada data yang cocok dengan kata kunci "<span id="itsKeyword"></span>"
            </div>

            <!-- TABLE -->
            <div class="table-responsive">
                <table class="data-table" id="itsTable">
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
                                <td>
                                    <strong><?= h($p['nama']) ?></strong>
                                </td>
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
                                            <form method="post"
                                                  style="display:inline;margin:0;"
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

<script>
/* ── Real-time Search ─────────────────────────────────── */
(function () {
    const input      = document.getElementById('itsSearch');
    const clearBtn   = document.getElementById('itsClearBtn');
    const noResult   = document.getElementById('itsNoResult');
    const keyword    = document.getElementById('itsKeyword');

    // Column indices to search: 1=Nama, 2=Email, 3=HP, 4=Penempatan, 5=OPS STI
    const COLS = [1, 2, 3, 4, 5];

    if (!input) return;

    function doSearch() {
        const kw   = input.value.trim().toLowerCase();
        const tbody = document.querySelector('#itsTable tbody');
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