<?php
/**
 * Master User Management
 * Path: pages/master-user.php
 */

session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$pdo = db();
$success = '';
$errors = [];

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
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, password, role, bagian, is_active) VALUES (:u, :p, :r, :b, 1)");
                $stmt->execute([':u' => $username, ':p' => $hashed, ':r' => $role, ':b' => $bagian]);
                $success = "User '$username' berhasil ditambahkan.";
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
        
        // Cek jangan hapus diri sendiri
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
            // Cek duplikat username (exclude diri sendiri)
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :u AND id != :id");
            $stmt->execute([':u' => $username, ':id' => $id]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = 'Username sudah digunakan user lain.';
            } else {
                if ($password !== '') {
                    // Update dengan password baru
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET username = :u, password = :p, role = :r, bagian = :b WHERE id = :id");
                    $stmt->execute([':u' => $username, ':p' => $hashed, ':r' => $role, ':b' => $bagian, ':id' => $id]);
                } else {
                    // Update tanpa ubah password
                    $stmt = $pdo->prepare("UPDATE users SET username = :u, role = :r, bagian = :b WHERE id = :id");
                    $stmt->execute([':u' => $username, ':r' => $role, ':b' => $bagian, ':id' => $id]);
                }
                $success = 'User berhasil diupdate.';
            }
        }
    }
}

// Load users
$users = $pdo->query("SELECT * FROM users ORDER BY id ASC")->fetchAll();

$page_title = "Master User";
$active_menu = "master-user";
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($page_title) ?> - PLN UID</title>
    <style>
        <?php include __DIR__ . '/../includes/admin-styles.css'; ?>
    </style>
</head>
<body>
    <?php 
    $content_start = true;
    include __DIR__ . '/../includes/layout.php'; 
    ?>

    <!-- Content Start -->
    <div class="card">
        <div class="card-header">
            <h2>👥 Master User Management</h2>
            <p>Kelola akses user dan permissions</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= h($success) ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="alert alert-error">
                <strong>Error:</strong>
                <ul><?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul>
            </div>
        <?php endif; ?>

        <div class="form-section">
            <h3>➕ Tambah User Baru</h3>
            <form method="post" class="form-inline">
                <input type="hidden" name="action" value="add_user">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
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
                        <th>Role</th>
                        <th>Bagian</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$users): ?>
                        <tr><td colspan="6" class="text-center">Belum ada user</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($users as $u): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= h($u['username']) ?></strong></td>
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
                                            <?= $u['is_active'] ? '🟢 AKTIF' : '🔴 NONAKTIF' ?>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-edit" onclick="editUser(<?= $u['id'] ?>, '<?= h($u['username']) ?>', '<?= h($u['role']) ?>', '<?= h($u['bagian'] ?? '') ?>')">Edit</button>
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
                    <label>Password Baru (kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" id="editPassword" placeholder="Isi jika ingin ubah password">
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
        function editUser(id, username, role, bagian) {
            document.getElementById('editUserId').value = id;
            document.getElementById('editUsername').value = username;
            document.getElementById('editRole').value = role;
            document.getElementById('editBagian').value = bagian;
            document.getElementById('editPassword').value = '';
            document.getElementById('editModal').classList.add('show');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('show');
        }

        window.onclick = function(e) {
            if (e.target.classList.contains('modal')) {
                e.target.classList.remove('show');
            }
        }
    </script>
</body>
</html>