<?php
session_start();
include_once __DIR__ . '/../../controllers/c_umpanbalik.php';
include '../layout/header.php';
include '../layout/sidebar.php';

// ambil data aspirasi MILIK SISWA LOGIN
$user_id = $_SESSION['data']['id'];
$data    = $umpanbalik->tampil_data($user_id);
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

.status {
  font-size: 12px;
  padding: 4px 10px;
  border-radius: 14px;
}

.status.baru { background:#e0e0e0; }
.status.diproses { background:#ffe08a; }
.status.selesai { background:#b7f5c4; }

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
</style>

<h2>Umpan Balik Aspirasi Saya</h2>
<p style="color:#666">Aspirasi yang pernah saya kirim & tanggapan admin</p>

<div class="card-wrapper">

<?php if ($data && mysqli_num_rows($data) > 0): ?>
<?php while ($row = mysqli_fetch_object($data)) : ?>

<div class="card-aspirasi">

  <!-- HEADER -->
  <div class="card-header">
    <strong><?= htmlspecialchars($row->judul) ?></strong>

    <!-- STATUS DARI ADMIN -->
    <span class="status <?= $row->status ?>">
      <?= ucfirst($row->status) ?>
    </span>
  </div>

  <!-- PESAN ASPIRASI -->
  <p class="pesan"><?= htmlspecialchars($row->pesan) ?></p>

  <!-- INFO BALASAN (DARI MODEL: jumlah_balasan) -->
  <p style="font-size:13px;color:#777">
    <?= $row->jumlah_balasan > 0
        ? 'Admin sudah memberikan tanggapan'
        : 'Menunggu tanggapan admin'; ?>
  </p>

  <!-- RIWAYAT BALASAN -->
  <div class="riwayat">
    <strong>Umpan Balik Admin (<?= $row->jumlah_balasan ?>)</strong>

    <?php if ($row->jumlah_balasan == 0): ?>
      <p><i>Belum ada tanggapan</i></p>
    <?php else: ?>
      <?php
        // detail balasan tetap ambil dari riwayat()
        $riwayat = $umpanbalik->riwayat($row->id);
      ?>
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
<p>Tidak ada aspirasi yang pernah dikirim</p>
<?php endif; ?>

</div>

<?php include '../layout/footer.php'; ?>
