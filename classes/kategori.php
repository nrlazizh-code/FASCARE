<?php
require_once __DIR__ . '/Database.php';

class Kategori {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM kategori WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function tambah($nama, $deskripsi) {
        $stmt = $this->db->prepare("INSERT INTO kategori (nama_kategori, deskripsi) VALUES (?, ?)");
        return $stmt->execute([$nama, $deskripsi]);
    }

    public function ubah($id, $nama, $deskripsi) {
        $stmt = $this->db->prepare("UPDATE kategori SET nama_kategori = ?, deskripsi = ? WHERE id = ?");
        return $stmt->execute([$nama, $deskripsi, $id]);
    }

    public function hapus($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM kategori WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false; // Gagal jika kategori masih dipakai oleh laporan
        }
    }
}