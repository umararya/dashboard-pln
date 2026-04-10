<?php
// pages/booking-zoom.content.php
// Variabel yang tersedia dari controller (booking-zoom.php):
// $rows, $is_filtered, $filter_unit, $filter_kondisi, $filter_zoom, $filter_from, $filter_to
// $zoom_links_all   → distinct zoom_link dari tabel booking (untuk filter dropdown)
// $zoom_links_active → email aktif dari tabel zoom_links (untuk availability checker & form)
// $units_all        → distinct unit dari tabel booking (untuk filter dropdown)
?>

<style>
/* ── Availability Checker ─────────────────────────────────────── */
.avail-section {
    padding: 20px 25px 24px;
    border-bottom: 1px solid #e5e7eb;
}
.avail-section h3 {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 16px 0;
}
.avail-date-controls {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 18px;
}
.avail-shortcut {
    padding: 8px 16px;
    border: 1.5px solid #d1d5db;
    border-radius: 8px;
    background: #f8fafc;
    color: #374151;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
}
.avail-shortcut:hover { border-color: #3b82f6; color: #1d4ed8; background: #eff6ff; }
.avail-shortcut.active { background: #3b82f6; color: #fff; border-color: #3b82f6; }

.avail-date-input {
    padding: 8px 12px;
    border: 1.5px solid #d1d5db;
    border-radius: 8px;
    font-size: 13px;
    outline: none;
    transition: border-color 0.2s;
}
.avail-date-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }

.zoom-avail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
    gap: 10px;
}
.zoom-avail-card {
    background: #f8fafc;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    padding: 12px 14px;
}
.zoom-avail-card.kosong  { border-color: #a7f3d0; background: #f0fdf9; }
.zoom-avail-card.dipakai { border-color: #fcd34d; background: #fffbeb; }

.zoom-avail-card .card-zoom-name {
    font-size: 12px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 6px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.zoom-avail-card .card-zoom-email {
    font-size: 11px;
    color: #64748b;
    margin-bottom: 8px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.zoom-avail-card .card-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    margin-bottom: 6px;
}
.zoom-avail-card.kosong  .card-status { background: #d1fae5; color: #065f46; }
.zoom-avail-card.dipakai .card-status { background: #fef3c7; color: #92400e; }

.zoom-avail-card .card-booking-info { font-size: 11px; color: #64748b; line-height: 1.5; }
.zoom-avail-card .card-booking-info .booking-row {
    padding: 3px 0;
    border-top: 1px solid #e5e7eb;
    margin-top: 4px;
}
.zoom-avail-card .card-booking-info .booking-row:first-child { border-top: none; margin-top: 0; }

.avail-legend {
    display: flex;
    gap: 16px;
    margin-bottom: 14px;
    font-size: 12px;
    color: #64748b;
}
.avail-legend span { display: flex; align-items: center; gap: 5px; }
.legend-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.legend-dot.kosong  { background: #10b981; }
.legend-dot.dipakai { background: #f59e0b; }

.avail-summary-bar { display: flex; gap: 12px; margin-bottom: 14px; flex-wrap: wrap; }
.avail-summary-pill {
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
}
.avail-summary-pill.kosong  { background: #d1fae5; color: #065f46; }
.avail-summary-pill.dipakai { background: #fef3c7; color: #92400e; }

.avail-empty {
    text-align: center;
    padding: 30px;
    color: #94a3b8;
    font-size: 13px;
}

/* ── Filter Bar ──────────────────────────────────────────────── */
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
.filter-actions { display: flex; gap: 8px; align-items: flex-end; }
.btn-filter {
    padding: 9px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    white-space: nowrap;
}
.btn-filter.primary { background: #3b82f6; color: #fff; }
.btn-filter.primary:hover { background: #2563eb; }
.btn-filter.reset {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #cbd5e1;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}
.btn-filter.reset:hover { background: #e2e8f0; }

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

/* ── Table ──────────────────────────────────────────────────── */
.kondisi-select {
    padding: 6px 10px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
}
.kondisi-select.kosong  { background: #d1fae5; color: #065f46; border-color: #10b981; }
.kondisi-select.dipakai { background: #fef3c7; color: #92400e; border-color: #f59e0b; }

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
.time-range .date-part { font-weight: 700; font-size: 13px; color: #1e293b; display: block; }
.time-range .time-part { font-size: 12px; color: #64748b; display: block; }
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
    .filter-grid       { grid-template-columns: 1fr 1fr; }
    .zoom-avail-grid   { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 480px) {
    .filter-grid       { grid-template-columns: 1fr; }
    .zoom-avail-grid   { grid-template-columns: 1fr; }
}
</style>

<?php
// Pass data ke JS
$all_bookings_json     = json_encode(array_map(function($r) {
    return [
        'zoom_link'      => $r['zoom_link'],
        'kondisi'        => $r['kondisi'],
        'unit'           => $r['unit'] ?? '',
        'start_datetime' => $r['start_datetime'] ?? null,
        'end_datetime'   => $r['end_datetime']   ?? null,
        'booking_date'   => $r['booking_date']   ?? null,
        'booking_time'   => $r['booking_time']   ?? null,
        'keterangan'     => $r['keterangan']     ?? '',
    ];
}, $rows), JSON_UNESCAPED_UNICODE);

// Zoom links aktif dari DB — ini sumber kebenaran tunggal
$zoom_links_active_json = json_encode($zoom_links_active, JSON_UNESCAPED_UNICODE);
?>

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

    <!-- ══════════════════════════════════════════════════════════
         AVAILABILITY CHECKER
    ══════════════════════════════════════════════════════════ -->
    <div class="avail-section">
        <h3>🔍 Cek Ketersediaan Zoom</h3>

        <?php if (empty($zoom_links_active)): ?>
            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:14px 16px;font-size:13px;color:#991b1b;">
                ⚠ Belum ada link Zoom aktif. Tambahkan di
                <a href="<?= base_url('pages/master-zoom.php') ?>" style="color:#1d4ed8;font-weight:600;">Master Zoom</a>.
            </div>
        <?php else: ?>
            <div class="avail-date-controls">
                <button class="avail-shortcut active" id="btn-today"    onclick="setAvailDate('today')">Hari Ini</button>
                <button class="avail-shortcut"        id="btn-tomorrow" onclick="setAvailDate('tomorrow')">Besok</button>
                <button class="avail-shortcut"        id="btn-week"     onclick="setAvailDate('week')">Minggu Ini</button>
                <input type="date" class="avail-date-input" id="avail-date-picker"
                       value="<?= date('Y-m-d') ?>"
                       onchange="setAvailDate('custom', this.value)">
                <span style="font-size:12px;color:#94a3b8;">atau pilih tanggal →</span>
            </div>

            <div class="avail-legend">
                <span><span class="legend-dot kosong"></span> Kosong / tersedia</span>
                <span><span class="legend-dot dipakai"></span> Ada booking di tanggal ini</span>
            </div>

            <div class="avail-summary-bar" id="avail-summary-bar"></div>
            <div class="zoom-avail-grid" id="avail-grid"></div>
        <?php endif; ?>
    </div>

    <!-- ══════════════════════════════════════════════════════════
         FILTER BAR
    ══════════════════════════════════════════════════════════ -->
    <form method="get" action="" id="filterForm">
        <div class="filter-bar">
            <div style="font-size:13px;font-weight:700;color:#475569;margin-bottom:12px;">📋 Filter Tabel Riwayat Booking</div>
            <div class="filter-grid">

                <div class="filter-field">
                    <label>Unit</label>
                    <select name="filter_unit">
                        <option value="">Semua Unit</option>
                        <?php
                        // Gabung unit dari DB booking + unit_all
                        $unit_options = array_unique($units_all);
                        sort($unit_options);
                        foreach ($unit_options as $u): ?>
                            <option value="<?= h($u) ?>" <?= $filter_unit === $u ? 'selected' : '' ?>>
                                <?= strtoupper(h($u)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-field">
                    <label>Kondisi</label>
                    <select name="filter_kondisi">
                        <option value="">Semua Kondisi</option>
                        <option value="KOSONG"  <?= $filter_kondisi === 'KOSONG'  ? 'selected' : '' ?>>🟢 KOSONG</option>
                        <option value="DIPAKAI" <?= $filter_kondisi === 'DIPAKAI' ? 'selected' : '' ?>>🟡 DIPAKAI</option>
                    </select>
                </div>

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

                <div class="filter-field">
                    <label>Dari Tanggal</label>
                    <input type="date" name="filter_from" value="<?= h($filter_from) ?>">
                </div>

                <div class="filter-field">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="filter_to" value="<?= h($filter_to) ?>">
                </div>

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

                            <td>
                                <?php
                                $hasRange = !empty($r['start_datetime']) && !empty($r['end_datetime']);
                                if ($hasRange):
                                    $start   = new DateTime($r['start_datetime']);
                                    $end     = new DateTime($r['end_datetime']);
                                    $diff    = $start->diff($end);
                                    $sameDay = $start->format('Y-m-d') === $end->format('Y-m-d');
                                    $durStr  = '';
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

                            <td>
                                <?php if (!empty($r['unit'])): ?>
                                    <span class="unit-badge"><?= h(strtoupper($r['unit'])) ?></span>
                                <?php else: ?>
                                    <span style="color:#cbd5e1;">—</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <span style="background:#dbeafe;color:#1e40af;padding:4px 10px;border-radius:6px;font-size:13px;font-weight:600;">
                                    <?= h($r['zoom_link']) ?>
                                </span>
                            </td>

                            <td><?= h($r['keterangan'] ?: '-') ?></td>

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

                            <td><?= h($r['booked_by_name'] ?? '-') ?></td>

                            <td>
                                <?php if (!empty($r['created_at'])): ?>
                                    <?= date('d M Y H:i', strtotime($r['created_at'])) ?>
                                <?php else: ?>—
                                <?php endif; ?>
                            </td>

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
/* ══════════════════════════════════════════════════════════════
   DATA — dari PHP (sumber kebenaran: tabel zoom_links di DB)
══════════════════════════════════════════════════════════════ */
const ALL_BOOKINGS   = <?= $all_bookings_json ?>;
const ALL_ZOOM_LINKS = <?= $zoom_links_active_json ?>;

/* ── Helpers ──────────────────────────────────────────────── */
function zoomLabel(email) {
    const m = email.match(/(\d+)@/);
    if (!m) {
        // Untuk email tanpa angka, ambil bagian sebelum @
        return email.split('@')[0];
    }
    return 'PLN ' + String(parseInt(m[1], 10)).padStart(3, '0');
}

function fmtTime(dt) {
    if (!dt) return '';
    const d = new Date(dt);
    return String(d.getHours()).padStart(2,'0') + ':' + String(d.getMinutes()).padStart(2,'0');
}

function fmtDate(dt) {
    if (!dt) return '';
    const d = new Date(dt);
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    return d.getDate() + ' ' + months[d.getMonth()];
}

function bookingOverlapsRange(booking, fromStr, toStr) {
    if (booking.start_datetime && booking.end_datetime) {
        const start      = new Date(booking.start_datetime);
        const end        = new Date(booking.end_datetime);
        const rangeStart = new Date(fromStr + 'T00:00:00');
        const rangeEnd   = new Date(toStr   + 'T23:59:59');
        return start <= rangeEnd && end >= rangeStart;
    }
    if (booking.booking_date) {
        return booking.booking_date >= fromStr && booking.booking_date <= toStr;
    }
    return false;
}

/* ── State ────────────────────────────────────────────────── */
let currentMode = 'today';
let currentDate = '<?= date('Y-m-d') ?>';

function setAvailDate(mode, customDate) {
    currentMode = mode;
    const today    = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(today.getDate() + 1);

    ['btn-today','btn-tomorrow','btn-week'].forEach(function(id) {
        const el = document.getElementById(id);
        if (el) el.classList.remove('active');
    });

    if (mode === 'today') {
        currentDate = today.toISOString().slice(0,10);
        document.getElementById('btn-today').classList.add('active');
        document.getElementById('avail-date-picker').value = currentDate;
    } else if (mode === 'tomorrow') {
        currentDate = tomorrow.toISOString().slice(0,10);
        document.getElementById('btn-tomorrow').classList.add('active');
        document.getElementById('avail-date-picker').value = currentDate;
    } else if (mode === 'week') {
        document.getElementById('btn-week').classList.add('active');
    } else if (mode === 'custom') {
        currentDate = customDate;
    }

    renderAvailability();
}

function renderAvailability() {
    const grid    = document.getElementById('avail-grid');
    const summary = document.getElementById('avail-summary-bar');
    if (!grid) return;

    /* Tentukan range tanggal */
    let fromDate, toDate;
    if (currentMode === 'week') {
        const today = new Date();
        const day   = today.getDay();
        const monday = new Date(today);
        monday.setDate(today.getDate() - (day === 0 ? 6 : day - 1));
        const sunday = new Date(monday);
        sunday.setDate(monday.getDate() + 6);
        fromDate = monday.toISOString().slice(0,10);
        toDate   = sunday.toISOString().slice(0,10);
    } else {
        fromDate = currentDate;
        toDate   = currentDate;
    }

    let kosongCount = 0, dipakaiCount = 0;

    const cards = ALL_ZOOM_LINKS.map(function(zoomEmail) {
        const bookings = ALL_BOOKINGS.filter(function(b) {
            return b.zoom_link === zoomEmail &&
                   bookingOverlapsRange(b, fromDate, toDate);
        });

        const hasBooking = bookings.length > 0;
        if (hasBooking) dipakaiCount++; else kosongCount++;

        let infoHtml = '';
        if (hasBooking) {
            infoHtml = bookings.map(function(b) {
                let timeStr = '';
                if (b.start_datetime && b.end_datetime) {
                    const sameDay = b.start_datetime.slice(0,10) === b.end_datetime.slice(0,10);
                    if (currentMode === 'week' && !sameDay) {
                        timeStr = fmtDate(b.start_datetime) + ' ' + fmtTime(b.start_datetime) +
                                  ' → ' + fmtDate(b.end_datetime) + ' ' + fmtTime(b.end_datetime);
                    } else {
                        timeStr = fmtDate(b.start_datetime) + ' ' + fmtTime(b.start_datetime) +
                                  ' – ' + fmtTime(b.end_datetime);
                    }
                } else if (b.booking_time) {
                    timeStr = (b.booking_date || '') + ' ' + b.booking_time;
                }
                const unit = b.unit ? '<strong>' + b.unit + '</strong> · ' : '';
                const ket  = b.keterangan ? ' <span style="color:#94a3b8;">(' + b.keterangan + ')</span>' : '';
                return '<div class="booking-row">' + unit + timeStr + ket + '</div>';
            }).join('');
        } else {
            infoHtml = '<span style="color:#10b981;font-size:11px;font-weight:600;">Tidak ada booking</span>';
        }

        const label = zoomLabel(zoomEmail);
        return '<div class="zoom-avail-card ' + (hasBooking ? 'dipakai' : 'kosong') + '">' +
               '<div class="card-zoom-name" title="' + zoomEmail + '">' + label + '</div>' +
               '<div class="card-zoom-email" title="' + zoomEmail + '">' + zoomEmail + '</div>' +
               '<div class="card-status">' + (hasBooking ? '🟡 Dipakai' : '🟢 Kosong') + '</div>' +
               '<div class="card-booking-info">' + infoHtml + '</div>' +
               '</div>';
    });

    grid.innerHTML = cards.length ? cards.join('') : '<div class="avail-empty">Tidak ada link Zoom aktif.</div>';

    /* Summary bar */
    let rangeLabel = '';
    if (currentMode === 'today')         rangeLabel = 'Hari ini';
    else if (currentMode === 'tomorrow') rangeLabel = 'Besok';
    else if (currentMode === 'week')     rangeLabel = 'Minggu ini';
    else                                 rangeLabel = currentDate;

    if (summary) {
        summary.innerHTML =
            '<span class="avail-summary-pill kosong">🟢 ' + kosongCount + ' zoom kosong</span>' +
            '<span class="avail-summary-pill dipakai">🟡 ' + dipakaiCount + ' zoom ada booking</span>' +
            '<span style="font-size:12px;color:#94a3b8;align-self:center;">— ' + rangeLabel + '</span>';
    }
}

document.addEventListener('DOMContentLoaded', renderAvailability);
</script>