<?php
include_once 'm_koneksi.php';

class aspirasi
{
    // Ditambahkan parameter $user_id dengan default null
    public function tampil_data($user_id = null)
    {
        $conn = new koneksi();
        $sql  = "SELECT aspirasi.*, kategori.nama_kategori 
                 FROM aspirasi 
                 JOIN kategori ON aspirasi.id_categori = kategori.id";
        
        // Jika user_id diisi (untuk User), maka difilter. Jika null (untuk Admin), tampil semua.
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

    public function tampil_aspirasi_untuk_umpanbalik($user_id = null)
    {
        $conn = new koneksi();
        $sql = "SELECT aspirasi.*, umpan_balik.id AS sudah_ditanggapi
                FROM aspirasi
                LEFT JOIN umpan_balik ON umpan_balik.aspirasi_id = aspirasi.id";

        if ($user_id !== null) {
            $sql .= " WHERE aspirasi.user_id = '$user_id'";
        }

        $sql .= " ORDER BY aspirasi.created_at DESC";
        $query = mysqli_query($conn->koneksi, $sql);
        $hasil = [];
        while ($row = mysqli_fetch_object($query)) {
            $hasil[] = $row;
        }
        return $hasil;
    }

    public function tambah_data($user_id, $nama_lengkap, $kelas, $id_categori, $judul, $pesan)
    {
        $conn = new koneksi();
        // Status default adalah 'menunggu'
        $sql  = "INSERT INTO aspirasi (user_id, nama_lengkap, kelas, id_categori, judul, pesan, status) 
                 VALUES ('$user_id', '$nama_lengkap', '$kelas', '$id_categori', '$judul', '$pesan', 'menunggu')";
        $query = mysqli_query($conn->koneksi, $sql);
        $this->alert_redirect($query, "Aspirasi berhasil dikirim", "../views/user/aspirasi.php");
    }

    public function edit_data($id, $nama_lengkap, $kelas, $id_categori, $judul, $pesan)
    {
        $conn = new koneksi();
        $sql  = "UPDATE aspirasi SET nama_lengkap='$nama_lengkap', kelas='$kelas', id_categori='$id_categori', judul='$judul', pesan='$pesan' WHERE id='$id'";
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
        // Hapus umpan balik terkait dulu agar tidak error constraint
        mysqli_query($conn->koneksi, "DELETE FROM umpan_balik WHERE aspirasi_id = '$id'");
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
?>