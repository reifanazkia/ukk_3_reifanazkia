<?php
include_once 'm_koneksi.php';

class kategori
{

    private $db;

    function __construct()
    {
        $koneksi = new koneksi();
        $this->db = $koneksi->koneksi;
    }

    // ================= TAMPIL DATA =================
    public function tampil_data()
    {
        $conn = new koneksi();
        $sql  = "SELECT * FROM kategori ORDER BY nama_kategori ASC";
        $query = mysqli_query($conn->koneksi, $sql);
        $hasil = [];
        if ($query) {
            while ($data = mysqli_fetch_object($query)) {
                $hasil[] = $data;
            }
        }
        return $hasil;
    }

    // ================= TAMBAH =================
    function tambah_kategori($nama_kategori)
    {
        $query = mysqli_query(
            $this->db,
            "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')"
        );

        if ($query) {
            header("Location: ../views/admin/kategori.php");
        } else {
            die(mysqli_error($this->db));
        }
    }

    // ================= GET BY ID =================
    function getById($id)
    {
        $query = mysqli_query(
            $this->db,
            "SELECT * FROM kategori WHERE id='$id'"
        );

        return mysqli_fetch_assoc($query);
    }

    // ================= UPDATE =================
    function ubah_kategori($id, $nama_kategori)
    {
        $query = mysqli_query(
            $this->db,
            "UPDATE kategori SET nama_kategori='$nama_kategori' WHERE id='$id'"
        );

        if ($query) {
            header("Location: ../views/admin/kategori.php");
        } else {
            die(mysqli_error($this->db));
        }
    }

    // ================= HAPUS =================
    function hapus_kategori($id)
    {
        $query = mysqli_query(
            $this->db,
            "DELETE FROM kategori WHERE id='$id'"
        );

        if ($query) {
            header("Location: ../views/admin/kategori.php");
        } else {
            die(mysqli_error($this->db));
        }
    }
}
