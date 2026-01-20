<?php
/**
 * Main Entry Point / Router
 * Path: index.php
 */

session_start();

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

// Cek apakah user sudah login
if (!is_logged_in()) {
    // Kalau belum login, redirect ke halaman login
    header('Location: auth/login.php');
    exit;
}

// Kalau sudah login, tampilkan dashboard
define('INCLUDED_FROM_INDEX', true);
require __DIR__ . '/pages/dashboard.php';