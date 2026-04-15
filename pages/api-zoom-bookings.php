<?php
/**
 * API: Get fresh zoom bookings (for cek-ketersediaan real-time)
 * Path: pages/api-zoom-bookings.php
 */

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';

require_login();
require_permission('booking-zoom');

header('Content-Type: application/json; charset=utf-8');

$pdo = db();

// Auto-release booking yang sudah lewat end_datetime
auto_release_zoom_bookings($pdo);

$bookings = $pdo->query("
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

echo json_encode($bookings, JSON_UNESCAPED_UNICODE);
exit;