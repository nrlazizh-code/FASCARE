<?php
require_once __DIR__ . '/User.php';

class Admin extends User {
    public function __construct() {
        parent::__construct();
        $this->role = 'admin';
    }

    public function getDashboardUrl() {
        return BASE_URL . 'admin/dashboard.php';
    }

    public function getAllPengguna() {
        $stmt = $this->db->query("SELECT id, nim_nip, nama, email, no_hp, role, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function hapusUser($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
        return $stmt->execute([$id]);
    }
}