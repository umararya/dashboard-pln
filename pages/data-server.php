<?php
/**
 * Data Server - List Page (Controller)
 * Path: pages/data-server.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

$pdo = db();
$success = '';

// Handle success messages
if (isset($_GET['added'])) {
    $success = 'Data server berhasil ditambahkan.';
}
if (isset($_GET['updated'])) {
    $success = 'Data server berhasil diupdate.';
}
if (isset($_GET['deleted'])) {
    $success = 'Data server berhasil dihapus.';
}

// Handle DELETE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_server') {
    if (is_admin()) {
        $id = (int)($_POST['server_id'] ?? 0);
        
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM data_servers WHERE id = :id");
            $stmt->execute([':id' => $id]);
            
            header('Location: ' . base_url('pages/data-server.php?deleted=1'));
            exit;
        }
    }
}

// Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Get total count
$total_count = $pdo->query("SELECT COUNT(*) FROM data_servers")->fetchColumn();
$total_pages = ceil($total_count / $per_page);

// Load data_servers
$stmt = $pdo->prepare("SELECT * FROM data_servers ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$servers = $stmt->fetchAll();

// Variables for layout
$page_title = "Data Server";
$active_menu = "data-server";
$content_file = __DIR__ . "/data-server.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;