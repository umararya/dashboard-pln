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

/* ── Zoom Status Button ──────────────────────────────────── */
.btn-zoom-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 16px;
    background: #f1f5f9;
    color: #334155;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    white-space: nowrap;
}
.btn-zoom-status:hover {
    background: #e2e8f0;
    border-color: #94a3b8;
    color: #1e293b;
}

/* ── Zoom Status Modal ───────────────────────────────────── */
.zoom-status-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 2000;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.zoom-status-modal-overlay.show {
    display: flex;
}
.zoom-status-modal {
    background: #fff;
    border-radius: 14px;
    width: 100%;
    max-width: 540px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    animation: modalFadeIn 0.25s ease;
}
@keyframes modalFadeIn {
    from { opacity: 0; transform: translateY(-16px); }
    to   { opacity: 1; transform: translateY(0); }
}
.zoom-modal-header {
    padding: 20px 24px 16px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    background: #fff;
    z-index: 1;
}
.zoom-modal-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
}
.zoom-modal-close {
    background: none;
    border: none;
    font-size: 26px;
    cursor: pointer;
    color: #94a3b8;
    line-height: 1;
    padding: 0 4px;
}
.zoom-modal-close:hover { color: #475569; }

.zoom-status-legend {
    display: flex;
    gap: 16px;
    padding: 12px 24px;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
    font-size: 13px;
}
.legend-dot {
    display: inline-block;
    width: 10px; height: 10px;
    border-radius: 50%;
    margin-right: 5px;
}
.legend-dot.kosong  { background: #10b981; }
.legend-dot.dipakai { background: #f59e0b; }

.zoom-link-list {
    padding: 16px 24px 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.zoom-link-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    border-radius: 10px;
    border: 1.5px solid #e5e7eb;
    transition: border-color 0.2s;
    gap: 12px;
}
.zoom-link-item.kosong {
    background: #f0fdf4;
    border-color: #bbf7d0;
}
.zoom-link-item.dipakai {
    background: #fffbeb;
    border-color: #fde68a;
}
.zoom-link-item:hover.kosong  { border-color: #6ee7b7; }
.zoom-link-item:hover.dipakai { border-color: #fcd34d; }

.zoom-link-info {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    min-width: 0;
}
.zoom-link-number {
    width: 26px; height: 26px;
    background: #e2e8f0;
    color: #475569;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700;
    flex-shrink: 0;
}
.zoom-link-email {
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.zoom-kondisi-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
    flex-shrink: 0;
}
.zoom-kondisi-badge.kosong {
    background: #d1fae5;
    color: #065f46;
}
.zoom-kondisi-badge.dipakai {
    background: #fef3c7;
    color: #92400e;
}

.zoom-summary-bar {
    margin: 0 24px 16px;
    padding: 10px 14px;
    border-radius: 8px;
    background: #f1f5f9;
    font-size: 13px;
    color: #475569;
    display: flex;
    gap: 20px;
}
.zoom-summary-bar span strong { color: #1e293b; }
</style>

<!-- ── ZOOM STATUS MODAL ──────────────────────────────────────── -->
<div class="zoom-status-modal-overlay" id="zoomStatusModal" onclick="closeZoomModal(event)">
    <div class="zoom-status-modal">
        <div class="zoom-modal-header">
            <h3>📋 Status Zoom Links</h3>
            <button class="zoom-modal-close" onclick="closeZoomModal()">&times;</button>
        </div>

        <div class="zoom-status-legend">
            <span><span class="legend-dot kosong"></span> KOSONG = Tersedia</span>
            <span><span class="legend-dot dipakai"></span> DIPAKAI = Sedang digunakan</span>
        </div>

        <?php
        $total_kosong  = count(array_filter($zoom_status_map, fn($s) => $s === 'KOSONG'));
        $total_dipakai = count($zoom_status_map) - $total_kosong;
        ?>
        <div class="zoom-summary-bar">
            <span>🟢 Tersedia: <strong><?= $total_kosong ?></strong></span>
            <span>🟡 Dipakai: <strong><?= $total_dipakai ?></strong></span>
            <span>Total: <strong><?= count($zoom_status_map) ?></strong></span>
        </div>

        <div class="zoom-link-list">
            <?php $i = 1; foreach ($zoom_status_map as $zl => $status): ?>
                <?php $isKosong = $status === 'KOSONG'; ?>
                <div class="zoom-link-item <?= $isKosong ? 'kosong' : 'dipakai' ?>">
                    <div class="zoom-link-info">
                        <span class="zoom-link-number"><?= $i++ ?></span>
                        <span class="zoom-link-email" title="<?= h($zl) ?>"><?= h($zl) ?></span>
                    </div>
                    <span class="zoom-kondisi-badge <?= $isKosong ? 'kosong' : 'dipakai' ?>">
                        <?= $isKosong ? '🟢 KOSONG' : '🟡 DIPAKAI' ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

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
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <!-- Button Status Zoom (kiri) -->
            <button type="button" class="btn-zoom-status" onclick="openZoomModal()">
                📋 Status Zoom
                <?php if ($total_dipakai > 0): ?>
                    <span style="
                        background:#f59e0b;color:#fff;
                        border-radius:20px;padding:1px 8px;
                        font-size:11px;font-weight:700;
                    "><?= $total_dipakai ?> dipakai</span>
                <?php else: ?>
                    <span style="
                        background:#10b981;color:#fff;
                        border-radius:20px;padding:1px 8px;
                        font-size:11px;font-weight:700;
                    ">semua kosong</span>
                <?php endif; ?>
            </button>

            <!-- Button Booking Baru (kanan) -->
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

<script>
function openZoomModal() {
    document.getElementById('zoomStatusModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeZoomModal(e) {
    if (!e || e.target === document.getElementById('zoomStatusModal') || e.currentTarget.tagName === 'BUTTON') {
        document.getElementById('zoomStatusModal').classList.remove('show');
        document.body.style.overflow = '';
    }
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeZoomModal();
});
</script>