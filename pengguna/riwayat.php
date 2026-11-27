<?php
require_once __DIR__ . '/../includes/auth_check.php';
wajibLogin('pengguna');

require_once __DIR__ . '/../classes/LaporanRepository.php';

$repo   = new LaporanRepository();
$status = $_GET['status'] ?? '';
$daftar = $repo->getByUser($_SESSION['user_id'], $status);

$pageTitle   = 'Riwayat Laporan - FASCARE';
$pageHeading = 'Riwayat Laporan Saya';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="app-layout">
    <?php require_once __DIR__ . '/../includes/sidebar_pengguna.php'; ?>
    <div class="main-content">
        <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
        <div class="content-body">
            <?php showFlash(); ?>

            <div class="card">
                <div class="card-header">
                    <h3>Daftar Semua Laporan</h3>
                    <form method="GET" style="display:flex; gap:.5rem;">
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <?php foreach (['Menunggu', 'Diproses', 'Selesai', 'Ditolak'] as $st): ?>
                                <option value="<?= $st ?>" <?= $status === $st ? 'selected' : '' ?>><?= $st ?></option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th>Tanggal Lapor</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($daftar)): ?>
                                <tr><td colspan="7" style="text-align:center;color:var(--text-muted);">Tidak ada data laporan ditemukan.</td></tr>
                            <?php else: ?>
                                <?php foreach ($daftar as $row): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($row['kode_laporan']) ?></strong></td>
                                        <td><?= htmlspecialchars($row['judul']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                        <td><?= htmlspecialchars($row['lokasi']) ?></td>
                                        <td><?= formatTanggal($row['created_at']) ?></td>
                                        <td><?= badgeStatus($row['status']) ?></td>
                                        <td><a href="detail_laporan.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Lihat Detail</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>