<?php
include_once __DIR__ . '/../models/m_umpanbalik.php';

$umpanbalik = new umpanbalik();

$aksi = $_GET['aksi'] ?? '';

if ($aksi == 'tambah') {

    $aspirasi_id = $_POST['aspirasi_id'];
    $tanggapan   = $_POST['tanggapan'];

    $simpan = $umpanbalik->tambah($aspirasi_id, $tanggapan);

    if ($simpan) {
        header("Location: ../views/admin/umpanbalik.php?status=berhasil");
    } else {
        header("Location: ../views/admin/umpanbalik.php?status=gagal");
    }
}
