<?php
/**
 * Helper Functions
 * Path: includes/functions.php
 */

// Escape HTML untuk mencegah XSS
function h($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

// Convert JSON array PIC IT Support ke text
function it_support_to_text($json) {
    $arr = json_decode($json, true);
    if (!is_array($arr)) return '';
    return implode(", ", $arr);
}

// Generate unique transaction ID
function generate_transaction_id(PDO $pdo): string {
    while (true) {
        $rand = str_pad((string)random_int(0, 999999), 6, "0", STR_PAD_LEFT);
        $id = "TRX-" . date("Ymd") . "-" . $rand;

        $stmt = $pdo->prepare("SELECT COUNT(*) c FROM schedules WHERE transaction_id = :id");
        $stmt->execute([':id' => $id]);
        $exists = (int)$stmt->fetchColumn();

        if ($exists === 0) return $id;
    }
}

// Check if user is logged in
function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function is_admin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Get current user info
function current_user() {
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? 'Guest',
        'role' => $_SESSION['role'] ?? 'user',
    ];
}

// Get base URL
function base_url($path = '') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    
    // Get base path dari SCRIPT_NAME
    $script_name = $_SERVER['SCRIPT_NAME'];
    $base_path = str_replace('\\', '/', dirname($script_name));
    
    // Jika kita ada di subfolder (pages, auth, dll), naik 1 level
    if (preg_match('#/(pages|auth|includes)/?$#', $base_path)) {
        $base_path = dirname($base_path);
    }
    
    $base = $protocol . '://' . $host . $base_path;
    return rtrim($base, '/') . '/' . ltrim($path, '/');
}

// Asset URL helper
function asset($path) {
    return base_url('assets/' . ltrim($path, '/'));
}

// Check login and redirect if not logged in
function require_login() {
    if (!is_logged_in()) {
        header('Location: ' . base_url('auth/login.php'));
        exit;
    }
}

// Check admin and redirect if not admin
function require_admin() {
    require_login();
    if (!is_admin()) {
        header('Location: ' . base_url('index.php'));
        exit;
    }
}

// Format tanggal Indonesia
function format_date_id($date) {
    if (!$date) return '-';
    $bulan = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
        5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
        9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
    ];
    $timestamp = strtotime($date);
    $d = date('d', $timestamp);
    $m = $bulan[(int)date('n', $timestamp)];
    $y = date('Y', $timestamp);
    return "$d $m $y";
}

// Sanitize input
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}