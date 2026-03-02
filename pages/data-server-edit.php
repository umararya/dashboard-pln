<?php
/**
 * Data Server - Edit Page (Controller)
 * Path: pages/data-server-edit.php
 */

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';

require_admin();
require_permission('data-server');

$pdo = db();
$errors    = [];
$server_id = (int)($_GET['id'] ?? 0);

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

// Set form values (retain POST on error, otherwise default from DB)
$ind                   = $_POST['ind']                   ?? $server['ind'];
$fungsi_server         = $_POST['fungsi_server']         ?? $server['fungsi_server'];
$ip                    = $_POST['ip']                    ?? $server['ip'];
$detail                = $_POST['detail']                ?? $server['detail'];
$merk                  = $_POST['merk']                  ?? $server['merk'];
$type                  = $_POST['type']                  ?? $server['type'];
$system_operasi        = $_POST['system_operasi']        ?? $server['system_operasi'];
$processor_merk        = $_POST['processor_merk']        ?? $server['processor_merk'];
$processor_type        = $_POST['processor_type']        ?? $server['processor_type'];
$processor_kecepatan   = $_POST['processor_kecepatan']   ?? $server['processor_kecepatan'];
$processor_keping      = $_POST['processor_keping']      ?? $server['processor_keping'];
$processor_core        = $_POST['processor_core']        ?? $server['processor_core'];
$ram_jenis             = $_POST['ram_jenis']             ?? $server['ram_jenis'];
$ram_kapasitas         = $_POST['ram_kapasitas']         ?? $server['ram_kapasitas'];
$ram_jumlah_keping     = $_POST['ram_jumlah_keping']     ?? $server['ram_jumlah_keping'];
$storage_jenis         = $_POST['storage_jenis']         ?? $server['storage_jenis'];
$storage_jumlah        = $_POST['storage_jumlah']        ?? $server['storage_jumlah'];
$storage_kapasitas_total = $_POST['storage_kapasitas_total'] ?? $server['storage_kapasitas_total'];
$keterangan_tambahan   = $_POST['keterangan_tambahan']   ?? $server['keterangan_tambahan'];
$server_fisik          = $_POST['server_fisik']          ?? $server['server_fisik'];
$status_server         = $_POST['status_server']         ?? $server['status_server'] ?? 'HIDUP';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_server') {
    $ind           = trim($ind);
    $fungsi_server = trim($fungsi_server);
    $ip            = trim($ip);
    $status_server = in_array($status_server, ['HIDUP', 'MATI']) ? $status_server : 'HIDUP';

    if ($ind === '')           $errors[] = 'IND wajib diisi.';
    if ($fungsi_server === '') $errors[] = 'Fungsi Server wajib diisi.';
    if ($ip === '')            $errors[] = 'IP wajib diisi.';

    // Handle upload gambar baru
    $gambar_filename = $server['gambar'];
    $upload_dir      = __DIR__ . '/../uploads/server_images/';

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
            $new_filename = 'server_' . time() . '_' . uniqid() . '.jpg';
            if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_filename)) {
                if ($server['gambar'] && file_exists($upload_dir . $server['gambar'])) {
                    unlink($upload_dir . $server['gambar']);
                }
                $gambar_filename = $new_filename;
            } else {
                $errors[] = 'Gagal menyimpan gambar ke server.';
            }
        }
    }

    // Handle hapus gambar
    if (isset($_POST['hapus_gambar']) && $_POST['hapus_gambar'] === '1' && empty($_FILES['gambar']['name'])) {
        if ($server['gambar'] && file_exists($upload_dir . $server['gambar'])) {
            unlink($upload_dir . $server['gambar']);
        }
        $gambar_filename = null;
    }

    if (!$errors) {
        $stmt = $pdo->prepare("
            UPDATE data_servers SET
                ind = :ind, fungsi_server = :fungsi_server, ip = :ip, detail = :detail,
                merk = :merk, type = :type, system_operasi = :system_operasi,
                processor_merk = :processor_merk, processor_type = :processor_type,
                processor_kecepatan = :processor_kecepatan, processor_keping = :processor_keping,
                processor_core = :processor_core, ram_jenis = :ram_jenis,
                ram_kapasitas = :ram_kapasitas, ram_jumlah_keping = :ram_jumlah_keping,
                storage_jenis = :storage_jenis, storage_jumlah = :storage_jumlah,
                storage_kapasitas_total = :storage_kapasitas_total,
                keterangan_tambahan = :keterangan_tambahan, server_fisik = :server_fisik,
                gambar = :gambar, status_server = :status_server
            WHERE id = :id
        ");

        $stmt->execute([
            ':id'                     => $server_id,
            ':ind'                    => $ind,
            ':fungsi_server'          => $fungsi_server,
            ':ip'                     => $ip,
            ':detail'                 => trim($detail),
            ':merk'                   => trim($merk),
            ':type'                   => trim($type),
            ':system_operasi'         => trim($system_operasi),
            ':processor_merk'         => trim($processor_merk),
            ':processor_type'         => trim($processor_type),
            ':processor_kecepatan'    => trim($processor_kecepatan),
            ':processor_keping'       => (int)$processor_keping,
            ':processor_core'         => (int)$processor_core,
            ':ram_jenis'              => trim($ram_jenis),
            ':ram_kapasitas'          => trim($ram_kapasitas),
            ':ram_jumlah_keping'      => (int)$ram_jumlah_keping,
            ':storage_jenis'          => trim($storage_jenis),
            ':storage_jumlah'         => (int)$storage_jumlah,
            ':storage_kapasitas_total'=> trim($storage_kapasitas_total),
            ':keterangan_tambahan'    => trim($keterangan_tambahan),
            ':server_fisik'           => trim($server_fisik),
            ':gambar'                 => $gambar_filename,
            ':status_server'          => $status_server,
        ]);

        header('Location: ' . base_url('pages/data-server-detail.php?id=' . $server_id . '&updated=1'));
        exit;
    }
}

$page_title   = "Edit Data Server";
$active_menu  = "data-server";
$content_file = __DIR__ . "/data-server-edit.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;