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
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/flash_message.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'FASCARE - Sistem Pelaporan Fasilitas Kampus' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
</head>
<body>