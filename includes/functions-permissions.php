<?php
/**
 * Permission System Functions
 * Path: includes/functions-permissions.php
 * 
 * CARA PAKAI:
 * Tambahkan ini di bagian bawah file includes/functions.php Anda:
 * require_once __DIR__ . '/functions-permissions.php';
 */

/**
 * Get user's permissions
 */
function get_user_permissions($user_id = null) {
    if ($user_id === null) {
        $user_id = $_SESSION['user_id'] ?? 0;
    }
    
    if ($user_id <= 0) return [];
    
    $pdo = db();
    $stmt = $pdo->prepare("SELECT page_slug FROM user_permissions WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

/**
 * Check if user has permission
 */
function has_permission($page_slug, $user_id = null) {
    // Admin always has access
    if (is_admin()) {
        return true;
    }
    
    if ($user_id === null) {
        $user_id = $_SESSION['user_id'] ?? 0;
    }
    
    if ($user_id <= 0) return false;
    
    $permissions = get_user_permissions($user_id);
    return in_array($page_slug, $permissions, true);
}

/**
 * Require permission or redirect
 */
function require_permission($page_slug, $redirect_url = null) {
    if (!has_permission($page_slug)) {
        if ($redirect_url === null) {
            $redirect_url = base_url('index.php?error=no_permission');
        }
        header('Location: ' . $redirect_url);
        exit;
    }
}

/**
 * Sync user permissions
 */
function sync_user_permissions($user_id, $page_slugs = []) {
    $pdo = db();
    
    try {
        $pdo->beginTransaction();
        
        // Delete all
        $stmt = $pdo->prepare("DELETE FROM user_permissions WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $user_id]);
        
        // Insert new
        if (!empty($page_slugs)) {
            $stmt = $pdo->prepare("INSERT INTO user_permissions (user_id, page_slug) VALUES (:user_id, :page_slug)");
            foreach ($page_slugs as $slug) {
                $stmt->execute([':user_id' => $user_id, ':page_slug' => $slug]);
            }
        }
        
        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        $pdo->rollBack();
        return false;
    }
}

/**
 * Get available pages
 */
function get_available_pages() {
    return [
        'data-jadwal' => [
            'name' => 'Data Jadwal',
            'icon' => '📅',
            'description' => 'Entry Jadwal & Export'
        ],
        'booking-zoom' => [
            'name' => 'Booking Zoom',
            'icon' => '🎥',
            'description' => 'Booking & Data Zoom'
        ],
        'data-server' => [
            'name' => 'Data Server',
            'icon' => '🖥️',
            'description' => 'Server & Maintenance'
        ],
    ];
}