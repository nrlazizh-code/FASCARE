<?php
require_once __DIR__ . '/classes/Auth.php';
require_once __DIR__ . '/classes/LaporanRepository.php';

if (Auth::check()) {
    $target = ($_SESSION['role'] === 'admin') ? 'admin/dashboard.php' : 'pengguna/dashboard.php';
    header("Location: " . BASE_URL . $target);
    exit;
}

$repo = new LaporanRepository();
$stat = $repo->getStatistikGlobal();

$pageTitle = 'FASCARE - Sistem Pelaporan Fasilitas Kampus';
require_once __DIR__ . '/includes/header.php';
?>

<nav class="landing-nav">
    <a href="<?= BASE_URL ?>" class="brand-logo">
        <i class="fas fa-building-shield"></i> FASCARE
    </a>
    <div style="display:flex; gap:.8rem;">
        <a href="login.php" class="btn btn-outline">Masuk</a>
        <a href="register.php" class="btn btn-primary">Daftar Akun</a>
    </div>
</nav>

<section class="hero">
    <h1>Jaga Fasilitas Kampus Tetap Nyaman & Layak Pakai</h1>
    <p>Laporkan kerusakan kursi, proyektor, lampu, pintu, maupun fasilitas kampus lainnya secara cepat, transparan, dan terpantau langsung oleh tim Sarpras.</p>
    <div style="display:flex; gap:1rem; justify-content:center; flex-wrap:wrap;">
        <a href="register.php" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Mulai Lapor Sekarang</a>
        <a href="login.php" class="btn btn-outline" style="background:#fff;"><i class="fas fa-sign-in-alt"></i> Masuk ke Akun</a>
    </div>
</section>

<section class="features-grid">
    <div class="feature-card">
        <i class="fas fa-camera"></i>
        <h3>Lapor dengan Bukti Foto</h3>
        <p style="color:var(--text-muted); margin-top:.5rem;">Unggah foto kerusakan beserta lokasi spesifik ruangan agar petugas cepat menangani.</p>
    </div>
    <div class="feature-card">
        <i class="fas fa-tasks"></i>
        <h3>Pantau Progres Real-Time</h3>
        <p style="color:var(--text-muted); margin-top:.5rem;">Cek status laporanmu mulai dari Menunggu, Diproses, hingga Selesai diperbaiki.</p>
    </div>
    <div class="feature-card">
        <i class="fas fa-bell"></i>
        <h3>Notifikasi Otomatis</h3>
        <p style="color:var(--text-muted); margin-top:.5rem;">Dapatkan pemberitahuan dan catatan langsung dari admin setiap ada pembaruan status.</p>
    </div>
    <div class="feature-card">
        <i class="fas fa-check-circle"></i>
        <h3><?= $stat['selesai'] ?? 0 ?> Fasilitas Diperbaiki</h3>
        <p style="color:var(--text-muted); margin-top:.5rem;">Dari total <?= $stat['total'] ?? 0 ?> laporan yang masuk dari civitas akademika.</p>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>