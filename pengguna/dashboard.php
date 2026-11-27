<?php
require_once __DIR__ . '/../includes/auth_check.php';
wajibLogin('pengguna');

require_once __DIR__ . '/../classes/Pengguna.php';
require_once __DIR__ . '/../classes/LaporanRepository.php';

$penggunaObj = new Pengguna();$repo        = new LaporanRepository();

$userId =$_SESSION['user_id'];
$stat   =$penggunaObj->getStatistikLaporan($userId);$recent = array_slice($repo->getByUser($userId), 0, 5);

$pageTitle   = 'Dashboard Pengguna - FASCARE';$pageHeading = 'Dashboard Mahasiswa';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="app-layout">
    <?php require_once __DIR__ . '/../includes/sidebar_pengguna.php'; ?>
    <div class="main-content">
        <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
        <div class="content-body">
            <?php showFlash(); ?>

            <div class="stats-grid">
                <div class="stat-card">
                    <div><span style="color:var(--text-muted);font-size:.85rem;">Total Laporan</span><h3><?= $stat['total'] ?? 0 ?></h3></div>
                    <div class="stat-icon" style="background:#eff6ff;color:var(--primary);"><i class="fas fa-file-alt"></i></div>
                </div>
                <div class="stat-card">
                    <div><span style="color:var(--text-muted);font-size:.85rem;">Menunggu</span><h3><?= $stat['menunggu'] ?? 0 ?></h3></div>
                    <div class="stat-icon" style="background:#fef3c7;color:var(--warning);"><i class="fas fa-clock"></i></div>
                </div>
                <div class="stat-card">
                    <div><span style="color:var(--text-muted);font-size:.85rem;">Diproses</span><h3><?= $stat['diproses'] ?? 0 ?></h3></div>
                    <div class="stat-icon" style="background:#e0f2fe;color:var(--info);"><i class="fas fa-tools"></i></div>
                </div>
                <div class="stat-card">
                    <div><span style="color:var(--text-muted);font-size:.85rem;">Selesai</span><h3><?= $stat['selesai'] ?? 0 ?></h3></div>
                    <div class="stat-icon" style="background:#d1fae5;color:var(--success);"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Laporan Terbaru Saya</h3>
                    <a href="laporan.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Buat Laporan Baru</a>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Judul Kerusakan</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent)): ?>
                                <tr><td colspan="7" style="text-align:center;color:var(--text-muted);">Belum ada laporan yang dikirim.</td></tr>
                            <?php else: ?>
                                <?php foreach ($recent as$row): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($row['kode_laporan']) ?></strong></td>
                                        <td><?= htmlspecialchars($row['judul']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                        <td><?= htmlspecialchars($row['lokasi']) ?></td>
                                        <td><?= formatTanggal($row['created_at']) ?></td>
                                        <td><?= badgeStatus($row['status']) ?></td>
                                        <td><a href="detail_laporan.php?id=<?= $row['id'] ?>" class="btn btn-outline btn-sm">Detail</a></td>
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