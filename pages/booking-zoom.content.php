<?php
// pages/booking-zoom.content.php
// Halaman utama: tabel data zoom + filter
?>

<style>
/* ── Filter Bar ────────────────────────────────────────────── */
.filter-bar {
    padding: 18px 25px;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 12px;
    align-items: end;
}

.filter-field label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
}

.filter-field select,
.filter-field input[type="date"] {
    width: 100%;
    padding: 9px 11px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 13px;
    background: #fff;
    outline: none;
    box-sizing: border-box;
    transition: border-color 0.2s;
}

.filter-field select:focus,
.filter-field input[type="date"]:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.filter-actions {
    display: flex;
    gap: 8px;
    align-items: flex-end;
    padding-top: 0;
}

.btn-filter {
    padding: 9px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    white-space: nowrap;
}

.btn-filter.primary {
    background: #3b82f6;
    color: #fff;
}

.btn-filter.primary:hover {
    background: #2563eb;
}

.btn-filter.reset {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #cbd5e1;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.btn-filter.reset:hover {
    background: #e2e8f0;
}

.filter-active-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #dbeafe;
    color: #1d4ed8;
    border-radius: 20px;
    padding: 3px 12px;
    font-size: 12px;
    font-weight: 700;
    margin-left: 10px;
}

/* ── Table ───────────────────────────────────────────────── */
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

.time-range .date-part {
    font-weight: 700;
    font-size: 13px;
    color: #1e293b;
    display: block;
}

.time-range .time-part {
    font-size: 12px;
    color: #64748b;
    display: block;
}

.duration-pill {
    display: inline-block;
    background: #f1f5f9;
    color: #475569;
    border-radius: 10px;
    padding: 2px 8px;
    font-size: 11px;
    font-weight: 600;
    margin-top: 3px;
}

