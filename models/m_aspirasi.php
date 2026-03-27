<?php
include_once 'm_koneksi.php';

class aspirasi
{
    public function tampil_data($user_id = null)
    {
        $conn = new koneksi();
        $sql  = "SELECT aspirasi.*, kategori.nama_kategori 
                 FROM aspirasi 
                 JOIN kategori ON aspirasi.id_categori = kategori.id";

        if ($user_id !== null) {
            $sql .= " WHERE aspirasi.user_id = '$user_id'";
        }

        $sql .= " ORDER BY aspirasi.created_at DESC";
        $query = mysqli_query($conn->koneksi, $sql);
        $hasil = [];
        if ($query) {
            while ($data = mysqli_fetch_object($query)) {
                $hasil[] = $data;
            }
        }
        return $hasil;
    }

    public function filter_data($tanggal = null, $bulan = null, $kategori = null, $siswa = null)
    {
        $conn = new koneksi();

        $sql = "SELECT aspirasi.*, kategori.nama_kategori
            FROM aspirasi
            JOIN kategori ON aspirasi.id_categori = kategori.id
            WHERE 1=1";

        // FILTER TANGGAL
        if (!empty($tanggal)) {
            $sql .= " AND DATE(aspirasi.created_at) = '$tanggal'";
        }

        // FILTER BULAN
        if (!empty($bulan)) {
            $sql .= " AND MONTH(aspirasi.created_at) = '$bulan'";
        }

        // FILTER KATEGORI
        if (!empty($kategori)) {
            $sql .= " AND aspirasi.id_categori = '$kategori'";
        }

        // FILTER SISWA
        if (!empty($siswa)) {
            $sql .= " AND aspirasi.nama_lengkap LIKE '%$siswa%'";
        }

        $sql .= " ORDER BY aspirasi.created_at DESC";

        $query = mysqli_query($conn->koneksi, $sql);

        $hasil = [];

        if ($query) {
            while ($data = mysqli_fetch_object($query)) {
                $hasil[] = $data;
            }
        }

        return $hasil;
    }

    public function tampil_data_per_user($user_id)
    {
        $conn = new koneksi();
        // Mengambil data aspirasi hanya untuk user tertentu
        $sql = "SELECT * FROM aspirasi WHERE user_id = '$user_id' ORDER BY id DESC";
        $query = mysqli_query($conn->koneksi, $sql);
        $hasil = [];
        while ($d = mysqli_fetch_object($query)) {
            $hasil[] = $d;
        }
        return $hasil;
    }

    public function hitung_total_aspirasi($user_id = null)
    {
        $conn = new koneksi();
        $sql = "SELECT COUNT(*) as total FROM aspirasi";
        if ($user_id) {
            $sql .= " WHERE user_id = '$user_id'";
        }
        $query = mysqli_query($conn->koneksi, $sql);
        $data = mysqli_fetch_assoc($query);
        return $data['total'];
    }

    // History ADMIN dengan Pagination
    public function tampil_semua_history_paginated($awalData, $jumlahDataPerHalaman)
    {
        $conn = new koneksi();
        $sql = "SELECT * FROM aspirasi ORDER BY id DESC LIMIT $awalData, $jumlahDataPerHalaman";
        $query = mysqli_query($conn->koneksi, $sql);
        $hasil = [];
        while ($d = mysqli_fetch_object($query)) {
            $hasil[] = $d;
        }
        return $hasil;
    }

    // History USER dengan Pagination
    public function tampil_history_per_user_paginated($user_id, $awalData, $jumlahDataPerHalaman)
    {
        $conn = new koneksi();
        $sql = "SELECT * FROM aspirasi WHERE user_id = '$user_id' ORDER BY id DESC LIMIT $awalData, $jumlahDataPerHalaman";
        $query = mysqli_query($conn->koneksi, $sql);
        $hasil = [];
        while ($d = mysqli_fetch_object($query)) {
            $hasil[] = $d;
        }
        return $hasil;
    }

    public function tambah_data($user_id, $nama_lengkap, $kelas, $id_categori, $judul, $pesan, $foto)
    {
        $conn = new koneksi();
        $sql  = "INSERT INTO aspirasi (user_id, nama_lengkap, kelas, id_categori, judul, pesan, foto, status) 
                 VALUES ('$user_id', '$nama_lengkap', '$kelas', '$id_categori', '$judul', '$pesan', '$foto', 'menunggu')";
        $query = mysqli_query($conn->koneksi, $sql);
        $this->alert_redirect($query, "Aspirasi berhasil dikirim", "../views/user/aspirasi.php");
    }

    public function edit_data($id, $nama_lengkap, $kelas, $id_categori, $judul, $pesan, $foto)
    {
        $conn = new koneksi();
        $sql  = "UPDATE aspirasi SET 
                 nama_lengkap='$nama_lengkap', 
                 kelas='$kelas', 
                 id_categori='$id_categori', 
                 judul='$judul', 
                 pesan='$pesan',
                 foto='$foto' 
                 WHERE id='$id'";
        $query = mysqli_query($conn->koneksi, $sql);
        $this->alert_redirect($query, "Data berhasil diubah", "../views/user/aspirasi.php");
    }

    public function edit_status_admin($id, $status)
    {
        $conn = new koneksi();
        $sql  = "UPDATE aspirasi SET status = '$status' WHERE id = '$id'";
        $query = mysqli_query($conn->koneksi, $sql);
        $this->alert_redirect($query, "Status berhasil diperbarui", "../views/admin/umpan_balik.php");
    }

    public function hapus($id)
    {
        $conn = new koneksi();
        $res = mysqli_query($conn->koneksi, "SELECT foto FROM aspirasi WHERE id = '$id'");
        $data = mysqli_fetch_object($res);

        // Hapus file fisik di folder image
        if ($data && $data->foto && file_exists("../../assets/image/" . $data->foto)) {
            unlink("../../assets/image/" . $data->foto);
        }

        mysqli_query($conn->koneksi, "DELETE FROM umpan_balik WHERE aspirasi_id = '$id'");
        $query = mysqli_query($conn->koneksi, "DELETE FROM aspirasi WHERE id = '$id'");
        $this->alert_redirect($query, "Data berhasil dihapus", "../views/user/aspirasi.php");
    }

    private function alert_redirect($query, $msg, $url)
    {
        if ($query) {
            echo "<script>alert('$msg');window.location='$url'</script>";
        } else {
            echo "<script>alert('Gagal memproses data');history.back()</script>";
        }
    }
}
