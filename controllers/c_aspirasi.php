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

        $foto = null;
        if (!empty($_FILES['foto']['name'])) {
            $foto = time() . "_" . $_FILES['foto']['name'];
            // Pastikan folder assets/image/ sudah ada
            move_uploaded_file($_FILES['foto']['tmp_name'], "../../assets/image/" . $foto);
        }

        $aspirasi->tambah_data($user_id, $nama_lengkap, $kelas, $id_categori, $judul, $pesan, $foto);

    } elseif ($aksi == 'update') {
        $id           = $_POST['id'];
        $nama_lengkap = $_POST['nama_lengkap'];
        $kelas        = $_POST['kelas'];
        $id_categori  = $_POST['id_categori'];
        $judul        = $_POST['judul'];
        $pesan        = $_POST['pesan'];
        
        $foto_lama = $_POST['foto_lama'];
        $foto = $foto_lama;

        if (!empty($_FILES['foto']['name'])) {
            // Hapus foto lama dari folder fisik jika ada
            if ($foto_lama && file_exists("../../assets/image/" . $foto_lama)) {
                unlink("../../assets/image/" . $foto_lama);
            }
            $foto = time() . "_" . $_FILES['foto']['name'];
            move_uploaded_file($_FILES['foto']['tmp_name'], "../../assets/image/" . $foto);
        }

        $aspirasi->edit_data($id, $nama_lengkap, $kelas, $id_categori, $judul, $pesan, $foto);

    } elseif ($aksi == 'update_status') {
        $id     = $_POST['id'];
        $status = $_POST['status'];
        $aspirasi->edit_status_admin($id, $status);

    } elseif ($aksi == 'hapus') {
        $id = $_GET['id'];
        $aspirasi->hapus($id);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>