<?php
require_once __DIR__ . '/../classes/Notifikasi.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/LaporanRepository.php';

$notifObj = new Notifikasi();
$userObj  = new User();
$userId   = (int)($_SESSION['user_id'] ?? 0);
$roleUser = $_SESSION['role'] ?? 'pengguna';

if (isset($_GET['baca_notif']) && $userId) {
    $notifObj->tandaiDibaca($userId);
}

if (isset($_GET['klik_notif']) && $userId) {
    $idNotifKlik = (int)$_GET['klik_notif'];
    $idLaporan   = (int)($_GET['lap'] ?? 0);
    $notifObj->tandaiSatuDibaca($idNotifKlik, $userId);

    $repoNav = new LaporanRepository();
    $cekLap  = $idLaporan > 0 ? $repoNav->getById($idLaporan) : false;

    if ($roleUser === 'admin') {
        $tujuan = $cekLap ? BASE_URL . "admin/detail_laporan.php?id=$idLaporan" : BASE_URL . "admin/data_laporan.php";
    } else {
        $tujuan = ($cekLap && (int)$cekLap['user_id'] === $userId)
            ? BASE_URL . "pengguna/detail_laporan.php?id=$idLaporan"
            : BASE_URL . "pengguna/riwayat.php";
    }

    echo "<script>window.location.href = " . json_encode($tujuan) . ";</script>";
    exit;
}

$jumlahNotif = $userId ? $notifObj->hitungBelumDibaca($userId, $roleUser) : 0;
$listNotif   = $userId ? $notifObj->getByUser($userId, 6, $roleUser) : [];

// Ambil data foto terbaru langsung dari database
$dataUserNav = $userId ? $userObj->getById($userId) : null;
$fotoProfil  = $dataUserNav['foto_profil'] ?? 'default.png';
$namaTampil  = $dataUserNav['nama'] ?? ($_SESSION['nama'] ?? 'User');
$pathFoto    = __DIR__ . '/../uploads/profil/' . $fotoProfil;
$profilUrl   = ($roleUser === 'admin') ? BASE_URL . 'admin/profil.php' : BASE_URL . 'pengguna/profil.php';
?>
<header class="topbar">
    <div class="topbar-left">
        <button id="sidebarToggle" class="btn-icon"><i class="fas fa-bars"></i></button>
        <h2 class="page-heading"><?= $pageHeading ?? 'Dashboard' ?></h2>
    </div>
    <div class="topbar-right">
        <div class="notif-wrapper">
            <button class="btn-icon notif-btn" id="notifToggle">
                <i class="fas fa-bell"></i>
                <?php if ($jumlahNotif > 0): ?>
                    <span class="notif-badge"><?= $jumlahNotif ?></span>
                <?php endif; ?>
            </button>
            <div class="notif-dropdown" id="notifDropdown">
                <div class="notif-header">
                    <span>Notifikasi</span>
                    <?php if ($jumlahNotif > 0): ?>
                        <a href="?baca_notif=1">Tandai dibaca</a>
                    <?php endif; ?>
                </div>
                <div class="notif-body">
                    <?php if (empty($listNotif)): ?>
                        <p class="notif-empty">Belum ada notifikasi.</p>
                    <?php else: ?>
                        <?php foreach ($listNotif as $n): ?>
                            <a href="?klik_notif=<?= (int)$n['id'] ?>&lap=<?= (int)($n['laporan_id'] ?? 0) ?>" class="notif-item <?= $n['is_read'] ? '' : 'unread' ?>" style="display:block; text-decoration:none; color:inherit; cursor:pointer;">
                                <p style="margin-bottom:.2rem;"><?= htmlspecialchars($n['pesan']) ?></p>
                                <small><?= formatTanggal($n['created_at']) ?></small>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <a href="<?= $profilUrl ?>" class="user-chip" style="display:flex; align-items:center; gap:.55rem; text-decoration:none;" title="Lihat Profil">
            <span style="font-weight:600;"><?= htmlspecialchars($namaTampil) ?></span>
            <?php if (!empty($fotoProfil) && $fotoProfil !== 'default.png' && file_exists($pathFoto)): ?>
                <img src="<?= BASE_URL ?>uploads/profil/<?= htmlspecialchars($fotoProfil) ?>" alt="Profil" style="width:34px; height:34px; border-radius:50%; object-fit:cover; border:2px solid var(--primary);">
            <?php else: ?>
                <i class="fas fa-user-circle" style="font-size:1.5rem; color:var(--primary);"></i>
            <?php endif; ?>
        </a>
    </div>
</header>