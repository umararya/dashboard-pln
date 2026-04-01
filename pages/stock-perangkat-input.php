<?php
/**
 * Stock Perangkat IT - Input Page (Controller)
 * Path: pages/stock-perangkat-input.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';

require_login();
require_permission('stock-perangkat');

$pdo    = db();
$errors = [];

// Default form values
$nama_barang = $_POST['nama_barang'] ?? '';
$type_barang = $_POST['type_barang'] ?? '';
$supplai     = $_POST['supplai']     ?? '';
$kondisi     = $_POST['kondisi']     ?? 'BAIK';
$keterangan  = $_POST['keterangan'] ?? '';

$KONDISI_OPTIONS = ['BAIK', 'RUSAK', 'PERLU SERVICE'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_perangkat') {
    $nama_barang = trim($nama_barang);
    $type_barang = trim($type_barang);
    $supplai     = trim($supplai);
    $keterangan  = trim($keterangan);
    $kondisi     = in_array($kondisi, $KONDISI_OPTIONS) ? $kondisi : 'BAIK';

    if ($nama_barang === '') {
        $errors[] = 'Nama Barang wajib diisi.';
    }

    // Handle upload foto
    $foto_filename = null;
    if (!empty($_FILES['foto']['name'])) {
        $file          = $_FILES['foto'];
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $max_size      = 2 * 1024 * 1024; // 2 MB

        if (!in_array($file['type'], $allowed_types)) {
            $errors[] = 'Foto hanya boleh format JPEG, PNG, atau WebP.';
        } elseif ($file['size'] > $max_size) {
            $errors[] = 'Ukuran foto maksimal 2MB.';
        } elseif ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Gagal mengupload foto.';
        } else {
            $upload_dir = __DIR__ . '/../uploads/stock_perangkat/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $ext           = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $foto_filename = 'sp_' . time() . '_' . uniqid() . '.' . $ext;
            if (!move_uploaded_file($file['tmp_name'], $upload_dir . $foto_filename)) {
                $errors[] = 'Gagal menyimpan foto ke server.';
                $foto_filename = null;
            }
        }
    }

    if (!$errors) {
        $stmt = $pdo->prepare("
            INSERT INTO stock_perangkat
                (nama_barang, type_barang, supplai, kondisi, keterangan, foto, created_by)
            VALUES
                (:nama_barang, :type_barang, :supplai, :kondisi, :keterangan, :foto, :created_by)
        ");
        $stmt->execute([
            ':nama_barang' => $nama_barang,
            ':type_barang' => $type_barang,
            ':supplai'     => $supplai,
            ':kondisi'     => $kondisi,
            ':keterangan'  => $keterangan,
            ':foto'        => $foto_filename,
            ':created_by'  => $_SESSION['user_id'] ?? null,
        ]);

        header('Location: ' . base_url('pages/stock-perangkat.php?added=1'));
        exit;
    }
}

$page_title   = "Input Stock Perangkat IT";
$active_menu  = "stock-perangkat";
$content_file = __DIR__ . "/stock-perangkat-input.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;