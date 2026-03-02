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

.unit-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    background: #e0f2fe;
    color: #0369a1;
    white-space: nowrap;
}

.time-range {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.time-range .date-part {
    font-weight: 700;
    font-size: 13px;
    color: #1e293b;
}

.time-range .time-part {
    font-size: 12px;
    color: #64748b;
}

.time-range .arrow-part {
    font-size: 11px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 4px;
}

.duration-pill {
    display: inline-block;
    background: #f1f5f9;
    color: #475569;
    border-radius: 10px;
    padding: 2px 8px;
    font-size: 11px;
    font-weight: 600;
    margin-top: 4px;
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
                        <th>Tanggal & Jam</th>
                        <th>Unit</th>
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
                                <?php
                                // Tampilkan pakai start_datetime/end_datetime jika ada,
                                // fallback ke booking_date + booking_time lama
                                $hasRange = !empty($r['start_datetime']) && !empty($r['end_datetime']);
                                ?>
                                <?php if ($hasRange): ?>
                                    <?php
                                    $start = new DateTime($r['start_datetime']);
                                    $end   = new DateTime($r['end_datetime']);
                                    $diff  = $start->diff($end);

                                    $sameDay = $start->format('Y-m-d') === $end->format('Y-m-d');

                                    $durStr = '';
                                    if ($diff->h > 0 && $diff->i > 0) {
                                        $durStr = $diff->h . ' jam ' . $diff->i . ' mnt';
                                    } elseif ($diff->h > 0) {
                                        $durStr = $diff->h . ' jam';
                                    } elseif ($diff->i > 0) {
                                        $durStr = $diff->i . ' menit';
                                    }
                                    ?>
                                    <div class="time-range">
                                        <span class="date-part">
                                            <?= $start->format('d M Y') ?>
                                        </span>
                                        <span class="time-part">
                                            <?= $start->format('H:i') ?>
                                            <?php if (!$sameDay): ?>
                                                → <?= $end->format('d M Y') ?> <?= $end->format('H:i') ?>
                                            <?php else: ?>
                                                – <?= $end->format('H:i') ?>
                                            <?php endif; ?>
                                        </span>
                                        <?php if ($durStr): ?>
                                            <span class="duration-pill">⏱ <?= $durStr ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="time-range">
                                        <span class="date-part"><?= date('d M Y', strtotime($r['booking_date'])) ?></span>
                                        <span class="time-part"><?= h($r['booking_time']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($r['unit'])): ?>
                                    <span class="unit-badge"><?= h(strtoupper($r['unit'])) ?></span>
                                <?php else: ?>
                                    <span style="color:#cbd5e1;">—</span>
                                <?php endif; ?>
                            </td>
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
                                <?php if (!empty($r['created_at'])): ?>
                                    <?= date('d M Y H:i', strtotime($r['created_at'])) ?>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="post" action="<?= base_url('pages/booking-zoom.php') ?>" style="margin:0;" onsubmit="return confirm('Yakin hapus booking ini?')">
                                    <input type="hidden" name="action" value="delete_booking">
                                    <input type="hidden" name="booking_id" value="<?= $r['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">🗑️ Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>