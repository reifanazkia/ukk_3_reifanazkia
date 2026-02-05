<?php
include_once __DIR__ . '/../../controllers/c_umpanbalik.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$data = $umpanbalik->tampil_data();
?>

<style>
.card-wrapper {
  display: flex;
  flex-direction: column;
  gap: 16px;
  margin-top: 20px;
}

.card-aspirasi {
  background: #fff;
  border-radius: 12px;
  padding: 18px;
  box-shadow: 0 3px 8px rgba(0,0,0,.08);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.kelas {
  font-size: 12px;
  background: #eee;
  padding: 3px 10px;
  border-radius: 20px;
  margin-left: 6px;
}

.status {
  font-size: 12px;
  padding: 4px 10px;
  border-radius: 14px;
  margin-left: 8px;
}

.status.baru { background:#e0e0e0; }
.status.diproses { background:#ffe08a; }
.status.selesai { background:#b7f5c4; }

.icon-btn {
  border: none;
  background: #f2f2f2;
  padding: 6px 10px;
  border-radius: 8px;
  cursor: pointer;
}

.icon-btn svg {
  width: 18px;
  height: 18px;
}

h4 { margin: 12px 0 6px; }
.pesan { font-size:14px; color:#555; }

.riwayat {
  margin-top: 14px;
  padding-top: 10px;
  border-top: 1px dashed #ddd;
}

.riwayat-list {
  margin-top: 8px;
  padding-left: 20px;
}

.riwayat-list li {
  margin-bottom: 8px;
  font-size: 14px;
}

.waktu {
  font-size: 11px;
  color: #888;
  margin-left: 6px;
}

/* MODAL */
.modal {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.5);
  z-index: 999;
}

.modal-box {
  background: #fff;
  width: 520px;
  margin: 80px auto;
  padding: 20px;
  border-radius: 12px;
}

.detail { font-size: 14px; margin-bottom: 12px; }
.detail .box {
  background:#f6f6f6;
  padding:10px;
  border-radius:6px;
  margin-bottom:10px;
}

textarea {
  width:100%;
  min-height:120px;
  padding:10px;
}

.modal-aksi {
  margin-top:15px;
  text-align:right;
}
</style>

<h2>Umpan Balik Aspirasi</h2>
<p style="color:#666">Admin dapat mengirim lebih dari satu tanggapan</p>

<div class="card-wrapper">

<?php if ($data && mysqli_num_rows($data) > 0): ?>
<?php while ($row = mysqli_fetch_object($data)) : ?>

<div class="card-aspirasi">

  <div class="card-header">
    <div>
      <strong><?= htmlspecialchars($row->nama_lengkap) ?></strong>
      <span class="kelas"><?= htmlspecialchars($row->kelas) ?></span>
      <span class="status <?= $row->status ?>">
        <?= ucfirst($row->status) ?>
      </span>
    </div>

    <button class="icon-btn btn-balas"
      data-id="<?= $row->id ?>"
      data-nama="<?= htmlspecialchars($row->nama_lengkap) ?>"
      data-kelas="<?= htmlspecialchars($row->kelas) ?>"
      data-judul="<?= htmlspecialchars($row->judul) ?>"
      data-pesan="<?= htmlspecialchars($row->pesan) ?>">

      <!-- CHAT SVG -->
      <svg viewBox="0 0 24 24" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <path d="M4 4H20V16H6L4 18V4Z"
          stroke="currentColor"
          stroke-width="2"
          stroke-linejoin="round"/>
      </svg>
    </button>
  </div>

  <h4><?= htmlspecialchars($row->judul) ?></h4>
  <p class="pesan"><?= htmlspecialchars($row->pesan) ?></p>

  <!-- RIWAYAT -->
  <div class="riwayat">
    <?php
      $riwayat = $umpanbalik->riwayat($row->id);
      $jumlah  = mysqli_num_rows($riwayat);
    ?>

    <strong>
      Riwayat Tanggapan
      <span class="status diproses"><?= $jumlah ?></span>
    </strong>

    <?php if ($jumlah == 0): ?>
      <p><i>Belum ada tanggapan</i></p>
    <?php else: ?>
      <ul class="riwayat-list">
        <?php while ($r = mysqli_fetch_object($riwayat)) : ?>
          <li>
            <?= htmlspecialchars($r->tanggapan) ?>
            <span class="waktu">
              (<?= date('d M Y H:i', strtotime($r->created_at)) ?>)
            </span>
          </li>
        <?php endwhile; ?>
      </ul>
    <?php endif; ?>
  </div>

</div>

<?php endwhile; ?>
<?php else: ?>
<p>Tidak ada aspirasi</p>
<?php endif; ?>

</div>

<!-- MODAL -->
<div id="modalBalas" class="modal">
  <div class="modal-box">
    <h3>Balas Aspirasi</h3>

    <div class="detail">
      <p><strong>Nama:</strong> <span id="d_nama"></span></p>
      <p><strong>Kelas:</strong> <span id="d_kelas"></span></p>
      <p><strong>Judul:</strong></p>
      <div id="d_judul" class="box"></div>
      <p><strong>Isi:</strong></p>
      <div id="d_pesan" class="box"></div>
    </div>

    <form action="../../controllers/c_umpanbalik.php?aksi=tambah" method="POST">
      <input type="hidden" name="aspirasi_id" id="aspirasi_id">
      <textarea name="tanggapan" required></textarea>

      <div class="modal-aksi">
        <button type="submit">Kirim</button>
        <button type="button" onclick="closeModal()">Batal</button>
      </div>
    </form>
  </div>
</div>

<script>
document.querySelectorAll('.btn-balas').forEach(btn => {
  btn.onclick = () => {
    modalBalas.style.display = 'block';
    aspirasi_id.value = btn.dataset.id;
    d_nama.innerText = btn.dataset.nama;
    d_kelas.innerText = btn.dataset.kelas;
    d_judul.innerText = btn.dataset.judul;
    d_pesan.innerText = btn.dataset.pesan;
  }
});

function closeModal() {
  modalBalas.style.display = 'none';
}
</script>

<?php include '../layout/footer.php'; ?>
