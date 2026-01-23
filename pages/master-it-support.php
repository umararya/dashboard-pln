<?php
/**
 * Master IT Support Management (Controller)
 * Path: pages/master-it-support.php
 */

session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$pdo = db();
$success = '';
$errors = [];

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_pic') {
        $name = trim($_POST['pic_name'] ?? '');
        if ($name === '') {
            $errors[] = 'Nama PIC IT Support wajib diisi.';
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM pic_it_support WHERE name = :name");
            $stmt->execute([':name' => $name]);
            if ((int)$stmt->fetchColumn() > 0) {
                $errors[] = 'Nama PIC IT Support sudah ada.';
            } else {
                $max_order = (int)$pdo->query("SELECT COALESCE(MAX(sort_order), 0) FROM pic_it_support")->fetchColumn();
                $stmt = $pdo->prepare("INSERT INTO pic_it_support (name, sort_order) VALUES (:name, :sort)");
                $stmt->execute([':name' => $name, ':sort' => $max_order + 1]);
                $success = "PIC IT Support '$name' berhasil ditambahkan.";
            }
        }
    }

    if ($action === 'delete_pic') {
        $id = (int)($_POST['pic_id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM pic_it_support WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $success = 'PIC IT Support berhasil dihapus.';
        }
    }

    if ($action === 'edit_pic') {
        $id = (int)($_POST['pic_id'] ?? 0);
        $name = trim($_POST['pic_name'] ?? '');
        if ($id > 0 && $name !== '') {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM pic_it_support WHERE name = :name AND id != :id");
            $stmt->execute([':name' => $name, ':id' => $id]);
            if ((int)$stmt->fetchColumn() > 0) {
                $errors[] = 'Nama PIC IT Support sudah ada.';
            } else {
                $stmt = $pdo->prepare("UPDATE pic_it_support SET name = :name WHERE id = :id");
                $stmt->execute([':name' => $name, ':id' => $id]);
                $success = 'PIC IT Support berhasil diupdate.';
            }
        }
    }
}

// Load data
$pic_list = $pdo->query("SELECT * FROM pic_it_support ORDER BY sort_order ASC, id ASC")->fetchAll();

// Variables for layout.php
$page_title   = "Master IT Support";
$active_menu  = "master-it-support";
$content_file = __DIR__ . "/master-it-support.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;
