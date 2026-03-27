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
            move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/image/" . $foto);
        }

        $progress->tambah_data($aspirasi_id, $keterangan, $estimasi_selesai, $foto);
        header("Location: ../views/admin/progress_perbaikan.php");


    } elseif ($aksi == 'hapus') {
        $id = $_GET['id'];
        $progress->hapus($id);
        header("Location: ../views/admin/progress_perbaikan.php");
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>