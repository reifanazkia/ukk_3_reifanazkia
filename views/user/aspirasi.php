<?php
include_once '../../controllers/c_aspirasi.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$data_aspirasi = $aspirasi->tampil_data();
$kategori = $aspirasi->tampil_data(); // pastikan fungsi ini ada
?>

<h2>Data Aspirasi Siswa</h2>
<br>

<button onclick="openTambah()">+ Tambah Aspirasi</button>
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
  <td><?= $row->status ?></td>
  <td>
    <a href="#" onclick="openEdit(
      '<?= $row->id ?>',
      '<?= $row->nama_lengkap ?>',
      '<?= $row->kelas ?>',
      '<?= $row->id_categori ?>',
      '<?= $row->judul ?>',
      '<?= $row->pesan ?>'
    )">Edit</a>

    <a href="../../controllers/c_aspirasi.php?aksi=hapus&id=<?= $row->id ?>"
       onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
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
      <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required>
      <input type="text" name="kelas" placeholder="Kelas" required>

      <select name="id_categori" required>
        <option value="">-- Pilih Kategori --</option>
        <?php foreach ($kategori as $k) : ?>
          <option value="<?= $k->id ?>"><?= $k->id ?></option>
        <?php endforeach; ?>
      </select>

      <input type="text" name="judul" placeholder="Judul" required>
      <textarea name="pesan" placeholder="Pesan" required></textarea>

      <button type="submit">Simpan</button>
    </form>
  </div>
</div>

<!-- ================= MODAL EDIT ================= -->
<div id="modalEdit" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeEdit()">&times;</span>
    <h3>Edit Aspirasi</h3>

    <form action="../../controllers/c_aspirasi.php?aksi=edit" method="post">
      <input type="hidden" name="id" id="edit_id">

      <input type="text" name="nama_lengkap" id="edit_nama" required>
      <input type="text" name="kelas" id="edit_kelas" required>

      <select name="id_categori" id="edit_kategori" required>
        <?php foreach ($kategori as $k) : ?>
          <option value="<?= $k->id ?>"><?= $k->id ?></option>
        <?php endforeach; ?>
      </select>

      <input type="text" name="judul" id="edit_judul" required>
      <textarea name="pesan" id="edit_pesan" required></textarea>

      <button type="submit">Update</button>
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
