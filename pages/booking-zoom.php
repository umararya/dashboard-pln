<?php
/**
 * Booking Jadwal Zoom - Halaman Utama (Tabel + Filter)
 * Path: pages/booking-zoom.php
 */

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';

require_login();
require_permission('booking-zoom');

$pdo = db();

$KONDISI_OPTIONS = ['KOSONG', 'DIPAKAI'];

// Handle POST - Update Kondisi
// ⚠️ auto_release dipanggil SETELAH handle POST agar update manual tidak langsung di-reset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_kondisi') {
    $id          = (int)($_POST['booking_id'] ?? 0);
    $new_kondisi = $_POST['kondisi'] ?? '';

    if ($id > 0 && in_array($new_kondisi, $KONDISI_OPTIONS, true)) {
        $stmt = $pdo->prepare("UPDATE zoom_bookings SET kondisi = :kondisi WHERE id = :id");
        $stmt->execute([':kondisi' => $new_kondisi, ':id' => $id]);
    }

    header('Location: ' . base_url('pages/booking-zoom.php?updated=1'));
    exit;
}

// Handle POST - Delete Booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_booking') {
    $id = (int)($_POST['booking_id'] ?? 0);

    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM zoom_bookings WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    header('Location: ' . base_url('pages/booking-zoom.php?deleted=1'));
    exit;
}

// Auto-release booking yang sudah melewati end_datetime
// ✅ Dipanggil di sini (setelah POST handler) agar update kondisi manual tidak ikut ter-reset
auto_release_zoom_bookings($pdo);

// ── FILTER ───────────────────────────────────────────────────────────────────
$filter_unit    = trim($_GET['filter_unit']    ?? '');
$filter_kondisi = trim($_GET['filter_kondisi'] ?? '');
$filter_zoom    = trim($_GET['filter_zoom']    ?? '');
$filter_from    = trim($_GET['filter_from']    ?? '');
$filter_to      = trim($_GET['filter_to']      ?? '');

$where  = [];
$params = [];

if ($filter_unit !== '') {
    $where[]         = "zb.unit = :unit";
    $params[':unit'] = $filter_unit;
}
if ($filter_kondisi !== '') {
    $where[]             = "zb.kondisi = :kondisi";
    $params[':kondisi']  = $filter_kondisi;
}
if ($filter_zoom !== '') {
    $where[]              = "zb.zoom_link = :zoom_link";
    $params[':zoom_link'] = $filter_zoom;
}
if ($filter_from !== '') {
    $where[]              = "(COALESCE(zb.start_datetime, CONCAT(zb.booking_date,' 00:00:00')) >= :date_from)";
    $params[':date_from'] = $filter_from . ' 00:00:00';
}
if ($filter_to !== '') {
    $where[]            = "(COALESCE(zb.start_datetime, CONCAT(zb.booking_date,' 23:59:59')) <= :date_to)";
    $params[':date_to'] = $filter_to . ' 23:59:59';
}

$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$stmt = $pdo->prepare("
    SELECT
        zb.*,
        u.username AS booked_by_name
    FROM zoom_bookings zb
    LEFT JOIN users u ON zb.created_by = u.id
    $whereSql
    ORDER BY zb.booking_date DESC, zb.id DESC
");
$stmt->execute($params);
$rows = $stmt->fetchAll();

// ── ZOOM LINKS dari DB (untuk filter dropdown & availability checker) ─────────
$zoom_links_all = $pdo->query(
    "SELECT DISTINCT zoom_link FROM zoom_bookings WHERE zoom_link IS NOT NULL ORDER BY zoom_link"
)->fetchAll(PDO::FETCH_COLUMN);

$zoom_links_active = $pdo->query(
    "SELECT email FROM zoom_links WHERE is_active = 1 ORDER BY sort_order ASC, id ASC"
)->fetchAll(PDO::FETCH_COLUMN);

// ── UNIT dari DB (untuk filter dropdown) ─────────────────────────────────────
$units_all = $pdo->query(
    "SELECT DISTINCT unit FROM zoom_bookings WHERE unit IS NOT NULL AND unit != '' ORDER BY unit"
)->fetchAll(PDO::FETCH_COLUMN);

$is_filtered = ($filter_unit || $filter_kondisi || $filter_zoom || $filter_from || $filter_to);

$page_title   = "Booking Jadwal Zoom";
$active_menu  = "booking-zoom";
$content_file = __DIR__ . "/booking-zoom.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;