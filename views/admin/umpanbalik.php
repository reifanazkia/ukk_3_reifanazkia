<?php
include_once __DIR__ . '../../../controllers/c_umpanbalk.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$data_umpanbalik = $umpanbalik->tampil_data();
?>

<h2>Umpan Balik Aspirasi</h2>
<p style="color:#666">Klik ikon pesan untuk membalas aspirasi siswa</p>

<div class="card-wrapper">
<?php if (!empty($data_umpanbalik)) : ?>
<?php foreach ($data_umpanbalik as $row) : ?>
  <div class="card-aspirasi">

    <div class="card-header">
      <div>
        <strong><?= $row->nama_lengkap ?></strong>
        <span class="kelas"><?= $row->kelas ?></span>
      </div>

      <div class="aksi">
        <button class="icon-btn"
          title="Balas Aspirasi"
          onclick="openEdit(
            '<?= $row->id ?>',
            '<?= htmlspecialchars($row->nama_lengkap) ?>',
            '<?= htmlspecialchars($row->kelas) ?>',
            '<?= htmlspecialchars($row->judul) ?>',
            `<?= htmlspecialchars($row->pesan) ?>`,
            `<?= htmlspecialchars($row->tanggapan) ?>`
          )">✉️</button>

        <a class="icon-btn danger"
           href="../../controllers/c_umpanbalik.php?aksi=hapus&id=<?= $row->id ?>"
           onclick="return confirm('Yakin hapus umpan balik ini?')">🗑️</a>
      </div>
    </div>

    <h4><?= $row->judul ?></h4>
    <p class="pesan"><?= $row->pesan ?></p>

    <div class="tanggapan">
      <strong>Tanggapan Admin:</strong>
      <p><?= $row->tanggapan ?: '<i>Belum ditanggapi</i>' ?></p>
    </div>

  </div>
<?php endforeach; ?>
<?php else : ?>
  <p>Tidak ada data umpan balik</p>
<?php endif; ?>
</div>

<!-- ================= MODAL BALAS ================= -->
<div id="modalEdit" class="modal">
  <div class="modal-box">
    <h3>Detail Aspirasi</h3>

    <div class="detail">
      <p><strong>Nama:</strong> <span id="d_nama"></span></p>
      <p><strong>Kelas:</strong> <span id="d_kelas"></span></p>

      <p><strong>Judul Aspirasi:</strong></p>
      <div id="d_judul" class="box"></div>

      <p><strong>Isi Aspirasi:</strong></p>
      <div id="d_pesan" class="box"></div>
    </div>

    <form action="../../controllers/c_umpanbalik.php?aksi=edit" method="POST">
      <input type="hidden" name="id" id="edit_id">

      <label>Tanggapan Admin</label>
      <textarea name="tanggapan" id="edit_tanggapan" required></textarea>

      <div class="modal-aksi">
        <button type="submit">Kirim</button>
        <button type="button" onclick="closeModal()">Batal</button>
      </div>
    </form>
  </div>
</div>

<!-- ================= STYLE ================= -->
<style>
.card-wrapper{
  display:flex;
  flex-direction:column;
  gap:16px;
  margin-top:20px;
}
.card-aspirasi{
  background:#fff;
  border-radius:10px;
  padding:16px;
  box-shadow:0 2px 6px rgba(0,0,0,.08);
  position:relative;
}
.card-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
}
.kelas{
  font-size:12px;
  background:#eee;
  padding:2px 8px;
  border-radius:20px;
  margin-left:6px;
}
.aksi{
  display:flex;
  gap:6px;
}
.icon-btn{
  border:none;
  background:#f3f3f3;
  padding:6px 10px;
  border-radius:6px;
  cursor:pointer;
  text-decoration:none;
  pointer-events:auto;
  z-index:10;
}
.icon-btn:hover{
  background:#ddd;
}
.icon-btn.danger{
  background:#ffe0e0;
}
h4{
  margin:10px 0 6px;
}
.pesan{
  color:#555;
  font-size:14px;
}
.tanggapan{
  margin-top:10px;
  padding-top:10px;
  border-top:1px dashed #ddd;
}

/* MODAL */
.modal{
  display:none;
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.5);
  z-index:999;
}
.modal-box{
  background:#fff;
  width:520px;
  margin:80px auto;
  padding:20px;
  border-radius:10px;
}
.detail{
  font-size:14px;
  margin-bottom:10px;
}
.detail .box{
  background:#f6f6f6;
  padding:10px;
  border-radius:6px;
  margin-bottom:8px;
  white-space:pre-wrap;
}
textarea{
  width:100%;
  min-height:120px;
  padding:10px;
}
.modal-aksi{
  margin-top:15px;
  text-align:right;
}
.modal-aksi button{
  padding:8px 14px;
}
</style>

<!-- ================= SCRIPT ================= -->
<script>
function openEdit(id, nama, kelas, judul, pesan, tanggapan){
  document.getElementById('modalEdit').style.display = 'block';

  document.getElementById('edit_id').value = id;
  document.getElementById('edit_tanggapan').value = tanggapan || '';

  document.getElementById('d_nama').innerText = nama;
  document.getElementById('d_kelas').innerText = kelas;
  document.getElementById('d_judul').innerText = judul;
  document.getElementById('d_pesan').innerText = pesan;
}
function closeModal(){
  document.getElementById('modalEdit').style.display = 'none';
}
</script>

<?php include '../layout/footer.php'; ?>
