<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Halaman Admin
if (!isset($_SESSION['data']) || $_SESSION['data']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

include_once '../../controllers/c_aspirasi.php';
include_once '../../models/m_kategori.php';
include '../layout/header.php';
include '../layout/sidebar.php';

// --- LOGIKA FILTER ---
$f_tgl  = $_GET['tanggal'] ?? '';
$f_bln  = $_GET['bulan'] ?? '';
$f_kat  = $_GET['kategori'] ?? '';
$f_nama = $_GET['siswa'] ?? '';

if (!empty($f_tgl) || !empty($f_bln) || !empty($f_kat) || !empty($f_nama)) {
    $data_aspirasi = $aspirasi->filter_data($f_tgl, $f_bln, $f_kat, $f_nama);
} else {
    $data_aspirasi = $aspirasi->tampil_data();
}
?>

<style>
    /* =========================================
       1. GLOBAL & LAYOUT
       ========================================= */
    :root {
        --primary: #2563eb;
        --primary-hover: #1e40af;
        --danger: #dc2626;
        --gray-bg: #f4f6f9;
        --border-color: #e2e8f0;
        --text-main: #334155;
        --text-muted: #64748b;
    }

    h2 { margin-bottom: 20px; color: var(--text-main); }

    /* =========================================
       2. FILTER SECTION
       ========================================= */
    .filter-wrapper {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        margin-bottom: 25px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        align-items: end;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    .filter-group { 
        display: flex; 
        flex-direction: column; 
        gap: 8px; 
    }

    .filter-group label { 
        font-size: 11px; 
        font-weight: 800; 
        color: var(--text-muted); 
        text-transform: uppercase; 
    }

    .filter-input { 
        padding: 10px 12px; 
        border-radius: 8px; 
        border: 1px solid var(--border-color); 
        font-size: 13px; 
        color: var(--text-main); 
        outline: none; 
        transition: 0.2s;
    }

    .filter-input:focus { 
        border-color: var(--primary); 
        box-shadow: 0 0 0 2px rgba(37,99,235,0.1); 
    }

    .btn-filter { 
        background: var(--primary); 
        color: #fff; 
        border: none; 
        padding: 11px; 
        border-radius: 8px; 
        cursor: pointer; 
        font-weight: 700; 
        font-size: 13px;
        transition: 0.2s;
        flex: 1;
    }

    .btn-filter:hover { background: var(--primary-hover); }

    .btn-reset {
        background: #e2e8f0;
        color: #475569;
        text-decoration: none;
        padding: 11px 15px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
        text-align: center;
        transition: 0.2s;
        border: 1px solid var(--border-color);
    }

    .btn-reset:hover {
        background: #cbd5e1;
        color: #1e293b;
    }

    /* =========================================
       3. TABLE STYLE
       ========================================= */
    .table-aspirasi {
        width: 100%; 
        border-collapse: collapse; 
        background: #fff;
        border-radius: 10px; 
        overflow: hidden; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .table-aspirasi th {
        background: var(--gray-bg); 
        padding: 14px; 
        font-size: 12px;
        text-transform: uppercase; 
        color: #555; 
        text-align: left;
    }

    .table-aspirasi td { 
        padding: 14px; 
        font-size: 14px; 
        color: #444; 
        border-top: 1px solid #eee; 
        vertical-align: middle; 
    }

    .table-aspirasi tr:hover { background: #f9fbfd; }

    .img-preview { 
        width: 60px; 
        height: 45px; 
        object-fit: cover; 
        border-radius: 6px; 
        border: 1px solid #ddd; 
    }

    /* =========================================
       4. BADGES & BUTTONS
       ========================================= */
    .status-badge { 
        padding: 6px 12px; 
        border-radius: 20px; 
        font-size: 11px; 
        text-transform: uppercase; 
        font-weight: 700; 
        display: inline-block; 
    }

    .status-selesai  { background: #dcfce7; color: #166534; }
    .status-ditolak  { background: #fee2e2; color: #991b1b; }
    .status-menunggu { background: #dbeafe; color: #1e3a8a; }
    .status-diproses { background: #fef9c3; color: #854d0e; }

    .action-buttons { display: flex; gap: 15px; }
    
    .btn-edit { 
        background: none; border: none; color: var(--primary); 
        font-weight: 600; cursor: pointer; font-size: 13px; 
    }

    .btn-delete { 
        color: var(--danger); font-size: 13px; font-weight: 600; 
        text-decoration: none; 
    }

    .btn-edit:hover, .btn-delete:hover { text-decoration: underline; }

    /* =========================================
       5. MODAL (OVERLAY FIXED)
       ========================================= */
    #modalStatus {
        display: none; 
        position: fixed; 
        inset: 0; 
        background: rgba(0, 0, 0, 0.5); 
        backdrop-filter: blur(4px); 
        z-index: 9999;
    }

    .modal-content {
        background: #fff; 
        width: 90%; 
        max-width: 400px; 
        margin: 100px auto; 
        padding: 25px;
        border-radius: 12px; 
        box-shadow: 0 20px 25px rgba(0,0,0,0.2); 
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown { 
        from { transform: translateY(-20px); opacity: 0; } 
        to { transform: translateY(0); opacity: 1; } 
    }

    .modal-buttons { 
        display: flex; 
        justify-content: flex-end; 
        gap: 10px; 
        margin-top: 25px; 
    }

    .btn-save { 
        background: var(--primary); 
        color: #fff; 
        border: none; 
        padding: 10px 20px; 
        border-radius: 6px; 
        cursor: pointer; 
        font-weight: 600; 
    }

    .btn-cancel { 
        background: #e2e8f0; 
        color: #475569; 
        border: none; 
        padding: 10px 20px; 
        border-radius: 6px; 
        cursor: pointer; 
    }
</style>

<h2>Data Aspirasi Siswa</h2>

<form method="GET">
    <div class="filter-wrapper">
        <div class="filter-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="filter-input" value="<?= $f_tgl ?>">
        </div>
        <div class="filter-group">
            <label>Bulan</label>
            <select name="bulan" class="filter-input">
                <option value="">Semua Bulan</option>
                <?php
                $bln_nama = [1=>"Januari", 2=>"Februari", 3=>"Maret", 4=>"April", 5=>"Mei", 6=>"Juni", 7=>"Juli", 8=>"Agustus", 9=>"September", 10=>"Oktober", 11=>"November", 12=>"Desember"];
                foreach ($bln_nama as $key => $val) {
                    $selected = ($f_bln == $key) ? 'selected' : '';
                    echo "<option value='$key' $selected>$val</option>";
                }
                ?>
            </select>
        </div>
        <div class="filter-group">
            <label>Kategori</label>
            <select name="kategori" class="filter-input">
                <option value="">Semua Kategori</option>
                <?php
                $kat_obj = new kategori();
                $data_kat = $kat_obj->tampil_data();
                foreach ($data_kat as $k) {
                    $selected = ($f_kat == $k->id) ? 'selected' : '';
                    echo "<option value='$k->id' $selected>$k->nama_kategori</option>";
                }
                ?>
            </select>
        </div>
        <div class="filter-group">
            <label>Nama Siswa</label>
            <input type="text" name="siswa" class="filter-input" placeholder="Cari nama..." value="<?= htmlspecialchars($f_nama) ?>">
        </div>
        <div class="filter-group" style="flex-direction: row; gap: 10px;">
            <button type="submit" class="btn-filter">Cari Data</button>
            <a href="index.php" class="btn-reset">Reset</a>
        </div>
    </div>
</form>

<table class="table-aspirasi">
    <thead>
        <tr>
            <th>No</th>
            <th>Foto</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Kategori</th>
            <th>Judul</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($data_aspirasi)) : ?>
            <?php $no = 1; foreach ($data_aspirasi as $row) : ?>
            <tr>
                <td><?= $no++ ?></td>
                <td>
                    <?php if (!empty($row->foto)) : ?>
                        <img src="../../assets/image/<?= $row->foto ?>" class="img-preview">
                    <?php else : ?>
                        <small style="color:#ccc">No Photo</small>
                    <?php endif; ?>
                </td>
                <td><strong><?= htmlspecialchars($row->nama_lengkap) ?></strong></td>
                <td><?= htmlspecialchars($row->kelas) ?></td>
                <td><span style="color:var(--primary); font-weight:600;"><?= htmlspecialchars($row->nama_kategori) ?></span></td>
                <td><?= htmlspecialchars($row->judul) ?></td>
                <td>
                    <span class="status-badge status-<?= strtolower($row->status) ?>">
                        <?= strtoupper($row->status) ?>
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-edit" onclick="openModal(<?= $row->id ?>, '<?= $row->status ?>')">Edit</button>
                        <a class="btn-delete" href="../../controllers/c_aspirasi.php?aksi=hapus&id=<?= $row->id ?>" onclick="return confirm('Hapus data?')">Hapus</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr><td colspan="8" align="center">Data tidak ditemukan</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<div id="modalStatus">
    <div class="modal-content">
        <h3 style="margin-bottom: 20px;">Update Status</h3>
        <form action="../../controllers/c_aspirasi.php?aksi=update_status" method="POST">
            <input type="hidden" name="id" id="id_aspirasi">
            
            <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 8px;">Pilih Status:</label>
            <select name="status" id="status_select" class="filter-input" style="width: 100%;" required>
                <option value="menunggu">MENUNGGU</option>
                <option value="diproses">DIPROSES</option>
                <option value="selesai">SELESAI</option>
                <option value="ditolak">DITOLAK</option>
            </select>

            <div class="modal-buttons">
                <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-save">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('modalStatus');

    function openModal(id, status) {
        document.getElementById('id_aspirasi').value = id;
        document.getElementById('status_select').value = status.toLowerCase();
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    window.onclick = function(e) {
        if (e.target == modal) closeModal();
    }
</script>

<?php include '../layout/footer.php'; ?>