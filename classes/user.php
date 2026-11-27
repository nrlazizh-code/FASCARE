<?php
require_once __DIR__ . '/Database.php';

class User {
    protected $db;
    protected $id;
    protected $nim_nip;
    protected $nama;
    protected $email;
    protected $no_hp;
    protected $role;
    protected $foto_profil;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateProfil($id, $nama, $email, $no_hp, $foto = null) {
        if ($foto) {
            $user = $this->getById($id);
            if ($user && $user['foto_profil'] !== 'default.png') {
                $fileLama = __DIR__ . '/../uploads/profil/' . $user['foto_profil'];
                if (file_exists($fileLama)) unlink($fileLama);
            }
            $stmt = $this->db->prepare("UPDATE users SET nama = ?, email = ?, no_hp = ?, foto_profil = ? WHERE id = ?");
            return $stmt->execute([$nama, $email, $no_hp, $foto, $id]);
        } else {
            $stmt = $this->db->prepare("UPDATE users SET nama = ?, email = ?, no_hp = ? WHERE id = ?");
            return $stmt->execute([$nama, $email, $no_hp, $id]);
        }
    }

    public function hapusFotoProfil($id) {
        $user = $this->getById($id);
        if ($user && !empty($user['foto_profil']) && $user['foto_profil'] !== 'default.png') {
            $fileLama = __DIR__ . '/../uploads/profil/' . $user['foto_profil'];
            if (file_exists($fileLama)) {
                unlink($fileLama);
            }
            $stmt = $this->db->prepare("UPDATE users SET foto_profil = 'default.png' WHERE id = ?");
            return $stmt->execute([$id]);
        }
        return false;
    }

    public function ubahPassword($id, $passwordLama, $passwordBaru) {
        $user = $this->getById($id);
        if ($user && password_verify($passwordLama, $user['password'])) {
            $hash = password_hash($passwordBaru, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
            return $stmt->execute([$hash, $id]);
        }
        return false;
    }

    public function getDashboardUrl() {
        return BASE_URL . 'index.php';
    }
}