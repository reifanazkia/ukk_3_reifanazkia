<?php
include_once __DIR__ . '../../../controllers/c_umpanbalik.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$data_umpanbalik = $umpanbalik->tampil_data();
?>

<h2>Umpan Balik Aspirasi</h2>
<p style="color:#666">Klik ikon 👁️ untuk melihat tanggapan admin</p>

<div class="card-wrapper">
<?php if (!empty($data_umpanbalik)) : ?>
<?php foreach ($data_umpanbalik as $row) : ?>
  <div class="card-aspirasi">

    <div class="card-header">
      <div>
        <strong><?= $row->judul ?></strong>
        <span class="kelas"><?= $row->kelas ?></span>
      </div>

      <div class="aksi">
        <button class="icon-btn"
          title="Lihat Umpan Balik"
          onclick="openLihat(
            '<?= htmlspecialchars($row->nama_lengkap) ?>',
            '<?= htmlspecialchars($row->kelas) ?>',
            '<?= htmlspecialchars($row->judul) ?>',
            `<?= htmlspecialchars($row->pesan) ?>`,
            `<?= htmlspecialchars($row->tanggapan) ?>`
          )">👁️</button>
      </div>
    </div>

    <p class="pesan"><?= $row->pesan ?></p>

    <div class="status">
      Status:
      <?php if ($row->tanggapan) : ?>
        <span class="badge done">Sudah Dibalas</span>
      <?php else : ?>
        <span class="badge wait">Belum Dibalas</span>
      <?php endif; ?>
    </div>

  </div>
<?php endforeach; ?>
<?php else : ?>
  <p>Tidak ada data aspirasi</p>
<?php endif; ?>
</div>

<!-- ============ MODAL LIHAT ============ -->
<div id="modalLihat" class="modal">
  <div class="modal-box">
    <h3>Detail Aspirasi</h3>

    <div class="detail">
      <p><strong>Nama:</strong> <span id="d_nama"></span></p>
      <p><strong>Kelas:</strong> <span id="d_kelas"></span></p>

      <p><strong>Judul Aspirasi:</strong></p>
      <div id="d_judul" class="box"></div>

      <p><strong>Isi Aspirasi:</strong></p>
      <div id="d_pesan" class="box"></div>

      <p><strong>Tanggapan Admin:</strong></p>
      <div id="d_tanggapan" class="box"></div>
    </div>

    <div class="modal-aksi">
      <button onclick="closeModal()">Tutup</button>
    </div>
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
.icon-btn{
  border:none;
  background:#f3f3f3;
  padding:6px 12px;
  border-radius:6px;
  cursor:pointer;
}
.icon-btn:hover{
  background:#ddd;
}
.pesan{
  color:#555;
  font-size:14px;
  margin-top:6px;
}
.status{
  margin-top:10px;
}
.badge{
  padding:4px 10px;
  border-radius:20px;
  font-size:12px;
}
.badge.done{
  background:#d4edda;
  color:#155724;
}
.badge.wait{
  background:#fff3cd;
  color:#856404;
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
.detail .box{
  background:#f6f6f6;
  padding:10px;
  border-radius:6px;
  margin-bottom:8px;
  white-space:pre-wrap;
}
.modal-aksi{
  text-align:right;
}
.modal-aksi button{
  padding:8px 14px;
}
</style>

<!-- ================= SCRIPT ================= -->
<script>
function openLihat(nama, kelas, judul, pesan, tanggapan){
  document.getElementById('modalLihat').style.display = 'block';

  document.getElementById('d_nama').innerText = nama;
  document.getElementById('d_kelas').innerText = kelas;
  document.getElementById('d_judul').innerText = judul;
  document.getElementById('d_pesan').innerText = pesan;
  document.getElementById('d_tanggapan').innerHTML =
    tanggapan ? tanggapan : '<i>Belum ada umpan balik dari admin</i>';
}
function closeModal(){
  document.getElementById('modalLihat').style.display = 'none';
}
</script>

<?php include '../layout/footer.php'; ?>
