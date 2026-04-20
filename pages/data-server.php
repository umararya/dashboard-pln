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
require_once __DIR__ . '/../includes/functions-permissions.php';

require_login();
require_permission('data-server');

$pdo = db();
$success = '';

// Handle success messages
if (isset($_GET['added']))          $success = 'Data server berhasil ditambahkan.';
if (isset($_GET['updated']))        $success = 'Data server berhasil diupdate.';
if (isset($_GET['deleted']))        $success = 'Data server berhasil dihapus.';
if (isset($_GET['status_updated'])) $success = 'Status server berhasil diupdate.';

// Handle TOGGLE status_server (admin only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_status_server') {
    if (is_admin()) {
        $id = (int)($_POST['server_id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("SELECT status_server FROM data_servers WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $current    = $stmt->fetchColumn();
            $new_status = ($current === 'HIDUP') ? 'MATI' : 'HIDUP';

            $stmt = $pdo->prepare("UPDATE data_servers SET status_server = :status WHERE id = :id");
            $stmt->execute([':status' => $new_status, ':id' => $id]);

            $page_param = isset($_POST['current_page']) ? '&page=' . (int)$_POST['current_page'] : '';
            $q_param    = isset($_POST['q']) ? '&q=' . urlencode($_POST['q']) : '';
            header('Location: ' . base_url('pages/data-server.php?status_updated=1' . $page_param . $q_param));
            exit;
        }
    }
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

// ── SERVER-SIDE SEARCH ──────────────────────────────────────────
$search = trim($_GET['q'] ?? '');

$where_parts = [];
$bind_params = [];

if ($search !== '') {
    $like = '%' . $search . '%';
    $where_parts[] = "(
        ind                LIKE :q1 OR
        fungsi_server      LIKE :q2 OR
        ip                 LIKE :q3 OR
        merk               LIKE :q4 OR
        type               LIKE :q5 OR
        system_operasi     LIKE :q6 OR
        processor_merk     LIKE :q7 OR
        processor_type     LIKE :q8 OR
        server_fisik       LIKE :q9 OR
        status_server      LIKE :q10
    )";
    for ($i = 1; $i <= 10; $i++) {
        $bind_params[":q$i"] = $like;
    }
}

$where_sql = $where_parts ? 'WHERE ' . implode(' AND ', $where_parts) : '';

// Pagination
$page     = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = 10;
$offset   = ($page - 1) * $per_page;

// COUNT dengan search
$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM data_servers $where_sql");
$count_stmt->execute($bind_params);
$total_count = (int)$count_stmt->fetchColumn();
$total_pages = ceil($total_count / $per_page);

// DATA dengan search + pagination
$data_params = array_merge($bind_params, [':limit' => $per_page, ':offset' => $offset]);
$data_stmt   = $pdo->prepare("SELECT * FROM data_servers $where_sql ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
foreach ($bind_params as $k => $v) {
    $data_stmt->bindValue($k, $v);
}
$data_stmt->bindValue(':limit',  $per_page, PDO::PARAM_INT);
$data_stmt->bindValue(':offset', $offset,   PDO::PARAM_INT);
$data_stmt->execute();
$servers = $data_stmt->fetchAll();

$page_title   = "Data Server";
$active_menu  = "data-server";
$content_file = __DIR__ . "/data-server.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;