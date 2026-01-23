<?php
// pages/data-jadwal.content.php
?>

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
    <div class="alert alert-success" style="margin: 15px 25px 0;">
      ✅ Jadwal berhasil ditambahkan.
    </div>
  <?php endif; ?>

  <?php if (!$rows): ?>
    <p style="text-align:center; padding: 40px; color:#94a3b8;">Belum ada data jadwal</p>
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
