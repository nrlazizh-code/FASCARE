<?php
require_once __DIR__ . '/../includes/auth_check.php';
wajibLogin('admin');

require_once __DIR__ . '/../classes/LaporanRepository.php';
require_once __DIR__ . '/../includes/functions.php';

$repo   = new LaporanRepository();
$status = $_GET['status'] ?? '';
$daftar = $repo->getAll($status, '', 1000, 0);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Laporan Fasilitas - FASCARE</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; padding: 20px; font-size: 13px; }
        .kop { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop h2 { margin: 0; }
        .kop p { margin: 4px 0 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #444; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        .ttd { margin-top: 40px; float: right; text-align: center; width: 220px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom:15px;">
        <button onclick="window.print()" style="padding:8px 16px; cursor:pointer;">Cetak / Simpan PDF</button>
        <button onclick="window.close()" style="padding:8px 16px; cursor:pointer;">Tutup</button>
    </div>

    <div class="kop">
        <h2>LAPORAN REKAPITULASI KERUSAKAN FASILITAS KAMPUS (FASCARE)</h2>
        <p>Filter Status: <?= $status ? htmlspecialchars($status) : 'Semua Status' ?> | Dicetak: <?= date('d/m/Y H:i') ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Pelapor (NIM)</th>
                <th>Kategori</th>
                <th>Judul & Lokasi</th>
                <th>Status</th>
                <th>Catatan Admin</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftar)): ?>
                <tr><td colspan="8" style="text-align:center;">Tidak ada data laporan.</td></tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftar as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['kode_laporan']) ?></td>
                        <td><?= formatTanggal($row['created_at']) ?></td>
                        <td><?= htmlspecialchars($row['nama_pelapor']) ?> (<?= htmlspecialchars($row['nim_nip']) ?>)</td>
                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                        <td><strong><?= htmlspecialchars($row['judul']) ?></strong><br><?= htmlspecialchars($row['lokasi']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= htmlspecialchars($row['catatan_admin'] ?: '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="ttd">
        <p>Petugas Sarana & Prasarana,</p>
        <br><br><br>
        <strong><u><?= htmlspecialchars($_SESSION['nama']) ?></u></strong><br>
        <span>NIP. <?= htmlspecialchars($_SESSION['nim_nip']) ?></span>
    </div>
</body>
</html>