<?php
if (session_status() === PHP_SESSION_NONE) {
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if (stripos($uri, '/admin/') !== false) {
        session_name('FASCARE_ADMIN_SESS');
    } elseif (stripos($uri, '/pengguna/') !== false) {
        session_name('FASCARE_USER_SESS');
    }
    session_set_cookie_params(0, '/');
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/flash_message.php';
require_once __DIR__ . '/functions.php';

function wajibLogin($roleWajib = null) {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        header("Location: " . BASE_URL . "login.php");
        exit;
    }

    if ($roleWajib !== null && $_SESSION['role'] !== $roleWajib) {
        header("Location: " . BASE_URL . "login.php");
        exit;
    }
}