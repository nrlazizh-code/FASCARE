<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth_check.php';
wajibLogin('pengguna');

require_once __DIR__ . '/../classes/LaporanRepository.php';
require_once __DIR__ . '/../includes/flash_message.php';
require_once __DIR__ . '/../includes/functions.php';

$id   = (int)($_GET['id'] ?? 0);
$repo = new LaporanRepository();
$data = $repo->getById($id);

if (!$data || (int)$data['user_id'] !== (int)$_SESSION['user_id']) {
    header("Location: riwayat.php");
    exit;
}

$pageTitle   = 'Detail Laporan - FASCARE';
$pageHeading = 'Detail Laporan ' . $data['kode_laporan'];
require_once __DIR__ . '/../includes/header.php';
?>

<div class="app-layout">
    <?php require_once __DIR__ . '/../includes/sidebar_pengguna.php'; ?>
    <div class="main-content">
        <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
        <div class="content-body">
            <a href="riwayat.php" class="btn btn-outline btn-sm" style="margin-bottom:1rem;">&larr; Kembali ke Riwayat</a>

            <div class="detail-grid">
                <div class="card">
                    <div class="card-header">
                        <h3><?= htmlspecialchars($data['judul']) ?></h3>
                        <?= badgeStatus($data['status']) ?>
                    </div>
                    <p style="margin-bottom:.8rem;"><strong>Kode Laporan:</strong> <?= htmlspecialchars($data['kode_laporan']) ?></p>
                    <p style="margin-bottom:.8rem;"><strong>Kategori:</strong> <?= htmlspecialchars($data['nama_kategori']) ?></p>
                    <p style="margin-bottom:.8rem;"><strong>Lokasi Fasilitas:</strong> <?= htmlspecialchars($data['lokasi']) ?></p>
                    <p style="margin-bottom:.8rem;"><strong>Waktu Dilaporkan:</strong> <?= formatTanggal($data['created_at']) ?></p>
                    <p style="margin-bottom:.4rem;"><strong>Deskripsi Kerusakan:</strong></p>
                    <p style="background:#f8fafc; padding:1rem; border-radius:8px; border:1px solid var(--border);"><?= nl2br(htmlspecialchars($data['deskripsi'])) ?></p>

                    <div style="margin-top:1.5rem; padding:1rem; border-radius:8px; background:#eff6ff; border-left:4px solid var(--primary);">
                        <strong>Catatan / Tanggapan Petugas Sarpras:</strong>
                        <p style="margin-top:.4rem; color:var(--text-main);">
                            <?= !empty($data['catatan_admin']) ? nl2br(htmlspecialchars($data['catatan_admin'])) : '<em>Belum ada catatan dari petugas.</em>' ?>
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Foto Bukti Kerusakan</h3>
                    </div>
                    <img src="<?= BASE_URL ?>uploads/laporan/<?= htmlspecialchars($data['foto']) ?>" alt="Bukti Kerusakan" class="preview-img">
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>