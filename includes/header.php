<?php
/**
 * Header Template
 * Path: includes/header.php
 */

$user = current_user();
$page_title = $page_title ?? 'Jadwal Kegiatan PLN';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($page_title) ?> - UID JATENG DIY</title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
<div class="container">
    <h1><?= h($page_title) ?></h1>

    <div class="header-bar">
        <div>
            <strong>👤 <?= h($user['username']) ?></strong>
            <span style="color: #6b7280; margin-left: 8px;">
                (<?= $user['role'] === 'admin' ? '🔑 Admin' : '👨‍💼 User' ?>)
            </span>
        </div>
        <div class="header-actions">
            <?php if ($user['role'] === 'admin'): ?>
                <a href="<?= base_url('pages/admin.php') ?>" class="btn btn-secondary">⚙️ Admin Panel</a>
            <?php endif; ?>
            <a href="<?= base_url('auth/logout.php') ?>" class="btn btn-secondary">Logout</a>
        </div>
    </div>