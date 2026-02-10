<?php
include_once '../../controllers/c_aspirasi.php';
include_once '../../controllers/c_kategori.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$data_aspirasi = $aspirasi->tampil_data();
$kategori = $kategori->tampil_data(); // pastikan ini menampilkan kategori
?>

<!-- ================= STYLE ================= -->
<style>
/* Modal overlay */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 999;
}

/* Modal box */
.modal-content {
    background: #fff;
    width: 450px;
    margin: 100px auto;
    padding: 20px;
    border-radius: 8px;
    position: relative;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}

/* Close button */
.close {
    position: absolute;
    top: 10px;
    right: 15px;
    cursor: pointer;
    font-size: 20px;
}

/* Form group */
.form-group {
    margin-bottom: 15px;
}
.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
}
.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
textarea {
    resize: vertical;
    height: 80px;
}

/* Buttons */
.btn-submit {
    background: #28a745;
    color: #fff;
    padding: 8px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.btn-primary {
    background: #007bff;
    color: #fff;
    padding: 8px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-bottom: 10px;
}
.btn-edit {
    background: #ffc107;
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    text-decoration: none;
    margin-right: 5px;
}
.btn-hapus {
    background: #dc3545;
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    text-decoration: none;
}
</style>

<h2>Data Aspirasi Siswa</h2>
<br>

<button onclick="openTambah()" class="btn-primary">+ Tambah Aspirasi</button>
<br><br>

<table class="table-aspirasi">
<tr>
  <th>No</th>
  <th>Nama</th>
  <th>Kelas</th>
  <th>Kategori</th>
  <th>Judul</th>
  <th>Pesan</th>
  <th>Status</th>
  <th>Aksi</th>
</tr>

<?php if (!empty($data_aspirasi)) : ?>
<?php $no = 1; foreach ($data_aspirasi as $row) : ?>
<tr>
  <td><?= $no++ ?></td>
  <td><?= $row->nama_lengkap ?></td>
  <td><?= $row->kelas ?></td>
  <td><?= $row->nama_kategori ?></td>
  <td><?= $row->judul ?></td>
  <td><?= $row->pesan ?></td>
  <td><?= ucfirst($row->status) ?></td>
  <td>
    <a href="#" onclick="openEdit(
      '<?= $row->id ?>',
      '<?= $row->nama_lengkap ?>',
      '<?= $row->kelas ?>',
      '<?= $row->id_categori ?>',
      '<?= $row->judul ?>',
      '<?= $row->pesan ?>'
    )" class="btn-edit">Edit</a>

  </td>
</tr>
<?php endforeach; ?>
<?php else : ?>
<tr>
  <td colspan="8" align="center">Data belum ada</td>
</tr>
<?php endif; ?>
</table>

<!-- ================= MODAL TAMBAH ================= -->
<div id="modalTambah" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeTambah()">&times;</span>
    <h3>Tambah Aspirasi</h3>

    <form action="../../controllers/c_aspirasi.php?aksi=tambah" method="post">
      <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required>
      </div>

      <div class="form-group">
        <label>Kelas</label>
        <input type="text" name="kelas" placeholder="Kelas" required>
      </div>

      <div class="form-group">
        <label>Kategori</label>
        <select name="id_categori" required>
          <option value="">-- Pilih Kategori --</option>
          <?php foreach ($kategori as $k) : ?>
            <option value="<?= $k->id ?>"><?= $k->nama_kategori ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label>Judul</label>
        <input type="text" name="judul" placeholder="Judul" required>
      </div>

      <div class="form-group">
        <label>Pesan</label>
        <textarea name="pesan" placeholder="Tulis pesan..." required></textarea>
      </div>

      <button type="submit" class="btn-submit">Simpan</button>
    </form>
  </div>
</div>

<!-- ================= MODAL EDIT ================= -->
<div id="modalEdit" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeEdit()">&times;</span>
    <h3>Edit Aspirasi</h3>

    <form action="../../controllers/c_aspirasi.php?aksi=update" method="post">
      <input type="hidden" name="id" id="edit_id">

      <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" name="nama_lengkap" id="edit_nama" required>
      </div>

      <div class="form-group">
        <label>Kelas</label>
        <input type="text" name="kelas" id="edit_kelas" required>
      </div>

      <div class="form-group">
        <label>Kategori</label>
        <select name="id_categori" id="edit_kategori" required>
          <?php foreach ($kategori as $k) : ?>
            <option value="<?= $k->id ?>"><?= $k->nama_kategori ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label>Judul</label>
        <input type="text" name="judul" id="edit_judul" required>
      </div>

      <div class="form-group">
        <label>Pesan</label>
        <textarea name="pesan" id="edit_pesan" required></textarea>
      </div>

      <button type="submit" class="btn-submit">Update</button>
    </form>
  </div>
</div>



<script>
function openTambah() { 
    document.getElementById('modalTambah').style.display = 'block'; 
}
function closeTambah() { 
    document.getElementById('modalTambah').style.display = 'none'; 
}
function openEdit(id, nama, kelas, kategori, judul, pesan) {
    document.getElementById('modalEdit').style.display = 'block';
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_kelas').value = kelas;
    document.getElementById('edit_kategori').value = kategori;
    document.getElementById('edit_judul').value = judul;
    document.getElementById('edit_pesan').value = pesan;
}
function closeEdit() { 
    document.getElementById('modalEdit').style.display = 'none'; 
}
</script>

<?php include '../layout/footer.php'; ?>
