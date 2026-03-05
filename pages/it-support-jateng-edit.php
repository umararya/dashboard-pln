<?php
/**
 * IT Support Jateng - Edit Page (Controller)
 * Path: pages/it-support-jateng-edit.php
 */

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';

require_login();
require_permission('it-support-jateng');

$pdo    = db();
$errors = [];
$id     = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: ' . base_url('pages/it-support-jateng.php'));
    exit;
}

// Load record
$stmt = $pdo->prepare("SELECT * FROM it_support_jateng WHERE id = :id");
$stmt->execute([':id' => $id]);
$person = $stmt->fetch();

if (!$person) {
    header('Location: ' . base_url('pages/it-support-jateng.php'));
    exit;
}

// Seed form values (retain POST on error, else use DB values)
$nama       = $_POST['nama']       ?? $person['nama'];
$email      = $_POST['email']      ?? $person['email'];
$no_hp      = $_POST['no_hp']      ?? $person['no_hp'];
$penempatan = $_POST['penempatan'] ?? $person['penempatan'];
$ops_sti    = $_POST['ops_sti']    ?? $person['ops_sti'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit_person') {
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
            UPDATE it_support_jateng
            SET nama = :nama, email = :email, no_hp = :no_hp,
                penempatan = :penempatan, ops_sti = :ops_sti
            WHERE id = :id
        ");
        $stmt->execute([
            ':nama'       => $nama,
            ':email'      => $email,
            ':no_hp'      => $no_hp,
            ':penempatan' => $penempatan,
            ':ops_sti'    => $ops_sti,
            ':id'         => $id,
        ]);

        header('Location: ' . base_url('pages/it-support-jateng.php?updated=1'));
        exit;
    }
}

$page_title   = "Edit IT Support Jateng";
$active_menu  = "it-support-jateng";
$content_file = __DIR__ . "/it-support-jateng-edit.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;