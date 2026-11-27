<?php
require_once __DIR__ . '/User.php';

class Pengguna extends User {
    public function __construct() {
        parent::__construct();
        $this->role = 'pengguna';
    }

    public function getDashboardUrl() {
        return BASE_URL . 'pengguna/dashboard.php';
    }

    public function getStatistikLaporan($userId) {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'Menunggu' THEN 1 ELSE 0 END) as menunggu,
                    SUM(CASE WHEN status = 'Diproses' THEN 1 ELSE 0 END) as diproses,
                    SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) as selesai,
                    SUM(CASE WHEN status = 'Ditolak' THEN 1 ELSE 0 END) as ditolak
                FROM laporan WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
}