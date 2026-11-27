<?php
require_once __DIR__ . '/../includes/auth_check.php';
wajibLogin('pengguna');

require_once __DIR__ . '/../classes/Kategori.php';
require_once __DIR__ . '/../classes/Laporan.php';
require_once __DIR__ . '/../classes/LaporanRepository.php';
require_once __DIR__ . '/../classes/Validasi.php';
require_once __DIR__ . '/../includes/upload_helper.php';
require_once __DIR__ . '/../includes/flash_message.php';

$kategoriObj = new Kategori();
$listKategori = $kategoriObj->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori_id = (int)($_POST['kategori_id'] ?? 0);
    $judul       = Validasi::bersih($_POST['judul'] ?? '');
    $lokasi      = Validasi::bersih($_POST['lokasi'] ?? '');
    $deskripsi   = Validasi::bersih($_POST['deskripsi'] ?? '');

    if ($kategori_id <= 0 || !Validasi::wajib($judul) || !Validasi::wajib($lokasi) || !Validasi::wajib($deskripsi)) {
        setFlash('danger', 'Semua kolom form laporan wajib diisi!');
    } else {
        $upload = uploadGambar($_FILES['foto'] ?? null, 'laporan');
        if (!$upload['status']) {
            setFlash('danger', $upload['pesan']);
        } else {
            $laporan = new Laporan([
                'user_id'     => $_SESSION['user_id'],
                'kategori_id' => $kategori_id,
                'judul'       => $judul,
                'deskripsi'   => $deskripsi,
                'lokasi'      => $lokasi,
                'foto'        => $upload['file_name']
            ]);

            $repo = new LaporanRepository();
            if ($repo->simpan($laporan)) {
                setFlash('success', "Laporan berhasil dikirim dengan kode {$laporan->kode_laporan}!");
                header("Location: riwayat.php");
                exit;
            } else {
                setFlash('danger', 'Gagal menyimpan laporan ke database.');
            }
        }
    }
}

$pageTitle   = 'Buat Laporan - FASCARE';
$pageHeading = 'Buat Laporan Kerusakan';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="app-layout">
    <?php require_once __DIR__ . '/../includes/sidebar_pengguna.php'; ?>
    <div class="main-content">
        <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
        <div class="content-body">
            <?php showFlash(); ?>

            <div class="card" style="max-width:780px;">
                <div class="card-header">
                    <h3>Formulir Pelaporan Fasilitas</h3>
                </div>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Kategori Fasilitas *</label>
                        <select name="kategori_id" class="form-control" required>
                            <option value="">-- Pilih Kategori Fasilitas --</option>
                            <?php foreach ($listKategori as $kat): ?>
                                <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Judul Kerusakan *</label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Proyektor Ruang Lab 2 Tidak Menyala" required>
                    </div>
                    <div class="form-group">
                        <label>Lokasi Spesifik *</label>
                        <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Gedung B Lantai 3, Ruang B.302" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Detail Kerusakan *</label>
                        <textarea name="deskripsi" rows="4" class="form-control" placeholder="Jelaskan kondisi kerusakan secara rinci..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Foto Bukti Kerusakan * (JPG/PNG/WEBP, Maks. 3MB)</label>
                        <input type="file" name="foto" id="fotoInput" class="form-control" accept="image/*" required>
                        <img id="fotoPreview" src="#" alt="Preview Foto" style="display:none; margin-top:1rem; max-height:220px; border-radius:8px; border:1px solid var(--border);">
                    </div>
                    <div style="display:flex; gap:.8rem; margin-top:1.5rem;">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim Laporan</button>
                        <a href="dashboard.php" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>