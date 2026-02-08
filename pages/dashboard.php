<?php
/**
 * Dashboard Page (Controller)
 * Path: pages/dashboard.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

$pdo = db();

$error_message = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'no_access') {
        $error_message = '🔒 Anda tidak memiliki akses ke halaman tersebut.';
    }
}

// Load data dari database (DYNAMIC)
$PIC_IT_OPTIONS = $pdo->query("SELECT name FROM pic_it_support WHERE is_active = 1 ORDER BY sort_order ASC, id ASC")
    ->fetchAll(PDO::FETCH_COLUMN);

$MEETING_ROOMS = $pdo->query("SELECT name FROM meeting_rooms WHERE is_active = 1 ORDER BY sort_order ASC, id ASC")
    ->fetchAll(PDO::FETCH_COLUMN);

$errors = [];
$success = null;

$PELAKSANAAN_OPTIONS = ["ONLINE", "OFFLINE", "HYBRID"];
$STANDBY_OPTIONS = ["STANDBY", "ON CALL"];
$TINDAK_LANJUT_OPTIONS = ["SOLVED", "UNSOLVED"];

// Handle submit (tetap pakai logika kamu)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = trim($_POST['start_date'] ?? '');
    $end_date   = trim($_POST['end_date'] ?? '');
    $pic_acara  = trim($_POST['pic_acara'] ?? '');
    $nama_acara = trim($_POST['nama_acara'] ?? '');
    $pic_it_support = $_POST['pic_it_support'] ?? [];
    $meeting_room    = trim($_POST['meeting_room'] ?? '');
    $pelaksanaan     = trim($_POST['pelaksanaan'] ?? '');
    $standby_status  = trim($_POST['standby_status'] ?? '');
    $kebutuhan_detail = trim($_POST['kebutuhan_detail'] ?? '');
    $tindak_lanjut   = trim($_POST['tindak_lanjut'] ?? '');

    if ($start_date === '') $errors[] = "Start wajib diisi.";
    if ($end_date === '')   $errors[] = "End wajib diisi.";
    if ($start_date !== '' && $end_date !== '' && $end_date < $start_date) $errors[] = "End tidak boleh lebih kecil dari Start.";
    if ($pic_acara === '')  $errors[] = "PIC Acara wajib diisi.";
    if ($nama_acara === '') $errors[] = "Nama Acara wajib diisi.";
    if ($meeting_room === '') $errors[] = "Ruang Rapat wajib dipilih.";
    if ($pelaksanaan === '') $errors[] = "Pelaksanaan wajib dipilih.";
    if ($standby_status === '') $errors[] = "Standby/On Call wajib dipilih.";
    if ($tindak_lanjut === '') $errors[] = "Tindak Lanjut wajib dipilih.";

    if ($meeting_room !== '' && !in_array($meeting_room, $MEETING_ROOMS, true)) $errors[] = "Ruang Rapat tidak valid.";
    if ($pelaksanaan !== '' && !in_array($pelaksanaan, $PELAKSANAAN_OPTIONS, true)) $errors[] = "Pelaksanaan tidak valid.";
    if ($standby_status !== '' && !in_array($standby_status, $STANDBY_OPTIONS, true)) $errors[] = "Standby/On Call tidak valid.";
    if ($tindak_lanjut !== '' && !in_array($tindak_lanjut, $TINDAK_LANJUT_OPTIONS, true)) $errors[] = "Tindak Lanjut tidak valid.";

    // whitelist checkbox
    $pic_it_support = array_values(array_intersect($pic_it_support, $PIC_IT_OPTIONS));
    $pic_it_support_json = json_encode($pic_it_support, JSON_UNESCAPED_UNICODE);

    if (!$errors) {
        $transaction_id = generate_transaction_id($pdo);

        $stmt = $pdo->prepare("
          INSERT INTO schedules (
            transaction_id,
            start_date, end_date,
            pic_acara, nama_acara,
            pic_it_support,
            meeting_room, pelaksanaan, standby_status,
            kebutuhan_detail, tindak_lanjut
          )
          VALUES (
            :transaction_id,
            :start_date, :end_date,
            :pic_acara, :nama_acara,
            :pic_it_support,
            :meeting_room, :pelaksanaan, :standby_status,
            :kebutuhan_detail, :tindak_lanjut
          )
        ");

        $stmt->execute([
          ':transaction_id' => $transaction_id,
          ':start_date' => $start_date,
          ':end_date' => $end_date,
          ':pic_acara' => $pic_acara,
          ':nama_acara' => $nama_acara,
          ':pic_it_support' => $pic_it_support_json,
          ':meeting_room' => $meeting_room,
          ':pelaksanaan' => $pelaksanaan,
          ':standby_status' => $standby_status,
          ':kebutuhan_detail' => $kebutuhan_detail,
          ':tindak_lanjut' => $tindak_lanjut,
        ]);

        $success = "Data berhasil disimpan.";
        $_POST = [];
    }
}

// Load data table
$rows = $pdo->query("SELECT * FROM schedules ORDER BY created_at DESC, id DESC")->fetchAll();

// Variabel untuk layout
$page_title   = "Dashboard";
$active_menu  = "dashboard";
$content_file = __DIR__ . "/dashboard.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;
