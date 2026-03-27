<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../models/m_progress_perbaikan.php';
$progress = new ProgressPerbaikan();

try {
    $aksi = $_GET['aksi'] ?? '';

    if ($aksi == 'tambah') {
        $aspirasi_id      = $_POST['aspirasi_id'];
        $keterangan       = $_POST['keterangan'];
        $estimasi_selesai = $_POST['estimasi_selesai'];

        $foto = null;
        if (!empty($_FILES['foto']['name'])) {
            $foto = time() . "_" . $_FILES['foto']['name'];
            // Mengunggah ke folder assets/image/
            move_uploaded_file($_FILES['foto']['tmp_name'], "../../assets/image/" . $foto);
        }

        $progress->tambah_data($aspirasi_id, $keterangan, $estimasi_selesai, $foto);

    } elseif ($aksi == 'update') {
        $id               = $_POST['id'];
        $aspirasi_id      = $_POST['aspirasi_id'];
        $keterangan       = $_POST['keterangan'];
        $estimasi_selesai = $_POST['estimasi_selesai'];
        
        $foto_lama = $_POST['foto_lama'];
        $foto = $foto_lama;

        if (!empty($_FILES['foto']['name'])) {
            // Hapus foto fisik yang lama jika ada file baru
            if ($foto_lama && file_exists("../../assets/image/" . $foto_lama)) {
                unlink("../../assets/image/" . $foto_lama);
            }
            $foto = time() . "_" . $_FILES['foto']['name'];
            move_uploaded_file($_FILES['foto']['tmp_name'], "../../assets/image/" . $foto);
        }

        $progress->edit_data($id, $aspirasi_id, $keterangan, $estimasi_selesai, $foto);

    } elseif ($aksi == 'hapus') {
        $id = $_GET['id'];
        $progress->hapus($id);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>