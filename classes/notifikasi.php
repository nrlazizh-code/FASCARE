<?php
require_once __DIR__ . '/Database.php';

class Notifikasi {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function kirim($userId, $laporanId, $pesan) {
        $stmt = $this->db->prepare("INSERT INTO notifikasi (user_id, laporan_id, pesan) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $laporanId, $pesan]);
    }

    public function kirimKeSemuaAdmin($laporanId, $pesan) {
        $admins = $this->db->query("SELECT id FROM users WHERE role = 'admin'")->fetchAll();
        foreach ($admins as $admin) {
            $this->kirim($admin['id'], $laporanId, $pesan);
        }
    }

    public function getByUser($userId, $limit = 6, $role = 'pengguna') {
        if ($role === 'admin') {
            $sql = "SELECT n.* FROM notifikasi n
                    INNER JOIN laporan l ON n.laporan_id = l.id
                    WHERE n.user_id = ?
                    ORDER BY n.created_at DESC LIMIT ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, (int)$userId, PDO::PARAM_INT);
            $stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
        } else {
            $sql = "SELECT n.* FROM notifikasi n
                    INNER JOIN laporan l ON n.laporan_id = l.id
                    WHERE n.user_id = ? AND l.user_id = ?
                    ORDER BY n.created_at DESC LIMIT ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, (int)$userId, PDO::PARAM_INT);
            $stmt->bindValue(2, (int)$userId, PDO::PARAM_INT);
            $stmt->bindValue(3, (int)$limit, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function hitungBelumDibaca($userId, $role = 'pengguna') {
        if ($role === 'admin') {
            $sql = "SELECT COUNT(*) FROM notifikasi n
                    INNER JOIN laporan l ON n.laporan_id = l.id
                    WHERE n.user_id = ? AND n.is_read = 0";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([(int)$userId]);
        } else {
            $sql = "SELECT COUNT(*) FROM notifikasi n
                    INNER JOIN laporan l ON n.laporan_id = l.id
                    WHERE n.user_id = ? AND l.user_id = ? AND n.is_read = 0";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([(int)$userId, (int)$userId]);
        }
        return (int)$stmt->fetchColumn();
    }

    public function tandaiDibaca($userId) {
        $stmt = $this->db->prepare("UPDATE notifikasi SET is_read = 1 WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }

    public function tandaiSatuDibaca($idNotif, $userId) {
        $stmt = $this->db->prepare("UPDATE notifikasi SET is_read = 1 WHERE id = ? AND user_id = ?");
        return $stmt->execute([$idNotif, $userId]);
    }
}