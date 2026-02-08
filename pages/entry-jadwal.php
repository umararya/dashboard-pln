<?php
/**
 * Entry Jadwal (Controller)
 * Path: pages/entry-jadwal.php
 */

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';

require_login();
require_permission('data-jadwal');

$pdo = db();

$PIC_IT_OPTIONS = $pdo->query("SELECT name FROM pic_it_support WHERE is_active = 1 ORDER BY sort_order ASC, id ASC")
    ->fetchAll(PDO::FETCH_COLUMN);

$MEETING_ROOMS = $pdo->query("SELECT name FROM meeting_rooms WHERE is_active = 1 ORDER BY sort_order ASC, id ASC")
    ->fetchAll(PDO::FETCH_COLUMN);

$PELAKSANAAN_OPTIONS = ["ONLINE", "OFFLINE", "HYBRID"];
$STANDBY_OPTIONS = ["STANDBY", "ON CALL"];
$TINDAK_LANJUT_OPTIONS = ["SOLVED", "UNSOLVED"];

$errors = [];
$success = null;

// default values (biar input nempel saat error)
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$pic_acara = $_POST['pic_acara'] ?? '';
$nama_acara = $_POST['nama_acara'] ?? '';
$meeting_room = $_POST['meeting_room'] ?? '';
$pelaksanaan = $_POST['pelaksanaan'] ?? '';
$standby_status = $_POST['standby_status'] ?? '';
$kebutuhan_detail = $_POST['kebutuhan_detail'] ?? '';
$tindak_lanjut = $_POST['tindak_lanjut'] ?? '';
$selected_pic_it = $_POST['pic_it_support'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = trim($start_date);
    $end_date   = trim($end_date);
    $pic_acara  = trim($pic_acara);
    $nama_acara = trim($nama_acara);
    $meeting_room = trim($meeting_room);
    $pelaksanaan = trim($pelaksanaan);
    $standby_status = trim($standby_status);
    $kebutuhan_detail = trim($kebutuhan_detail);
    $tindak_lanjut = trim($tindak_lanjut);

    if ($start_date === '') $errors[] = "Start wajib diisi.";
    if ($end_date === '') $errors[] = "End wajib diisi.";
    if ($start_date !== '' && $end_date !== '' && $end_date < $start_date) $errors[] = "End tidak boleh lebih kecil dari Start.";
    if ($pic_acara === '') $errors[] = "PIC Acara wajib diisi.";
    if ($nama_acara === '') $errors[] = "Nama Acara wajib diisi.";
    if ($meeting_room === '') $errors[] = "Ruang Rapat wajib dipilih.";
    if ($pelaksanaan === '') $errors[] = "Pelaksanaan wajib dipilih.";
    if ($standby_status === '') $errors[] = "Standby/On Call wajib dipilih.";
    if ($tindak_lanjut === '') $errors[] = "Tindak Lanjut wajib dipilih.";

    if ($meeting_room !== '' && !in_array($meeting_room, $MEETING_ROOMS, true)) $errors[] = "Ruang Rapat tidak valid.";
    if ($pelaksanaan !== '' && !in_array($pelaksanaan, $PELAKSANAAN_OPTIONS, true)) $errors[] = "Pelaksanaan tidak valid.";
    if ($standby_status !== '' && !in_array($standby_status, $STANDBY_OPTIONS, true)) $errors[] = "Standby/On Call tidak valid.";
    if ($tindak_lanjut !== '' && !in_array($tindak_lanjut, $TINDAK_LANJUT_OPTIONS, true)) $errors[] = "Tindak Lanjut tidak valid.";

    $selected_pic_it = array_values(array_intersect((array)$selected_pic_it, $PIC_IT_OPTIONS));
    $pic_it_support_json = json_encode($selected_pic_it, JSON_UNESCAPED_UNICODE);

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

        header('Location: ' . base_url('pages/dashboard.php?added=1'));
        exit;
    }
}

$page_title   = "Entry Jadwal";
$active_menu  = "dashboard";
$content_file = __DIR__ . "/entry-jadwal.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;
    