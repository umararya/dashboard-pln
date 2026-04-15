<?php
/**
 * Cek Ketersediaan Zoom
 * Path: pages/cek-ketersediaan-zoom.php
 */

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';

require_login();
require_permission('booking-zoom');

$pdo = db();

// Auto-release booking yang sudah lewat end_datetime
auto_release_zoom_bookings($pdo);

// Zoom links aktif dari DB
$zoom_links_active = $pdo->query(
    "SELECT email FROM zoom_links WHERE is_active = 1 ORDER BY sort_order ASC, id ASC"
)->fetchAll(PDO::FETCH_COLUMN);

// Semua booking (untuk dikirim ke JS)
$all_bookings = $pdo->query("
    SELECT
        zb.zoom_link,
        zb.kondisi,
        zb.unit,
        zb.start_datetime,
        zb.end_datetime,
        zb.booking_date,
        zb.booking_time,
        zb.keterangan,
        u.username AS booked_by_name
    FROM zoom_bookings zb
    LEFT JOIN users u ON zb.created_by = u.id
    ORDER BY zb.booking_date DESC, zb.id DESC
")->fetchAll();

$page_title   = "Cek Ketersediaan Zoom";
$active_menu  = "booking-zoom";
$content_file = __DIR__ . "/cek-ketersediaan-zoom.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;