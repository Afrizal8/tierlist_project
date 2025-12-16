<?php
// Berisi fungsi umum yang dipakai oleh banyak file

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Ambil user berdasarkan id (menggunakan $pdo dari config)
function getUserById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT id, username, email, created_at FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

//flash message sederhana (set / get)
function flash_set($key, $msg) {
    $_SESSION['flash_' . $key] = $msg;
}
function flash_get($key) {
    $k = 'flash_' . $key;
    if (isset($_SESSION[$k])) {
        $m = $_SESSION[$k];
        unset($_SESSION[$k]);
        return $m;
    }
    return null;
}
