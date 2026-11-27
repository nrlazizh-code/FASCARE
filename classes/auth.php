<?php
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Pengguna.php';
require_once __DIR__ . '/Admin.php';

class Auth {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function login($identity, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? OR nim_nip = ? LIMIT 1");
        $stmt->execute([$identity, $identity]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_write_close();
            }

            $sessName = ($user['role'] === 'admin') ? 'FASCARE_ADMIN_SESS' : 'FASCARE_USER_SESS';
            session_name($sessName);
            session_set_cookie_params(0, '/');
            session_start();

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nim_nip'] = $user['nim_nip'];
            $_SESSION['nama']    = $user['nama'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['role']    = $user['role'];
            $_SESSION['foto']    = $user['foto_profil'];

            $akunObj  = ($user['role'] === 'admin') ? new Admin() : new Pengguna();
            $redirect = $akunObj->getDashboardUrl();

            session_write_close();

            return [
                'status'   => true,
                'redirect' => $redirect
            ];
        }

        return [
            'status' => false,
            'pesan'  => 'NIM/NIP, Email, atau Password salah!'
        ];
    }

    public function register($nim_nip, $nama, $email, $no_hp, $password) {
        $cek = $this->db->prepare("SELECT id FROM users WHERE email = ? OR nim_nip = ?");
        $cek->execute([$email, $nim_nip]);
        if ($cek->fetch()) {
            return ['status' => false, 'pesan' => 'NIM atau Email sudah terdaftar!'];
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (nim_nip, nama, email, no_hp, password, role) VALUES (?, ?, ?, ?, ?, 'pengguna')");
        if ($stmt->execute([$nim_nip, $nama, $email, $no_hp, $hash])) {
            return ['status' => true, 'pesan' => 'Registrasi berhasil! Silakan masuk.'];
        }

        return ['status' => false, 'pesan' => 'Terjadi kesalahan saat mendaftar.'];
    }

    public static function check() {
        if (session_status() === PHP_SESSION_NONE) {
            $uri = $_SERVER['REQUEST_URI'] ?? '';
            if (stripos($uri, '/admin/') !== false) {
                session_name('FASCARE_ADMIN_SESS');
            } else {
                session_name('FASCARE_USER_SESS');
            }
            session_set_cookie_params(0, '/');
            session_start();
        }
        return isset($_SESSION['user_id']);
    }
}