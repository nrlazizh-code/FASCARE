<?php
class Laporan {
    public $id;
    public $kode_laporan;
    public $user_id;
    public $kategori_id;
    public $judul;
    public $deskripsi;
    public $lokasi;
    public $foto;
    public $status;
    public $catatan_admin;

    public function __construct($data = []) {
        $this->id            = $data['id'] ?? null;
        $this->kode_laporan  = $data['kode_laporan'] ?? $this->generateKode();
        $this->user_id       = $data['user_id'] ?? null;
        $this->kategori_id   = $data['kategori_id'] ?? null;
        $this->judul         = $data['judul'] ?? '';
        $this->deskripsi     = $data['deskripsi'] ?? '';
        $this->lokasi        = $data['lokasi'] ?? '';
        $this->foto          = $data['foto'] ?? '';
        $this->status        = $data['status'] ?? 'Menunggu';
        $this->catatan_admin = $data['catatan_admin'] ?? null;
    }

    private function generateKode() {
        return 'FC-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
    }
}