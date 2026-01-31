<?php
/**
 * Data Server - Input Page (Controller)
 * Path: pages/data-server-input.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

$pdo = db();
$errors = [];

// Default values (untuk retain input saat error)
$ind = $_POST['ind'] ?? '';
$fungsi_server = $_POST['fungsi_server'] ?? '';
$ip = $_POST['ip'] ?? '';
$detail = $_POST['detail'] ?? '';
$merk = $_POST['merk'] ?? '';
$type = $_POST['type'] ?? '';
$system_operasi = $_POST['system_operasi'] ?? '';
$processor_merk = $_POST['processor_merk'] ?? '';
$processor_type = $_POST['processor_type'] ?? '';
$processor_kecepatan = $_POST['processor_kecepatan'] ?? '';
$processor_keping = $_POST['processor_keping'] ?? '';
$processor_core = $_POST['processor_core'] ?? '';
$ram_jenis = $_POST['ram_jenis'] ?? '';
$ram_kapasitas = $_POST['ram_kapasitas'] ?? '';
$ram_jumlah_keping = $_POST['ram_jumlah_keping'] ?? '';
$storage_jenis = $_POST['storage_jenis'] ?? '';
$storage_jumlah = $_POST['storage_jumlah'] ?? '';
$storage_kapasitas_total = $_POST['storage_kapasitas_total'] ?? '';
$keterangan_tambahan = $_POST['keterangan_tambahan'] ?? '';
$server_fisik = $_POST['server_fisik'] ?? '';

// Handle POST - Add Server
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_server') {
    $ind = trim($ind);
    $fungsi_server = trim($fungsi_server);
    $ip = trim($ip);
    $detail = trim($detail);
    $merk = trim($merk);
    $type = trim($type);
    $system_operasi = trim($system_operasi);
    
    $processor_merk = trim($processor_merk);
    $processor_type = trim($processor_type);
    $processor_kecepatan = trim($processor_kecepatan);
    $processor_keping = (int)$processor_keping;
    $processor_core = (int)$processor_core;
    
    $ram_jenis = trim($ram_jenis);
    $ram_kapasitas = trim($ram_kapasitas);
    $ram_jumlah_keping = (int)$ram_jumlah_keping;
    
    $storage_jenis = trim($storage_jenis);
    $storage_jumlah = (int)$storage_jumlah;
    $storage_kapasitas_total = trim($storage_kapasitas_total);
    
    $keterangan_tambahan = trim($keterangan_tambahan);
    $server_fisik = trim($server_fisik);

    // Validation
    if ($ind === '') $errors[] = 'IND wajib diisi.';
    if ($fungsi_server === '') $errors[] = 'Fungsi Server wajib diisi.';
    if ($ip === '') $errors[] = 'IP wajib diisi.';

    if (!$errors) {
        $stmt = $pdo->prepare("
            INSERT INTO data_servers (
                ind, fungsi_server, ip, detail, merk, type, system_operasi,
                processor_merk, processor_type, processor_kecepatan, processor_keping, processor_core,
                ram_jenis, ram_kapasitas, ram_jumlah_keping,
                storage_jenis, storage_jumlah, storage_kapasitas_total,
                keterangan_tambahan, server_fisik, created_by
            ) VALUES (
                :ind, :fungsi_server, :ip, :detail, :merk, :type, :system_operasi,
                :processor_merk, :processor_type, :processor_kecepatan, :processor_keping, :processor_core,
                :ram_jenis, :ram_kapasitas, :ram_jumlah_keping,
                :storage_jenis, :storage_jumlah, :storage_kapasitas_total,
                :keterangan_tambahan, :server_fisik, :created_by
            )
        ");

        $stmt->execute([
            ':ind' => $ind,
            ':fungsi_server' => $fungsi_server,
            ':ip' => $ip,
            ':detail' => $detail,
            ':merk' => $merk,
            ':type' => $type,
            ':system_operasi' => $system_operasi,
            ':processor_merk' => $processor_merk,
            ':processor_type' => $processor_type,
            ':processor_kecepatan' => $processor_kecepatan,
            ':processor_keping' => $processor_keping,
            ':processor_core' => $processor_core,
            ':ram_jenis' => $ram_jenis,
            ':ram_kapasitas' => $ram_kapasitas,
            ':ram_jumlah_keping' => $ram_jumlah_keping,
            ':storage_jenis' => $storage_jenis,
            ':storage_jumlah' => $storage_jumlah,
            ':storage_kapasitas_total' => $storage_kapasitas_total,
            ':keterangan_tambahan' => $keterangan_tambahan,
            ':server_fisik' => $server_fisik,
            ':created_by' => $_SESSION['user_id'] ?? null,
        ]);

        header('Location: ' . base_url('pages/data-server.php?added=1'));
        exit;
    }
}

// Variables for layout
$page_title = "Input Data Server";
$active_menu = "data-server";
$content_file = __DIR__ . "/data-server-input.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;