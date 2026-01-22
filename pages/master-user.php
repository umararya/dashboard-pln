<?php
/**
 * Master User Management - Simple with Sidebar Copy
 * Path: pages/master-user.php
 *
 * NOTE:
 * - This version copies the sidebar markup directly into this file (quick & simple).
 * - For a cleaner architecture, consider moving sidebar into includes/layout.php and
 *   using a content-only view as previously suggested.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$pdo = db();
$success = '';
$errors = [];

/**
 * Minimal $user for sidebar (using session as quick fallback).
 * If your project has a current_user() helper, you can replace this.
 */
$user = [
    'username' => $_SESSION['username'] ?? 'Guest',
    'role' => $_SESSION['role'] ?? 'user'
];

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ADD USER
    if ($action === 'add_user') {
        $username = clean_input($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';
        $bagian = clean_input($_POST['bagian'] ?? '');

        if ($username === '' || $password === '') {
            $errors[] = 'Username dan password wajib diisi.';
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
            $stmt->execute([':username' => $username]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = 'Username sudah digunakan.';
            } else {
                // Cek apakah kolom plain_password ada
                try {
                    $stmt = $pdo->prepare("INSERT INTO users (username, plain_password, role, bagian, is_active) VALUES (:u, :p, :r, :b, 1)");
                    $stmt->execute([':u' => $username, ':p' => $password, ':r' => $role, ':b' => $bagian]);
                    $success = "User '$username' berhasil ditambahkan.";
                } catch (PDOException $e) {
                    // Jika kolom plain_password belum ada, pakai kolom password (hash)
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, bagian, is_active) VALUES (:u, :p, :r, :b, 1)");
                    $stmt->execute([':u' => $username, ':p' => $hashed, ':r' => $role, ':b' => $bagian]);
                    $success = "User '$username' berhasil ditambahkan (password ter-hash).";
                }
            }
        }
    }

    // TOGGLE ACTIVE
    if ($action === 'toggle_active') {
        $id = (int)($_POST['user_id'] ?? 0);
        $current_status = (int)($_POST['current_status'] ?? 0);
        
        if ($id > 0) {
            $new_status = $current_status ? 0 : 1;
            $stmt = $pdo->prepare("UPDATE users SET is_active = :status WHERE id = :id");
            $stmt->execute([':status' => $new_status, ':id' => $id]);
            $success = 'Status user berhasil diupdate.';
        }
    }

    // DELETE USER
    if ($action === 'delete_user') {
        $id = (int)($_POST['user_id'] ?? 0);
        
        if ($id === (int)$_SESSION['user_id']) {
            $errors[] = 'Tidak dapat menghapus akun sendiri.';
        } elseif ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $success = 'User berhasil dihapus.';
        }
    }

    // EDIT USER
    if ($action === 'edit_user') {
        $id = (int)($_POST['user_id'] ?? 0);
        $username = clean_input($_POST['username'] ?? '');
        $role = $_POST['role'] ?? 'user';
        $bagian = clean_input($_POST['bagian'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($id > 0 && $username !== '') {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :u AND id != :id");
            $stmt->execute([':u' => $username, ':id' => $id]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = 'Username sudah digunakan user lain.';
            } else {
                try {
                    if ($password !== '') {
                        $stmt = $pdo->prepare("UPDATE users SET username = :u, plain_password = :p, role = :r, bagian = :b WHERE id = :id");
                        $stmt->execute([':u' => $username, ':p' => $password, ':r' => $role, ':b' => $bagian, ':id' => $id]);
                    } else {
                        $stmt = $pdo->prepare("UPDATE users SET username = :u, role = :r, bagian = :b WHERE id = :id");
                        $stmt->execute([':u' => $username, ':r' => $role, ':b' => $bagian, ':id' => $id]);
                    }
                    $success = 'User berhasil diupdate.';
                } catch (PDOException $e) {
                    $errors[] = 'Error update user: ' . $e->getMessage();
                }
            }
        }
    }
}

// Load users
$users = $pdo->query("SELECT * FROM users ORDER BY id ASC")->fetchAll();

$page_title = "Master User";
$active_menu = "master-user";

/**
 * Helper for logo path: prefer asset() if available, otherwise point to assets/images/
 */
$logo = function_exists('asset') ? asset('images/logo_pln.png') : base_url('assets/images/logo_pln.png');
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($page_title) ?> - PLN UID</title>
    <style>
        /* Inline CSS untuk menghindari error file not found */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f5f7fa;
            overflow-x: hidden;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: #fff;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
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

        .page-title {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
        }

        .content {
            padding: 25px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 0;
            margin-bottom: 25px;
        }

        .card-header {
            padding: 20px 25px;
            border-bottom: 1px solid #e5e7eb;
        }

        .card-header h2 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #1e293b;
        }

        .card-header p {
            color: #64748b;
            font-size: 14px;
            margin: 0;
        }

        .alert {
            padding: 12px 20px;
            border-radius: 8px;
            margin: 20px 25px;
            font-size: 14px;
        }

        .alert-success {
            background: #ecfdf5;
            border: 1px solid #10b981;
            color: #065f46;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #ef4444;
            color: #991b1b;
        }

        .form-section {
            padding: 20px 25px;
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
        }

        .form-section h3 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #1e293b;
        }

        .form-inline {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .form-inline input,
        .form-inline select {
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
        }

        .form-inline input[type="text"],
        .form-inline input[type="password"] {
            min-width: 180px;
            flex: 1;
        }

        .table-responsive {
            padding: 0;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .data-table thead th {
            background: #f8fafc;
            padding: 14px 20px;
            text-align: left;
            font-weight: 700;
            color: #475569;
            border-bottom: 2px solid #e5e7eb;
        }

        .data-table tbody td {
            padding: 14px 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .data-table tbody tr:hover {
            background: #f8fafc;
        }

        .text-center {
            text-align: center;
            color: #94a3b8;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-primary { background: #dbeafe; color: #1e40af; }
        .badge-danger { background: #fee2e2; color: #991b1b; }

        .toggle-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .toggle-btn.active { background: #10b981; color: white; }
        .toggle-btn.inactive { background: #ef4444; color: white; }

        .btn-group {
            display: flex;
            gap: 6px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-sm { padding: 6px 12px; font-size: 13px; }
        .btn-primary { background: #3b82f6; color: white; }
        .btn-edit { background: #10b981; color: white; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-secondary { background: #e5e7eb; color: #374151; }

        .password-cell {
            font-family: 'Courier New', monospace;
            background: #f3f4f6;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 600;
            color: #1e40af;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal.show { display: flex; }

        .modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 25px;
            border-bottom: 1px solid #e5e7eb;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #94a3b8;
        }

        .form-group {
            padding: 0 25px;
            margin: 20px 0;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            padding: 20px 25px;
            border-top: 1px solid #e5e7eb;
        }

        /* Sidebar-specific small styles (submenu) */
        .menu-item { padding: 12px 20px; display:flex; align-items:center; gap:12px; color:#cbd5e1; text-decoration:none; cursor:pointer; border-left:3px solid transparent; }
        .menu-item:hover { background: rgba(255,255,255,0.05); color:#fff; }
        .menu-item.active { background: rgba(59,130,246,0.2); color:#fff; border-left-color:#3b82f6; }
        .submenu { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; background: rgba(0,0,0,0.2); }
        .submenu.show { max-height: 500px; }
        .submenu-item { padding: 10px 20px 10px 52px; display:block; color:#cbd5e1; text-decoration:none; font-size:13px; }
        .submenu-item.active { color:#3b82f6; font-weight:600; }
        .sidebar-overlay { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; }
        .sidebar-overlay.show { display:block; }
    </style>
</head>
<body>
    <!-- Sidebar (copied from layout.php) -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo" style="display:flex; align-items:center; gap:12px; margin-bottom:15px; padding:20px; background:rgba(0,0,0,0.05);">
                <img src="<?= h($logo) ?>" alt="PLN" style="width:40px;height:40px;border-radius:8px;background:white;padding:4px;">
                <h2 style="font-size:18px; color:#fff; margin:0;">PLN UID</h2>
            </div>
            <div class="user-info-sidebar" style="padding:10px;">
                <strong>👤 <?= h($user['username']) ?></strong>
                <span style="display:inline-block;padding:2px 8px;background:#10b981;border-radius:4px;font-size:11px;font-weight:600;text-transform:uppercase;">
                    <?= $user['role'] === 'admin' ? 'ADMIN' : 'USER' ?>
                </span>
            </div>
        </div>

        <nav class="sidebar-menu" style="padding:10px 0;">
            <!-- Dashboard -->
            <div class="menu-section">
                <a href="<?= base_url('index.php') ?>" class="menu-item <?= $active_menu === 'dashboard' ? 'active' : '' ?>">
                    <span class="icon">📊</span>
                    <span class="text">Dashboard</span>
                </a>
            </div>

            <!-- IT SUPPORT Section -->
            <div class="menu-section">
                <div class="menu-section-title" style="padding:15px 20px 8px;font-size:11px;font-weight:700;text-transform:uppercase;color:#94a3b8;">IT Support</div>
                
                <div class="menu-item" onclick="toggleSubmenu('it-support')">
                    <span class="icon">💻</span>
                    <span class="text">IT Support</span>
                    <span class="arrow">▸</span>
                </div>
                <div class="submenu" id="submenu-it-support">
                    <a href="<?= base_url('pages/data-jadwal.php') ?>" class="submenu-item <?= $active_menu === 'data-jadwal' ? 'active' : '' ?>">
                        📅 Data Jadwal
                    </a>
                    <a href="<?= base_url('pages/data-server.php') ?>" class="submenu-item <?= $active_menu === 'data-server' ? 'active' : '' ?>">
                        🖥️ Data Server
                    </a>
                </div>
            </div>

            <?php if ($user['role'] === 'admin'): ?>
            <!-- ADMINISTRATOR Section (Admin Only) -->
            <div class="menu-section">
                <div class="menu-section-title" style="padding:15px 20px 8px;font-size:11px;font-weight:700;text-transform:uppercase;color:#94a3b8;">Administrator</div>
                
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

    <!-- Simplified Layout (tanpa include layout.php untuk testing) -->
    <div class="main-wrapper" id="mainWrapper">
        <div class="topbar">
            <h1 class="page-title"><?= h($page_title) ?></h1>
            <a href="<?= base_url('auth/logout.php') ?>" class="btn btn-danger">Logout</a>
        </div>

        <div class="content">
            <div class="card">
                <div class="card-header">
                    <h2>👥 Master User Management</h2>
                    <p>Kelola akses user dan permissions</p>
                </div>

                <?php if ($success): ?>
                    <div class="alert alert-success">✓ <?= h($success) ?></div>
                <?php endif; ?>

                <?php if ($errors): ?>
                    <div class="alert alert-error">
                        <strong>⚠ Error:</strong>
                        <ul style="margin: 8px 0 0 20px;">
                            <?php foreach ($errors as $e): ?>
                                <li><?= h($e) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="form-section">
                    <h3>➕ Tambah User Baru</h3>
                    <form method="post" class="form-inline">
                        <input type="hidden" name="action" value="add_user">
                        <input type="text" name="username" placeholder="Username" required>
                        <input type="text" name="password" placeholder="Password" required>
                        <select name="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                        <input type="text" name="bagian" placeholder="Bagian/Divisi">
                        <button type="submit" class="btn btn-primary">Tambah User</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Role</th>
                                <th>Bagian</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$users): ?>
                                <tr><td colspan="7" class="text-center">Belum ada user</td></tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($users as $u): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><strong><?= h($u['username']) ?></strong></td>
                                        <td>
                                            <span class="password-cell">
                                                <?= h($u['plain_password'] ?? '******') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?= $u['role'] === 'admin' ? 'danger' : 'primary' ?>">
                                                <?= strtoupper($u['role']) ?>
                                            </span>
                                        </td>
                                        <td><?= h($u['bagian'] ?? '-') ?></td>
                                        <td>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="action" value="toggle_active">
                                                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                                <input type="hidden" name="current_status" value="<?= $u['is_active'] ?>">
                                                <button type="submit" class="toggle-btn <?= $u['is_active'] ? 'active' : 'inactive' ?>">
                                                    <?= $u['is_active'] ? '🟢 ON' : '🔴 OFF' ?>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-edit" onclick="editUser(<?= $u['id'] ?>, '<?= h($u['username']) ?>', '<?= h($u['plain_password'] ?? '') ?>', '<?= h($u['role']) ?>', '<?= h($u['bagian'] ?? '') ?>')">Edit</button>
                                                <?php if ($u['id'] !== (int)$_SESSION['user_id']): ?>
                                                    <form method="post" style="display: inline;" onsubmit="return confirm('Yakin hapus user ini?')">
                                                        <input type="hidden" name="action" value="delete_user">
                                                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>✏️ Edit User</h3>
                <button class="close-modal" onclick="closeEditModal()">&times;</button>
            </div>
            <form method="post">
                <input type="hidden" name="action" value="edit_user">
                <input type="hidden" name="user_id" id="editUserId">
                
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" id="editUsername" required>
                </div>
                
                <div class="form-group">
                    <label>Password (kosongkan jika tidak diubah)</label>
                    <input type="text" name="password" id="editPassword" placeholder="Isi jika ingin ubah password">
                </div>
                
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" id="editRole" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Bagian/Divisi</label>
                    <input type="text" name="bagian" id="editBagian">
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Sidebar toggles (simple)
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

        function toggleSubmenu(name) {
            const submenu = document.getElementById('submenu-' + name);
            if (!submenu) return;
            const menuItem = submenu.previousElementSibling;
            submenu.classList.toggle('show');
            if (menuItem) menuItem.classList.toggle('expanded');
        }

        // Auto expand active submenu (if any)
        document.addEventListener('DOMContentLoaded', function() {
            const activeSubmenuItem = document.querySelector('.submenu-item.active');
            if (activeSubmenuItem) {
                const submenu = activeSubmenuItem.closest('.submenu');
                const menuItem = submenu.previousElementSibling;
                submenu.classList.add('show');
                if (menuItem) menuItem.classList.add('expanded');
            }

            // init delete confirm handlers if any (already used via onsubmit attr)
            const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Yakin ingin menghapus data ini?')) {
                        e.preventDefault();
                    }
                });
            });
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                const overlay = document.getElementById('sidebarOverlay');
                if (overlay) overlay.classList.remove('show');
            }
        });

        // Modal functions for Edit User
        function editUser(id, username, password, role, bagian) {
            const idInput = document.getElementById('editUserId');
            const usernameInput = document.getElementById('editUsername');
            const passwordInput = document.getElementById('editPassword');
            const roleInput = document.getElementById('editRole');
            const bagianInput = document.getElementById('editBagian');
            const modal = document.getElementById('editModal');

            if (idInput && usernameInput && passwordInput && roleInput && bagianInput && modal) {
                idInput.value = id;
                usernameInput.value = username;
                passwordInput.value = '';
                passwordInput.placeholder = 'Password saat ini: ' + (password || '******');
                roleInput.value = role;
                bagianInput.value = bagian;
                modal.classList.add('show');
            }
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            if (modal) modal.classList.remove('show');
        }

        // Close modal when clicking outside (non-destructive: use event listener, don't override window.onclick)
        document.addEventListener('click', function(e) {
            if (e.target.classList && e.target.classList.contains('modal')) {
                e.target.classList.remove('show');
            }
        });
    </script>
</body>
</html>