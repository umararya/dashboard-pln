<?php
// pages/cek-ketersediaan-zoom.content.php
?>

<style>
/* ── Filter Card ─────────────────────────────────────────────── */
.avail-filter-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    padding: 28px;
    margin-bottom: 20px;
}
.avail-filter-card h3 {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 20px 0;
}
.avail-filter-row {
    display: flex;
    gap: 14px;
    align-items: flex-end;
    flex-wrap: wrap;
}
.avail-filter-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.avail-filter-field label {
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.avail-filter-field input[type="date"] {
    padding: 10px 14px;
    border: 1.5px solid #d1d5db;
    border-radius: 9px;
    font-size: 14px;
    outline: none;
    background: #fff;
    transition: border-color 0.2s, box-shadow 0.2s;
    min-width: 180px;
}
.avail-filter-field input[type="date"]:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
.btn-cek {
    padding: 10px 24px;
    background: #3b82f6;
    color: #fff;
    border: none;
    border-radius: 9px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    transition: background 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 7px;
}
.btn-cek:hover { background: #2563eb; }

/* ── Result section ──────────────────────────────────────────── */
.avail-result-section { display: none; }
.avail-result-section.visible { display: block; }

.avail-result-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 14px;
}
.avail-result-title {
    font-size: 15px;
    font-weight: 700;
    color: #1e293b;
}
.avail-summary-pills {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.summary-pill {
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
}
.summary-pill.kosong  { background: #d1fae5; color: #065f46; }
.summary-pill.dipakai { background: #fef3c7; color: #92400e; }

/* ── Table ───────────────────────────────────────────────────── */
.avail-table-wrap {
    overflow-x: auto;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
}
.avail-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.avail-table thead th {
    background: #f8fafc;
    padding: 12px 16px;
    text-align: left;
    font-weight: 700;
    color: #475569;
    border-bottom: 2px solid #e5e7eb;
    white-space: nowrap;
}
.avail-table tbody td {
    padding: 12px 16px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: top;
}
.avail-table tbody tr:last-child td { border-bottom: none; }
.avail-table tbody tr:hover { background: #f8fafc; }

/* Status badges */
.badge-kosong {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #6ee7b7;
    white-space: nowrap;
}
.badge-dipakai {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fcd34d;
    white-space: nowrap;
}

/* Booking detail list */
.booking-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.booking-item {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 7px;
    padding: 8px 12px;
    font-size: 12px;
    color: #334155;
    line-height: 1.5;
}
.booking-item .bk-time {
    font-weight: 700;
    color: #1e293b;
}
.booking-item .bk-unit {
    display: inline-block;
    padding: 1px 7px;
    background: #e0f2fe;
    color: #0369a1;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    margin-left: 4px;
}
.booking-item .bk-ket {
    color: #64748b;
    font-style: italic;
    margin-top: 2px;
    display: block;
}
.no-booking {
    font-size: 12px;
    color: #10b981;
    font-weight: 600;
}

/* Zoom email style */
.zoom-email-cell {
    font-family: monospace;
    font-size: 12px;
    background: #f0f9ff;
    padding: 3px 8px;
    border-radius: 5px;
    color: #0369a1;
    display: inline-block;
}

/* Empty state */
.avail-empty {
    text-align: center;
    padding: 60px 20px;
    color: #94a3b8;
}

/* Info box */
.avail-info-box {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 10px;
    padding: 14px 18px;
    font-size: 13px;
    color: #1e40af;
    margin-bottom: 16px;
}

@media (max-width: 600px) {
    .avail-filter-row { flex-direction: column; align-items: stretch; }
    .avail-filter-field input[type="date"] { min-width: 100%; }
}
</style>

<?php
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
        'booked_by_name' => $r['booked_by_name'] ?? '',
    ];
}, $all_bookings), JSON_UNESCAPED_UNICODE);

$zoom_links_active_json = json_encode($zoom_links_active, JSON_UNESCAPED_UNICODE);
?>

<!-- Filter Card -->
<div class="avail-filter-card">
    <h3>🔍 Pilih Tanggal untuk Cek Ketersediaan</h3>

    <?php if (empty($zoom_links_active)): ?>
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:14px 16px;font-size:13px;color:#991b1b;">
            ⚠ Belum ada link Zoom aktif. Tambahkan di
            <a href="<?= base_url('pages/master-zoom.php') ?>" style="color:#1d4ed8;font-weight:600;">Master Zoom</a>.
        </div>
    <?php else: ?>
        <div class="avail-filter-row">
            <div class="avail-filter-field">
                <label>Dari Tanggal</label>
                <input type="date" id="filterFrom" value="<?= date('Y-m-d') ?>">
            </div>
            <div class="avail-filter-field">
                <label>Sampai Tanggal</label>
                <input type="date" id="filterTo" value="<?= date('Y-m-d') ?>">
            </div>
            <button type="button" class="btn-cek" onclick="cekKetersediaan()">
                🔍 Cek Ketersediaan
            </button>
        </div>

        <div class="avail-info-box" style="margin-top:16px;margin-bottom:0;">
            💡 Pilih rentang tanggal, lalu klik <strong>"Cek Ketersediaan"</strong> untuk melihat status setiap akun Zoom.
        </div>
    <?php endif; ?>
</div>

<!-- Result Section -->
<div class="card avail-result-section" id="availResult">
    <div class="card-header">
        <div class="avail-result-header">
            <div>
                <div class="avail-result-title" id="resultTitle">Hasil Pengecekan</div>
                <div style="font-size:13px;color:#64748b;margin-top:3px;" id="resultSubtitle"></div>
            </div>
            <div class="avail-summary-pills" id="summaryPills"></div>
        </div>
    </div>

    <div style="padding:20px 25px;">
        <div class="avail-table-wrap">
            <table class="avail-table" id="availTable">
                <thead>
                    <tr>
                        <th style="width:46px;">No</th>
                        <th>Akun Zoom</th>
                        <th style="width:130px;">Status</th>
                        <th>Detail Booking pada Rentang Tanggal</th>
                    </tr>
                </thead>
                <tbody id="availTableBody">
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
const ALL_BOOKINGS   = <?= $all_bookings_json ?>;
const ALL_ZOOM_LINKS = <?= $zoom_links_active_json ?>;

function fmtDate(dtStr) {
    if (!dtStr) return '';
    const d = new Date(dtStr);
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    return d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
}

function fmtTime(dtStr) {
    if (!dtStr) return '';
    const d = new Date(dtStr);
    return String(d.getHours()).padStart(2,'0') + ':' + String(d.getMinutes()).padStart(2,'0');
}

function fmtDateShort(dtStr) {
    if (!dtStr) return '';
    const d = new Date(dtStr);
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

function fmtDateRange(fromStr, toStr) {
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const fD = new Date(fromStr);
    const tD = new Date(toStr);
    const fFmt = fD.getDate() + ' ' + months[fD.getMonth()] + ' ' + fD.getFullYear();
    const tFmt = tD.getDate() + ' ' + months[tD.getMonth()] + ' ' + tD.getFullYear();
    if (fromStr === toStr) return fFmt;
    return fFmt + ' — ' + tFmt;
}

function cekKetersediaan() {
    const fromStr = document.getElementById('filterFrom').value;
    const toStr   = document.getElementById('filterTo').value;

    if (!fromStr || !toStr) {
        alert('Pilih tanggal dari dan sampai terlebih dahulu.');
        return;
    }
    if (toStr < fromStr) {
        alert('Tanggal "Sampai" tidak boleh sebelum tanggal "Dari".');
        return;
    }

    const resultEl   = document.getElementById('availResult');
    const titleEl    = document.getElementById('resultTitle');
    const subtitleEl = document.getElementById('resultSubtitle');
    const pillsEl    = document.getElementById('summaryPills');
    const tbodyEl    = document.getElementById('availTableBody');

    let kosongCount = 0, dipakaiCount = 0;
    let rows = '';

    ALL_ZOOM_LINKS.forEach(function(email, idx) {
        const bookings = ALL_BOOKINGS.filter(function(b) {
            return b.zoom_link === email && bookingOverlapsRange(b, fromStr, toStr);
        });

        const hasBk = bookings.length > 0;
        if (hasBk) dipakaiCount++; else kosongCount++;

        // Badge status
        const statusBadge = hasBk
            ? '<span class="badge-dipakai">🟡 Ada Booking</span>'
            : '<span class="badge-kosong">🟢 Kosong</span>';

        // Detail bookings
        let detailHtml = '';
        if (hasBk) {
            detailHtml = '<div class="booking-list">';
            bookings.forEach(function(b) {
                let timeStr = '';
                if (b.start_datetime && b.end_datetime) {
                    const sameDay = b.start_datetime.slice(0,10) === b.end_datetime.slice(0,10);
                    if (sameDay) {
                        timeStr = fmtDate(b.start_datetime) + ' &nbsp;' +
                                  fmtTime(b.start_datetime) + ' – ' + fmtTime(b.end_datetime);
                    } else {
                        timeStr = fmtDateShort(b.start_datetime) + ' ' + fmtTime(b.start_datetime) +
                                  ' → ' + fmtDateShort(b.end_datetime) + ' ' + fmtTime(b.end_datetime);
                    }
                } else if (b.booking_date) {
                    timeStr = fmtDate(b.booking_date) + (b.booking_time ? ' &nbsp;' + b.booking_time : '');
                }

                const unitBadge = b.unit
                    ? '<span class="bk-unit">' + b.unit.toUpperCase() + '</span>'
                    : '';

                const byStr = b.booked_by_name
                    ? ' · <span style="color:#94a3b8;">oleh ' + b.booked_by_name + '</span>'
                    : '';

                const ketStr = b.keterangan
                    ? '<span class="bk-ket">📝 ' + b.keterangan + '</span>'
                    : '';

                detailHtml += '<div class="booking-item">' +
                              '<span class="bk-time">' + timeStr + '</span>' +
                              unitBadge + byStr +
                              ketStr +
                              '</div>';
            });
            detailHtml += '</div>';
        } else {
            detailHtml = '<span class="no-booking">✅ Tidak ada booking pada rentang tanggal ini</span>';
        }

        const rowBg = (idx % 2 === 0) ? '' : 'style="background:#f8fafc;"';

        rows += '<tr ' + rowBg + '>' +
                '<td style="font-weight:700;color:#475569;">' + (idx + 1) + '</td>' +
                '<td><span class="zoom-email-cell">' + email + '</span></td>' +
                '<td>' + statusBadge + '</td>' +
                '<td>' + detailHtml + '</td>' +
                '</tr>';
    });

    tbodyEl.innerHTML = rows || '<tr><td colspan="4" style="text-align:center;padding:40px;color:#94a3b8;">Tidak ada akun Zoom aktif</td></tr>';

    titleEl.textContent    = 'Hasil Pengecekan Ketersediaan Zoom';
    subtitleEl.textContent = 'Rentang: ' + fmtDateRange(fromStr, toStr);

    pillsEl.innerHTML =
        '<span class="summary-pill kosong">🟢 ' + kosongCount + ' kosong</span>' +
        '<span class="summary-pill dipakai">🟡 ' + dipakaiCount + ' ada booking</span>';

    resultEl.classList.add('visible');
    resultEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Allow pressing Enter on date inputs to trigger check
document.getElementById('filterFrom') && document.getElementById('filterFrom').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') cekKetersediaan();
});
document.getElementById('filterTo') && document.getElementById('filterTo').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') cekKetersediaan();
});
</script>