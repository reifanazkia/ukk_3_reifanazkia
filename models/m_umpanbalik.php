<?php
include_once __DIR__ . '/m_koneksi.php';

class umpanbalik
{
    // =========================
    // TAMPIL ASPIRASI + STATUS
    // =========================
    public function tampil_data()
    {
        $conn = new koneksi();

        $sql = "SELECT *
                FROM aspirasi
                ORDER BY created_at DESC";

        return mysqli_query($conn->koneksi, $sql);
    }

    // =========================
    // RIWAYAT UMPAN BALIK
    // =========================
    public function riwayat($aspirasi_id)
    {
        $conn = new koneksi();

        $sql = "SELECT *
                FROM umpan_balik
                WHERE aspirasi_id = '$aspirasi_id'
                ORDER BY created_at ASC";

        return mysqli_query($conn->koneksi, $sql);
    }

    // =========================
    // TAMBAH UMPAN BALIK (INSERT TERUS)
    // =========================
    public function tambah($aspirasi_id, $tanggapan)
    {
        $conn = new koneksi();

        $sql = "INSERT INTO umpan_balik (aspirasi_id, tanggapan)
                VALUES ('$aspirasi_id', '$tanggapan')";

        $query = mysqli_query($conn->koneksi, $sql);

        // update status aspirasi
        if ($query) {
            mysqli_query(
                $conn->koneksi,
                "UPDATE aspirasi
                 SET status = 'diproses'
                 WHERE id = '$aspirasi_id'"
            );
        }

        return $query;
    }
}
