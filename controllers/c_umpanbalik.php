<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../models/m_umpanbalik.php';

if (!isset($_SESSION['data'])) {
    header("Location: ../index.php");
    exit;
}

$umpanbalik = new umpanbalik();
$role   = $_SESSION['data']['role'];
$userId = $_SESSION['data']['id'];
 try {
    //code...
$aksi = $_GET['aksi'] ?? '';

// ================= PROSES TAMBAH (ADMIN) =================
if ($aksi === 'tambah') {

    if ($role !== 'admin') {
        header("HTTP/1.1 403 Forbidden");
        exit('Akses ditolak');
    }

    $aspirasi_id = $_POST['aspirasi_id'] ?? '';
    $tanggapan   = trim($_POST['tanggapan'] ?? '');

    if (!$aspirasi_id || !$tanggapan) {
        header("Location: ../views/admin/umpanbalik.php?status=invalid");
        exit;
    }

    $simpan = $umpanbalik->tambah($aspirasi_id, $tanggapan);

    header(
        "Location: ../views/admin/umpanbalik.php?status=" .
        ($simpan ? 'berhasil' : 'gagal')
    );
    exit;
}

// ================= AMBIL DATA =================
if ($role === 'admin') {
    $data = $umpanbalik->tampil_data();
} else {
    $data = $umpanbalik->tampil_data($userId);
}

} catch (Exception $e) {
     echo "Error: " . $e->getMessage();
 }