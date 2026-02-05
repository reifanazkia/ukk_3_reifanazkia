<?php
include_once '../../controllers/c_aspirasi.php';
include '../layout/header.php';
include '../layout/sidebar.php';
$data_aspirasi = $aspirasi->tampil_data();
?>

<h2>Data Aspirasi Siswa</h2>
<br>

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
    <?php $no = 1;
    foreach ($data_aspirasi as $row) : ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $row->nama_lengkap ?></td>
        <td><?= $row->kelas ?></td>
        <td><?= $row->nama_kategori ?></td>
        <td><?= $row->judul ?></td>
        <td><?= $row->pesan ?></td>
        <td><b><?= strtoupper($row->status) ?></b></td>
        <td>
          <button onclick="openModal(<?= $row->id ?>, '<?= $row->status ?>')">Edit Status</button>
          <a href="../../controllers/c_aspirasi.php?aksi=hapus&id=<?= $row->id ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
        </td>
      </tr>
    <?php endforeach; ?>
  <?php else : ?>
    <tr>
      <td colspan="8" align="center">Data belum ada</td>
    </tr>
  <?php endif; ?>
</table>

<div id="modalStatus" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:999;">
  <div style="background:#fff; width:400px; margin:120px auto; padding:20px; border-radius:8px;">
    <h3>Edit Status Aspirasi</h3>
    <form action="../../controllers/c_aspirasi.php?aksi=update_status" method="POST">
      <input type="hidden" name="id" id="id_aspirasi">
      <label>Status</label><br>
      <select name="status" id="status_select" required style="width:100%; padding:8px; margin-top:10px;">
        <option value="menunggu">Menunggu</option>
        <option value="diproses">Diproses</option>
        <option value="selesai">Selesai</option>
        <option value="ditolak">Ditolak</option>
      </select>
      <br><br>
      <button type="submit">Simpan Status</button>
      <button type="button" onclick="closeModal()">Batal</button>
    </form>
  </div>
</div>

<script>
  function openModal(id, status) {
    document.getElementById('modalStatus').style.display = 'block';
    document.getElementById('id_aspirasi').value = id;
    document.getElementById('status_select').value = status;
  }

  function closeModal() {
    document.getElementById('modalStatus').style.display = 'none';
  }
</script>

<?php include '../layout/footer.php'; ?>