@media (max-width: 768px) {
    .filter-grid {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 480px) {
    .filter-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="card">
    <!-- HEADER -->
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div>
            <h2>🎥 Booking Jadwal Zoom</h2>
            <p>
                Total: <strong><?= count($rows) ?></strong> booking
                <?php if ($is_filtered): ?>
                    <span class="filter-active-badge">🔍 Filter aktif</span>
                <?php endif; ?>
            </p>
        </div>
        <div>
            <a class="btn btn-primary btn-sm" href="<?= base_url('pages/booking-zoom-form.php') ?>">
                ➕ Booking Baru
            </a>
        </div>
    </div>

    <!-- ALERT MESSAGES -->
    <?php if (isset($_GET['added'])): ?>
        <div class="alert alert-success" style="margin: 12px 25px 0;">✅ Booking zoom berhasil ditambahkan.</div>
    <?php endif; ?>
    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success" style="margin: 12px 25px 0;">✅ Kondisi berhasil diupdate.</div>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success" style="margin: 12px 25px 0;">✅ Booking berhasil dihapus.</div>
    <?php endif; ?>

    <!-- FILTER BAR -->
    <form method="get" action="" id="filterForm">
        <div class="filter-bar">
            <div class="filter-grid">

                <!-- Filter Unit -->
                <div class="filter-field">
                    <label>Unit</label>
                    <select name="filter_unit">
                        <option value="">Semua Unit</option>
                        <?php
                        $ALL_UNITS = [
                            'STI',
                            'PERENCANAAN',
                            'HUKUM',
                            'FUNGSIONAL AHLI',
                            'KKU',
                            'NIAGA',
                            'KEUANGAN',
                            'DISTRIBUSI',
                            'UP2K',
                            'SDM',
                            'YBM',
                            'IKPLN',
                            'UID Jawa Tengah & D.I. Yogyakarta',
                            'UP3 Kudus',
                            'UP3 Surakarta',
                            'UP3 Yogyakarta',
                            'UP3 Magelang',
                            'UP3 Purwokerto',
                            'UP3 Tegal',
                            'UP3 Semarang',
                            'UP3 Salatiga',
                            'UP3 Klaten',
                            'UP3 Pekalongan',
                            'UP3 Cilacap',
                            'UP3 Grobogan',
                            'UP3 Sukoharjo',
                            'UP2D Jateng & DIY'
                        ];
                        // Gabungkan: dari DB + opsi tetap
                        $unit_options = array_unique(array_merge($ALL_UNITS, $units_all));
                        sort($unit_options);
                        foreach ($unit_options as $u): ?>
                            <option value="<?= h($u) ?>" <?= $filter_unit === $u ? 'selected' : '' ?>>
                                <?= strtoupper(h($u)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Filter Kondisi -->
                <div class="filter-field">
                    <label>Kondisi</label>
                    <select name="filter_kondisi">
                        <option value="">Semua Kondisi</option>
                        <option value="KOSONG"  <?= $filter_kondisi === 'KOSONG'  ? 'selected' : '' ?>>🟢 KOSONG</option>
                        <option value="DIPAKAI" <?= $filter_kondisi === 'DIPAKAI' ? 'selected' : '' ?>>🟡 DIPAKAI</option>
                    </select>
                </div>

                <!-- Filter Link Zoom -->
                <div class="filter-field">
                    <label>Link Zoom</label>
                    <select name="filter_zoom">
                        <option value="">Semua Zoom</option>
                        <?php foreach ($zoom_links_all as $zl): ?>
                            <option value="<?= h($zl) ?>" <?= $filter_zoom === $zl ? 'selected' : '' ?>>
                                <?= h($zl) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Filter Dari Tanggal -->
                <div class="filter-field">
                    <label>Dari Tanggal</label>
                    <input type="date" name="filter_from" value="<?= h($filter_from) ?>">
                </div>

                <!-- Filter Sampai Tanggal -->
                <div class="filter-field">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="filter_to" value="<?= h($filter_to) ?>">
                </div>

                <!-- Tombol aksi filter -->
                <div class="filter-actions">
                    <button type="submit" class="btn-filter primary">🔍 Filter</button>
                    <?php if ($is_filtered): ?>
                        <a href="<?= base_url('pages/booking-zoom.php') ?>" class="btn-filter reset">✕ Reset</a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </form>

    <!-- TABLE -->
    <?php if (!$rows): ?>
        <p style="text-align:center; padding: 50px; color:#94a3b8;">
            <?= $is_filtered ? '😕 Tidak ada data yang sesuai filter.' : 'Belum ada data booking zoom.' ?>
        </p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">NO</th>
                        <th>Tanggal &amp; Jam</th>
                        <th>Unit</th>
                        <th>Link Zoom</th>
                        <th>Keterangan</th>
                        <th>Kondisi</th>
                        <th>Dibuat Oleh</th>
                        <th>Dibuat Pada</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($rows as $r): ?>
                        <tr>
                            <td><?= $no++ ?></td>

                            <!-- Tanggal & Jam -->
                            <td>
                                <?php
                                $hasRange = !empty($r['start_datetime']) && !empty($r['end_datetime']);
                                if ($hasRange):
                                    $start   = new DateTime($r['start_datetime']);
                                    $end     = new DateTime($r['end_datetime']);
                                    $diff    = $start->diff($end);
                                    $sameDay = $start->format('Y-m-d') === $end->format('Y-m-d');

                                    $durStr = '';
                                    if ($diff->days > 0) {
                                        $durStr = $diff->days . ' hr ' . ($diff->h ? $diff->h . ' jam' : '');
                                    } elseif ($diff->h > 0 && $diff->i > 0) {
                                        $durStr = $diff->h . ' jam ' . $diff->i . ' mnt';
                                    } elseif ($diff->h > 0) {
                                        $durStr = $diff->h . ' jam';
                                    } elseif ($diff->i > 0) {
                                        $durStr = $diff->i . ' menit';
                                    }
                                ?>
                                    <div class="time-range">
                                        <span class="date-part"><?= $start->format('d M Y') ?></span>
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

                            <!-- Unit -->
                            <td>
                                <?php if (!empty($r['unit'])): ?>
                                    <span class="unit-badge"><?= h(strtoupper($r['unit'])) ?></span>
                                <?php else: ?>
                                    <span style="color:#cbd5e1;">—</span>
                                <?php endif; ?>
                            </td>

                            <!-- Link Zoom -->
                            <td>
                                <span style="background:#dbeafe;color:#1e40af;padding:4px 10px;border-radius:6px;font-size:13px;font-weight:600;">
                                    <?= h($r['zoom_link']) ?>
                                </span>
                            </td>

                            <!-- Keterangan -->
                            <td><?= h($r['keterangan'] ?: '-') ?></td>

                            <!-- Kondisi -->
                            <td>
                                <form method="post" action="<?= base_url('pages/booking-zoom.php') ?>" style="margin:0;">
                                    <input type="hidden" name="action"     value="update_kondisi">
                                    <input type="hidden" name="booking_id" value="<?= $r['id'] ?>">
                                    <select
                                        name="kondisi"
                                        class="kondisi-select <?= strtolower($r['kondisi']) ?>"
                                        onchange="this.form.submit()"
                                    >
                                        <option value="KOSONG"  <?= $r['kondisi'] === 'KOSONG'  ? 'selected' : '' ?>>🟢 KOSONG</option>
                                        <option value="DIPAKAI" <?= $r['kondisi'] === 'DIPAKAI' ? 'selected' : '' ?>>🟡 DIPAKAI</option>
                                    </select>
                                </form>
                            </td>

                            <!-- Dibuat Oleh -->
                            <td><?= h($r['booked_by_name'] ?? '-') ?></td>

                            <!-- Dibuat Pada -->
                            <td>
                                <?php if (!empty($r['created_at'])): ?>
                                    <?= date('d M Y H:i', strtotime($r['created_at'])) ?>
                                <?php else: ?>—
                                <?php endif; ?>
                            </td>

                            <!-- Aksi -->
                            <td>
                                <form method="post" action="<?= base_url('pages/booking-zoom.php') ?>" style="margin:0;" onsubmit="return confirm('Yakin hapus booking ini?')">
                                    <input type="hidden" name="action"     value="delete_booking">
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