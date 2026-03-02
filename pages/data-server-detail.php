<?php
/**
 * Data Server - Detail Page (Controller)
 * Path: pages/data-server-detail.php
 */

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';

require_login();
require_permission('data-server');

$pdo = db();
$server_id = (int)($_GET['id'] ?? 0);
$success = '';

if ($server_id <= 0) {
    header('Location: ' . base_url('pages/data-server.php'));
    exit;
}

// Handle success messages
if (isset($_GET['updated'])) {
    $success = 'Data server berhasil diupdate.';
}
if (isset($_GET['maintenance_added'])) {
    $success = 'History pemeliharaan berhasil ditambahkan.';
}
if (isset($_GET['maintenance_updated'])) {
    $success = 'History pemeliharaan berhasil diupdate.';
}
if (isset($_GET['maintenance_deleted'])) {
    $success = 'History pemeliharaan berhasil dihapus.';
}
if (isset($_GET['status_updated'])) {
    $success = 'Status server berhasil diupdate.';
}
if (isset($_GET['server_mati'])) {
    $success = '⚠️ Aksi tidak diizinkan. Status server sedang MATI.';
}

// Load server data
$stmt = $pdo->prepare("SELECT * FROM data_servers WHERE id = :id");
$stmt->execute([':id' => $server_id]);
$server = $stmt->fetch();

if (!$server) {
    header('Location: ' . base_url('pages/data-server.php'));
    exit;
}

// Handle TOGGLE status_server (admin only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_status_server') {
    if (is_admin()) {
        $new_status = ($server['status_server'] === 'HIDUP') ? 'MATI' : 'HIDUP';
        $stmt = $pdo->prepare("UPDATE data_servers SET status_server = :status WHERE id = :id");
        $stmt->execute([':status' => $new_status, ':id' => $server_id]);

        header('Location: ' . base_url('pages/data-server-detail.php?id=' . $server_id . '&status_updated=1'));
        exit;
    }
}

// Handle DELETE maintenance
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_maintenance') {
    if (is_admin()) {
        $maintenance_id = (int)($_POST['maintenance_id'] ?? 0);
        
        if ($maintenance_id > 0) {
            $stmt = $pdo->prepare("DELETE FROM server_maintenance WHERE id = :id AND server_id = :server_id");
            $stmt->execute([':id' => $maintenance_id, ':server_id' => $server_id]);
            
            header('Location: ' . base_url('pages/data-server-detail.php?id=' . $server_id . '&maintenance_deleted=1'));
            exit;
        }
    }
}

// Load maintenance history
$stmt = $pdo->prepare("
    SELECT sm.*, u.username as created_by_name
    FROM server_maintenance sm
    LEFT JOIN users u ON sm.created_by = u.id
    WHERE sm.server_id = :server_id
    ORDER BY sm.created_at DESC
");
$stmt->execute([':server_id' => $server_id]);
$maintenance_history = $stmt->fetchAll();

$page_title = "Detail Server";
$active_menu = "data-server";
$content_file = __DIR__ . "/data-server-detail.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;