<?php
require_once __DIR__ . '/../includes/auth_check.php';
wajibLogin('admin');

require_once __DIR__ . '/../classes/LaporanRepository.php';
require_once __DIR__ . '/../classes/Validasi.php';
require_once __DIR__ . '/../includes/flash_message.php';

$id   = (int)($_GET['id'] ?? 0);
$repo = new LaporanRepository();
$data = $repo->getById($id);

if (!$data) {
    setFlash('danger', 'Laporan tidak ditemukan.');
    header("Location: data_laporan.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status  = Validasi::bersih($_POST['status'] ?? '');
    $catatan = Validasi::bersih($_POST['catatan_admin'] ?? '');

    if (!in_array($status, ['Menunggu', 'Diproses', 'Selesai', 'Ditolak'])) {
        setFlash('danger', 'Status tidak valid!');
    } elseif ($repo->updateStatus($id, $status, $catatan)) {
        setFlash('success', "Status laporan {$data['kode_laporan']} diperbarui menjadi {$status} & notifikasi dikirim ke pelapor!");
        header("Location: detail_laporan.php?id={$id}");
        exit;
    } else {
        setFlash('danger', 'Gagal memperbarui status laporan.');
    }
}

$pageTitle   = 'Kelola Laporan - Admin FASCARE';
$pageHeading = 'Tindak Lanjut Laporan';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="app-layout">
    <?php require_once __DIR__ . '/../includes/sidebar_admin.php'; ?>
    <div class="main-content">
        <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
        <div class="content-body">
            <?php showFlash(); ?>

            <div class="card" style="max-width:720px;">
                <div class="card-header">
                    <h3>Update Status: <?= htmlspecialchars($data['kode_laporan']) ?></h3>
                </div>
                <p style="margin-bottom:1rem; color:var(--text-muted);">
                    <strong>Judul:</strong> <?= htmlspecialchars($data['judul']) ?> | <strong>Lokasi:</strong> <?= htmlspecialchars($data['lokasi']) ?>
                </p>

                <form method="POST">
                    <div class="form-group">
                        <label>Status Penanganan *</label>
                        <select name="status" class="form-control" required>
                            <?php foreach (['Menunggu', 'Diproses', 'Selesai', 'Ditolak'] as $st): ?>
                                <option value="<?= $st ?>" <?= $data['status'] === $st ? 'selected' : '' ?>><?= $st ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Catatan / Keterangan untuk Mahasiswa</label>
                        <textarea name="catatan_admin" rows="4" class="form-control" placeholder="Contoh: Teknisi sedang menuju lokasi, atau alasan penolakan laporan..."><?= htmlspecialchars($data['catatan_admin'] ?? '') ?></textarea>
                    </div>
                    <div style="display:flex; gap:.8rem;">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan & Kirim Notifikasi</button>
                        <a href="detail_laporan.php?id=<?= $id ?>" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>