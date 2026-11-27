<?php
require_once __DIR__ . '/config/database.php';

$role = $_GET['role'] ?? 'pengguna';
$sessName = ($role === 'admin') ? 'FASCARE_ADMIN_SESS' : 'FASCARE_USER_SESS';

session_name($sessName);
session_start();
$_SESSION = [];
session_destroy();

header("Location: " . BASE_URL . "login.php");
exit;