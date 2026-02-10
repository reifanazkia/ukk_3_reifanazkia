<?php
include_once __DIR__ . '/../models/m_kategori.php';

$kategori = new kategori();

$aksi = $_GET['aksi'] ?? null;

if ($aksi === "tambah") {

    $nama_kategori = $_POST['nama_kategori'];
    $kategori->tambah_kategori($nama_kategori);

} elseif ($aksi === "edit") {

    $id = $_POST['id'];
    $nama_kategori = $_POST['nama_kategori'];
    $kategori->ubah_kategori($id, $nama_kategori);

} elseif ($aksi === "hapus") {

    $id = $_GET['id'];
    $kategori->hapus_kategori($id);

}
