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

<!-- Rekapan Pemesanan Ruangan -->
<div style="margin-bottom: 10px;">
    <h3 style="font-size:16px;font-weight:700;color:#1e293b;display:flex;align-items:center;gap:8px;">
        📋 Rekapan Pemesanan Ruangan
    </h3>
    <p style="font-size:13px;color:#64748b;margin-top:4px;">Data jadwal pemesanan ruangan secara keseluruhan</p>
</div>

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

<?php if (!empty($error_message)): ?>
    <div class="alert alert-error" style="margin-bottom: 20px;">
        <?= h($error_message) ?>
    </div>
<?php endif; ?>

<!-- Zoom Stats Section -->
<div style="margin-bottom: 10px;">
    <h3 style="font-size:16px;font-weight:700;color:#1e293b;display:flex;align-items:center;gap:8px;">
        🎥 Rekapan Booking Zoom
    </h3>
    <p style="font-size:13px;color:#64748b;margin-top:4px;">Data booking zoom secara keseluruhan</p>
</div>

<div class="stats-grid">
    <div class="stat-card" style="background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%); box-shadow: 0 4px 12px rgba(14,165,233,0.3);">
        <h3>🎥 Total Booking Zoom</h3>
        <div class="number"><?= $zoom_total ?></div>
    </div>
    <div class="stat-card" style="background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); box-shadow: 0 4px 12px rgba(139,92,246,0.3);">
        <h3>📅 Booking Zoom Bulan Ini</h3>
        <div class="number"><?= $zoom_month ?></div>
    </div>
    <div class="stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 4px 12px rgba(16,185,129,0.3);">
        <h3>🟢 Zoom Tersedia Hari Ini</h3>
        <div class="number"><?= $zoom_available_today ?> <span style="font-size:14px;opacity:0.8;">/ <?= $zoom_active_total ?></span></div>
    </div>
</div>

