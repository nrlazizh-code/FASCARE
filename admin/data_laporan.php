<?php
require_once __DIR__ . '/../includes/auth_check.php';
wajibLogin('admin');

require_once __DIR__ . '/../classes/LaporanRepository.php';
require_once __DIR__ . '/../includes/pagination.php';

$repo    = new LaporanRepository();
$status  = $_GET['status'] ?? '';
$keyword = trim($_GET['q'] ?? '');
$page    = max(1, (int)($_GET['page'] ?? 1));
$limit   = 10;
$offset  = ($page - 1) * $limit;

$totalData = $repo->countAll($status, $keyword);
$daftar    = $repo->getAll($status, $keyword, $limit, $offset);

$pageTitle   = 'Data Laporan - FASCARE';
$pageHeading = 'Manajemen Laporan Fasilitas';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="app-layout">
    <?php require_once __DIR__ . '/../includes/sidebar_admin.php'; ?>
    <div class="main-content">
        <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
        <div class="content-body">
            <?php showFlash(); ?>

            <div class="card">
                <div class="card-header">
                    <div style="display:flex; align-items:center; gap:.8rem;">
                    <h3>Semua Laporan Masuk (<?= $totalData ?>)</h3>
                    <a href="cetak_laporan.php?status=<?= urlencode($status) ?>" target="_blank" class="btn btn-outline btn-sm">
                        <i class="fas fa-print"></i> Cetak Rekap
                    </a>
                    <form method="GET" style="display:flex; gap:.5rem; flex-wrap:wrap;">
                        <input type="text" name="q" class="form-control" style="width:220px;" placeholder="Cari kode, judul, lokasi..." value="<?= htmlspecialchars($keyword) ?>">
                        <select name="status" class="form-control" style="width:160px;">
                            <option value="">Semua Status</option>
                            <?php foreach (['Menunggu', 'Diproses', 'Selesai', 'Ditolak'] as $st): ?>
                                <option value="<?= $st ?>" <?= $status === $st ? 'selected' : '' ?>><?= $st ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        <?php if ($status || $keyword): ?>
                            <a href="data_laporan.php" class="btn btn-outline btn-sm">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Pelapor</th>
                                <th>Kategori</th>
                                <th>Judul & Lokasi</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($daftar)): ?>
                                <tr><td colspan="7" style="text-align:center;color:var(--text-muted);">Data laporan tidak ditemukan.</td></tr>
                            <?php else: ?>
                                <?php foreach ($daftar as $row): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($row['kode_laporan']) ?></strong></td>
                                        <td><?= htmlspecialchars($row['nama_pelapor']) ?><br><small><?= htmlspecialchars($row['nim_nip']) ?></small></td>
                                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($row['judul']) ?></strong><br>
                                            <small style="color:var(--text-muted);"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($row['lokasi']) ?></small>
                                        </td>
                                        <td><?= formatTanggal($row['created_at']) ?></td>
                                        <td><?= badgeStatus($row['status']) ?></td>
                                        <td style="white-space:nowrap;">
                                            <a href="detail_laporan.php?id=<?= $row['id'] ?>" class="btn btn-outline btn-sm">Detail</a>
                                            <a href="kelola_laporan.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Kelola</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php
                $queryParams = http_build_query(array_filter(['status' => $status, 'q' => $keyword]));
                renderPagination($totalData, $limit, $page, $queryParams);
                ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>