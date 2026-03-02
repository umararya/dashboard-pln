<?php
// maintenance-input.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';

require_login();
require_permission('data-server');

$pdo = db();
$errors = [];
$server_id = (int)($_GET['server_id'] ?? 0);

if ($server_id <= 0) {
    header('Location: ' . base_url('pages/data-server.php'));
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM data_servers WHERE id = :id");
$stmt->execute([':id' => $server_id]);
$server = $stmt->fetch();

if (!$server) {
    header('Location: ' . base_url('pages/data-server.php'));
    exit;
}

// Block jika status server MATI
if (($server['status_server'] ?? 'HIDUP') === 'MATI') {
    header('Location: ' . base_url('pages/data-server-detail.php?id=' . $server_id . '&server_mati=1'));
    exit;
}

$waktu_pemeliharaan = $_POST['waktu_pemeliharaan'] ?? '';
$temuan             = $_POST['temuan'] ?? '';
$dicek_oleh         = $_POST['dicek_oleh'] ?? '';
$kondisi            = $_POST['kondisi'] ?? '';
$status             = $_POST['status'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_maintenance') {
    $waktu_pemeliharaan = trim($waktu_pemeliharaan);
    $temuan             = trim($temuan);
    $dicek_oleh         = trim($dicek_oleh);
    $kondisi            = trim($kondisi);
    $status             = trim($status);

    if ($waktu_pemeliharaan === '') $errors[] = 'Waktu pemeliharaan wajib diisi.';
    if ($temuan === '')             $errors[] = 'Temuan wajib diisi.';
    if ($dicek_oleh === '')         $errors[] = 'Dicek oleh wajib diisi.';
    if (!in_array($kondisi, ['HIDUP', 'MATI']))    $errors[] = 'Kondisi tidak valid.';
    if (!in_array($status,  ['PROBLEM', 'AMAN']))  $errors[] = 'Status tidak valid.';

    // Handle upload gambar
    $gambar_filename = null;
    if (!empty($_FILES['gambar']['name'])) {
        $file          = $_FILES['gambar'];
        $allowed_types = ['image/jpeg', 'image/jpg'];
        $max_size      = 2 * 1024 * 1024;

        if (!in_array($file['type'], $allowed_types)) {
            $errors[] = 'Gambar hanya boleh format JPEG/JPG.';
        } elseif ($file['size'] > $max_size) {
            $errors[] = 'Ukuran gambar maksimal 2MB.';
        } elseif ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Gagal mengupload gambar.';
        } else {
            $upload_dir = __DIR__ . '/../uploads/maintenance_images/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            $gambar_filename = 'mnt_' . time() . '_' . uniqid() . '.jpg';
            if (!move_uploaded_file($file['tmp_name'], $upload_dir . $gambar_filename)) {
                $errors[] = 'Gagal menyimpan gambar ke server.';
                $gambar_filename = null;
            }
        }
    }

    if (!$errors) {
        $stmt = $pdo->prepare("
            INSERT INTO server_maintenance
                (server_id, waktu_pemeliharaan, temuan, dicek_oleh, kondisi, status, gambar, created_by)
            VALUES
                (:server_id, :waktu, :temuan, :dicek_oleh, :kondisi, :status, :gambar, :created_by)
        ");
        $stmt->execute([
            ':server_id'  => $server_id,
            ':waktu'      => $waktu_pemeliharaan,
            ':temuan'     => $temuan,
            ':dicek_oleh' => $dicek_oleh,
            ':kondisi'    => $kondisi,
            ':status'     => $status,
            ':gambar'     => $gambar_filename,
            ':created_by' => $_SESSION['user_id'] ?? null,
        ]);

        header('Location: ' . base_url('pages/data-server-detail.php?id=' . $server_id . '&maintenance_added=1'));
        exit;
    }
}

$page_title   = "Input History Pemeliharaan";
$active_menu  = "data-server";
$content_file = __DIR__ . "/maintenance-input.content.php";
require_once __DIR__ . '/../includes/layout.php';
exit;