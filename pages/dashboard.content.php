<?php
// pages/dashboard.content.php
?>

<style>
/* Additional styles for dashboard */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.stat-card h3 {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 10px;
    opacity: 0.9;
}

.stat-card .number {
    font-size: 32px;
    font-weight: 700;
}

.multiselect {
    position: relative;
    width: 100%;
}

.selectBox {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 10px 14px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
}

.ms-panel {
    display: none;
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 8px;
    max-height: 220px;
    overflow-y: auto;
    z-index: 9999;
    box-shadow: 0 10px 24px rgba(0,0,0,0.12);
    min-height: 56px;
}

.cb-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    border-radius: 6px;
    margin: 0;
}

.cb-item:hover {
    background: #f3f4f6;
}

.cb-item input.cb {
    width: auto !important;
    height: 16px;
    margin: 0 !important;
    flex: 0 0 auto;
}

.cb-text {
    flex: 1;
}
</style>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <h3>📊 Total Jadwal</h3>
        <div class="number"><?= count($rows) ?></div>
    </div>
    <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <h3>📅 Bulan Ini</h3>
        <div class="number">
            <?php
            $thisMonth = date('Y-m');
            $monthCount = 0;
            foreach ($rows as $r) {
                if (substr($r['created_at'], 0, 7) === $thisMonth) {
                    $monthCount++;
                }
            }
            echo $monthCount;
            ?>
        </div>
    </div>
    <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
        <h3>✅ Solved</h3>
        <div class="number">
            <?php
            $solved = 0;
            foreach ($rows as $r) {
                if ($r['tindak_lanjut'] === 'SOLVED') {
                    $solved++;
                }
            }
            echo $solved;
            ?>
        </div>
    </div>
</div>



<!-- Data Table -->
<div class="card">
<div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
  <div>
    <h2>📋 Data Jadwal</h2>
    <p>Total: <strong><?= count($rows) ?></strong> jadwal</p>
  </div>

  <div style="display:flex; gap:10px;">
    <a class="btn btn-primary btn-sm" href="<?= base_url('pages/entry-jadwal.php') ?>">+ Tambah Jadwal</a>
    <a class="btn btn-secondary btn-sm" href="<?= base_url('pages/export.php') ?>">Export</a>
  </div>
</div>

<?php if (isset($_GET['added'])): ?>
  <div class="alert alert-success">✅ Jadwal berhasil ditambahkan.</div>
<?php endif; ?>


    <?php if (!$rows): ?>
        <p style="text-align: center; padding: 40px; color: #94a3b8;">Belum ada data jadwal</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
            <?php
            $isAdmin = (current_user()['role'] ?? '') === 'admin';
            ?>
                <thead>
                    <tr>
                        <th>NO</th>
                        <?php if ($isAdmin): ?>
                            <th>ID Transaksi</th>
                        <?php endif; ?>
                        <th>Start</th>
                        <th>End</th>
                        <th>PIC Acara</th>
                        <th>Nama Acara</th>
                        <th>PIC IT Support</th>
                        <th>Ruang</th>
                        <th>Pelaksanaan</th>
                        <th>Standby</th>
                        <th>Kebutuhan</th>
                        <th>Tindak Lanjut</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($rows as $r): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <?php if ($isAdmin): ?>
                                <td><strong><?= h($r['transaction_id']) ?></strong></td>
                            <?php endif; ?>
                            <td><?= h($r['start_date']) ?></td>
                            <td><?= h($r['end_date']) ?></td>
                            <td><?= h($r['pic_acara']) ?></td>
                            <td><?= h($r['nama_acara']) ?></td>
                            <td><?= h(it_support_to_text($r['pic_it_support'])) ?></td>
                            <td><?= h($r['meeting_room']) ?></td>
                            <td><?= h($r['pelaksanaan']) ?></td>
                            <td><?= h($r['standby_status']) ?></td>
                            <td><?= h($r['kebutuhan_detail']) ?></td>
                            <td>
                                <span class="badge badge-<?= $r['tindak_lanjut'] === 'SOLVED' ? 'success' : 'warning' ?>">
                                    <?= h($r['tindak_lanjut']) ?>
                                </span>
                            </td>
                            <td><?= h($r['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
let picItOpen = false;

function togglePicIt() {
    const panel = document.getElementById("picItPanel");
    picItOpen = !picItOpen;
    panel.style.display = picItOpen ? "block" : "none";
}

function updateSelectedText() {
    const checked = document.querySelectorAll('#picItPanel input.cb:checked');
    const values = Array.from(checked).map(cb => cb.value);
    document.getElementById("selectedText").innerText = values.length ? values.join(", ") : "Pilih PIC IT Support";
}

document.addEventListener("click", function(e) {
    const wrap = document.getElementById("picItDropdown");
    const panel = document.getElementById("picItPanel");
    if (wrap && panel && !wrap.contains(e.target)) {
        panel.style.display = "none";
        picItOpen = false;
    }
});

document.querySelectorAll('#picItPanel input.cb').forEach(cb => cb.addEventListener('change', updateSelectedText));
updateSelectedText();
</script>
