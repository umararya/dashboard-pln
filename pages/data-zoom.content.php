<?php
// pages/data-zoom.content.php
?>

<style>
.kondisi-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
}

.kondisi-badge.kosong {
    background: #d1fae5;
    color: #065f46;
}

.kondisi-badge.dipakai {
    background: #fef3c7;
    color: #92400e;
}

.kondisi-select {
    padding: 6px 10px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
}

.kondisi-select.kosong {
    background: #d1fae5;
    color: #065f46;
    border-color: #10b981;
}

.kondisi-select.dipakai {
    background: #fef3c7;
    color: #92400e;
    border-color: #f59e0b;
}
</style>

<div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div>
            <h2>📋 Data Jadwal Zoom</h2>
            <p>Total: <strong><?= count($rows) ?></strong> booking</p>
        </div>
        
        <div style="display:flex; gap:10px;">
            <a class="btn btn-primary btn-sm" href="<?= base_url('pages/booking-zoom.php') ?>">
                ➕ Booking Baru
            </a>
        </div>
    </div>

    <?php if (isset($_GET['added'])): ?>
        <div class="alert alert-success" style="margin: 15px 25px 0;">
            ✅ Booking zoom berhasil ditambahkan.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success" style="margin: 15px 25px 0;">
            ✅ Kondisi berhasil diupdate.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success" style="margin: 15px 25px 0;">
            ✅ Booking berhasil dihapus.
        </div>
    <?php endif; ?>

    <?php if (!$rows): ?>
        <p style="text-align:center; padding: 40px; color:#94a3b8;">
            Belum ada data booking zoom
        </p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">NO</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Link Zoom</th>
                        <th>Keterangan</th>
                        <th>Kondisi</th>
                        <th>Dibuat Oleh</th>
                        <th>Dibuat Pada</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($rows as $r): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <strong><?= date('d M Y', strtotime($r['booking_date'])) ?></strong>
                            </td>
                            <td><?= h($r['booking_time']) ?></td>
                            <td>
                                <span style="background: #dbeafe; color: #1e40af; padding: 4px 10px; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                    <?= h($r['zoom_link']) ?>
                                </span>
                            </td>
                            <td><?= h($r['keterangan'] ?: '-') ?></td>
                            <td>
                                <form method="post" action="<?= base_url('pages/booking-zoom.php') ?>" style="margin: 0;">
                                    <input type="hidden" name="action" value="update_kondisi">
                                    <input type="hidden" name="booking_id" value="<?= $r['id'] ?>">
                                    <select 
                                        name="kondisi" 
                                        class="kondisi-select <?= strtolower($r['kondisi']) ?>"
                                        onchange="this.form.submit()"
                                    >
                                        <option value="KOSONG" <?= $r['kondisi'] === 'KOSONG' ? 'selected' : '' ?>>
                                            🟢 KOSONG
                                        </option>
                                        <option value="DIPAKAI" <?= $r['kondisi'] === 'DIPAKAI' ? 'selected' : '' ?>>
                                            🟡 DIPAKAI
                                        </option>
                                    </select>
                                </form>
                            </td>
                            <td><?= h($r['booked_by_name'] ?? '-') ?></td>
                            <td>
                                <small style="color: #64748b;">
                                    <?= date('d M Y H:i', strtotime($r['created_at'])) ?>
                                </small>
                            </td>
                            <td>
                                <?php if (is_admin()): ?>
                                    <form 
                                        method="post" 
                                        action="<?= base_url('pages/booking-zoom.php') ?>"
                                        style="margin: 0;"
                                        onsubmit="return confirm('Yakin ingin menghapus booking ini?')"
                                    >
                                        <input type="hidden" name="action" value="delete_booking">
                                        <input type="hidden" name="booking_id" value="<?= $r['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            Hapus
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span style="color: #94a3b8; font-size: 13px;">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <h2>📌 Keterangan Status</h2>
    </div>
    <div style="padding: 20px 25px;">
        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="kondisi-badge kosong">🟢 KOSONG</span>
                <span style="color: #64748b; font-size: 14px;">= Zoom tersedia untuk digunakan</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="kondisi-badge dipakai">🟡 DIPAKAI</span>
                <span style="color: #64748b; font-size: 14px;">= Zoom sedang digunakan</span>
            </div>
        </div>
        <p style="margin-top: 15px; color: #64748b; font-size: 13px;">
            💡 <strong>Tips:</strong> Klik dropdown "Kondisi" untuk mengubah status zoom (KOSONG/DIPAKAI)
        </p>
    </div>
</div>