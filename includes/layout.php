<?php
/**
 * Main Layout with Sidebar (UPDATED - with Booking Zoom)
 * Path: includes/layout.php
 */

$user = current_user();
$page_title = $page_title ?? 'Dashboard';
$active_menu = $active_menu ?? 'dashboard';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <title><?= h($page_title) ?> - PLN UID JATENG DIY</title>
    <style>
        <?php include __DIR__ . '/admin-style.css'; ?>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f5f7fa;
            overflow-x: hidden;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: #fff;
            transition: transform 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar.collapsed {
            transform: translateX(-260px);
        }

        .sidebar-header {
            padding: 20px;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .sidebar-logo img {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: white;
            padding: 4px;
        }

        .sidebar-logo h2 {
            font-size: 18px;
            font-weight: 700;
        }

        .user-info-sidebar {
            background: rgba(255,255,255,0.1);
            padding: 10px;
            border-radius: 8px;
            font-size: 13px;
        }

        .user-info-sidebar strong {
            display: block;
            margin-bottom: 3px;
        }

        .user-role-badge {
            display: inline-block;
            padding: 2px 8px;
            background: #10b981;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .sidebar-menu {
            padding: 10px 0;
        }

        .menu-section {
            margin-bottom: 5px;
        }

        .menu-section-title {
            padding: 15px 20px 8px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 0.5px;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
            border-left: 3px solid transparent;
        }

        .menu-item:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        .menu-item.active {
            background: rgba(59, 130, 246, 0.2);
            color: #fff;
            border-left-color: #3b82f6;
        }

        .menu-item .icon {
            font-size: 18px;
            width: 20px;
            text-align: center;
        }

        .menu-item .text {
            flex: 1;
            font-size: 14px;
        }

        .menu-item .arrow {
            font-size: 12px;
            transition: transform 0.3s;
        }

        .menu-item.expanded .arrow {
            transform: rotate(90deg);
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: rgba(0,0,0,0.2);
        }

        .submenu.show {
            max-height: 500px;
        }

        .submenu-item {
            padding: 10px 20px 10px 52px;
            display: block;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.2s;
        }

        .submenu-item:hover {
            background: rgba(255,255,255,0.05);
            color: #fff;
        }

        .submenu-item.active {
            color: #3b82f6;
            font-weight: 600;
        }

        /* MAIN CONTENT */
        .main-wrapper {
            margin-left: 260px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        .main-wrapper.expanded {
            margin-left: 0;
        }

        .topbar {
            background: #fff;
            padding: 15px 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .toggle-sidebar {
            background: #f1f5f9;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: all 0.2s;
        }

        .toggle-sidebar:hover {
            background: #e2e8f0;
        }

        .page-title {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-logout {
            padding: 8px 16px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-logout:hover {
            background: #dc2626;
        }

        .content {
            padding: 25px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-260px);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-wrapper {
                margin-left: 0;
            }
        }

        /* OVERLAY */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        .sidebar-overlay.show {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="<?= asset('images/logo_pln.png') ?>" alt="PLN">
                <h2>PLN UID JATENG DIY</h2>
            </div>
            <div class="user-info-sidebar">
                <strong>👤 <?= h($user['username']) ?></strong>
                <span class="user-role-badge"><?= $user['role'] === 'admin' ? 'ADMIN' : 'USER' ?></span>
            </div>
        </div>

        <nav class="sidebar-menu">
            <!-- Dashboard -->
            <div class="menu-section">
                <a href="<?= base_url('index.php') ?>" class="menu-item <?= $active_menu === 'dashboard' ? 'active' : '' ?>">
                    <span class="icon">📊</span>
                    <span class="text">Dashboard</span>
                </a>
            </div>

            <!-- IT SUPPORT Section -->
            <div class="menu-section">
                <div class="menu-section-title">IT Support</div>
                
                <div class="menu-item" onclick="toggleSubmenu('it-support')">
                    <span class="icon">💻</span>
                    <span class="text">IT Support</span>
                    <span class="arrow">▸</span>
                </div>
                <div class="submenu" id="submenu-it-support">
                    <a href="<?= base_url('pages/data-jadwal.php') ?>" class="submenu-item <?= $active_menu === 'data-jadwal' ? 'active' : '' ?>">
                        📅 Data Jadwal
                    </a>
                    <a href="<?= base_url('pages/booking-zoom.php') ?>" class="submenu-item <?= in_array($active_menu, ['booking-zoom', 'data-zoom']) ? 'active' : '' ?>">
                        🎥 Booking Jadwal Zoom
                    </a>
                    <a href="<?= base_url('pages/data-server.php') ?>" class="submenu-item <?= $active_menu === 'data-server' ? 'active' : '' ?>">
                        🖥️ Data Server
                    </a>
                </div>
            </div>

            <?php if ($user['role'] === 'admin'): ?>
            <!-- ADMINISTRATOR Section (Admin Only) -->
            <div class="menu-section">
                <div class="menu-section-title">Administrator</div>
                
                <div class="menu-item" onclick="toggleSubmenu('administrator')">
                    <span class="icon">⚙️</span>
                    <span class="text">Administrator</span>
                    <span class="arrow">▸</span>
                </div>
                <div class="submenu" id="submenu-administrator">
                    <a href="<?= base_url('pages/master-user.php') ?>" class="submenu-item <?= $active_menu === 'master-user' ? 'active' : '' ?>">
                        👥 Master User
                    </a>
                    <a href="<?= base_url('pages/master-ruangan.php') ?>" class="submenu-item <?= $active_menu === 'master-ruangan' ? 'active' : '' ?>">
                        🏢 Master Ruangan
                    </a>
                    <a href="<?= base_url('pages/master-it-support.php') ?>" class="submenu-item <?= $active_menu === 'master-it-support' ? 'active' : '' ?>">
                        👨‍💻 Master IT Support
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Logout -->
            <div class="menu-section" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 10px;">
                <a href="<?= base_url('auth/logout.php') ?>" class="menu-item" onclick="return confirm('Yakin ingin logout?')">
                    <span class="icon">🚪</span>
                    <span class="text">Logout</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Main Content -->
    <div class="main-wrapper" id="mainWrapper">
        <div class="topbar">
            <div class="topbar-left">
                <button class="toggle-sidebar" onclick="toggleSidebar()">☰</button>
                <h1 class="page-title"><?= h($page_title) ?></h1>
            </div>
            <div class="topbar-right">
                <a href="<?= base_url('auth/logout.php') ?>" class="btn-logout" onclick="return confirm('Yakin ingin logout?')">Logout</a>
            </div>
        </div>

        <div class="content">
        <?php
if (isset($content_file) && file_exists($content_file)) {
    $IS_CONTENT = true;
    include $content_file;
    unset($IS_CONTENT);
} else {
    echo '<div style="padding:20px;background:#fee2e2;border:1px solid #ef4444;border-radius:8px;color:#991b1b;">
            CONTENT FILE NOT FOUND: ' . htmlspecialchars($content_file ?? '(not set)') . '
          </div>';
}
?>
        </div>
    </div>

    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainWrapper = document.getElementById('mainWrapper');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
                mainWrapper.classList.toggle('expanded');
            }
        }

        // Toggle Submenu
        function toggleSubmenu(name) {
            const submenu = document.getElementById('submenu-' + name);
            const menuItem = submenu.previousElementSibling;
            
            submenu.classList.toggle('show');
            menuItem.classList.toggle('expanded');
        }

        // Auto expand active submenu
        document.addEventListener('DOMContentLoaded', function() {
            const activeSubmenuItem = document.querySelector('.submenu-item.active');
            if (activeSubmenuItem) {
                const submenu = activeSubmenuItem.closest('.submenu');
                const menuItem = submenu.previousElementSibling;
                submenu.classList.add('show');
                menuItem.classList.add('expanded');
            }
        });

        // Close sidebar on overlay click (mobile)
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.getElementById('sidebarOverlay').classList.remove('show');
            }
        });
    </script>
</body>
</html>