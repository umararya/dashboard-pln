<?php
/**
 * Data Jadwal (Controller)
 * Path: pages/data-jadwal.php
 */

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

$pdo = db();

// ambil semua jadwal (atau kamu bisa paginasi nanti)
$rows = $pdo->query("SELECT * FROM schedules ORDER BY created_at DESC, id DESC")->fetchAll();

$page_title   = "Data Jadwal";
$active_menu  = "data-jadwal"; // sesuaikan key menu sidebar kamu
$content_file = __DIR__ . "/data-jadwal.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;
