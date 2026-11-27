<?php
function formatTanggal($datetime) {
    $bulan = [
        1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
    ];
    $ts = strtotime($datetime);
    return date('d', $ts) . ' ' . $bulan[(int)date('m', $ts)] . ' ' . date('Y H:i', $ts);
}

function badgeStatus($status) {
    $map = [
        'Menunggu' => 'badge-warning',
        'Diproses' => 'badge-info',
        'Selesai'  => 'badge-success',
        'Ditolak'  => 'badge-danger'
    ];
    $class = $map[$status] ?? 'badge-secondary';
    return "<span class='badge {$class}'>{$status}</span>";
}

function redirect($url) {
    header("Location: " . $url);
    exit;
}