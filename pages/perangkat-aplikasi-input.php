<?php
/**
 * Perangkat Aplikasi - Input Page (Controller)
 * Path: pages/perangkat-aplikasi-input.php
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

// Retain POST values on validation error
$jenis_perangkat          = $_POST['jenis_perangkat']          ?? '';
$url                      = $_POST['url']                      ?? '';
$ip                       = $_POST['ip']                       ?? '';
$brand                    = $_POST['brand']                    ?? '';
$type                     = $_POST['type']                     ?? '';
$server                   = $_POST['server']                   ?? '';
$os                       = $_POST['os']                       ?? '';
$lokasi                   = $_POST['lokasi']                   ?? '';
$bidang                   = $_POST['bidang']                   ?? '';
$msb_sub_bidang           = $_POST['msb_sub_bidang']           ?? '';
$firmware_patch           = $_POST['firmware_patch']           ?? '⌛';
$network_device_patch     = $_POST['network_device_patch']     ?? '⌛';
$application_patch        = $_POST['application_patch']        ?? '⌛';
$os_patch                 = $_POST['os_patch']                 ?? '⌛';
$library_dependency_patch = $_POST['library_dependency_patch'] ?? '⌛';
$database_patch           = $_POST['database_patch']           ?? '⌛';
$pemilik_aset             = $_POST['pemilik_aset']             ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_perangkat_aplikasi') {
    $jenis_perangkat          = trim($jenis_perangkat);
    $url                      = trim($url);
    $ip                       = trim($ip);
    $brand                    = trim($brand);
    $type                     = trim($type);
    $server                   = trim($server);
    $os                       = trim($os);
    $lokasi                   = trim($lokasi);
    $bidang                   = trim($bidang);
    $msb_sub_bidang           = trim($msb_sub_bidang);
    $firmware_patch           = trim($firmware_patch);
    $network_device_patch     = trim($network_device_patch);
    $application_patch        = trim($application_patch);
    $os_patch                 = trim($os_patch);
    $library_dependency_patch = trim($library_dependency_patch);
    $database_patch           = trim($database_patch);
    $pemilik_aset             = trim($pemilik_aset);

    if ($jenis_perangkat === '') $errors[] = 'Jenis Perangkat wajib dipilih.';

    $valid_patches = array_keys($PATCH_OPTIONS);
    if (!in_array($firmware_patch,           $valid_patches, true)) $errors[] = 'Firmware Patch tidak valid.';
    if (!in_array($network_device_patch,     $valid_patches, true)) $errors[] = 'Network Device Patch tidak valid.';
    if (!in_array($application_patch,        $valid_patches, true)) $errors[] = 'Application Patch tidak valid.';
    if (!in_array($os_patch,                 $valid_patches, true)) $errors[] = 'OS Patch tidak valid.';
    if (!in_array($library_dependency_patch, $valid_patches, true)) $errors[] = 'Library/Dependency Patch tidak valid.';
    if (!in_array($database_patch,           $valid_patches, true)) $errors[] = 'Database Patch tidak valid.';

    if (!$errors) {
        $stmt = $pdo->prepare("
            INSERT INTO perangkat_aplikasi
                (jenis_perangkat, url, ip, brand, type, server, os, lokasi, bidang,
                 msb_sub_bidang, firmware_patch, network_device_patch,
                 application_patch, os_patch, library_dependency_patch, database_patch,
                 pemilik_aset, created_by)
            VALUES
                (:jenis_perangkat, :url, :ip, :brand, :type, :server, :os, :lokasi, :bidang,
                 :msb_sub_bidang, :firmware_patch, :network_device_patch,
                 :application_patch, :os_patch, :library_dependency_patch, :database_patch,
                 :pemilik_aset, :created_by)
        ");
        $stmt->execute([
            ':jenis_perangkat'          => $jenis_perangkat,
            ':url'                      => $url,
            ':ip'                       => $ip,
            ':brand'                    => $brand,
            ':type'                     => $type,
            ':server'                   => $server,
            ':os'                       => $os,
            ':lokasi'                   => $lokasi,
            ':bidang'                   => $bidang,
            ':msb_sub_bidang'           => $msb_sub_bidang,
            ':firmware_patch'           => $firmware_patch,
            ':network_device_patch'     => $network_device_patch,
            ':application_patch'        => $application_patch,
            ':os_patch'                 => $os_patch,
            ':library_dependency_patch' => $library_dependency_patch,
            ':database_patch'           => $database_patch,
            ':pemilik_aset'             => $pemilik_aset,
            ':created_by'               => $_SESSION['user_id'] ?? null,
        ]);

        header('Location: ' . base_url('pages/perangkat-aplikasi.php?added=1'));
        exit;
    }
}

$page_title   = "Input Perangkat Aplikasi";
$active_menu  = "perangkat-aplikasi";
$content_file = __DIR__ . "/perangkat-aplikasi-input.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;