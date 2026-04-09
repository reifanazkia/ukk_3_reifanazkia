<?php
include_once __DIR__ . '/m_koneksi.php';

class umpanbalik
{
    public function tampil_data($user_id = null)
    {
        $conn = new koneksi();

        if ($user_id) {
            $sql = "SELECT * FROM aspirasi 
                WHERE user_id = '$user_id' 
                ORDER BY created_at DESC";
        } else {
            $sql = "SELECT * FROM aspirasi 
                ORDER BY created_at DESC";
        }

        return mysqli_query($conn->koneksi, $sql);
    }

    public function riwayat($aspirasi_id)
    {
        $conn = new koneksi();

        return mysqli_query(
            $conn->koneksi,
            "SELECT *
             FROM umpan_balik
             WHERE aspirasi_id = '$aspirasi_id'
             ORDER BY created_at ASC"
        );
    }

    public function tambah($aspirasi_id, $tanggapan)
    {
        $conn = new koneksi();

        mysqli_query($conn->koneksi, "SET time_zone = '+07:00'");

        return mysqli_query(
            $conn->koneksi,
            "INSERT INTO umpan_balik (aspirasi_id, tanggapan, created_at)
             VALUES ('$aspirasi_id', '$tanggapan', NOW())"
        );
    }
}