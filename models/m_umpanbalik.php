<?php
include_once 'm_koneksi.php';

class umpanbalik
{

  // MENAMPILKAN SEMUA ASPIRASI + CEK SUDAH DIBALAS / BELUM
  public function tampil_data()
  {
    $conn = new koneksi();

    $sql = "SELECT
              aspirasi.id AS aspirasi_id,
              aspirasi.nama_lengkap,
              aspirasi.kelas,
              aspirasi.judul,
              aspirasi.pesan,
              umpan_balik.id AS umpan_id,
              umpan_balik.tanggapan,
              umpan_balik.created
            FROM aspirasi
            LEFT JOIN umpan_balik
              ON umpan_balik.aspirasi_id = aspirasi.id
            ORDER BY aspirasi.created_at DESC";

    $query = mysqli_query($conn->koneksi, $sql);
    $hasil = [];

    while ($row = mysqli_fetch_object($query)) {
      $hasil[] = $row;
    }

    return $hasil;
  }

  public function tambah_data($aspirasi_id, $tanggapan)
  {
    $conn = new koneksi();
    $sql = "INSERT INTO umpan_balik (aspirasi_id, tanggapan)
            VALUES ('$aspirasi_id', '$tanggapan')";
    mysqli_query($conn->koneksi, $sql);
    header("Location: ../views/admin/umpanbalik.php");
  }

  public function edit_data($id, $tanggapan)
  {
    $conn = new koneksi();
    $sql = "UPDATE umpan_balik SET tanggapan='$tanggapan' WHERE id='$id'";
    mysqli_query($conn->koneksi, $sql);
    header("Location: ../views/admin/umpanbalik.php");
  }

  public function hapus_data($id)
  {
    $conn = new koneksi();
    $sql = "DELETE FROM umpan_balik WHERE id='$id'";
    mysqli_query($conn->koneksi, $sql);
    header("Location: ../views/admin/umpanbalik.php");
  }
}
