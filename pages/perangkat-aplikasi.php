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

// Handle success messages
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

// Pagination
$page      = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page  = 15;
$offset    = ($page - 1) * $per_page;

$total_count = (int)$pdo->query("SELECT COUNT(*) FROM perangkat_aplikasi")->fetchColumn();
$total_pages = (int)ceil($total_count / $per_page);

$stmt = $pdo->prepare(
    "SELECT pa.*, u.username AS created_by_name
     FROM perangkat_aplikasi pa
     LEFT JOIN users u ON pa.created_by = u.id
     ORDER BY pa.created_at DESC
     LIMIT :lim OFFSET :off"
);
$stmt->bindValue(':lim', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':off', $offset,   PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();

$page_title   = "Perangkat Aplikasi";
$active_menu  = "perangkat-aplikasi";
$content_file = __DIR__ . "/perangkat-aplikasi.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;
