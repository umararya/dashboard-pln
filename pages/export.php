<?php
/**
 * Export CSV
 * Path: pages/export.php
 */

session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Cek login
require_login();

$pdo = db();
$rows = $pdo->query("SELECT * FROM schedules ORDER BY created_at ASC, id ASC")->fetchAll();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="jadwal_kegiatan_' . date('Ymd') . '.csv"');
echo "\xEF\xBB\xBF"; // UTF-8 BOM

$out = fopen('php://output', 'w');
fputcsv($out, [
    'No', 'Transaction ID', 'Start Date', 'End Date', 'PIC Acara', 'Nama Acara',
    'PIC IT Support', 'Meeting Room', 'Pelaksanaan', 'Standby Status',
    'Kebutuhan Detail', 'Tindak Lanjut', 'Created At'
], ';');

$no = 1;
foreach ($rows as $r) {
    fputcsv($out, [
        $no++,
        $r['transaction_id'],
        $r['start_date'],
        $r['end_date'],
        $r['pic_acara'],
        $r['nama_acara'],
        it_support_to_text($r['pic_it_support']),
        $r['meeting_room'],
        $r['pelaksanaan'],
        $r['standby_status'],
        $r['kebutuhan_detail'],
        $r['tindak_lanjut'],
        $r['created_at'],
    ], ';');
}

fclose($out);
exit;