<?php
session_start();

if (!isset($_SESSION['data']) || $_SESSION['data']['role'] !== 'admin') {
  header("Location: ../index.php");
  exit;
}

include_once '../../controllers/c_aspirasi.php';
include '../layout/header.php';
include '../layout/sidebar.php';
$data_aspirasi = $aspirasi->tampil_data();
?>

<style>
.table-aspirasi {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.table-aspirasi th {
  background: #f4f6f9;
  padding: 14px;
  font-size: 13px;
  text-transform: uppercase;
  letter-spacing: .5px;
  color: #555;
}

.table-aspirasi td {
  padding: 14px;
  font-size: 14px;
  color: #444;
  border-top: 1px solid #eee;
}

.table-aspirasi tr:hover {
  background: #f9fbfd;
}

.action-buttons {
  display: flex;
  gap: 14px;
  align-items: center;
}

/* BUTTONS */
.btn-edit {
  background: none;
  border: none;
  color: #2563eb;
  font-weight: 600;
  cursor: pointer;
  font-size: 13px;
  transition: .2s;
}

.btn-edit:hover {
  color: #1e40af;
  text-decoration: underline;
}

.btn-delete {
  color: #dc2626;
  font-size: 13px;
  text-decoration: none;
  font-weight: 600;
  transition: .2s;
}

.btn-delete:hover {
  color: #991b1b;
  text-decoration: underline;
}

/* STATUS BADGE */
.status-badge {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: .5px;
  display: inline-block;
}

.status-selesai {
  background: #dcfce7;
  color: #166534;
}

.status-ditolak {
  background: #fee2e2;
  color: #991b1b;
}

.status-menunggu {
  background: #dbeafe;
  color: #1e3a8a;
}

.status-diproses {
  background: #fef9c3;
  color: #854d0e;
}

/* MODAL */
#modalStatus {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.45);
  backdrop-filter: blur(3px);
  z-index: 999;
}

.modal-content {
  background: #fff;
  width: 420px;
  margin: 120px auto;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
  animation: fadeIn .2s ease-in-out;
}

@keyframes fadeIn {
  from { transform: translateY(-10px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

.modal-content h3 {
  margin-bottom: 20px;
  font-size: 18px;
  color: #333;
}

.modal-content select {
  width: 100%;
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #ddd;
  margin-top: 10px;
  font-size: 14px;
}

.modal-buttons {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 20px;
}

.btn-save {
  background: #2563eb;
  border: none;
  padding: 8px 16px;
  color: #fff;
  border-radius: 6px;
  cursor: pointer;
  font-size: 13px;
  transition: .2s;
}

.btn-save:hover {
  background: #1e40af;
}

.btn-cancel {
  background: #e5e7eb;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 13px;
}

.btn-cancel:hover {
  background: #d1d5db;
}
</style>

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
    <?php $no = 1; foreach ($data_aspirasi as $row) : ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $row->nama_lengkap ?></td>
        <td><?= $row->kelas ?></td>
        <td><?= $row->nama_kategori ?></td>
        <td><?= $row->judul ?></td>
        <td><?= $row->pesan ?></td>

        <td>
          <?php
            $statusClass = '';

            if ($row->status == 'selesai') {
              $statusClass = 'status-selesai';
            } elseif ($row->status == 'ditolak') {
              $statusClass = 'status-ditolak';
            } elseif ($row->status == 'menunggu') {
              $statusClass = 'status-menunggu';
            } elseif ($row->status == 'diproses') {
              $statusClass = 'status-diproses';
            }
          ?>
          <span class="status-badge <?= $statusClass ?>">
            <?= strtoupper($row->status) ?>
          </span>
        </td>

        <td>
          <div class="action-buttons">
            <button class="btn-edit"
              onclick="openModal(<?= $row->id ?>, '<?= $row->status ?>')">
              Edit
            </button>

            <a class="btn-delete"
              href="../../controllers/c_aspirasi.php?aksi=hapus&id=<?= $row->id ?>"
              onclick="return confirm('Yakin hapus data ini?')">
              Hapus
            </a>
          </div>
        </td>

      </tr>
    <?php endforeach; ?>
  <?php else : ?>
    <tr>
      <td colspan="8" align="center">Data belum ada</td>
    </tr>
  <?php endif; ?>
</table>

<!-- MODAL -->
<div id="modalStatus">
  <div class="modal-content">
    <h3>Edit Status Aspirasi</h3>

    <form action="../../controllers/c_aspirasi.php?aksi=update_status" method="POST">
      <input type="hidden" name="id" id="id_aspirasi">

      <label>Status</label>
      <select name="status" id="status_select" required>
        <option value="menunggu">Menunggu</option>
        <option value="diproses">Diproses</option>
        <option value="selesai">Selesai</option>
        <option value="ditolak">Ditolak</option>
      </select>

      <div class="modal-buttons">
        <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
        <button type="submit" class="btn-save">Simpan</button>
      </div>
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