<?php
include_once __DIR__ . '/../models/m_aspirasi.php';
$aspirasi = new aspirasi();

try {
    $aksi = $_GET['aksi'] ?? '';

    if ($aksi == 'tambah') {
        $nama_lengkap = $_POST['nama_lengkap'];
        $kelas        = $_POST['kelas'];
        $id_categori  = $_POST['id_categori'];
        $judul        = $_POST['judul'];
        $pesan        = $_POST['pesan'];
        $aspirasi->tambah_data($nama_lengkap, $kelas, $id_categori, $judul, $pesan);

    } elseif ($aksi == 'update') {
        // Update Versi USER (Edit Isi)
        $id          = $_POST['id'];
        $nama_lengkap = $_POST['nama_lengkap'];
        $kelas = $_POST['kelas'];
        $id_categori = $_POST['id_categori'];
        $judul       = $_POST['judul'];
        $pesan       = $_POST['pesan'];
        $aspirasi->edit_data($id, $nama_lengkap, $kelas, $id_categori, $judul, $pesan);

    } elseif ($aksi == 'update_status') {
        // Update Versi ADMIN (Hanya Status)
        $id     = $_POST['id'];
        $status = $_POST['status'];
        $aspirasi->edit_status_admin($id, $status);

    } elseif ($aksi == 'hapus') {
        $id = $_GET['id'];
        $aspirasi->hapus_data($id);
        
    } else {
        $data_aspirasi = $aspirasi->tampil_data();
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}