<?php
/**
 * Perangkat Aplikasi - List Page (Controller)
 * Path: pages/perangkat-aplikasi.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';

require_login();
require_permission('perangkat-aplikasi');

$pdo     = db();
$success = '';

if (isset($_GET['added']))   $success = 'Data perangkat aplikasi berhasil ditambahkan.';
if (isset($_GET['updated'])) $success = 'Data perangkat aplikasi berhasil diupdate.';
if (isset($_GET['deleted'])) $success = 'Data perangkat aplikasi berhasil dihapus.';

// Handle DELETE (admin only)
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['action'])
    && $_POST['action'] === 'delete_perangkat_aplikasi'
    && is_admin()
) {
    $id = (int)($_POST['item_id'] ?? 0);
    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM perangkat_aplikasi WHERE id = :id");
        $stmt->execute([':id' => $id]);
        header('Location: ' . base_url('pages/perangkat-aplikasi.php?deleted=1'));
        exit;
    }
}

// ── FILTER (dropdown) ─────────────────────────────────────────────
$filter_jenis = trim($_GET['filter_jenis'] ?? '');
$filter_msb   = trim($_GET['filter_msb']   ?? '');

// ── SERVER-SIDE SEARCH (text) ─────────────────────────────────────
$search = trim($_GET['q'] ?? '');

$is_filtered  = ($filter_jenis !== '' || $filter_msb !== '');
$is_searching = ($search !== '');

$where_parts = [];
$bind_params = [];

if ($filter_jenis !== '') {
    $where_parts[]           = "pa.jenis_perangkat = :jenis";
    $bind_params[':jenis']   = $filter_jenis;
}
if ($filter_msb !== '') {
    $where_parts[]          = "pa.msb_sub_bidang = :msb";
    $bind_params[':msb']    = $filter_msb;
}
if ($search !== '') {
    $like = '%' . $search . '%';
    $where_parts[] = "(
        pa.jenis_perangkat  LIKE :q1 OR
        pa.url              LIKE :q2 OR
        pa.ip               LIKE :q3 OR
        pa.brand            LIKE :q4 OR
        pa.type             LIKE :q5 OR
        pa.server           LIKE :q6 OR
        pa.os               LIKE :q7 OR
        pa.lokasi           LIKE :q8 OR
        pa.bidang           LIKE :q9 OR
        pa.msb_sub_bidang   LIKE :q10 OR
        pa.pemilik_aset     LIKE :q11
    )";
    for ($i = 1; $i <= 11; $i++) {
        $bind_params[":q$i"] = $like;
    }
}

$where_sql = $where_parts ? 'WHERE ' . implode(' AND ', $where_parts) : '';

// ── Dropdown options untuk filter ────────────────────────────────
$jenis_options = $pdo->query(
    "SELECT DISTINCT jenis_perangkat FROM perangkat_aplikasi
     WHERE jenis_perangkat IS NOT NULL AND jenis_perangkat != ''
     ORDER BY jenis_perangkat ASC"
)->fetchAll(PDO::FETCH_COLUMN);

$msb_options = $pdo->query(
    "SELECT DISTINCT msb_sub_bidang FROM perangkat_aplikasi
     WHERE msb_sub_bidang IS NOT NULL AND msb_sub_bidang != ''
     ORDER BY msb_sub_bidang ASC"
)->fetchAll(PDO::FETCH_COLUMN);

// ── Pagination ────────────────────────────────────────────────────
$page      = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page  = 15;
$offset    = ($page - 1) * $per_page;

$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM perangkat_aplikasi pa $where_sql");
$count_stmt->execute($bind_params);
$total_count = (int)$count_stmt->fetchColumn();
$total_pages = (int)ceil($total_count / $per_page);

$data_stmt = $pdo->prepare(
    "SELECT pa.*, u.username AS created_by_name
     FROM perangkat_aplikasi pa
     LEFT JOIN users u ON pa.created_by = u.id
     $where_sql
     ORDER BY pa.created_at DESC
     LIMIT :lim OFFSET :off"
);
foreach ($bind_params as $k => $v) {
    $data_stmt->bindValue($k, $v);
}
$data_stmt->bindValue(':lim', $per_page, PDO::PARAM_INT);
$data_stmt->bindValue(':off', $offset,   PDO::PARAM_INT);
$data_stmt->execute();
$rows = $data_stmt->fetchAll();

$page_title   = "Perangkat Aplikasi";
$active_menu  = "perangkat-aplikasi";
$content_file = __DIR__ . "/perangkat-aplikasi.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;