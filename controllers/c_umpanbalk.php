<?php
include_once __DIR__ . '/../models/m_aspirasi.php';
include_once __DIR__ . '/../models/m_umpanbalik.php';

$aspirasi   = new aspirasi();
$umpanbalik = new umpanbalik();

$aksi = $_GET['aksi'] ?? '';

if ($aksi == 'tambah') {

    $umpanbalik->tambah_data(
        $_POST['aspirasi_id'],
        $_POST['tanggapan']
    );

} elseif ($aksi == 'edit') {

    $umpanbalik->edit_data(
        $_POST['id'],
        $_POST['aspirasi_id'],
        $_POST['tanggapan']
    );

} elseif ($aksi == 'hapus') {

    $umpanbalik->hapus_data($_GET['id']);

} else {

    // INI YANG BENAR UNTUK MENU UMPAN BALIK
    $data_aspirasi = $aspirasi->tampil_aspirasi_untuk_umpanbalik();
}
