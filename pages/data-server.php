<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$page_title = "Data Server";
$active_menu = "data-server";
$pdo = db();

// Handle POST actions (add, edit, delete server)
// Load servers
$servers = $pdo->query("SELECT * FROM servers ORDER BY created_at DESC")->fetchAll();
?>
<!-- Include layout.php dan tampilkan tabel server -->