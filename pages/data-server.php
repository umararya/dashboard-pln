<?php
/**
 * Data Server Page (Single File: controller + content)
 * Path: pages/data-server.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

/**
 * Kalau file ini di-include oleh layout sebagai content,
 * layout akan set $IS_CONTENT = true.
 */
if (!isset($IS_CONTENT)) {
    // === CONTROLLER MODE ===
    $page_title   = "Data Server";
    $active_menu  = "data-server";

    // Arahkan layout untuk include file ini lagi, tapi dalam mode content
    $content_file = __FILE__;

    require_once __DIR__ . '/../includes/layout.php';
    exit;
}

// === CONTENT MODE ===
?>

<div class="card">
    <div class="card-header">
        <h2>🖥️ Data Server</h2>
        <p>Halaman ini masih dalam pengembangan</p>
    </div>

    <div style="padding: 60px 25px; text-align: center; color: #94a3b8;">
        <div style="font-size: 64px; margin-bottom: 20px;">🚧</div>
        <h3 style="color: #475569; margin-bottom: 10px;">Halaman Dalam Pengembangan</h3>
        <p>Fitur Data Server akan segera hadir.</p>
    </div>
</div>
