<?php
include_once __DIR__ . '/m_koneksi.php';

class umpanbalik
{
    // ====================================================
    // TAMPIL DATA ASPIRASI
    //
    //  ADMIN
    //    → panggil: tampil_data()
    //    → hasil : SEMUA aspirasi siswa
    //
    //  SISWA
    //    → panggil: tampil_data($user_id)
    //    → hasil : HANYA aspirasi milik siswa login
    // ====================================================
    public function tampil_data($user_id = null)
    {
        $conn = new koneksi();

        // ============================
        // QUERY UNTUK SISWA
        // ============================
        if ($user_id) {
            $sql = "SELECT 
                        a.*,
                        (
                            SELECT COUNT(*) 
                            FROM umpan_balik ub
                            WHERE ub.aspirasi_id = a.id
                        ) AS jumlah_balasan
                    FROM aspirasi a
                    WHERE a.user_id = '$user_id'
                    ORDER BY a.created_at DESC";
        }

        // ============================
        // QUERY UNTUK ADMIN
        // ============================
        else {
            $sql = "SELECT 
                        a.*,
                        (
                            SELECT COUNT(*) 
                            FROM umpan_balik ub
                            WHERE ub.aspirasi_id = a.id
                        ) AS jumlah_balasan
                    FROM aspirasi a
                    ORDER BY a.created_at DESC";
        }

        return mysqli_query($conn->koneksi, $sql);
    }

    // ====================================================
    // RIWAYAT BALASAN (ADMIN & SISWA)
    // ====================================================
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

    // ====================================================
    // TAMBAH BALASAN
    //
    //  HANYA ADMIN
    //  TIDAK MENGUBAH STATUS ASPIRASI
    // ====================================================
    public function tambah($aspirasi_id, $tanggapan)
    {
        $conn = new koneksi();

        return mysqli_query(
            $conn->koneksi,
            "INSERT INTO umpan_balik (aspirasi_id, tanggapan)
             VALUES ('$aspirasi_id', '$tanggapan')"
        );
    }
}
