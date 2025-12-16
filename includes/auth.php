<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika belum login, redirect ke halaman login menggunakan BASE_URL
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit;
}
