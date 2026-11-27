<?php
require_once __DIR__ . '/classes/Auth.php';
require_once __DIR__ . '/classes/Validasi.php';
require_once __DIR__ . '/includes/flash_message.php';

$auth = new Auth();
if (Auth::check()) {
    header("Location: " . BASE_URL . "pengguna/dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim_nip  = Validasi::bersih($_POST['nim_nip'] ?? '');
    $nama     = Validasi::bersih($_POST['nama'] ?? '');
    $email    = Validasi::bersih($_POST['email'] ?? '');
    $no_hp    = Validasi::bersih($_POST['no_hp'] ?? '');
    $password = $_POST['password'] ?? '';
    $konfirm  = $_POST['konfirmasi_password'] ?? '';

    if (!Validasi::wajib($nim_nip) || !Validasi::wajib($nama) || !Validasi::wajib($email) || !Validasi::wajib($password)) {
        setFlash('danger', 'Semua kolom bertanda * wajib diisi!');
    } elseif (!Validasi::email($email)) {
        setFlash('danger', 'Format email tidak valid!');
    } elseif (!Validasi::minimalKarakter($password, 6)) {
        setFlash('danger', 'Password minimal 6 karakter!');
    } elseif ($password !== $konfirm) {
        setFlash('danger', 'Konfirmasi password tidak cocok!');
    } else {
        $hasil = $auth->register($nim_nip, $nama, $email, $no_hp, $password);
        if ($hasil['status']) {
            setFlash('success', $hasil['pesan']);
            header("Location: login.php");
            exit;
        } else {
            setFlash('danger', $hasil['pesan']);
        }
    }
}

$pageTitle = 'Daftar Akun - FASCARE';
require_once __DIR__ . '/includes/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h2><i class="fas fa-user-plus"></i> Daftar Akun</h2>
            <p style="color:var(--text-muted); font-size:.92rem;">Buat akun pelapor fasilitas kampus FASCARE</p>
        </div>

        <?php showFlash(); ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>NIM *</label>
                <input type="text" name="nim_nip" class="form-control" placeholder="Masukkan NIM Anda" required>
            </div>
            <div class="form-group">
                <label>Nama Lengkap *</label>
                <input type="text" name="nama" class="form-control" placeholder="Nama lengkap sesuai KTM" required>
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" class="form-control" placeholder="nama@student.ac.id" required>
            </div>
            <div class="form-group">
                <label>No. WhatsApp / HP</label>
                <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx">
            </div>
            <div class="form-group">
                <label>Password * (Min. 6 karakter)</label>
                <input type="password" name="password" class="form-control" placeholder="Buat password" required>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password *</label>
                <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block" style="margin-top:1.2rem;">
                <i class="fas fa-check-circle"></i> Daftar Sekarang
            </button>
        </form>

        <p style="text-align:center; margin-top:1.3rem; font-size:.9rem; color:var(--text-muted);">
            Sudah punya akun? <a href="login.php" style="color:var(--primary); font-weight:600;">Masuk di sini</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>