<?php
/**
 * IT Support Jateng - Input Page (Controller)
 * Path: pages/it-support-jateng-input.php
 */

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';

require_login();
require_permission('it-support-jateng');

$pdo    = db();
$errors = [];

// Retain POST values on validation error
$nama        = $_POST['nama']        ?? '';
$email       = $_POST['email']       ?? '';
$no_hp       = $_POST['no_hp']       ?? '';
$penempatan  = $_POST['penempatan']  ?? '';
$ops_sti     = $_POST['ops_sti']     ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_person') {
    $nama       = trim($nama);
    $email      = trim($email);
    $no_hp      = trim($no_hp);
    $penempatan = trim($penempatan);
    $ops_sti    = trim($ops_sti);

    if ($nama === '') $errors[] = 'Nama wajib diisi.';
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare("
            INSERT INTO it_support_jateng (nama, email, no_hp, penempatan, ops_sti)
            VALUES (:nama, :email, :no_hp, :penempatan, :ops_sti)
        ");
        $stmt->execute([
            ':nama'       => $nama,
            ':email'      => $email,
            ':no_hp'      => $no_hp,
            ':penempatan' => $penempatan,
            ':ops_sti'    => $ops_sti,
        ]);

        header('Location: ' . base_url('pages/it-support-jateng.php?added=1'));
        exit;
    }
}

$page_title   = "Input IT Support Jateng";
$active_menu  = "it-support-jateng";
$content_file = __DIR__ . "/it-support-jateng-input.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;