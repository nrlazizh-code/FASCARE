<?php
require_once __DIR__ . '/../includes/auth_check.php';
wajibLogin('admin');

require_once __DIR__ . '/../classes/Admin.php';
require_once __DIR__ . '/../classes/Validasi.php';
require_once __DIR__ . '/../includes/upload_helper.php';
require_once __DIR__ . '/../includes/flash_message.php';

$adminObj = new Admin();
$userId   = $_SESSION['user_id'];
$profil   = $adminObj->getById($userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';

    if ($aksi === 'update_profil') {
        $nama     = Validasi::bersih($_POST['nama'] ?? '');
        $email    = Validasi::bersih($_POST['email'] ?? '');
        $no_hp    = Validasi::bersih($_POST['no_hp'] ?? '');
        $fotoBaru = null;

        if (!empty($_FILES['foto_profil']['name'])) {
            $upload = uploadGambar($_FILES['foto_profil'], 'profil');
            if (!$upload['status']) {
                setFlash('danger', $upload['pesan']);
                header("Location: profil.php");
                exit;
            }
            $fotoBaru = $upload['file_name'];
        }

        if (!Validasi::wajib($nama) || !Validasi::email($email)) {
            setFlash('danger', 'Nama dan Email yang valid wajib diisi!');
        } elseif ($adminObj->updateProfil($userId, $nama, $email, $no_hp, $fotoBaru)) {
            $_SESSION['nama'] = $nama;
            if ($fotoBaru) {
                $_SESSION['foto'] = $fotoBaru;
            }
            setFlash('success', 'Profil admin berhasil diperbarui!');
            header("Location: profil.php");
            exit;
        }
    } elseif ($aksi === 'hapus_foto') {
        if ($adminObj->hapusFotoProfil($userId)) {
            $_SESSION['foto'] = 'default.png';
            setFlash('success', 'Foto profil admin berhasil dihapus.');
        }
        header("Location: profil.php");
        exit;
    } elseif ($aksi === 'ubah_password') {
        $lama = $_POST['password_lama'] ?? '';
        $baru = $_POST['password_baru'] ?? '';

        if (!Validasi::minimalKarakter($baru, 6)) {
            setFlash('danger', 'Password baru minimal 6 karakter!');
        } elseif ($adminObj->ubahPassword($userId, $lama, $baru)) {
            setFlash('success', 'Password admin berhasil diubah!');
            header("Location: profil.php");
            exit;
        } else {
            setFlash('danger', 'Password lama salah!');
        }
    }
}

$pageTitle   = 'Profil Admin - FASCARE';
$pageHeading = 'Profil Administrator';
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
                    <div class="card-header">
                        <h3>Informasi Administrator</h3>
                        <?php if (!empty($profil['foto_profil']) && $profil['foto_profil'] !== 'default.png'): ?>
                            <form method="POST" style="margin:0;" onsubmit="return confirm('Hapus foto profil saat ini?')">
                                <input type="hidden" name="aksi" value="hapus_foto">
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus Foto</button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="aksi" value="update_profil">
                        <div class="form-group" style="display:flex; align-items:center; gap:1rem;">
                            <?php if (!empty($profil['foto_profil']) && $profil['foto_profil'] !== 'default.png'): ?>
                                <img src="<?= BASE_URL ?>uploads/profil/<?= htmlspecialchars($profil['foto_profil']) ?>" alt="Foto Profil" style="width:68px; height:68px; border-radius:50%; object-fit:cover; border:2px solid var(--primary);">
                            <?php else: ?>
                                <div style="width:68px; height:68px; border-radius:50%; background:#eff6ff; color:var(--primary); display:flex; align-items:center; justify-content:center; font-size:2rem;">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                            <?php endif; ?>
                            <div style="flex:1;">
                                <label>Ganti Foto Profil (Opsional)</label>
                                <input type="file" name="foto_profil" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>NIP / Kode Admin</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($profil['nim_nip']) ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($profil['nama']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($profil['email']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>No. HP / WhatsApp</label>
                            <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($profil['no_hp'] ?? '') ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>

                <div class="card">
                    <div class="card-header"><h3>Ganti Password</h3></div>
                    <form method="POST">
                        <input type="hidden" name="aksi" value="ubah_password">
                        <div class="form-group">
                            <label>Password Lama</label>
                            <input type="password" name="password_lama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Password Baru (Min. 6 karakter)</label>
                            <input type="password" name="password_baru" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-outline">Perbarui Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>