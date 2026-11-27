<?php
require_once __DIR__ . '/../includes/auth_check.php';
wajibLogin('admin');

require_once __DIR__ . '/../classes/Admin.php';
require_once __DIR__ . '/../includes/flash_message.php';

$adminObj = new Admin();

if (isset($_GET['hapus'])) {
    if ($adminObj->hapusUser((int)$_GET['hapus'])) {
        setFlash('success', 'Akun pengguna berhasil dihapus.');
    } else {
        setFlash('danger', 'Gagal menghapus akun.');
    }
    header("Location: data_pengguna.php");
    exit;
}

$users = $adminObj->getAllPengguna();

$pageTitle   = 'Data Pengguna - Admin FASCARE';
$pageHeading = 'Manajemen Data Pengguna';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="app-layout">
    <?php require_once __DIR__ . '/../includes/sidebar_admin.php'; ?>
    <div class="main-content">
        <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
        <div class="content-body">
            <?php showFlash(); ?>

            <div class="card">
                <div class="card-header"><h3>Daftar Akun Terdaftar (<?= count($users) ?>)</h3></div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>NIM / NIP</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>No. HP</th>
                                <th>Role</th>
                                <th>Terdaftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($u['nim_nip']) ?></strong></td>
                                    <td><?= htmlspecialchars($u['nama']) ?></td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td><?= htmlspecialchars($u['no_hp'] ?: '-') ?></td>
                                    <td>
                                        <span class="badge <?= $u['role'] === 'admin' ? 'badge-info' : 'badge-success' ?>">
                                            <?= strtoupper($u['role']) ?>
                                        </span>
                                    </td>
                                    <td><?= formatTanggal($u['created_at']) ?></td>
                                    <td>
                                        <?php if ($u['role'] !== 'admin'): ?>
                                            <a href="data_pengguna.php?hapus=<?= $u['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pengguna ini beserta seluruh laporannya?')">Hapus</a>
                                        <?php else: ?>
                                            <span style="color:var(--text-muted);font-size:.82rem;">Protected</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>