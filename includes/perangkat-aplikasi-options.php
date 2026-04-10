<?php
/**
 * Shared dropdown options for Perangkat Aplikasi
 * — Mengambil dari DB (master tables), fallback ke array statis jika tabel belum ada
 * Path: includes/perangkat-aplikasi-options.php
 */

function _pa_load_options(string $table, array $fallback): array {
    try {
        $pdo  = db();
        $rows = $pdo->query(
            "SELECT name FROM `$table` WHERE is_active = 1 ORDER BY sort_order ASC, id ASC"
        )->fetchAll(PDO::FETCH_COLUMN);
        return !empty($rows) ? $rows : $fallback;
    } catch (Exception $e) {
        return $fallback;
    }
}

// ── Fallback (dipakai jika tabel DB belum dibuat / kosong) ──────
$_PA_FALLBACK_NAMA = [
    'Aplikasi Web', 'Aplikasi Mobile', 'Sistem Informasi Manajemen',
    'ERP', 'CRM', 'SCADA', 'DMS (Distribution Management System)',
    'HIS (Hospital Information System)', 'HRMS', 'e-Office',
    'e-Procurement', 'SIMKEU', 'Middleware', 'API Gateway',
    'Database Server', 'File Server', 'Mail Server',
    'Monitoring System', 'Backup System', 'Antivirus / Endpoint Protection',
];

$_PA_FALLBACK_BRAND = [
    'Microsoft', 'Oracle', 'SAP', 'IBM', 'Cisco', 'VMware',
    'Red Hat', 'Ubuntu / Canonical', 'Dell', 'HP / HPE', 'Lenovo',
    'Fortinet', 'Palo Alto', 'Juniper', 'Custom / In-house', 'Open Source', 'Lainnya',
];

$_PA_FALLBACK_LOKASI = [
    'UID Jawa Tengah & D.I. Yogyakarta', 'UP3 Semarang', 'UP3 Surakarta',
    'UP3 Yogyakarta', 'UP3 Magelang', 'UP3 Purwokerto', 'UP3 Tegal',
    'UP3 Kudus', 'UP3 Salatiga', 'UP3 Klaten', 'UP3 Pekalongan',
    'UP3 Cilacap', 'UP3 Grobogan', 'UP3 Sukoharjo',
    'UP2D Jateng & DIY', 'UP2K', 'Data Center Utama', 'Data Center Backup',
];

$_PA_FALLBACK_BIDANG = [
    'STI', 'Niaga', 'Distribusi', 'Transmisi', 'Keuangan',
    'SDM', 'Hukum', 'Perencanaan', 'K3 & Lingkungan', 'Komunikasi & TJSL', 'Pengadaan',
];

$_PA_FALLBACK_MSB = [
    'Sistem & Infrastruktur', 'Keamanan Informasi', 'Pengembangan Aplikasi',
    'Operasional TI', 'Jaringan & Komunikasi', 'Data & Analitik',
    'Layanan Pengguna', 'Niaga & Pelanggan', 'Keuangan & Akuntansi',
    'SDM & Administrasi', 'Operasional Distribusi', 'Operasional Transmisi',
    'Perencanaan & Investasi', 'K3 & Lingkungan', 'Pengadaan & Logistik',
];

// ── Load dari DB ────────────────────────────────────────────────
$NAMA_PERANGKAT_OPTIONS = _pa_load_options('master_pa_nama_perangkat', $_PA_FALLBACK_NAMA);
$BRAND_OPTIONS          = _pa_load_options('master_pa_brand',          $_PA_FALLBACK_BRAND);
$LOKASI_OPTIONS         = _pa_load_options('master_pa_lokasi',         $_PA_FALLBACK_LOKASI);
$BIDANG_OPTIONS         = _pa_load_options('master_pa_bidang',         $_PA_FALLBACK_BIDANG);
$MSB_OPTIONS            = _pa_load_options('master_pa_msb',            $_PA_FALLBACK_MSB);

// ── Status patch (tetap hardcode, tidak masuk master DB) ────────
$PATCH_OPTIONS = [
    '✅' => '✅  Up-to-date',
    '❌' => '❌  Belum Up-to-date',
    '–'  => '–   Tidak relevan / tidak ada patch',
    '⌛' => '⌛  Belum Konfirmasi',
];