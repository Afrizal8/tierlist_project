<?php
require_once __DIR__ . "/helpers.php";

/**
 * =========================
 * DETEKSI ENVIRONMENT
 * =========================
 * Railway otomatis menyediakan variable PORT
 */
$isRailway = isset($_ENV['RAILWAY_ENVIRONMENT']) || isset($_ENV['PORT']);

/**
 * =========================
 * KONFIGURASI DATABASE
 * =========================
 */
try {
    if ($isRailway) {
        // ===== RAILWAY =====
        $DB_HOST = $_ENV['MYSQLHOST'];
        $DB_PORT = $_ENV['MYSQLPORT'];
        $DB_NAME = $_ENV['MYSQLDATABASE'];
        $DB_USER = $_ENV['MYSQLUSER'];
        $DB_PASS = $_ENV['MYSQLPASSWORD'];

        $dsn = "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset=utf8mb4";
    } else {
        // ===== LOCALHOST =====
        $DB_HOST = '127.0.0.1';
        $DB_NAME = 'tierlist_db';
        $DB_USER = 'root';
        $DB_PASS = '';

        $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
    }

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

/**
 * =========================
 * BASE URL
 * =========================
 */
if ($isRailway) {
    define('BASE_URL', '/'); 
} else {
    define('BASE_URL', 'http://localhost/tierlist_project/public/');
}

/**
 * =========================
 * PATH ROOT PROJECT
 * =========================
 */
define('PROJECT_ROOT', realpath(__DIR__ . '/../') . '/');

/**
 * =========================
 * SESSION
 * =========================
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
