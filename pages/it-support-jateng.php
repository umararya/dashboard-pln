<?php
/**
 * IT Support Jateng - List Page (Controller)
 * Path: pages/it-support-jateng.php
 */

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';

require_login();
require_permission('it-support-jateng');

$pdo = db();
$success = '';

if (isset($_GET['added']))   $success = 'Data IT Support berhasil ditambahkan.';
if (isset($_GET['updated'])) $success = 'Data IT Support berhasil diupdate.';
if (isset($_GET['deleted'])) $success = 'Data IT Support berhasil dihapus.';

// Handle DELETE (admin only)
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['action'])
    && $_POST['action'] === 'delete_person'
    && is_admin()
) {
    $id = (int)($_POST['person_id'] ?? 0);
    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM it_support_jateng WHERE id = :id");
        $stmt->execute([':id' => $id]);
        header('Location: ' . base_url('pages/it-support-jateng.php?deleted=1'));
        exit;
    }
}

// ── SERVER-SIDE SEARCH ──────────────────────────────────────────
$search = trim($_GET['q'] ?? '');

$where_parts = [];
$bind_params = [];

if ($search !== '') {
    $like = '%' . $search . '%';
    $where_parts[] = "(
        nama        LIKE :q1 OR
        email       LIKE :q2 OR
        no_hp       LIKE :q3 OR
        penempatan  LIKE :q4 OR
        ops_sti     LIKE :q5
    )";
    for ($i = 1; $i <= 5; $i++) {
        $bind_params[":q$i"] = $like;
    }
}

$where_sql = $where_parts ? 'WHERE ' . implode(' AND ', $where_parts) : '';

// ── PAGINATION ──────────────────────────────────────────────────
$per_page = 10;
$page     = max(1, (int)($_GET['page'] ?? 1));
$offset   = ($page - 1) * $per_page;

$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM it_support_jateng $where_sql");
$count_stmt->execute($bind_params);
$total_count = (int)$count_stmt->fetchColumn();
$total_pages = (int)ceil($total_count / $per_page);

$data_stmt = $pdo->prepare(
    "SELECT * FROM it_support_jateng $where_sql ORDER BY nama ASC, id ASC LIMIT :lim OFFSET :off"
);
foreach ($bind_params as $k => $v) {
    $data_stmt->bindValue($k, $v);
}
$data_stmt->bindValue(':lim', $per_page, PDO::PARAM_INT);
$data_stmt->bindValue(':off', $offset,   PDO::PARAM_INT);
$data_stmt->execute();
$people = $data_stmt->fetchAll();

$page_title   = "IT Support Jateng";
$active_menu  = "it-support-jateng";
$content_file = __DIR__ . "/it-support-jateng.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;