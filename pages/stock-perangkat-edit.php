<?php
/**
 * Stock Perangkat IT - Edit Page (Controller)
 * Path: pages/stock-perangkat-edit.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';

require_admin(); // Hanya admin yang boleh edit
require_permission('stock-perangkat');

$pdo    = db();
$errors = [];
$id     = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: ' . base_url('pages/stock-perangkat.php'));
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM stock_perangkat WHERE id = :id");
$stmt->execute([':id' => $id]);
$perangkat = $stmt->fetch();

if (!$perangkat) {
    header('Location: ' . base_url('pages/stock-perangkat.php'));
    exit;
}

$KONDISI_OPTIONS = ['BAIK', 'RUSAK', 'PERLU SERVICE'];

// Seed form values
$nama_barang = $_POST['nama_barang'] ?? $perangkat['nama_barang'];
$type_barang = $_POST['type_barang'] ?? $perangkat['type_barang'];
$supplai     = $_POST['supplai']     ?? $perangkat['supplai'];
$kondisi     = $_POST['kondisi']     ?? $perangkat['kondisi'];
$keterangan  = $_POST['keterangan'] ?? $perangkat['keterangan'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit_perangkat') {
    $nama_barang = trim($nama_barang);
    $type_barang = trim($type_barang);
    $supplai     = trim($supplai);
    $keterangan  = trim($keterangan);
    $kondisi     = in_array($kondisi, $KONDISI_OPTIONS) ? $kondisi : 'BAIK';

    if ($nama_barang === '') {
        $errors[] = 'Nama Barang wajib diisi.';
    }

    $foto_filename = $perangkat['foto'];
    $upload_dir    = __DIR__ . '/../uploads/stock_perangkat/';

    // Upload foto baru
    if (!empty($_FILES['foto']['name'])) {
        $file          = $_FILES['foto'];
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $max_size      = 2 * 1024 * 1024;

        if (!in_array($file['type'], $allowed_types)) {
            $errors[] = 'Foto hanya boleh format JPEG, PNG, atau WebP.';
        } elseif ($file['size'] > $max_size) {
            $errors[] = 'Ukuran foto maksimal 2MB.';
        } elseif ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Gagal mengupload foto.';
        } else {
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            $ext          = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $new_filename = 'sp_' . time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_filename)) {
                // Hapus foto lama
                if ($perangkat['foto'] && file_exists($upload_dir . $perangkat['foto'])) {
                    unlink($upload_dir . $perangkat['foto']);
                }
                $foto_filename = $new_filename;
            } else {
                $errors[] = 'Gagal menyimpan foto ke server.';
            }
        }
    }

    // Hapus foto
    if (isset($_POST['hapus_foto']) && $_POST['hapus_foto'] === '1' && empty($_FILES['foto']['name'])) {
        if ($perangkat['foto'] && file_exists($upload_dir . $perangkat['foto'])) {
            unlink($upload_dir . $perangkat['foto']);
        }
        $foto_filename = null;
    }

    if (!$errors) {
        $stmt = $pdo->prepare("
            UPDATE stock_perangkat
            SET nama_barang = :nama_barang,
                type_barang = :type_barang,
                supplai     = :supplai,
                kondisi     = :kondisi,
                keterangan  = :keterangan,
                foto        = :foto
            WHERE id = :id
        ");
        $stmt->execute([
            ':nama_barang' => $nama_barang,
            ':type_barang' => $type_barang,
            ':supplai'     => $supplai,
            ':kondisi'     => $kondisi,
            ':keterangan'  => $keterangan,
            ':foto'        => $foto_filename,
            ':id'          => $id,
        ]);

        header('Location: ' . base_url('pages/stock-perangkat.php?updated=1'));
        exit;
    }
}

$page_title   = "Edit Stock Perangkat IT";
$active_menu  = "stock-perangkat";
$content_file = __DIR__ . "/stock-perangkat-edit.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;