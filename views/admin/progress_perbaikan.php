<?php
session_start();

/**
 * Keamanan: Proteksi Halaman Admin
 */
if (!isset($_SESSION['data']) || $_SESSION['data']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

/**
 * Inisialisasi Model & Database
 */
include_once __DIR__ . '/../../models/m_aspirasi.php';
include_once __DIR__ . '/../../models/m_progress_perbaikan.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$m_aspirasi = new aspirasi();
$m_progress = new ProgressPerbaikan();

// Mengambil semua data aspirasi
$data_aspirasi = $m_aspirasi->tampil_data();
?>

<style>
    :root {
        --primary: #2563eb;
        --text-main: #111827;
        --text-muted: #6b7280;
        --border-color: #e5e7eb;
    }

    /* Layout Card yang Lebih Rapat */
    .card-aspirasi {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        margin-bottom: 0.75rem;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.6rem 1rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .card-body { padding: 0.75rem 1rem; }

    /* User Info & Status Samping Nama */
    .user-meta { display: flex; align-items: center; gap: 0.6rem; }
    .avatar {
        width: 1.8rem; height: 1.8rem; background: #eff6ff; color: var(--primary);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.75rem;
    }
    .user-info-row { display: flex; align-items: center; gap: 8px; }
    .user-info-row .name { font-weight: 700; color: var(--text-main); font-size: 0.85rem; text-transform: uppercase; }
    
    .badge {
        font-size: 0.6rem; font-weight: 800; padding: 0.15rem 0.5rem;
        border-radius: 9999px; text-transform: uppercase;
    }
    .badge-menunggu { background: #f3f4f6; color: #4b5563; }
    .badge-diproses { background: #fef3c7; color: #92400e; }
    .badge-selesai  { background: #d1fae5; color: #065f46; }

    /* Dropdown Menu Titik Tiga */
    .menu-container { position: relative; }
    .btn-menu { background: none; border: none; cursor: pointer; color: #94a3b8; padding: 4px; }
    .dropdown-menu {
        display: none; position: absolute; right: 0; top: 100%; width: 200px;
        background: white; border: 1px solid var(--border-color); border-radius: 8px;
        box-shadow: 0 10px 15px rgba(0,0,0,0.1); z-index: 100; padding: 4px;
    }
    .dropdown-menu.show { display: block; }

    .dropdown-item {
        display: flex; align-items: center; gap: 8px; padding: 8px 10px;
        font-size: 12px; font-weight: 600; color: #4b5563; text-decoration: none;
        border-radius: 6px; cursor: pointer; border: none; width: 100%; background: none;
    }
    .dropdown-item:hover { background: #f9fafb; color: var(--primary); }
    .dropdown-item.text-danger:hover { background: #fef2f2; color: #ef4444; }

    /* Konten Aspirasi */
    .aspirasi-title { font-size: 0.95rem; font-weight: 800; color: var(--text-main); margin-bottom: 2px; }
    .aspirasi-text { font-size: 0.8rem; color: var(--text-muted); line-height: 1.4; margin-bottom: 0.75rem; }

    /* Log Perbaikan */
    .progress-section { background: #f9fafb; border-radius: 8px; padding: 0.6rem 0.8rem; border: 1px solid #f1f5f9; }
    .progress-label { display: flex; align-items: center; gap: 6px; font-size: 0.65rem; font-weight: 800; color: #94a3b8; margin-bottom: 6px; }
    .progress-item { display: flex; gap: 10px; padding: 5px 0; border-bottom: 1px solid #eee; align-items: center; }
    .progress-item:last-child { border-bottom: none; }
    .progress-thumb { width: 32px; height: 32px; border-radius: 4px; object-fit: cover; border: 1px solid #ddd; }

    /* Modal Styling */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; backdrop-filter: blur(2px); }
    .modal-box { background: #fff; width: 90%; max-width: 380px; margin: 60px auto; border-radius: 12px; overflow: hidden; }
    .modal-header { padding: 1rem; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
    .modal-body { padding: 1rem; }
    .form-input { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 6px; font-size: 0.8rem; margin-top: 4px; }
    .form-label { display:block; font-size:0.7rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; }
</style>

<div class="page-header">
    <h2 class="page-title">Progres Perbaikan</h2>
    <p style="font-size: 0.8rem; color: var(--text-muted);">Monitoring tahapan perbaikan fasilitas berdasarkan aspirasi.</p>
</div>

<div class="container-cards">
    <?php foreach ($data_aspirasi as $row) : 
        $riwayat = $m_progress->tampil_data_per_id($row->id);
    ?>
        <article class="card-aspirasi">
            <div class="card-top">
                <div class="user-meta">
                    <div class="avatar"><?= strtoupper(substr($row->nama_lengkap, 0, 1)) ?></div>
                    <div class="user-info-row">
                        <span class="name"><?= htmlspecialchars($row->nama_lengkap) ?></span>
                        <span class="badge badge-<?= strtolower($row->status) ?>"><?= $row->status ?></span>
                    </div>
                </div>

                <div class="menu-container">
                    <button class="btn-menu" onclick="toggleDropdown('dropdown-<?= $row->id ?>')">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
                    </button>
                    <div id="dropdown-<?= $row->id ?>" class="dropdown-menu">
                        <button class="dropdown-item" onclick="openModalTambah('<?= $row->id ?>')">
                            Tambah Progres
                        </button>
                        
                        <?php if (!empty($riwayat)) : $last = $riwayat[0]; ?>
                            <hr style="border:0; border-top:1px solid #eee; margin:2px 0;">
                            <button class="dropdown-item" onclick="openModalEdit('<?= $last->id ?>', '<?= htmlspecialchars($last->keterangan) ?>', '<?= $last->estimasi_selesai ?>', '<?= $last->foto ?>')">
                                Edit Progres Terakhir
                            </button>
                            <a href="../../controllers/c_progress_perbaikan.php?aksi=hapus&id=<?= $last->id ?>" class="dropdown-item text-danger" onclick="return confirm('Hapus progres terakhir?')">
                                Hapus Progres Terakhir
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <h3 class="aspirasi-title"><?= htmlspecialchars($row->judul) ?></h3>
                <p class="aspirasi-text"><?= htmlspecialchars($row->pesan) ?></p>

                <section class="progress-section">
                    <div class="progress-label">LOG PERBAIKAN</div>
                    <?php if (!empty($riwayat)) : 
                        foreach ($riwayat as $p) : ?>
                            <div class="progress-item">
                                <?php if ($p->foto) : ?>
                                    <img src="../../assets/image/<?= $p->foto ?>" class="progress-thumb">
                                <?php endif; ?>
                                <div style="flex:1;">
                                    <div style="font-size:0.75rem; font-weight:700; color:var(--text-main);"><?= htmlspecialchars($p->keterangan) ?></div>
                                    <div style="font-size:0.65rem; color:var(--text-muted);">
                                        📅 <?= date('d M Y', strtotime($p->tanggal)) ?> | <span style="color:var(--primary)">Est: <?= date('d M Y', strtotime($p->estimasi_selesai)) ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; 
                    else : ?>
                        <p style="font-size:0.7rem; color:#999; font-style:italic; margin:0;">Belum ada riwayat perbaikan.</p>
                    <?php endif; ?>
                </section>
            </div>
        </article>
    <?php endforeach; ?>
</div>

<div id="modalTambah" class="modal-overlay">
    <div class="modal-box">
        <header class="modal-header">
            <h4 style="font-weight:800; font-size:0.9rem;">Tambah Progres Baru</h4>
            <button onclick="closeModal('modalTambah')" style="background:none; border:none; cursor:pointer; font-size:1.2rem;">&times;</button>
        </header>
        <form action="../../controllers/c_progress_perbaikan.php?aksi=tambah" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="aspirasi_id" id="tambah_id">
                <div style="margin-bottom: 0.75rem;">
                    <label class="form-label">Keterangan Progres</label>
                    <textarea name="keterangan" class="form-input" placeholder="Tulis progres perbaikan..." style="min-height: 80px;" required></textarea>
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <label class="form-label">Estimasi Selesai</label>
                    <input type="date" name="estimasi_selesai" class="form-input" required>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Foto Dokumentasi</label>
                    <input type="file" name="foto" class="form-input" accept="image/*">
                </div>
                <button type="submit" style="width:100%; background:var(--primary); color:white; border:none; padding:0.7rem; border-radius:6px; font-weight:700; cursor:pointer;">Simpan Progres</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEdit" class="modal-overlay">
    <div class="modal-box">
        <header class="modal-header">
            <h4 style="font-weight:800; font-size:0.9rem;">Edit Progres Terakhir</h4>
            <button onclick="closeModal('modalEdit')" style="background:none; border:none; cursor:pointer; font-size:1.2rem;">&times;</button>
        </header>
        <form action="../../controllers/c_progress_perbaikan.php?aksi=update" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="foto_lama" id="edit_foto_lama">
                <div style="margin-bottom: 0.75rem;">
                    <label class="form-label">Keterangan Progres</label>
                    <textarea name="keterangan" id="edit_keterangan" class="form-input" style="min-height: 80px;" required></textarea>
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <label class="form-label">Estimasi Selesai</label>
                    <input type="date" name="estimasi_selesai" id="edit_estimasi" class="form-input" required>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Ganti Foto (Opsional)</label>
                    <input type="file" name="foto" class="form-input" accept="image/*">
                    <small style="color: #94a3b8; font-size: 0.65rem;">*Biarkan kosong jika tidak ingin mengubah foto dokumentasi.</small>
                </div>
                <button type="submit" style="width:100%; background:var(--primary); color:white; border:none; padding:0.7rem; border-radius:6px; font-weight:700; cursor:pointer;">Update Progres</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleDropdown(id) {
        document.querySelectorAll('.dropdown-menu').forEach(m => m.id !== id && m.classList.remove('show'));
        document.getElementById(id).classList.toggle('show');
    }

    function openModalTambah(id) {
        document.getElementById('modalTambah').style.display = 'block';
        document.getElementById('tambah_id').value = id;
    }

    function openModalEdit(id, ket, est, foto) {
        document.getElementById('modalEdit').style.display = 'block';
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_keterangan').value = ket;
        document.getElementById('edit_estimasi').value = est;
        document.getElementById('edit_foto_lama').value = foto;
    }

    function closeModal(id) { document.getElementById(id).style.display = 'none'; }

    window.onclick = (e) => {
        if (!e.target.closest('.menu-container')) document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));
        if (e.target.className === 'modal-overlay') e.target.style.display = 'none';
    }
</script>

<?php include '../layout/footer.php'; ?>