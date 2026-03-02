<?php
/**
 * Form Booking Jadwal Zoom Baru
 * Path: pages/booking-zoom-form.php
 */

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';

require_login();
require_permission('booking-zoom');

$pdo = db();

$ZOOM_OPTIONS = [
    'zoomplnuidjty001@gmail.com',
    'zoomplnuidjty002@gmail.com',
    'zoomplnuidjty003@gmail.com',
    'zoomplnuidjty004@gmail.com',
    'zoomplnuidjty005@gmail.com',
    'zoomplnuidjty0066@gmail.com',
    'zoomplnuidjty007@gmail.com',
    'zoomplnuidjty008@gmail.com',
    'zoomplnuidjty009@gmail.com',
];

$UNIT_OPTIONS = [
    'STI',
    'PERENCANAAN',
    'HUKUM',
    'FUNGSIONAL AHLI',
    'KKU',
    'NIAGA',
    'KEUANGAN',
    'DISTRIBUSI',
    'UP2K',
    'SDM',
    'YBM',
    'IKPLN',
    'UID Jawa Tengah & D.I. Yogyakarta',
    'UP3 Kudus',
    'UP3 Surakarta',
    'UP3 Yogyakarta',
    'UP3 Magelang',
    'UP3 Purwokerto',
    'UP3 Tegal',
    'UP3 Semarang',
    'UP3 Salatiga',
    'UP3 Klaten',
    'UP3 Pekalongan',
    'UP3 Cilacap',
    'UP3 Grobogan',
    'UP3 Sukoharjo',
    'UP2D Jateng & DIY'
];

$errors = [];

// Retain input saat error
$start_datetime = $_POST['start_datetime'] ?? '';
$end_datetime   = $_POST['end_datetime']   ?? '';
$zoom_link      = $_POST['zoom_link']      ?? '';
$unit           = $_POST['unit']           ?? '';
$keterangan     = $_POST['keterangan']     ?? '';

// Handle POST - Add Booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_booking') {
    $start_datetime = trim($start_datetime);
    $end_datetime   = trim($end_datetime);
    $zoom_link      = trim($zoom_link);
    $unit           = trim($unit);
    $keterangan     = trim($keterangan);

    if ($start_datetime === '') $errors[] = "Tanggal & jam mulai wajib diisi.";
    if ($end_datetime === '')   $errors[] = "Tanggal & jam selesai wajib diisi.";
    if ($zoom_link === '')      $errors[] = "Link Zoom wajib dipilih.";
    if ($unit === '')           $errors[] = "Unit wajib dipilih.";

    if ($zoom_link !== '' && !in_array($zoom_link, $ZOOM_OPTIONS, true)) {
        $errors[] = "Link Zoom tidak valid.";
    }
    if ($unit !== '' && !in_array($unit, $UNIT_OPTIONS, true)) {
        $errors[] = "Unit tidak valid.";
    }
    if ($start_datetime !== '' && $start_datetime < date('Y-m-d\TH:i')) {
        $errors[] = "Tanggal & jam mulai tidak boleh di masa lampau.";
    }
    if ($start_datetime !== '' && $end_datetime !== '' && $end_datetime <= $start_datetime) {
        $errors[] = "Tanggal & jam selesai harus setelah tanggal & jam mulai.";
    }

    if (!$errors) {
        $start_db     = date('Y-m-d H:i:s', strtotime($start_datetime));
        $end_db       = date('Y-m-d H:i:s', strtotime($end_datetime));
        $booking_date = date('Y-m-d', strtotime($start_datetime));
        $booking_time = date('H:i', strtotime($start_datetime)) . ' - ' . date('H:i', strtotime($end_datetime));

        $stmt = $pdo->prepare("
            INSERT INTO zoom_bookings (
                booking_date, booking_time, start_datetime, end_datetime,
                zoom_link, unit, keterangan, kondisi, created_by
            ) VALUES (
                :booking_date, :booking_time, :start_datetime, :end_datetime,
                :zoom_link, :unit, :keterangan, 'DIPAKAI', :created_by
            )
        ");
        $stmt->execute([
            ':booking_date'   => $booking_date,
            ':booking_time'   => $booking_time,
            ':start_datetime' => $start_db,
            ':end_datetime'   => $end_db,
            ':zoom_link'      => $zoom_link,
            ':unit'           => $unit,
            ':keterangan'     => $keterangan,
            ':created_by'     => $_SESSION['user_id'] ?? null,
        ]);

        header('Location: ' . base_url('pages/booking-zoom.php?added=1'));
        exit;
    }
}

$page_title   = "Booking Zoom Baru";
$active_menu  = "booking-zoom";
$content_file = __DIR__ . "/booking-zoom-form.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;