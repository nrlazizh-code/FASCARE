<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-building-shield"></i>
        <span>FASCARE <small>Admin</small></span>
    </div>
    <nav class="sidebar-nav">
        <a href="<?= BASE_URL ?>admin/dashboard.php" class="<?= $currentPage == 'dashboard.php' ? 'active' : '' ?>">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>
        <a href="<?= BASE_URL ?>admin/data_laporan.php" class="<?= in_array($currentPage, ['data_laporan.php', 'detail_laporan.php', 'kelola_laporan.php']) ? 'active' : '' ?>">
            <i class="fas fa-clipboard-list"></i> Data Laporan
        </a>
        <a href="<?= BASE_URL ?>admin/kategori.php" class="<?= $currentPage == 'kategori.php' ? 'active' : '' ?>">
            <i class="fas fa-tags"></i> Kategori Fasilitas
        </a>
        <a href="<?= BASE_URL ?>admin/data_pengguna.php" class="<?= $currentPage == 'data_pengguna.php' ? 'active' : '' ?>">
            <i class="fas fa-users"></i> Data Pengguna
        </a>
        <a href="<?= BASE_URL ?>admin/profil.php" class="<?= $currentPage == 'profil.php' ? 'active' : '' ?>">
            <i class="fas fa-user-shield"></i> Profil Admin
        </a>
        <a href="<?= BASE_URL ?>logout.php?role=admin" class="nav-logout" onclick="return confirm('Yakin ingin keluar?')">
            <i class="fas fa-sign-out-alt"></i> Keluar
        </a>
    </nav>
</aside>