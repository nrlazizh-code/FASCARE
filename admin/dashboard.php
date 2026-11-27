<?php
require_once __DIR__ . '/../includes/auth_check.php';
wajibLogin('admin');

require_once __DIR__ . '/../classes/LaporanRepository.php';

$repo         = new LaporanRepository();
$stat         = $repo->getStatistikGlobal();
$statKategori = $repo->getStatistikPerKategori();
$recent       = $repo->getAll('', '', 6, 0);

$labelKategori = array_column($statKategori, 'nama_kategori');
$dataKategori  = array_column($statKategori, 'jumlah');

$pageTitle   = 'Dashboard Admin - FASCARE';
$pageHeading = 'Dashboard Petugas Sarpras';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="app-layout">
    <?php require_once __DIR__ . '/../includes/sidebar_admin.php'; ?>
    <div class="main-content">
        <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
        <div class="content-body">
            <?php showFlash(); ?>

            <div class="stats-grid">
                <div class="stat-card">
                    <div><span style="color:var(--text-muted);font-size:.85rem;">Seluruh Laporan</span><h3><?= $stat['total'] ?? 0 ?></h3></div>
                    <div class="stat-icon" style="background:#eff6ff;color:var(--primary);"><i class="fas fa-clipboard-list"></i></div>
                </div>
                <div class="stat-card">
                    <div><span style="color:var(--text-muted);font-size:.85rem;">Menunggu Verifikasi</span><h3><?= $stat['menunggu'] ?? 0 ?></h3></div>
                    <div class="stat-icon" style="background:#fef3c7;color:var(--warning);"><i class="fas fa-hourglass-half"></i></div>
                </div>
                <div class="stat-card">
                    <div><span style="color:var(--text-muted);font-size:.85rem;">Sedang Diproses</span><h3><?= $stat['diproses'] ?? 0 ?></h3></div>
                    <div class="stat-icon" style="background:#e0f2fe;color:var(--info);"><i class="fas fa-cogs"></i></div>
                </div>
                <div class="stat-card">
                    <div><span style="color:var(--text-muted);font-size:.85rem;">Selesai Diperbaiki</span><h3><?= $stat['selesai'] ?? 0 ?></h3></div>
                    <div class="stat-icon" style="background:#d1fae5;color:var(--success);"><i class="fas fa-check-double"></i></div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Statistik Kerusakan Berdasarkan Kategori Fasilitas</h3>
                </div>
                <div style="height:260px;">
                    <canvas id="kategoriChart"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Laporan Masuk Terbaru</h3>
                    <a href="data_laporan.php" class="btn btn-primary btn-sm">Lihat Semua Laporan</a>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Pelapor</th>
                                <th>Judul Kerusakan</th>
                                <th>Lokasi</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent)): ?>
                                <tr><td colspan="7" style="text-align:center;color:var(--text-muted);">Belum ada laporan masuk.</td></tr>
                            <?php else: ?>
                                <?php foreach ($recent as $row): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($row['kode_laporan']) ?></strong></td>
                                        <td><?= htmlspecialchars($row['nama_pelapor']) ?> <br><small style="color:var(--text-muted);"><?= htmlspecialchars($row['nim_nip']) ?></small></td>
                                        <td><?= htmlspecialchars($row['judul']) ?></td>
                                        <td><?= htmlspecialchars($row['lokasi']) ?></td>
                                        <td><?= formatTanggal($row['created_at']) ?></td>
                                        <td><?= badgeStatus($row['status']) ?></td>
                                        <td>
                                            <a href="detail_laporan.php?id=<?= $row['id'] ?>" class="btn btn-outline btn-sm">Detail</a>
                                            <a href="kelola_laporan.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Tindak Lanjuti</a>
                                        </td>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('kategoriChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labelKategori) ?>,
        datasets: [{
            label: 'Jumlah Laporan',
            data: <?= json_encode($dataKategori) ?>,
            backgroundColor: '#2563eb',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>