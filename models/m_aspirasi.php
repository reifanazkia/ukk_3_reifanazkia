<?php
include_once 'm_koneksi.php';

class aspirasi
{
    public function tampil_data()
    {
        $conn = new koneksi();
        $sql  = "SELECT aspirasi.*, kategori.nama_kategori 
                 FROM aspirasi 
                 JOIN kategori ON aspirasi.id_categori = kategori.id 
                 ORDER BY aspirasi.created_at DESC";
        $query = mysqli_query($conn->koneksi, $sql);
        $hasil = [];
        if ($query) {
            while ($data = mysqli_fetch_object($query)) {
                $hasil[] = $data;
            }
        }
        return $hasil;
    }

    public function tampil_aspirasi_untuk_umpanbalik()
    {
        $conn = new koneksi();
        $sql = "SELECT aspirasi.*,
                   umpan_balik.id AS sudah_ditanggapi
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


    public function tambah_data($nama_lengkap, $kelas, $id_categori, $judul, $pesan)
    {
        $conn = new koneksi();
        $sql  = "INSERT INTO aspirasi (nama_lengkap, kelas, id_categori, judul, pesan, status) 
                 VALUES ('$nama_lengkap', '$kelas', '$id_categori', '$judul', '$pesan', 'menunggu')";
        $query = mysqli_query($conn->koneksi, $sql);
        $this->alert_redirect($query, "Data berhasil dikirim", "../views/admin/aspirasi.php");
    }

    public function edit_data($id, $nama_lengkap, $kelas, $id_categori, $judul, $pesan)
    {
        $conn = new koneksi();
        $sql  = "UPDATE aspirasi SET nama_lengkap='$nama_lengkap', kelas='$kelas', id_categori='$id_categori', judul='$judul', pesan='$pesan' WHERE id='$id'";
        $query = mysqli_query($conn->koneksi, $sql);
        $this->alert_redirect($query, "Data berhasil diubah", "../views/admin/aspirasi.php");
    }

    public function edit_status_admin($id, $status)
    {
        $conn = new koneksi();
        $sql  = "UPDATE aspirasi SET status = '$status' WHERE id = '$id'";
        $query = mysqli_query($conn->koneksi, $sql);
        $this->alert_redirect($query, "Status berhasil diperbarui", "../views/admin/aspirasi.php");
    }

    public function hapus($id)
    {
        $conn = new koneksi();

        // hapus semua umpan balik milik aspirasi ini
        mysqli_query($conn->koneksi, "DELETE FROM umpan_balik WHERE aspirasi_id = '$id'");
        // hapus aspirasi
        $query = mysqli_query($conn->koneksi, "DELETE FROM aspirasi WHERE id = '$id'");

        $this->alert_redirect($query, "Data berhasil dihapus", "../views/admin/aspirasi.php");
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
