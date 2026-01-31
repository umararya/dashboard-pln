<?php
// maintenance-edit.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pdo = db();
$errors = [];
$maintenance_id = (int)($_GET['id'] ?? 0);

if ($maintenance_id <= 0) {
    header('Location: ' . base_url('pages/data-server.php'));
    exit;
}

$stmt = $pdo->prepare("SELECT sm.*, s.ind, s.fungsi_server FROM server_maintenance sm JOIN data_servers s ON sm.server_id = s.id WHERE sm.id = :id");
$stmt->execute([':id' => $maintenance_id]);
$maintenance = $stmt->fetch();

if (!$maintenance) {
    header('Location: ' . base_url('pages/data-server.php'));
    exit;
}

$server_id = $maintenance['server_id'];
$waktu_pemeliharaan = $_POST['waktu_pemeliharaan'] ?? $maintenance['waktu_pemeliharaan'];
$temuan = $_POST['temuan'] ?? $maintenance['temuan'];
$dicek_oleh = $_POST['dicek_oleh'] ?? $maintenance['dicek_oleh'];
$kondisi = $_POST['kondisi'] ?? $maintenance['kondisi'];
$status = $_POST['status'] ?? $maintenance['status'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_maintenance') {
    $waktu_pemeliharaan = trim($waktu_pemeliharaan);
    $temuan = trim($temuan);
    $dicek_oleh = trim($dicek_oleh);

    if ($waktu_pemeliharaan === '') $errors[] = 'Waktu pemeliharaan wajib diisi.';
    if ($temuan === '') $errors[] = 'Temuan wajib diisi.';
    if ($dicek_oleh === '') $errors[] = 'Dicek oleh wajib diisi.';
    if (!in_array($kondisi, ['HIDUP', 'MATI'])) $errors[] = 'Kondisi tidak valid.';
    if (!in_array($status, ['PROBLEM', 'AMAN'])) $errors[] = 'Status tidak valid.';

    if (!$errors) {
        $stmt = $pdo->prepare("
            UPDATE server_maintenance SET waktu_pemeliharaan = :waktu, temuan = :temuan, 
                   dicek_oleh = :dicek_oleh, kondisi = :kondisi, status = :status
            WHERE id = :id
        ");
        $stmt->execute([
            ':waktu' => $waktu_pemeliharaan, ':temuan' => $temuan,
            ':dicek_oleh' => $dicek_oleh, ':kondisi' => $kondisi,
            ':status' => $status, ':id' => $maintenance_id
        ]);

        header('Location: ' . base_url('pages/data-server-detail.php?id=' . $server_id . '&maintenance_updated=1'));
        exit;
    }
}

$page_title = "Edit History Pemeliharaan";
$active_menu = "data-server";
$content_file = __DIR__ . "/maintenance-edit.content.php";
require_once __DIR__ . '/../includes/layout.php';
exit;