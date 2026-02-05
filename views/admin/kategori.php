<?php
session_start();

if (!isset($_SESSION['data']) || $_SESSION['data']['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}

include_once '../../models/m_kategori.php';

$kategori = new kategori();
$data_kategori = $kategori->tampil_data();
?>

<?php include '../layout/header.php'; ?>
<?php include '../layout/sidebar.php'; ?>

<main class="content">

<style>
.table-wrapper {
    overflow-x: auto;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}
.table-kategori {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    min-width: 500px;
}
.table-kategori th,
.table-kategori td {
    padding: 12px 10px;
    border-bottom: 1px solid #e5e7eb;
}
.table-kategori thead th {
    background: #f1f5f9;
}
.aksi {
    text-align: center;
}
.btn-edit {
    background: #3b82f6;
    color: white;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
}
.btn-hapus {
    background: #ef4444;
    color: white;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
}

/* MODAL */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.4);
    z-index: 999;
    justify-content: center;
    align-items: center;
}
.modal-box {
    background: white;
    padding: 20px;
    width: 90%;
    max-width: 400px;
    border-radius: 12px;
}
.modal-box h3 {
    margin-bottom: 15px;
}
.modal-box input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #ddd;
}
.modal-aksi {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}
.modal-aksi button {
    padding: 8px 14px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
}
.btn-simpan {
    background: #3b82f6;
    color: white;
}
</style>

<h2>Data Kategori</h2>

<a href="#" onclick="bukaTambah()"
   style="background:#3b82f6;padding:10px 16px;border-radius:10px;color:white;text-decoration:none;display:inline-block;margin-bottom:16px;">
   + Tambah Kategori
</a>

<div class="table-wrapper">
<table class="table-kategori">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kategori</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($data_kategori)) {
        $no = 1;
        foreach ($data_kategori as $row) { ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row->nama_kategori) ?></td>
            <td class="aksi">
                <a href="#" class="btn-edit"
                   onclick="bukaEdit('<?= $row->id ?>','<?= htmlspecialchars($row->nama_kategori) ?>')">
                   Edit
                </a>
                <a href="../../controllers/c_kategori.php?aksi=hapus&id=<?= $row->id ?>"
                   class="btn-hapus"
                   onclick="return confirm('Yakin hapus kategori ini?')">
                   Hapus
                </a>
            </td>
        </tr>
    <?php }} else { ?>
        <tr>
            <td colspan="3" style="text-align:center;">Data kosong</td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</div>

<!-- MODAL TAMBAH -->
<div class="modal" id="modalTambah">
    <div class="modal-box">
        <h3>Tambah Kategori</h3>
        <form action="../../controllers/c_kategori.php?aksi=tambah" method="POST">
            <input type="text" name="nama_kategori" placeholder="Nama kategori" required>
            <div class="modal-aksi">
                <button type="button" onclick="tutupModal()">Batal</button>
                <button type="submit" class="btn-simpan">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT -->
<div class="modal" id="modalEdit">
    <div class="modal-box">
        <h3>Edit Kategori</h3>
        <form action="../../controllers/c_kategori.php?aksi=edit" method="POST">
            <input type="hidden" name="id" id="edit_id">
            <input type="text" name="nama_kategori" id="edit_nama" required>
            <div class="modal-aksi">
                <button type="button" onclick="tutupModal()">Batal</button>
                <button type="submit" class="btn-simpan">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function bukaTambah() {
    document.getElementById('modalTambah').style.display = 'flex';
}
function bukaEdit(id, nama) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('modalEdit').style.display = 'flex';
}
function tutupModal() {
    document.getElementById('modalTambah').style.display = 'none';
    document.getElementById('modalEdit').style.display = 'none';
}
</script>

</main>

<?php include '../layout/footer.php'; ?>
