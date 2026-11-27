<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Auth.php';
require_once __DIR__ . '/classes/Validasi.php';

$auth = new Auth();
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identity = Validasi::bersih($_POST['identity'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!Validasi::wajib($identity) || !Validasi::wajib($password)) {
        $errorMsg = 'Email/NIM dan Password wajib diisi!';
    } else {
        $hasil = $auth->login($identity, $password);
        if ($hasil['status']) {
            header("Location: " . $hasil['redirect']);
            exit;
        } else {
            $errorMsg = $hasil['pesan'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - FASCARE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h2><i class="fas fa-building-shield"></i> FASCARE</h2>
            <p style="color:var(--text-muted); font-size:.92rem;">Masuk menggunakan NIM/NIP atau Email kampus</p>
        </div>

        <?php if (!empty($errorMsg)): ?>
            <div class="alert alert-danger">
                <span><?= htmlspecialchars($errorMsg) ?></span>
                <button type="button" class="alert-close" onclick="this.parentElement.remove()">&times;</button>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>NIM / NIP atau Email</label>
                <input type="text" name="identity" class="form-control" placeholder="Contoh: 2024001 atau budi@student.ac.id" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block" style="margin-top:1.2rem;">
                <i class="fas fa-sign-in-alt"></i> Masuk
            </button>
        </form>

        <p style="text-align:center; margin-top:1.3rem; font-size:.9rem; color:var(--text-muted);">
            Belum punya akun mahasiswa? <a href="register.php" style="color:var(--primary); font-weight:600;">Daftar di sini</a>
        </p>
        <p style="text-align:center; margin-top:.5rem; font-size:.85rem;">
            <a href="index.php" style="color:var(--secondary);">&larr; Kembali ke Beranda</a>
        </p>
    </div>
</div>
</body>
</html>