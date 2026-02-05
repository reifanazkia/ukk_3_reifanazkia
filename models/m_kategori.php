<?php
include_once 'm_koneksi.php';

class kategori {

    private $db;

    function __construct() {
        $koneksi = new koneksi();
        $this->db = $koneksi->koneksi;
    }

    // ================= TAMPIL DATA =================
    function tampil_data() {
        $data = [];
        $query = mysqli_query($this->db, "SELECT * FROM kategori");

        while ($row = mysqli_fetch_object($query)) {
            $data[] = $row;
        }

        return $data;
    }

    // ================= TAMBAH =================
    function tambah_kategori($nama_kategori) {
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
    function getById($id) {
        $query = mysqli_query(
            $this->db,
            "SELECT * FROM kategori WHERE id='$id'"
        );

        return mysqli_fetch_assoc($query);
    }

    // ================= UPDATE =================
    function ubah_kategori($id, $nama_kategori) {
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
    function hapus_kategori($id) {
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