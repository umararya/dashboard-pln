<?php
/**
 * Master Perangkat Aplikasi (Controller)
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
    'jenis'  => ['table' => 'master_pa_jenis_perangkat', 'label' => 'Jenis Perangkat'],
    'brand'  => ['table' => 'master_pa_brand',           'label' => 'Brand'],
    'lokasi' => ['table' => 'master_pa_lokasi',          'label' => 'Lokasi'],
    'bidang' => ['table' => 'master_pa_bidang',          'label' => 'Bidang'],
    'msb'    => ['table' => 'master_pa_msb',             'label' => 'MSB / Sub Bidang'],
];

// Tab aktif
$active_tab = $_GET['tab'] ?? 'jenis';
if (!array_key_exists($active_tab, $MASTER_MAP)) $active_tab = 'jenis';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action    = $_POST['action']   ?? '';
    $tab_key   = $_POST['tab_key']  ?? '';
    $item_id   = (int)($_POST['item_id'] ?? 0);
    $item_name = trim($_POST['item_name'] ?? '');

    if (!array_key_exists($tab_key, $MASTER_MAP)) {
        $errors[] = 'Tab tidak valid.';
    } else {
        $tbl   = $MASTER_MAP[$tab_key]['table'];
        $label = $MASTER_MAP[$tab_key]['label'];

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

        if ($action === 'delete_item') {
            if ($item_id > 0) {
                $stmt = $pdo->prepare("DELETE FROM `$tbl` WHERE id = :id");
                $stmt->execute([':id' => $item_id]);
                $success = "$label berhasil dihapus.";
            }
        }

        if ($action === 'toggle_item') {
            if ($item_id > 0) {
                $stmt = $pdo->prepare("UPDATE `$tbl` SET is_active = NOT is_active WHERE id = :id");
                $stmt->execute([':id' => $item_id]);
                $success = "Status $label berhasil diubah.";
            }
        }

        $active_tab = $tab_key;
        if (!$errors) {
            header('Location: ' . base_url('pages/master-perangkat-aplikasi.php?tab=' . $active_tab . '&ok=1'));
            exit;
        }
    }
}

// ── PAGINATION PER TAB ──────────────────────────────────────────
$per_page = 10;
$page     = max(1, (int)($_GET['page'] ?? 1));

// When switching tabs, reset to page 1
$data        = [];
$tab_totals  = [];
$tab_pages   = [];

foreach ($MASTER_MAP as $key => $cfg) {
    $tbl = $cfg['table'];

    // Count for every tab (needed for badge display)
    $tab_totals[$key] = (int)$pdo->query("SELECT COUNT(*) FROM `$tbl`")->fetchColumn();
    $tab_pages[$key]  = (int)ceil($tab_totals[$key] / $per_page);

    // Load paged data only for active tab
    if ($key === $active_tab) {
        $p      = $page;
        $offset = ($p - 1) * $per_page;

        $stmt = $pdo->prepare("SELECT * FROM `$tbl` ORDER BY sort_order ASC, id ASC LIMIT :lim OFFSET :off");
        $stmt->bindValue(':lim', $per_page, PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset,   PDO::PARAM_INT);
        $stmt->execute();
        $data[$key] = $stmt->fetchAll();
    } else {
        $data[$key] = [];
    }
}

$total_count = $tab_totals[$active_tab];
$total_pages = $tab_pages[$active_tab];
$offset      = ($page - 1) * $per_page;

$page_title   = 'Master Perangkat Aplikasi';
$active_menu  = 'master-perangkat-aplikasi';
$content_file = __DIR__ . '/master-perangkat-aplikasi.content.php';

require_once __DIR__ . '/../includes/layout.php';
exit;