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

// Handle success messages
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

// Fetch all records
$people = $pdo->query("SELECT * FROM it_support_jateng ORDER BY nama ASC, id ASC")->fetchAll();

$page_title   = "IT Support Jateng";
$active_menu  = "it-support-jateng";
$content_file = __DIR__ . "/it-support-jateng.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;