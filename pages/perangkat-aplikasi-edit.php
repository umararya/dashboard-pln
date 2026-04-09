<?php
/**
 * Perangkat Aplikasi - Edit Page (Controller)
 * Path: pages/perangkat-aplikasi-edit.php
 */

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';
require_once __DIR__ . '/../includes/perangkat-aplikasi-options.php';

require_login();
require_permission('perangkat-aplikasi');

$pdo    = db();
$errors = [];
$id     = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: ' . base_url('pages/perangkat-aplikasi.php'));
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM perangkat_aplikasi WHERE id = :id");
$stmt->execute([':id' => $id]);
$record = $stmt->fetch();

if (!$record) {
    header('Location: ' . base_url('pages/perangkat-aplikasi.php'));
    exit;
}

// Seed form values
$nama_perangkat      = $_POST['nama_perangkat']      ?? $record['nama_perangkat'];
$url                 = $_POST['url']                 ?? $record['url'];
$ip                  = $_POST['ip']                  ?? $record['ip'];
$brand               = $_POST['brand']               ?? $record['brand'];
$type                = $_POST['type']                ?? $record['type'];
$server              = $_POST['server']              ?? $record['server'];
$os                  = $_POST['os']                  ?? $record['os'];
$lokasi              = $_POST['lokasi']              ?? $record['lokasi'];
$bidang              = $_POST['bidang']              ?? $record['bidang'];
$msb_sub_bidang      = $_POST['msb_sub_bidang']      ?? $record['msb_sub_bidang'];
$firmware_patch      = $_POST['firmware_patch']      ?? $record['firmware_patch'];
$database_patch      = $_POST['database_patch']      ?? $record['database_patch'];
$network_device_patch = $_POST['network_device_patch'] ?? $record['network_device_patch'];
$pemilik_aset        = $_POST['pemilik_aset']        ?? $record['pemilik_aset'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit_perangkat_aplikasi') {
    $nama_perangkat      = trim($nama_perangkat);
    $url                 = trim($url);
    $ip                  = trim($ip);
    $brand               = trim($brand);
    $type                = trim($type);
    $server              = trim($server);
    $os                  = trim($os);
    $lokasi              = trim($lokasi);
    $bidang              = trim($bidang);
    $msb_sub_bidang      = trim($msb_sub_bidang);
    $firmware_patch      = trim($firmware_patch);
    $database_patch      = trim($database_patch);
    $network_device_patch = trim($network_device_patch);
    $pemilik_aset        = trim($pemilik_aset);

    if ($nama_perangkat === '') $errors[] = 'Nama Perangkat wajib dipilih.';
    if (!in_array($firmware_patch,      array_keys($PATCH_OPTIONS), true)) $errors[] = 'Firmware Patch tidak valid.';
    if (!in_array($database_patch,      array_keys($PATCH_OPTIONS), true)) $errors[] = 'Database Patch tidak valid.';
    if (!in_array($network_device_patch, array_keys($PATCH_OPTIONS), true)) $errors[] = 'Network Device Patch tidak valid.';

    if (!$errors) {
        $stmt = $pdo->prepare("
            UPDATE perangkat_aplikasi SET
                nama_perangkat      = :nama_perangkat,
                url                 = :url,
                ip                  = :ip,
                brand               = :brand,
                type                = :type,
                server              = :server,
                os                  = :os,
                lokasi              = :lokasi,
                bidang              = :bidang,
                msb_sub_bidang      = :msb_sub_bidang,
                firmware_patch      = :firmware_patch,
                database_patch      = :database_patch,
                network_device_patch = :network_device_patch,
                pemilik_aset        = :pemilik_aset
            WHERE id = :id
        ");
        $stmt->execute([
            ':nama_perangkat'      => $nama_perangkat,
            ':url'                 => $url,
            ':ip'                  => $ip,
            ':brand'               => $brand,
            ':type'                => $type,
            ':server'              => $server,
            ':os'                  => $os,
            ':lokasi'              => $lokasi,
            ':bidang'              => $bidang,
            ':msb_sub_bidang'      => $msb_sub_bidang,
            ':firmware_patch'      => $firmware_patch,
            ':database_patch'      => $database_patch,
            ':network_device_patch' => $network_device_patch,
            ':pemilik_aset'        => $pemilik_aset,
            ':id'                  => $id,
        ]);

        header('Location: ' . base_url('pages/perangkat-aplikasi.php?updated=1'));
        exit;
    }
}

$page_title   = "Edit Perangkat Aplikasi";
$active_menu  = "perangkat-aplikasi";
$content_file = __DIR__ . "/perangkat-aplikasi-edit.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;
