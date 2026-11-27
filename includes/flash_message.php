<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function setFlash($tipe, $pesan) {
    $_SESSION['flash'] = [
        'tipe'  => $tipe, // 'success', 'danger', 'warning'
        'pesan' => $pesan
    ];
}

function showFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo "<div class='alert alert-{$flash['tipe']}'>
                <span>{$flash['pesan']}</span>
                <button type='button' class='alert-close' onclick='this.parentElement.remove()'>&times;</button>
              </div>";
    }
}