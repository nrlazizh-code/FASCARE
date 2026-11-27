<?php
require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance()->getConnection();
if ($db) {
    echo "Koneksi ke database '" . DB_NAME . "' BERHASIL!";
}