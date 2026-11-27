<?php
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Laporan.php';
require_once __DIR__ . '/Notifikasi.php';

class LaporanRepository {
    private $db;
    private $notif;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->notif = new Notifikasi();
    }

    public function simpan(Laporan $laporan) {
        $sql = "INSERT INTO laporan (kode_laporan, user_id, kategori_id, judul, deskripsi, lokasi, foto, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $berhasil = $stmt->execute([
            $laporan->kode_laporan,
            $laporan->user_id,
            $laporan->kategori_id,
            $laporan->judul,
            $laporan->deskripsi,
            $laporan->lokasi,
            $laporan->foto,
            $laporan->status
        ]);

        if ($berhasil) {
            $laporanId = $this->db->lastInsertId();
            $this->notif->kirimKeSemuaAdmin($laporanId, "Laporan baru masuk ({$laporan->kode_laporan}): {$laporan->judul}");
            return true;
        }
        return false;
    }

    public function getAll($status = '', $keyword = '', $limit = 10, $offset = 0) {
        $sql = "SELECT l.*, u.nama as nama_pelapor, u.nim_nip, COALESCE(k.nama_kategori, 'Umum') as nama_kategori 
                FROM laporan l 
                LEFT JOIN users u ON l.user_id = u.id 
                LEFT JOIN kategori k ON l.kategori_id = k.id 
                WHERE 1=1";
        $params = [];

        if (!empty($status)) {
            $sql .= " AND l.status = ?";
            $params[] = $status;
        }
        if (!empty($keyword)) {
            $sql .= " AND (l.judul LIKE ? OR l.kode_laporan LIKE ? OR l.lokasi LIKE ? OR u.nama LIKE ?)";
            $like = "%$keyword%";
            array_push($params, $like, $like, $like, $like);
        }

        $sql .= " ORDER BY l.created_at DESC LIMIT $limit OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function countAll($status = '', $keyword = '') {
        $sql = "SELECT COUNT(*) FROM laporan l LEFT JOIN users u ON l.user_id = u.id WHERE 1=1";
        $params = [];
        if (!empty($status)) {
            $sql .= " AND l.status = ?";
            $params[] = $status;
        }
        if (!empty($keyword)) {
            $sql .= " AND (l.judul LIKE ? OR l.kode_laporan LIKE ? OR l.lokasi LIKE ? OR u.nama LIKE ?)";
            $like = "%$keyword%";
            array_push($params, $like, $like, $like, $like);
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getByUser($userId, $status = '') {
        $sql = "SELECT l.*, COALESCE(k.nama_kategori, 'Umum') as nama_kategori 
                FROM laporan l 
                LEFT JOIN kategori k ON l.kategori_id = k.id 
                WHERE l.user_id = ?";
        $params = [$userId];

        if (!empty($status)) {
            $sql .= " AND l.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY l.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT l.*, u.nama as nama_pelapor, u.nim_nip, u.email, u.no_hp, COALESCE(k.nama_kategori, 'Umum') as nama_kategori 
                FROM laporan l 
                LEFT JOIN users u ON l.user_id = u.id 
                LEFT JOIN kategori k ON l.kategori_id = k.id 
                WHERE l.id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([(int)$id]);
        return $stmt->fetch();
    }

    public function updateStatus($id, $status, $catatan) {
        $laporan = $this->getById($id);
        if (!$laporan) return false;

        $stmt = $this->db->prepare("UPDATE laporan SET status = ?, catatan_admin = ? WHERE id = ?");
        if ($stmt->execute([$status, $catatan, $id])) {
            $pesan = "Status laporan [{$laporan['kode_laporan']}] '{$laporan['judul']}' diubah menjadi: {$status}.";
            $this->notif->kirim($laporan['user_id'], $id, $pesan);
            return true;
        }
        return false;
    }

    public function getStatistikGlobal() {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'Menunggu' THEN 1 ELSE 0 END) as menunggu,
                    SUM(CASE WHEN status = 'Diproses' THEN 1 ELSE 0 END) as diproses,
                    SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) as selesai,
                    SUM(CASE WHEN status = 'Ditolak' THEN 1 ELSE 0 END) as ditolak
                FROM laporan";
        return $this->db->query($sql)->fetch();
    }

    public function getStatistikPerKategori() {
        $sql = "SELECT k.nama_kategori, COUNT(l.id) as jumlah 
                FROM kategori k 
                LEFT JOIN laporan l ON k.id = l.kategori_id 
                GROUP BY k.id, k.nama_kategori 
                ORDER BY jumlah DESC";
        return $this->db->query($sql)->fetchAll();
    }
}