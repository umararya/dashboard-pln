<?php
// pages/booking-zoom.content.php
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
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .grid-2 {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div>
            <h2>📅 Booking Jadwal Zoom</h2>
            <p>Form untuk memesan jadwal penggunaan Zoom Meeting</p>
        </div>
        <a class="btn btn-secondary btn-sm" href="<?= base_url('pages/data-zoom.php') ?>">📋 Lihat Data Zoom</a>
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

    <?php if ($success): ?>
        <div class="alert alert-success" style="margin: 20px 25px;">
            ✅ <?= h($success) ?>
        </div>
    <?php endif; ?>

    <div style="padding: 25px;">
        <form method="post">
            <input type="hidden" name="action" value="add_booking">

            <div class="grid-2">
                <div class="form-group">
                    <label>Tanggal Booking <span style="color: #ef4444;">*</span></label>
                    <input 
                        type="date" 
                        name="booking_date" 
                        value="<?= h($booking_date) ?>" 
                        min="<?= date('Y-m-d') ?>"
                        required
                    >
                    <small style="color: #64748b; font-size: 12px; display: block; margin-top: 4px;">
                        Pilih tanggal untuk booking zoom
                    </small>
                </div>

                <div class="form-group">
                    <label>Jam <span style="color: #ef4444;">*</span></label>
                    <input 
                        type="text" 
                        name="booking_time" 
                        value="<?= h($booking_time) ?>" 
                        placeholder="Contoh: 09:00 - 11:00 atau 14.00"
                        required
                    >
                    <small style="color: #64748b; font-size: 12px; display: block; margin-top: 4px;">
                        Masukkan jam booking (format bebas)
                    </small>
                </div>
            </div>

            <div class="form-group">
                <label>Link Zoom <span style="color: #ef4444;">*</span></label>
                <select name="zoom_link" required>
                    <option value="">-- Pilih Link Zoom --</option>
                    <?php foreach ($ZOOM_OPTIONS as $zoom): ?>
                        <option value="<?= h($zoom) ?>" <?= ($zoom_link === $zoom) ? 'selected' : '' ?>>
                            <?= h($zoom) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small style="color: #64748b; font-size: 12px; display: block; margin-top: 4px;">
                    Pilih link zoom yang tersedia (sementara Zoom 1-10, akan diupdate dengan link asli)
                </small>
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea 
                    name="keterangan" 
                    rows="4" 
                    placeholder="Tambahkan keterangan atau catatan tambahan untuk booking ini (opsional)"
                ><?= h($keterangan) ?></textarea>
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <button type="submit" class="btn btn-primary">
                    💾 Simpan Booking
                </button>
                <a href="<?= base_url('pages/data-zoom.php') ?>" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>ℹ️ Informasi</h2>
    </div>
    <div style="padding: 20px 25px; color: #475569; font-size: 14px; line-height: 1.6;">
        <ul style="margin: 0; padding-left: 20px;">
            <li style="margin-bottom: 8px;">Pastikan tanggal dan jam booking tidak bentrok dengan booking lain</li>
            <li style="margin-bottom: 8px;">Status "Kondisi" akan otomatis diset ke <strong>DIPAKAI</strong> setelah booking</li>
            <li style="margin-bottom: 8px;">Admin dapat mengubah kondisi menjadi <strong>KOSONG</strong> setelah selesai digunakan</li>
            
        </ul>
    </div>
</div>