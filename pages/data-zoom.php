<?php
/**
 * Data Jadwal Zoom (Controller)
 * Path: pages/data-zoom.php
 */

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

$pdo = db();

// Load all zoom bookings
$rows = $pdo->query("
    SELECT 
        zb.*,
        u.username as booked_by_name
    FROM zoom_bookings zb
    LEFT JOIN users u ON zb.created_by = u.id
    ORDER BY zb.booking_date DESC, zb.id DESC
")->fetchAll();

$page_title   = "Data Jadwal Zoom";
$active_menu  = "data-zoom";
$content_file = __DIR__ . "/data-zoom.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;