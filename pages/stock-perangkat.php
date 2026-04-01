<?php
/**
 * Stock Perangkat IT - List Page (Controller)
 * Path: pages/stock-perangkat.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';

require_login();
require_permission('stock-perangkat');

$pdo     = db();
$success = '';

// Handle success messages
if (isset($_GET['added']))   $success = 'Data perangkat berhasil ditambahkan.';
if (isset($_GET['updated'])) $success = 'Data perangkat berhasil diupdate.';
if (isset($_GET['deleted'])) $success = 'Data perangkat berhasil dihapus.';

// Handle DELETE (admin only)
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['action'])
    && $_POST['action'] === 'delete_perangkat'
    && is_admin()
) {
    $id = (int)($_POST['perangkat_id'] ?? 0);
    if ($id > 0) {
        // Hapus foto dari disk jika ada
        $stmt = $pdo->prepare("SELECT foto FROM stock_perangkat WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        if ($row && $row['foto']) {
            $path = __DIR__ . '/../uploads/stock_perangkat/' . $row['foto'];
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $stmt = $pdo->prepare("DELETE FROM stock_perangkat WHERE id = :id");
        $stmt->execute([':id' => $id]);

        header('Location: ' . base_url('pages/stock-perangkat.php?deleted=1'));
        exit;
    }
}

// Pagination
$page      = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page  = 10;
$offset    = ($page - 1) * $per_page;

$total_count = (int)$pdo->query("SELECT COUNT(*) FROM stock_perangkat")->fetchColumn();
$total_pages = (int)ceil($total_count / $per_page);

$stmt = $pdo->prepare(
    "SELECT sp.*, u.username AS created_by_name
     FROM stock_perangkat sp
     LEFT JOIN users u ON sp.created_by = u.id
     ORDER BY sp.created_at DESC
     LIMIT :lim OFFSET :off"
);
$stmt->bindValue(':lim', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':off', $offset,   PDO::PARAM_INT);
$stmt->execute();
$perangkat_list = $stmt->fetchAll();

$page_title   = "Stock Perangkat IT";
$active_menu  = "stock-perangkat";
$content_file = __DIR__ . "/stock-perangkat.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;