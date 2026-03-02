<?php
// pages/booking-zoom-form.content.php
?>

<style>
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #374151;
    font-size: 14px;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    font-size: 14px;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Date Range Picker */
.daterange-wrapper {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.daterange-wrapper .range-title {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.daterange-row {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    gap: 12px;
    align-items: center;
}

.daterange-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 20px;
    padding-top: 20px;
}

.daterange-field label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.daterange-field input[type="datetime-local"] {
    width: 100%;
    padding: 11px 13px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    font-size: 14px;
    outline: none;
    background: white;
    box-sizing: border-box;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.daterange-field input[type="datetime-local"]:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.daterange-summary {
    margin-top: 12px;
    padding: 10px 14px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 8px;
    font-size: 13px;
    color: #1d4ed8;
    display: none;
}

.daterange-summary.visible {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.duration-badge {
    background: #dbeafe;
    color: #1e40af;
    padding: 2px 10px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 12px;
    margin-left: auto;
    white-space: nowrap;
}

.unit-badge-preview {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    background: #e0f2fe;
    color: #0369a1;
    margin-left: 8px;
    vertical-align: middle;
}

@media (max-width: 600px) {
    .daterange-row {
        grid-template-columns: 1fr;
    }
    .daterange-arrow {
        display: none;
    }
}
</style>

<div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div>
            <h2>📅 Booking Jadwal Zoom Baru</h2>
            <p>Form untuk memesan jadwal penggunaan Zoom Meeting</p>
        </div>
        <a class="btn btn-secondary btn-sm" href="<?= base_url('pages/booking-zoom.php') ?>">← Kembali ke Daftar</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error" style="margin: 20px 25px;">
            <strong>⚠ Perbaiki input berikut:</strong>
            <ul style="margin: 8px 0 0 20px;">
                <?php foreach ($errors as $e): ?>
                    <li><?= h($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div style="padding: 25px;">
        <form method="post" id="bookingForm">
            <input type="hidden" name="action" value="add_booking">

            <!-- DATE RANGE PICKER -->
            <div class="daterange-wrapper">
                <div class="range-title">
                    🕐 Waktu Booking
                    <span style="color:#94a3b8;font-weight:400;font-size:13px;">— Pilih tanggal &amp; jam mulai hingga selesai</span>
                    <span style="color:#ef4444;font-size:16px;margin-left:2px;">*</span>
                </div>

                <div class="daterange-row">
                    <div class="daterange-field">
                        <label>🟢 Mulai</label>
                        <input
                            type="datetime-local"
                            name="start_datetime"
                            id="start_datetime"
                            value="<?= h($start_datetime) ?>"
                            min="<?= date('Y-m-d\TH:i') ?>"
                            required
                        >
                    </div>

                    <div class="daterange-arrow">→</div>

                    <div class="daterange-field">
                        <label>🔴 Selesai</label>
                        <input
                            type="datetime-local"
                            name="end_datetime"
                            id="end_datetime"
                            value="<?= h($end_datetime) ?>"
                            min="<?= date('Y-m-d\TH:i') ?>"
                            required
                        >
                    </div>
                </div>

                <!-- Preview otomatis -->
                <div class="daterange-summary" id="daterangeSummary">
                    <span>📌</span>
                    <span id="summaryText"></span>
                    <span class="duration-badge" id="durationBadge"></span>
                </div>
            </div>

            <!-- UNIT -->
            <div class="form-group">
                <label>
                    Unit <span style="color:#ef4444;">*</span>
                    <span class="unit-badge-preview" id="unitPreview" style="display:none;"></span>
                </label>
                <select name="unit" id="unitSelect" required onchange="updateUnitPreview(this)">
                    <option value="">-- Pilih Unit --</option>
                    <?php foreach ($UNIT_OPTIONS as $u): ?>
                        <option value="<?= h($u) ?>" <?= ($unit === $u) ? 'selected' : '' ?>>
                            <?= strtoupper(h($u)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small style="color:#64748b;font-size:12px;display:block;margin-top:4px;">
                    Pilih unit/bagian yang melakukan booking
                </small>
            </div>

            <!-- LINK ZOOM -->
            <div class="form-group">
                <label>Link Zoom <span style="color:#ef4444;">*</span></label>
                <select name="zoom_link" required>
                    <option value="">-- Pilih Link Zoom --</option>
                    <?php foreach ($ZOOM_OPTIONS as $zoom): ?>
                        <option value="<?= h($zoom) ?>" <?= ($zoom_link === $zoom) ? 'selected' : '' ?>>
                            <?= h($zoom) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small style="color:#64748b;font-size:12px;display:block;margin-top:4px;">
                    Pilih akun Zoom yang akan digunakan
                </small>
            </div>

            <!-- KETERANGAN -->
            <div class="form-group">
                <label>Keterangan</label>
                <textarea
                    name="keterangan"
                    rows="4"
                    placeholder="Tambahkan keterangan atau catatan tambahan (opsional)"
                ><?= h($keterangan) ?></textarea>
            </div>

            <div style="display:flex;gap:12px;align-items:center;">
                <button type="submit" class="btn btn-primary">💾 Simpan Booking</button>
                <a href="<?= base_url('pages/booking-zoom.php') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>ℹ️ Informasi</h2></div>
    <div style="padding:20px 25px;color:#475569;font-size:14px;line-height:1.6;">
        <ul style="margin:0;padding-left:20px;">
            <li style="margin-bottom:8px;">Pastikan waktu booking tidak bentrok dengan booking lain</li>
            <li style="margin-bottom:8px;">Status "Kondisi" akan otomatis diset ke <strong>DIPAKAI</strong> setelah booking</li>
            <li style="margin-bottom:8px;">Jangan lupa mengubah kondisi menjadi <strong>KOSONG</strong> setelah selesai digunakan</li>
        </ul>
    </div>
</div>

<script>
const startInput = document.getElementById('start_datetime');
const endInput   = document.getElementById('end_datetime');
const summary    = document.getElementById('daterangeSummary');
const summaryTxt = document.getElementById('summaryText');
const durationBg = document.getElementById('durationBadge');

function formatDT(val) {
    if (!val) return '';
    const d = new Date(val);
    const days   = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    return days[d.getDay()] + ', ' + d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear()
        + ' ' + String(d.getHours()).padStart(2,'0') + ':' + String(d.getMinutes()).padStart(2,'0');
}

function formatDuration(sv, ev) {
    const diffMs    = new Date(ev) - new Date(sv);
    if (diffMs <= 0) return null;
    const totalMins = Math.floor(diffMs / 60000);
    const hours     = Math.floor(totalMins / 60);
    const mins      = totalMins % 60;
    if (hours === 0) return mins + ' menit';
    if (mins  === 0) return hours + ' jam';
    return hours + ' jam ' + mins + ' menit';
}

function updateSummary() {
    const sv = startInput.value, ev = endInput.value;
    if (sv && ev) {
        const dur = formatDuration(sv, ev);
        if (dur) {
            summaryTxt.textContent = formatDT(sv) + '  →  ' + formatDT(ev);
            durationBg.textContent = '⏱ ' + dur;
            summary.classList.add('visible');
            endInput.setCustomValidity('');
        } else {
            summary.classList.remove('visible');
            endInput.setCustomValidity('Jam selesai harus setelah jam mulai.');
        }
    } else {
        summary.classList.remove('visible');
    }
}

function updateUnitPreview(select) {
    const preview = document.getElementById('unitPreview');
    if (select.value) {
        preview.textContent = select.value.toUpperCase();
        preview.style.display = 'inline-block';
    } else {
        preview.style.display = 'none';
    }
}

startInput.addEventListener('change', function() {
    if (this.value) endInput.min = this.value;
    updateSummary();
});
endInput.addEventListener('change', updateSummary);

document.addEventListener('DOMContentLoaded', function() {
    updateSummary();
    const sel = document.getElementById('unitSelect');
    if (sel && sel.value) updateUnitPreview(sel);
});
</script>