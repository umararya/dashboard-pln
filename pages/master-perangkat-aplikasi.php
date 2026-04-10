<?php
/**
 * Master Perangkat Aplikasi (Controller)
 * Mengelola 5 tabel master: Nama Perangkat, Brand, Lokasi, Bidang, MSB/Sub Bidang
 * Path: pages/master-perangkat-aplikasi.php
 */

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';

require_admin();

$pdo     = db();
$success = '';
$errors  = [];

// ── Mapping: tab key → table name ──────────────────────────────
$MASTER_MAP = [
    'nama'   => ['table' => 'master_pa_nama_perangkat', 'label' => 'Nama Perangkat'],
    'brand'  => ['table' => 'master_pa_brand',          'label' => 'Brand'],
    'lokasi' => ['table' => 'master_pa_lokasi',         'label' => 'Lokasi'],
    'bidang' => ['table' => 'master_pa_bidang',         'label' => 'Bidang'],
    'msb'    => ['table' => 'master_pa_msb',            'label' => 'MSB / Sub Bidang'],
];

// ── POST Handler ────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action   = $_POST['action']   ?? '';
    $tab_key  = $_POST['tab_key']  ?? '';
    $item_id  = (int)($_POST['item_id'] ?? 0);
    $item_name = trim($_POST['item_name'] ?? '');

    if (!array_key_exists($tab_key, $MASTER_MAP)) {
        $errors[] = 'Tab tidak valid.';
    } else {
        $tbl   = $MASTER_MAP[$tab_key]['table'];
        $label = $MASTER_MAP[$tab_key]['label'];

        // ADD
        if ($action === 'add_item') {
            if ($item_name === '') {
                $errors[] = "Nama $label wajib diisi.";
            } else {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM `$tbl` WHERE name = :name");
                $stmt->execute([':name' => $item_name]);
                if ((int)$stmt->fetchColumn() > 0) {
                    $errors[] = "'$item_name' sudah ada di daftar $label.";
                } else {
                    $max = (int)$pdo->query("SELECT COALESCE(MAX(sort_order),0) FROM `$tbl`")->fetchColumn();
                    $stmt = $pdo->prepare("INSERT INTO `$tbl` (name, sort_order) VALUES (:name, :sort)");
                    $stmt->execute([':name' => $item_name, ':sort' => $max + 1]);
                    $success = "$label '$item_name' berhasil ditambahkan.";
                }
            }
        }

        // EDIT
        if ($action === 'edit_item') {
            if ($item_id > 0 && $item_name !== '') {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM `$tbl` WHERE name = :name AND id != :id");
                $stmt->execute([':name' => $item_name, ':id' => $item_id]);
                if ((int)$stmt->fetchColumn() > 0) {
                    $errors[] = "'$item_name' sudah ada di daftar $label.";
                } else {
                    $stmt = $pdo->prepare("UPDATE `$tbl` SET name = :name WHERE id = :id");
                    $stmt->execute([':name' => $item_name, ':id' => $item_id]);
                    $success = "$label berhasil diupdate.";
                }
            }
        }

        // DELETE
        if ($action === 'delete_item') {
            if ($item_id > 0) {
                $stmt = $pdo->prepare("DELETE FROM `$tbl` WHERE id = :id");
                $stmt->execute([':id' => $item_id]);
                $success = "$label berhasil dihapus.";
            }
        }

        // TOGGLE AKTIF
        if ($action === 'toggle_item') {
            if ($item_id > 0) {
                $stmt = $pdo->prepare("UPDATE `$tbl` SET is_active = NOT is_active WHERE id = :id");
                $stmt->execute([':id' => $item_id]);
                $success = "Status $label berhasil diubah.";
            }
        }
    }

    // Tetap di tab yang sama setelah redirect
    $active_tab = $tab_key ?: 'nama';
    if (!$errors) {
        header('Location: ' . base_url('pages/master-perangkat-aplikasi.php?tab=' . $active_tab . '&ok=1'));
        exit;
    }
}

// ── Load semua data ─────────────────────────────────────────────
$data = [];
foreach ($MASTER_MAP as $key => $cfg) {
    $tbl        = $cfg['table'];
    $data[$key] = $pdo->query(
        "SELECT * FROM `$tbl` ORDER BY sort_order ASC, id ASC"
    )->fetchAll();
}

// Tab aktif
$active_tab = $_GET['tab'] ?? 'nama';
if (!array_key_exists($active_tab, $MASTER_MAP)) $active_tab = 'nama';

$page_title   = 'Master Perangkat Aplikasi';
$active_menu  = 'master-perangkat-aplikasi';
$content_file = __DIR__ . '/master-perangkat-aplikasi.content.php';

require_once __DIR__ . '/../includes/layout.php';
exit;