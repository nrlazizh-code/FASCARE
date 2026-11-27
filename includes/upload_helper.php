<?php
function uploadGambar($file, $folderTujuan = 'laporan') {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['status' => false, 'pesan' => 'File gambar wajib diunggah!'];
    }

    $ekstensiValid = ['jpg', 'jpeg', 'png', 'webp'];
    $namaAsli = $file['name'];
    $ukuran   = $file['size'];
    $tmpName  = $file['tmp_name'];

    $ekstensi = strtolower(pathinfo($namaAsli, PATHINFO_EXTENSION));
    if (!in_array($ekstensi, $ekstensiValid)) {
        return ['status' => false, 'pesan' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP!'];
    }

    // Maksimal 3MB
    if ($ukuran > 3 * 1024 * 1024) {
        return ['status' => false, 'pesan' => 'Ukuran gambar maksimal 3MB!'];
    }

    $targetDir = __DIR__ . '/../uploads/' . $folderTujuan . '/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $namaBaru = uniqid('img_') . '_' . time() . '.' . $ekstensi;
    if (move_uploaded_file($tmpName, $targetDir . $namaBaru)) {
        return ['status' => true, 'file_name' => $namaBaru];
    }

    return ['status' => false, 'pesan' => 'Gagal menyimpan file ke server.'];
}