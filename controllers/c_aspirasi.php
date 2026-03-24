<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../models/m_aspirasi.php';
$aspirasi = new aspirasi();

try {
    $aksi = $_GET['aksi'] ?? '';

    if ($aksi == 'tambah') {
        $user_id      = $_SESSION['data']['id'];
        $nama_lengkap = $_POST['nama_lengkap'];
        $kelas        = $_POST['kelas'];
        $id_categori  = $_POST['id_categori'];
        $judul        = $_POST['judul'];
        $pesan        = $_POST['pesan'];
        $aspirasi->tambah_data($user_id, $nama_lengkap, $kelas, $id_categori, $judul, $pesan);

    } elseif ($aksi == 'update') {
        $id           = $_POST['id'];
        $nama_lengkap = $_POST['nama_lengkap'];
        $kelas        = $_POST['kelas'];
        $id_categori  = $_POST['id_categori'];
        $judul        = $_POST['judul'];
        $pesan        = $_POST['pesan'];
        $aspirasi->edit_data($id, $nama_lengkap, $kelas, $id_categori, $judul, $pesan);

    } elseif ($aksi == 'update_status') {
        // Digunakan oleh Admin untuk ubah status ke (diproses/selesai/ditolak)
        $id     = $_POST['id'];
        $status = $_POST['status'];
        $aspirasi->edit_status_admin($id, $status);

    } elseif ($aksi == 'hapus') {
        $id = $_GET['id'];
        $aspirasi->hapus($id);
        
    } else {
        // LOGIKA FILTER:
        // Jika yang login adalah 'user', ambil ID-nya. Jika 'admin', biarkan null.
        $user_id = null;
        if (isset($_SESSION['data']) && $_SESSION['data']['role'] === 'user') {
            $user_id = $_SESSION['data']['id'];
        }
        
        $data_aspirasi = $aspirasi->tampil_data($user_id);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>