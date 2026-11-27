<?php
require_once __DIR__ . '/../includes/auth_check.php';
wajibLogin('admin');

require_once __DIR__ . '/../classes/LaporanRepository.php';

$id   = (int)($_GET['id'] ?? 0);
$repo = new LaporanRepository();
$data = $repo->getById($id);

if (!$data) {
    setFlash('danger', 'Data laporan tidak ditemukan.');
    header("Location: data_laporan.php");
    exit;
}

$pageTitle   = 'Detail Laporan - Admin FASCARE';
$pageHeading = 'Detail Laporan ' . $data['kode_laporan'];
require_once __DIR__ . '/../includes/header.php';
?>

<div class="app-layout">
    <?php require_once __DIR__ . '/../includes/sidebar_admin.php'; ?>
    <div class="main-content">
        <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
        <div class="content-body">
            <div style="display:flex; justify-content:space-between; margin-bottom:1rem;">
                <a href="data_laporan.php" class="btn btn-outline btn-sm">&larr; Kembali</a>
                <a href="kelola_laporan.php?id=<?= $data['id'] ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Ubah Status & Catatan</a>
            </div>

            <div class="detail-grid">
                <div class="card">
                    <div class="card-header">
                        <h3><?= htmlspecialchars($data['judul']) ?></h3>
                        <?= badgeStatus($data['status']) ?>
                    </div>
                    <p style="margin-bottom:.6rem;"><strong>Pelapor:</strong> <?= htmlspecialchars($data['nama_pelapor']) ?> (<?= htmlspecialchars($data['nim_nip']) ?>)</p>
                    <p style="margin-bottom:.6rem;"><strong>Kontak Pelapor:</strong> <?= htmlspecialchars($data['email']) ?> / <?= htmlspecialchars($data['no_hp'] ?: '-') ?></p>
                    <p style="margin-bottom:.6rem;"><strong>Kategori:</strong> <?= htmlspecialchars($data['nama_kategori']) ?></p>
                    <p style="margin-bottom:.6rem;"><strong>Lokasi:</strong> <?= htmlspecialchars($data['lokasi']) ?></p>
                    <p style="margin-bottom:.6rem;"><strong>Tanggal Masuk:</strong> <?= formatTanggal($data['created_at']) ?></p>
                    <p style="margin-bottom:.4rem;"><strong>Deskripsi Kerusakan:</strong></p>
                    <p style="background:#f8fafc; padding:1rem; border-radius:8px; border:1px solid var(--border);"><?= nl2br(htmlspecialchars($data['deskripsi'])) ?></p>

                    <div style="margin-top:1.2rem; padding:1rem; background:#eff6ff; border-radius:8px;">
                        <strong>Catatan Admin Terakhir:</strong>
                        <p style="margin-top:.3rem;"><?= !empty($data['catatan_admin']) ? nl2br(htmlspecialchars($data['catatan_admin'])) : '<em>Belum ada catatan.</em>' ?></p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3>Foto Bukti Laporan</h3></div>
                    <img src="<?= BASE_URL ?>uploads/laporan/<?= htmlspecialchars($data['foto']) ?>" alt="Foto Laporan" class="preview-img">
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>