<?php

date_default_timezone_set('Asia/Jakarta');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../models/m_umpanbalik.php';

// Inisialisasi
$umpanbalik = new umpanbalik();

// Cek Login & Identitas
if (!isset($_SESSION['data'])) {
    header("Location: ../../index.php");
    exit;
}

$role   = $_SESSION['data']['role'];
$userId = $_SESSION['data']['id'];

// PROSES TAMBAH (HANYA ADMIN)
if (isset($_GET['aksi']) && $_GET['aksi'] === 'tambah') {
    if ($role !== 'admin') {
        die("Akses ditolak!");
    }

    $aspirasi_id = $_POST['aspirasi_id'] ?? '';
    $tanggapan   = trim($_POST['tanggapan'] ?? '');

    if ($aspirasi_id && $tanggapan) {
        $simpan = $umpanbalik->tambah($aspirasi_id, $tanggapan);
        $status = $simpan ? 'berhasil' : 'gagal';
        header("Location: ../views/admin/umpanbalik.php?status=$status");
    } else {
        header("Location: ../views/admin/umpanbalik.php?status=invalid");
    }
    exit;
}

//  AMBIL DATA UNTUK DITAMPILKAN 
// Variabel $data inilah yang akan digunakan oleh file VIEW
if ($role === 'admin') {
    $data = $umpanbalik->tampil_data(); // Admin lihat semua
} else {
    $data = $umpanbalik->tampil_data($userId); // User lihat miliknya saja
}
?>