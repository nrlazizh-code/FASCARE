<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-building-shield"></i>
        <span>FASCARE</span>
    </div>
    <nav class="sidebar-nav">
        <a href="<?= BASE_URL ?>pengguna/dashboard.php" class="<?= $currentPage == 'dashboard.php' ? 'active' : '' ?>">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="<?= BASE_URL ?>pengguna/laporan.php" class="<?= $currentPage == 'laporan.php' ? 'active' : '' ?>">
            <i class="fas fa-plus-circle"></i> Buat Laporan
        </a>
        <a href="<?= BASE_URL ?>pengguna/riwayat.php" class="<?= in_array($currentPage, ['riwayat.php', 'detail_laporan.php']) ? 'active' : '' ?>">
            <i class="fas fa-history"></i> Riwayat Laporan
        </a>
        <a href="<?= BASE_URL ?>pengguna/profil.php" class="<?= $currentPage == 'profil.php' ? 'active' : '' ?>">
            <i class="fas fa-user-cog"></i> Profil Saya
        </a>
        <a href="<?= BASE_URL ?>logout.php" class="nav-logout" onclick="return confirm('Yakin ingin keluar?')">
            <i class="fas fa-sign-out-alt"></i> Keluar
        </a>
    </nav>
</aside>