<?php
session_start();

if (!isset($_SESSION['data']) || $_SESSION['data']['role'] !== 'user') {
    header("Location: ../index.php");
    exit;
}

include_once '../../controllers/c_aspirasi.php';
include_once '../../controllers/c_kategori.php';
include '../layout/header.php';
include '../layout/sidebar.php';

// Ambil data hanya milik user yang sedang login
$user_id = $_SESSION['data']['id'];
$data_aspirasi = $aspirasi->tampil_data($user_id); 
$kategori = $kategori->tampil_data(); 
?>

<style>
/* ================= CSS TABEL (LURUS & RAPI) ================= */
.table-aspirasi {
    width: 100%;
    border-collapse: collapse;
    background-color: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    margin-top: 10px;
}

.table-aspirasi th {
    background-color: #f8fafc;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    font-weight: 700;
    padding: 15px 20px;
    text-align: left;
    border-bottom: 2px solid #e2e8f0;
}

.table-aspirasi td {
    padding: 15px 20px;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    font-size: 14px;
    vertical-align: middle;
}

.table-aspirasi tr:hover {
    background-color: #f9fafb;
}

/* ================= MODAL STYLING ================= */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
}

.modal-content {
    background: #fff;
    width: 90%;
    max-width: 500px;
    margin: 60px auto;
    padding: 25px;
    border-radius: 12px;
    position: relative;
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2);
}

.close {
    position: absolute;
    top: 15px;
    right: 20px;
    cursor: pointer;
    font-size: 24px;
    color: #94a3b8;
}

/* ================= FORM & BUTTONS ================= */
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #1e293b;
    font-size: 14px;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-family: inherit;
    font-size: 14px;
}

textarea {
    resize: vertical;
    min-height: 100px;
}

.btn-primary {
    background: #2563eb;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}

.btn-primary:hover {
    background: #1d4ed8;
}

.btn-submit {
    background: #16a34a;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    width: 100%;
    margin-top: 10px;
}

.btn-edit {
    background: #fbbf24;
    color: #000;
    padding: 6px 14px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.btn-edit:hover {
    background: #f59e0b;
}
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2 style="margin: 0; color: #1e293b;">Data Aspirasi Siswa</h2>
    <button onclick="openTambah()" class="btn-primary">+ Tambah Aspirasi</button>
</div>

<table class="table-aspirasi">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Kategori</th>
            <th>Judul</th>
            <th>Pesan</th>
            <th>Status</th>
            <th style="text-align: center;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($data_aspirasi)) : ?>
            <?php $no = 1; foreach ($data_aspirasi as $row) : ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row->nama_lengkap) ?></td>
                <td><?= htmlspecialchars($row->kelas) ?></td>
                <td><?= htmlspecialchars($row->nama_kategori) ?></td>
                <td><?= htmlspecialchars($row->judul) ?></td>
                <td><?= nl2br(htmlspecialchars($row->pesan)) ?></td>
                <td><?= ucfirst($row->status) ?></td>
                <td align="center">
                    <a href="javascript:void(0)" onclick="openEdit(
                        '<?= $row->id ?>',
                        '<?= htmlspecialchars($row->nama_lengkap) ?>',
                        '<?= htmlspecialchars($row->kelas) ?>',
                        '<?= $row->id_categori ?>',
                        '<?= htmlspecialchars($row->judul) ?>',
                        '<?= htmlspecialchars($row->pesan) ?>'
                    )" class="btn-edit">Edit</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="8" align="center" style="color: #94a3b8; padding: 40px;">Data belum ada</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div id="modalTambah" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeTambah()">&times;</span>
        <h3 style="margin-top: 0;">Tambah Aspirasi</h3>
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
            <button type="submit" class="btn-submit">Simpan Aspirasi</button>
        </form>
    </div>
</div>

<div id="modalEdit" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEdit()">&times;</span>
        <h3 style="margin-top: 0;">Edit Aspirasi</h3>
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
            <button type="submit" class="btn-submit" style="background: #fbbf24; color: #000;">Update Aspirasi</button>
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

// Tutup modal jika klik di luar area modal
window.onclick = function(event) {
    if (event.target == document.getElementById('modalTambah')) closeTambah();
    if (event.target == document.getElementById('modalEdit')) closeEdit();
}
</script>

<?php include '../layout/footer.php'; ?>