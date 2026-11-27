<?php
require_once __DIR__ . '/../includes/auth_check.php';
wajibLogin('admin');

require_once __DIR__ . '/../classes/Kategori.php';
require_once __DIR__ . '/../classes/Validasi.php';
require_once __DIR__ . '/../includes/flash_message.php';

$katObj = new Kategori();
$editData = null;

if (isset($_GET['edit'])) {
    $editData = $katObj->getById((int)$_GET['edit']);
}

if (isset($_GET['hapus'])) {
    if ($katObj->hapus((int)$_GET['hapus'])) {
        setFlash('success', 'Kategori berhasil dihapus.');
    } else {
        setFlash('danger', 'Kategori tidak dapat dihapus karena masih digunakan pada data laporan.');
    }
    header("Location: kategori.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = (int)($_POST['id'] ?? 0);
    $nama = Validasi::bersih($_POST['nama_kategori'] ?? '');
    $desk = Validasi::bersih($_POST['deskripsi'] ?? '');

    if (!Validasi::wajib($nama)) {
        setFlash('danger', 'Nama kategori wajib diisi!');
    } else {
        if ($id > 0) {
            $katObj->ubah($id, $nama, $desk);
            setFlash('success', 'Kategori berhasil diperbarui!');
        } else {
            $katObj->tambah($nama, $desk);
            setFlash('success', 'Kategori baru berhasil ditambahkan!');
        }
        header("Location: kategori.php");
        exit;
    }
}

$daftarKategori = $katObj->getAll();

$pageTitle   = 'Kategori Fasilitas - Admin FASCARE';
$pageHeading = 'Manajemen Kategori Fasilitas';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="app-layout">
    <?php require_once __DIR__ . '/../includes/sidebar_admin.php'; ?>
    <div class="main-content">
        <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
        <div class="content-body">
            <?php showFlash(); ?>

            <div class="detail-grid">
                <div class="card">
                    <div class="card-header"><h3>Daftar Kategori</h3></div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($daftarKategori as $k): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($k['nama_kategori']) ?></strong></td>
                                        <td><?= htmlspecialchars($k['deskripsi']) ?></td>
                                        <td style="white-space:nowrap;">
                                            <a href="kategori.php?edit=<?= $k['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
                                            <a href="kategori.php?hapus=<?= $k['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus kategori ini?')">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3><?= $editData ? 'Edit Kategori' : 'Tambah Kategori Baru' ?></h3></div>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?= $editData['id'] ?? 0 ?>">
                        <div class="form-group">
                            <label>Nama Kategori *</label>
                            <input type="text" name="nama_kategori" class="form-control" value="<?= htmlspecialchars($editData['nama_kategori'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" rows="3" class="form-control"><?= htmlspecialchars($editData['deskripsi'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"><?= $editData ? 'Simpan Perubahan' : 'Tambah Kategori' ?></button>
                        <?php if ($editData): ?>
                            <a href="kategori.php" class="btn btn-outline">Batal</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>