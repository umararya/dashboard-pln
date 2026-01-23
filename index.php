<?php
/**
 * Main Entry Point / Router - Updated
 * Path: index.php
 * 
 * Dengan fitur:
 * - Auto logout saat buka tab/window baru
 * - Session timeout otomatis
 * - Wajib login setiap akses URL
 */

// Mulai session
session_start();

// Load dependencies
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

// ============================================
// SESSION TIMEOUT & AUTO LOGOUT
// ============================================
// Set session timeout = 0 untuk auto logout setiap buka URL baru
define('SESSION_TIMEOUT', 0); // dalam detik (0 = selalu logout)

// Cek apakah user sudah login
if (!is_logged_in()) {
    // Belum login, redirect ke login page
    header('Location: auth/login.php');
    exit;
}

// Cek session timeout (jika > 0)
if (SESSION_TIMEOUT > 0 && isset($_SESSION['login_time'])) {
    $elapsed = time() - $_SESSION['login_time'];
    
    if ($elapsed > SESSION_TIMEOUT) {
        // Session expired, logout otomatis
        session_unset();
        session_destroy();
        header('Location: auth/login.php?msg=timeout');
        exit;
    }
}

// ============================================
// LOAD DASHBOARD
// ============================================
// Jika sudah login dan session valid, tampilkan dashboard
define('INCLUDED_FROM_INDEX', true);
require __DIR__ . '/pages/dashboard.php';
exit;