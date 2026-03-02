<?php
// maintenance-edit.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';

require_admin();
require_permission('data-server');

$pdo = db();
$errors = [];
$maintenance_id = (int)($_GET['id'] ?? 0);

if ($maintenance_id <= 0) {
    header('Location: ' . base_url('pages/data-server.php'));
    exit;
}

$stmt = $pdo->prepare("
    SELECT sm.*, s.ind, s.fungsi_server, s.status_server
    FROM server_maintenance sm
    JOIN data_servers s ON sm.server_id = s.id
    WHERE sm.id = :id
");
$stmt->execute([':id' => $maintenance_id]);
$maintenance = $stmt->fetch();

if (!$maintenance) {
    header('Location: ' . base_url('pages/data-server.php'));
    exit;
}

$server_id = $maintenance['server_id'];

// Block jika status server MATI
if (($maintenance['status_server'] ?? 'HIDUP') === 'MATI') {
    header('Location: ' . base_url('pages/data-server-detail.php?id=' . $server_id . '&server_mati=1'));
    exit;
}

$waktu_pemeliharaan = $_POST['waktu_pemeliharaan'] ?? $maintenance['waktu_pemeliharaan'];
$temuan             = $_POST['temuan']             ?? $maintenance['temuan'];
$dicek_oleh         = $_POST['dicek_oleh']         ?? $maintenance['dicek_oleh'];
$kondisi            = $_POST['kondisi']            ?? $maintenance['kondisi'];
$status             = $_POST['status']             ?? $maintenance['status'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_maintenance') {
    $waktu_pemeliharaan = trim($waktu_pemeliharaan);
    $temuan             = trim($temuan);
    $dicek_oleh         = trim($dicek_oleh);

    if ($waktu_pemeliharaan === '') $errors[] = 'Waktu pemeliharaan wajib diisi.';
    if ($temuan === '')             $errors[] = 'Temuan wajib diisi.';
    if ($dicek_oleh === '')         $errors[] = 'Dicek oleh wajib diisi.';
    if (!in_array($kondisi, ['HIDUP', 'MATI']))   $errors[] = 'Kondisi tidak valid.';
    if (!in_array($status,  ['PROBLEM', 'AMAN']))  $errors[] = 'Status tidak valid.';

    // Handle upload gambar baru
    $gambar_filename = $maintenance['gambar']; // default gambar lama
    $upload_dir = __DIR__ . '/../uploads/maintenance_images/';

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
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            $new_filename = 'mnt_' . time() . '_' . uniqid() . '.jpg';
            if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_filename)) {
                // Hapus gambar lama
                if ($maintenance['gambar'] && file_exists($upload_dir . $maintenance['gambar'])) {
                    unlink($upload_dir . $maintenance['gambar']);
                }
                $gambar_filename = $new_filename;
            } else {
                $errors[] = 'Gagal menyimpan gambar ke server.';
            }
        }
    }

    // Handle hapus gambar
    if (isset($_POST['hapus_gambar']) && $_POST['hapus_gambar'] === '1' && empty($_FILES['gambar']['name'])) {
        if ($maintenance['gambar'] && file_exists($upload_dir . $maintenance['gambar'])) {
            unlink($upload_dir . $maintenance['gambar']);
        }
        $gambar_filename = null;
    }

    if (!$errors) {
        $stmt = $pdo->prepare("
            UPDATE server_maintenance
            SET waktu_pemeliharaan = :waktu, temuan = :temuan,
                dicek_oleh = :dicek_oleh, kondisi = :kondisi,
                status = :status, gambar = :gambar
            WHERE id = :id
        ");
        $stmt->execute([
            ':waktu'      => $waktu_pemeliharaan,
            ':temuan'     => $temuan,
            ':dicek_oleh' => $dicek_oleh,
            ':kondisi'    => $kondisi,
            ':status'     => $status,
            ':gambar'     => $gambar_filename,
            ':id'         => $maintenance_id,
        ]);

        header('Location: ' . base_url('pages/data-server-detail.php?id=' . $server_id . '&maintenance_updated=1'));
        exit;
    }
}

$page_title   = "Edit History Pemeliharaan";
$active_menu  = "data-server";
$content_file = __DIR__ . "/maintenance-edit.content.php";
require_once __DIR__ . '/../includes/layout.php';
exit;