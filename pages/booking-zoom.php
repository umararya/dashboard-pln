<?php
/**
 * Booking Jadwal Zoom (Controller)
 * Path: pages/booking-zoom.php
 */

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

$pdo = db();

// Zoom options (temporary - will be updated with real zoom links later)
$ZOOM_OPTIONS = [
    'Zoom 1',
    'Zoom 2',
    'Zoom 3',
    'Zoom 4',
    'Zoom 5',
    'Zoom 6',
    'Zoom 7',
    'Zoom 8',
    'Zoom 9',
    'Zoom 10',
];

$KONDISI_OPTIONS = ['KOSONG', 'DIPAKAI'];

$errors = [];
$success = null;

// Default values (untuk retain input saat error)
$booking_date = $_POST['booking_date'] ?? '';
$booking_time = $_POST['booking_time'] ?? '';
$zoom_link = $_POST['zoom_link'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';

// Handle POST - Add Booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_booking') {
    $booking_date = trim($booking_date);
    $booking_time = trim($booking_time);
    $zoom_link = trim($zoom_link);
    $keterangan = trim($keterangan);

    // Validasi
    if ($booking_date === '') $errors[] = "Tanggal wajib diisi.";
    if ($booking_time === '') $errors[] = "Jam wajib diisi.";
    if ($zoom_link === '') $errors[] = "Link Zoom wajib dipilih.";
    if ($zoom_link !== '' && !in_array($zoom_link, $ZOOM_OPTIONS, true)) {
        $errors[] = "Link Zoom tidak valid.";
    }

    // Validasi tanggal tidak boleh masa lampau
    if ($booking_date !== '' && $booking_date < date('Y-m-d')) {
        $errors[] = "Tanggal booking tidak boleh di masa lampau.";
    }

    if (!$errors) {
        $stmt = $pdo->prepare("
            INSERT INTO zoom_bookings (
                booking_date, 
                booking_time, 
                zoom_link, 
                keterangan,
                kondisi,
                created_by
            ) VALUES (
                :booking_date,
                :booking_time,
                :zoom_link,
                :keterangan,
                'DIPAKAI',
                :created_by
            )
        ");

        $stmt->execute([
            ':booking_date' => $booking_date,
            ':booking_time' => $booking_time,
            ':zoom_link' => $zoom_link,
            ':keterangan' => $keterangan,
            ':created_by' => $_SESSION['user_id'] ?? null,
        ]);

        header('Location: ' . base_url('pages/data-zoom.php?added=1'));
        exit;
    }
}

// Handle POST - Update Kondisi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_kondisi') {
    $id = (int)($_POST['booking_id'] ?? 0);
    $new_kondisi = $_POST['kondisi'] ?? '';

    if ($id > 0 && in_array($new_kondisi, $KONDISI_OPTIONS, true)) {
        $stmt = $pdo->prepare("UPDATE zoom_bookings SET kondisi = :kondisi WHERE id = :id");
        $stmt->execute([':kondisi' => $new_kondisi, ':id' => $id]);
        
        header('Location: ' . base_url('pages/data-zoom.php?updated=1'));
        exit;
    }
}

// Handle POST - Delete Booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_booking') {
    $id = (int)($_POST['booking_id'] ?? 0);
    
    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM zoom_bookings WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        header('Location: ' . base_url('pages/data-zoom.php?deleted=1'));
        exit;
    }
}

$page_title   = "Booking Jadwal Zoom";
$active_menu  = "booking-zoom";
$content_file = __DIR__ . "/booking-zoom.content.php";

require_once __DIR__ . '/../includes/layout.php';
exit;