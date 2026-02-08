<?php
session_start();

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

// ============================================
// SESSION TIMEOUT & AUTO LOGOUT
// ============================================
define('SESSION_TIMEOUT', 0);

if (!is_logged_in()) {
    header('Location: auth/login.php');
    exit;
}

if (SESSION_TIMEOUT > 0 && isset($_SESSION['login_time'])) {
    $elapsed = time() - $_SESSION['login_time'];
    
    if ($elapsed > SESSION_TIMEOUT) {
        session_unset();
        session_destroy();
        header('Location: auth/login.php?msg=timeout');
        exit;
    }
}

// ============================================
// PERMISSION CHECK - Redirect user tanpa akses
// ============================================
if (!is_admin()) {
    $user_permissions = get_user_permissions();
    
    // Jika user tidak punya permission sama sekali
    if (empty($user_permissions)) {
        // Tampilkan halaman error atau redirect ke halaman khusus
        echo '<!doctype html>
        <html lang="id">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Akses Ditolak</title>
            <style>
                body { font-family: Arial, sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; background: #f5f7fa; margin: 0; }
                .error-box { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-align: center; max-width: 500px; }
                .error-box h1 { color: #ef4444; margin-bottom: 15px; font-size: 24px; }
                .error-box p { color: #64748b; margin-bottom: 25px; line-height: 1.6; }
                .btn { display: inline-block; padding: 12px 24px; background: #3b82f6; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; }
                .btn:hover { background: #2563eb; }
            </style>
        </head>
        <body>
            <div class="error-box">
                <h1>🔒 Akses Ditolak</h1>
                <p><strong>Maaf, Anda tidak memiliki akses ke halaman manapun.</strong></p>
                <p>Silakan hubungi Administrator untuk mendapatkan akses.</p>
                <a href="' . base_url('auth/logout.php') . '" class="btn">Logout</a>
            </div>
        </body>
        </html>';
        exit;
    }
}

// ============================================
// LOAD DASHBOARD
// ============================================
define('INCLUDED_FROM_INDEX', true);
require __DIR__ . '/pages/dashboard.php';
exit;