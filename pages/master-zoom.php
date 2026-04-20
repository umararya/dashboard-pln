<?php
/**
 * Master Zoom - Unit & Link Zoom Management (Controller)
 * Path: pages/master-zoom.php
 */

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';

require_admin();

$pdo     = db();
$success = '';
$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ── ZOOM UNIT ─────────────────────────────────────────────
    if ($action === 'add_unit') {
        $name = trim($_POST['unit_name'] ?? '');
        if ($name === '') {
            $errors[] = 'Nama Unit wajib diisi.';
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM zoom_units WHERE name = :name");
            $stmt->execute([':name' => $name]);
            if ((int)$stmt->fetchColumn() > 0) {
                $errors[] = "Unit '$name' sudah ada.";
            } else {
                $max = (int)$pdo->query("SELECT COALESCE(MAX(sort_order),0) FROM zoom_units")->fetchColumn();
                $stmt = $pdo->prepare("INSERT INTO zoom_units (name, sort_order) VALUES (:name, :sort)");
                $stmt->execute([':name' => $name, ':sort' => $max + 1]);
                $success = "Unit '$name' berhasil ditambahkan.";
            }
        }
    }

    if ($action === 'edit_unit') {
        $id   = (int)($_POST['unit_id']   ?? 0);
        $name = trim($_POST['unit_name'] ?? '');
        if ($id > 0 && $name !== '') {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM zoom_units WHERE name = :name AND id != :id");
            $stmt->execute([':name' => $name, ':id' => $id]);
            if ((int)$stmt->fetchColumn() > 0) {
                $errors[] = "Unit '$name' sudah ada.";
            } else {
                $stmt = $pdo->prepare("UPDATE zoom_units SET name = :name WHERE id = :id");
                $stmt->execute([':name' => $name, ':id' => $id]);
                $success = 'Unit berhasil diupdate.';
            }
        }
    }

    if ($action === 'delete_unit') {
        $id = (int)($_POST['unit_id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM zoom_units WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $success = 'Unit berhasil dihapus.';
        }
    }

    if ($action === 'toggle_unit') {
        $id = (int)($_POST['unit_id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE zoom_units SET is_active = NOT is_active WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $success = 'Status unit berhasil diubah.';
        }
    }

    // ── ZOOM LINK ─────────────────────────────────────────────
    if ($action === 'add_link') {
        $email = trim($_POST['link_email'] ?? '');
        if ($email === '') {
            $errors[] = 'Email Zoom wajib diisi.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format email tidak valid.';
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM zoom_links WHERE email = :email");
            $stmt->execute([':email' => $email]);
            if ((int)$stmt->fetchColumn() > 0) {
                $errors[] = "Email '$email' sudah ada.";
            } else {
                $max = (int)$pdo->query("SELECT COALESCE(MAX(sort_order),0) FROM zoom_links")->fetchColumn();
                $stmt = $pdo->prepare("INSERT INTO zoom_links (email, sort_order) VALUES (:email, :sort)");
                $stmt->execute([':email' => $email, ':sort' => $max + 1]);
                $success = "Link Zoom '$email' berhasil ditambahkan.";
            }
        }
    }

    if ($action === 'edit_link') {
        $id    = (int)($_POST['link_id']    ?? 0);
        $email = trim($_POST['link_email'] ?? '');
        if ($id > 0 && $email !== '') {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Format email tidak valid.';
            } else {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM zoom_links WHERE email = :email AND id != :id");
                $stmt->execute([':email' => $email, ':id' => $id]);
                if ((int)$stmt->fetchColumn() > 0) {
                    $errors[] = "Email '$email' sudah ada.";
                } else {
                    $stmt = $pdo->prepare("UPDATE zoom_links SET email = :email WHERE id = :id");
                    $stmt->execute([':email' => $email, ':id' => $id]);
                    $success = 'Link Zoom berhasil diupdate.';
                }
            }
        }
    }

    if ($action === 'delete_link') {
        $id = (int)($_POST['link_id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM zoom_links WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $success = 'Link Zoom berhasil dihapus.';
        }
    }

    if ($action === 'toggle_link') {
        $id = (int)($_POST['link_id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE zoom_links SET is_active = NOT is_active WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $success = 'Status link zoom berhasil diubah.';
        }
    }
}

// ── PAGINATION — UNITS ──────────────────────────────────────────
$per_page      = 10;
$page_unit     = max(1, (int)($_GET['page_unit'] ?? 1));
$offset_unit   = ($page_unit - 1) * $per_page;

$total_units   = (int)$pdo->query("SELECT COUNT(*) FROM zoom_units")->fetchColumn();
$pages_unit    = (int)ceil($total_units / $per_page);

$stmt = $pdo->prepare("SELECT * FROM zoom_units ORDER BY sort_order ASC, id ASC LIMIT :lim OFFSET :off");
$stmt->bindValue(':lim', $per_page,    PDO::PARAM_INT);
$stmt->bindValue(':off', $offset_unit, PDO::PARAM_INT);
$stmt->execute();
$unit_list = $stmt->fetchAll();

// ── PAGINATION — LINKS ──────────────────────────────────────────
$page_link     = max(1, (int)($_GET['page_link'] ?? 1));
$offset_link   = ($page_link - 1) * $per_page;

$total_links   = (int)$pdo->query("SELECT COUNT(*) FROM zoom_links")->fetchColumn();
$pages_link    = (int)ceil($total_links / $per_page);

$stmt = $pdo->prepare("SELECT * FROM zoom_links ORDER BY sort_order ASC, id ASC LIMIT :lim OFFSET :off");
$stmt->bindValue(':lim', $per_page,    PDO::PARAM_INT);
$stmt->bindValue(':off', $offset_link, PDO::PARAM_INT);
$stmt->execute();
$link_list = $stmt->fetchAll();

$page_title   = 'Master Zoom';
$active_menu  = 'master-zoom';
$content_file = __DIR__ . '/master-zoom.content.php';

require_once __DIR__ . '/../includes/layout.php';
exit;