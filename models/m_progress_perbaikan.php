<?php
include_once 'm_koneksi.php';

class ProgressPerbaikan
{
    public function tampil_data($role, $user_id = null)
    {
        $conn = new koneksi();

        // Query dasar: Join ke tabel aspirasi untuk mendapatkan judul dan user_id pemilik aspirasi
        $sql  = "SELECT progress_perbaikan.*, aspirasi.judul, aspirasi.user_id 
             FROM progress_perbaikan 
             JOIN aspirasi ON progress_perbaikan.aspirasi_id = aspirasi.id";

        // JIKA role-nya bukan admin, maka filter hanya aspirasi milik user tersebut
        if ($role !== 'admin' && $user_id !== null) {
            $sql .= " WHERE aspirasi.user_id = '$user_id'";
        }

        $sql .= " ORDER BY progress_perbaikan.tanggal DESC";

        $query = mysqli_query($conn->koneksi, $sql);
        $hasil = [];
        if ($query) {
            while ($data = mysqli_fetch_object($query)) {
                $hasil[] = $data;
            }
        }
        return $hasil;
    }

    // Tambahkan fungsi ini di dalam class ProgressPerbaikan
    public function tampil_data_per_id($aspirasi_id)
    {
        $conn = new koneksi();
        $sql  = "SELECT * FROM progress_perbaikan WHERE aspirasi_id = '$aspirasi_id' ORDER BY tanggal DESC";
        $query = mysqli_query($conn->koneksi, $sql);
        $hasil = [];
        while ($data = mysqli_fetch_object($query)) {
            $hasil[] = $data;
        }
        return $hasil;
    }

    public function tambah_data($aspirasi_id, $keterangan, $estimasi_selesai, $foto)
    {
        $conn = new koneksi();
        $sql  = "INSERT INTO progress_perbaikan (aspirasi_id, keterangan, estimasi_selesai, foto) 
                 VALUES ('$aspirasi_id', '$keterangan', '$estimasi_selesai', '$foto')";
        $query = mysqli_query($conn->koneksi, $sql);
        $this->alert_redirect($query, "Progress berhasil ditambahkan", "../views/admin/progress_perbaikan.php");
    }


    public function hapus($id)
    {
        $conn = new koneksi();

        // Ambil nama file foto sebelum data dihapus dari DB
        $res = mysqli_query($conn->koneksi, "SELECT foto FROM progress_perbaikan WHERE id = '$id'");
        $data = mysqli_fetch_object($res);

        // Hapus file fisik di folder assets/image
        if ($data && $data->foto && file_exists("../../assets/image/" . $data->foto)) {
            unlink("../../assets/image/" . $data->foto);
        }

        $query = mysqli_query($conn->koneksi, "DELETE FROM progress_perbaikan WHERE id = '$id'");
        $this->alert_redirect($query, "Data progress berhasil dihapus", "../views/admin/progress_perbaikan.php");
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
