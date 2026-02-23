<?php
session_start();

if (!isset($_SESSION['data']) || $_SESSION['data']['role'] !== 'admin') {
  header("Location: ../index.php");
  exit;
}

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
  border-radius: 14px;
  padding: 20px;
  box-shadow: 0 6px 18px rgba(0,0,0,.06);
  transition: .2s;
}

.card-aspirasi:hover {
  transform: translateY(-3px);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.kelas {
  font-size: 12px;
  background: #f3f4f6;
  padding: 4px 10px;
  border-radius: 20px;
  margin-left: 6px;
}

.status {
  font-size: 12px;
  padding: 4px 10px;
  border-radius: 14px;
  margin-left: 8px;
  font-weight: 500;
}

.status.baru { background:#e5e7eb; }
.status.diproses { background:#e5e7eb; }
.status.selesai { background:#d1d5db; }

/* TOMBOL ABU MODERN */
.icon-btn {
  border: none;
  background: #4b5563;
  padding: 8px 12px;
  border-radius: 8px;
  cursor: pointer;
  color: white;
  transition: all .2s ease;
}

.icon-btn:hover {
  background: #374151;
  transform: scale(1.05);
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

/* ================= MODAL ================= */

.modal {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.4); /* TANPA BLUR */
  z-index: 999;
}

.modal-box {
  background: #fff;
  width: 550px;
  margin: 70px auto;
  padding: 25px;
  border-radius: 14px;
  box-shadow: 0 15px 30px rgba(0,0,0,.15);
  animation: fadeIn .2s ease-in-out;
}

@keyframes fadeIn {
  from { transform: translateY(-10px); opacity:0 }
  to { transform: translateY(0); opacity:1 }
}

.modal-box h3 {
  margin-bottom: 15px;
}

.detail p {
  margin-bottom: 6px;
  font-size: 14px;
}

.detail .box {
  background:#f3f4f6;
  padding:12px;
  border-radius:8px;
  margin-bottom:12px;
  font-size:14px;
}

textarea {
  width:100%;
  min-height:130px;
  padding:12px;
  border-radius:8px;
  border:1px solid #d1d5db;
  resize:none;
  font-family:inherit;
  font-size:14px;
  transition:.2s;
}

textarea:focus {
  outline:none;
  border:1px solid #4b5563;
  box-shadow:0 0 0 2px rgba(75,85,99,.2);
}

.modal-aksi {
  margin-top:18px;
  text-align:right;
}

.modal-aksi button {
  padding:8px 16px;
  border:none;
  border-radius:8px;
  cursor:pointer;
  font-size:14px;
  transition:.2s;
}

.modal-aksi button[type="submit"] {
  background:#2563eb;
  color:white;
}

.modal-aksi button[type="submit"]:hover {
  background:#1d4ed8;
}

.modal-aksi button[type="button"] {
  background:#e5e7eb;
  margin-left:8px;
}

.modal-aksi button[type="button"]:hover {
  background:#d1d5db;
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
      <svg viewBox="0 0 24 24" fill="none">
        <path d="M4 4H20V16H6L4 18V4Z"
          stroke="currentColor"
          stroke-width="2"
          stroke-linejoin="round"/>
      </svg>
    </button>
  </div>

  <h4><?= htmlspecialchars($row->judul) ?></h4>
  <p class="pesan"><?= htmlspecialchars($row->pesan) ?></p>

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
      <textarea name="tanggapan" required placeholder="Tulis tanggapan di sini..."></textarea>

      <div class="modal-aksi">
        <button type="submit">Kirim</button>
        <button type="button" onclick="closeModal()">Batal</button>
      </div>
    </form>
  </div>
</div>

<script>
const modalBalas = document.getElementById('modalBalas');
const aspirasi_id = document.getElementById('aspirasi_id');
const d_nama = document.getElementById('d_nama');
const d_kelas = document.getElementById('d_kelas');
const d_judul = document.getElementById('d_judul');
const d_pesan = document.getElementById('d_pesan');

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

window.onclick = function(e) {
  if (e.target == modalBalas) {
    closeModal();
  }
}
</script>

<?php include '../layout/footer.php'; ?>