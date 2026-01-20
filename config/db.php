<?php
/**
 * Database Connection Handler
 * Path: config/db.php
 */

function db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;

    $host = "127.0.0.1";
    $db   = "jadwal_db";
    $user = "root";
    $pass = ""; // default XAMPP biasanya kosong
    $charset = "utf8mb4";

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    
    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }

    return $pdo;
}