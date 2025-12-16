<?php
require_once __DIR__ . "/helpers.php";

// Koneksi database (PDO)
$DB_HOST = '127.0.0.1';
$DB_NAME = 'tierlist_db';
$DB_USER = 'root';
$DB_PASS = '';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4", $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// BASE_URL
define('BASE_URL', 'http://localhost/tierlist_project/public/');

// Path filesystem root berfungsi untuk menyimpan file
define('PROJECT_ROOT', realpath(__DIR__ . '/../') . '/');
