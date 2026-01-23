<?php
/**
 * Master User Management (Controller)
 * Path: pages/master-user.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

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
                try {
                    $stmt = $pdo->prepare("INSERT INTO users (username, plain_password, role, bagian, is_active) VALUES (:u, :p, :r, :b, 1)");
                    $stmt->execute([':u' => $username, ':p' => $password, ':r' => $role, ':b' => $bagian]);
                    $success = "User '$username' berhasil ditambahkan.";
                } catch (PDOException $e) {
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

        if ($id === (int)($_SESSION['user_id'] ?? 0)) {
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

// Variabel untuk layout
$page_title = "Master User";
$active_menu = "master-user";
$content_file = __DIR__ . '/master-user.content.php';

// Render via layout
require_once __DIR__ . '/../includes/layout.php';
exit;
