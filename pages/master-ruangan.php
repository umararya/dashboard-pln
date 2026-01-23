<?php
/**
 * Master Ruangan Management (Controller)
 * Path: pages/master-ruangan.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

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

    if ($action === 'add_room') {
        $name = trim($_POST['room_name'] ?? '');
        if ($name === '') {
            $errors[] = 'Nama Ruang Rapat wajib diisi.';
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM meeting_rooms WHERE name = :name");
            $stmt->execute([':name' => $name]);
            if ((int)$stmt->fetchColumn() > 0) {
                $errors[] = 'Nama Ruang Rapat sudah ada.';
            } else {
                $max_order = (int)$pdo->query("SELECT COALESCE(MAX(sort_order), 0) FROM meeting_rooms")->fetchColumn();
                $stmt = $pdo->prepare("INSERT INTO meeting_rooms (name, sort_order) VALUES (:name, :sort)");
                $stmt->execute([':name' => $name, ':sort' => $max_order + 1]);
                $success = "Ruang Rapat '$name' berhasil ditambahkan.";
            }
        }
    }

    if ($action === 'delete_room') {
        $id = (int)($_POST['room_id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM meeting_rooms WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $success = 'Ruang Rapat berhasil dihapus.';
        }
    }

    if ($action === 'edit_room') {
        $id = (int)($_POST['room_id'] ?? 0);
        $name = trim($_POST['room_name'] ?? '');
        if ($id > 0 && $name !== '') {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM meeting_rooms WHERE name = :name AND id != :id");
            $stmt->execute([':name' => $name, ':id' => $id]);
            if ((int)$stmt->fetchColumn() > 0) {
                $errors[] = 'Nama Ruang Rapat sudah ada.';
            } else {
                $stmt = $pdo->prepare("UPDATE meeting_rooms SET name = :name WHERE id = :id");
                $stmt->execute([':name' => $name, ':id' => $id]);
                $success = 'Ruang Rapat berhasil diupdate.';
            }
        }
    }
}

// Load data
$room_list = $pdo->query("SELECT * FROM meeting_rooms ORDER BY sort_order ASC, id ASC")->fetchAll();

// Variabel untuk layout
$page_title   = "Master Ruangan";
$active_menu  = "master-ruangan";
$content_file = __DIR__ . "/master-ruangan.content.php";

// Render via layout
require_once __DIR__ . '/../includes/layout.php';
exit;
