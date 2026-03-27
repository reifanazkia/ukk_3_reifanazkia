<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['data']) || $_SESSION['data']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

include_once __DIR__ . '/../../models/m_aspirasi.php';
include_once __DIR__ . '/../../models/m_progress_perbaikan.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$m_aspirasi = new aspirasi();
$m_progress = new ProgressPerbaikan();
$data_aspirasi = $m_aspirasi->tampil_data();
?>

<style>
    :root {
        --primary: #2563eb;
        --text-main: #111827;
        --text-muted: #6b7280;
        --border-color: #e5e7eb;
    }

    .card-aspirasi {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        margin-bottom: 1rem;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.8rem 1rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .user-meta { display: flex; align-items: center; gap: 0.8rem; }
    .avatar {
        width: 2rem; height: 2rem; background: #eff6ff; color: var(--primary);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.85rem;
    }
    .user-info-row .name { font-weight: 700; color: var(--text-main); font-size: 0.9rem; text-transform: uppercase; }
    
    .badge {
        font-size: 0.65rem; font-weight: 800; padding: 0.2rem 0.6rem;
        border-radius: 9999px; text-transform: uppercase;
    }
    .badge-menunggu { background: #f3f4f6; color: #4b5563; }
    .badge-diproses { background: #fef3c7; color: #92400e; }
    .badge-selesai  { background: #d1fae5; color: #065f46; }

    .menu-container { position: relative; }
    .btn-menu { background: none; border: none; cursor: pointer; color: #94a3b8; padding: 6px; border-radius: 50%; transition: 0.2s; }
    .btn-menu:hover { background: #f1f5f9; color: var(--primary); }
    
    .dropdown-menu {
        display: none; position: absolute; right: 0; top: 110%; width: 210px;
        background: white; border: 1px solid var(--border-color); border-radius: 10px;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); z-index: 100; padding: 6px;
    }
    .dropdown-menu.show { display: block; }

    .dropdown-item {
        display: flex; align-items: center; gap: 10px; padding: 10px 12px;
        font-size: 13px; font-weight: 600; color: #4b5563; text-decoration: none;
        border-radius: 8px; cursor: pointer; border: none; width: 100%; background: none; text-align: left;
    }
    .dropdown-item:hover { background: #f8fafc; color: var(--primary); }
    .dropdown-item.text-danger:hover { background: #fff1f2; color: #e11d48; }

    .aspirasi-title { font-size: 1rem; font-weight: 800; color: var(--text-main); margin: 1rem 1rem 0.2rem 1rem; }
    .aspirasi-text { font-size: 0.85rem; color: var(--text-muted); margin: 0 1rem 1rem 1rem; line-height: 1.5; }

    /* Perbaikan Style Foto Aspirasi ADMIN - Dikecilkan */
    .aspirasi-foto-container { margin: 0 1rem 1rem 1rem; }
    .aspirasi-img { max-width: 200px; /* Ukuran maksimal lebar dikecilkan */ border-radius: 8px; border: 1px solid #e5e7eb; cursor: zoom-in; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }

    .progress-section { background: #f8fafc; border-radius: 10px; padding: 1rem; margin: 0 1rem 1rem 1rem; border: 1px solid #f1f5f9; }
    .progress-label { display: flex; align-items: center; gap: 8px; font-size: 0.7rem; font-weight: 800; color: #94a3b8; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .progress-item { display: flex; gap: 14px; padding: 12px 0; border-bottom: 1px dotted #e2e8f0; align-items: flex-start; }
    .progress-item:last-child { border-bottom: none; }
    
    .progress-thumb { 
        width: 65px; height: 65px; border-radius: 10px; object-fit: cover; 
        border: 1px solid #e2e8f0; cursor: zoom-in; flex-shrink: 0; transition: 0.2s;
    }
    .progress-thumb:hover { transform: scale(1.05); }

    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 9999; backdrop-blur: 4px; }
    .modal-box { background: #fff; width: 90%; max-width: 420px; margin: 50px auto; border-radius: 16px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
    .modal-header { padding: 1.2rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
    .modal-body { padding: 1.2rem; }
    .form-input { width: 100%; padding: 0.7rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; margin-top: 6px; box-sizing: border-box; }
    .form-label { display:block; font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; }
</style>

<div class="page-header" style="margin-bottom: 2rem;">
    <h2 class="page-title">Progres Perbaikan</h2>
    <p style="font-size: 0.9rem; color: var(--text-muted);">Lacak dan kelola tahapan perbaikan fasilitas sekolah.</p>
</div>

<div class="container-cards">
    <?php foreach ($data_aspirasi as $row) : 
        $riwayat = $m_progress->tampil_data_per_id($row->id);
    ?>
        <article class="card-aspirasi">
            <div class="card-top">
                <div class="user-meta">
                    <div class="avatar"><?= strtoupper(substr($row->nama_lengkap ?? 'U', 0, 1)) ?></div>
                    <div class="user-info-row">
                        <span class="name"><?= htmlspecialchars($row->nama_lengkap) ?></span>
                        <span class="badge badge-<?= strtolower($row->status) ?>"><?= $row->status ?></span>
                    </div>
                </div>

                <div class="menu-container">
                    <button class="btn-menu" onclick="toggleDropdown('dropdown-<?= $row->id ?>')">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                    </button>
                    <div id="dropdown-<?= $row->id ?>" class="dropdown-menu">
                        <button class="dropdown-item" onclick="openModalTambah('<?= $row->id ?>')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Tambah Progres
                        </button>
                        
                        <?php if (!empty($riwayat)) : $last = $riwayat[0]; ?>
                            <hr style="border:0; border-top:1px solid #f1f5f9; margin:4px 0;">
                            <a href="../../controllers/c_progress_perbaikan.php?aksi=hapus&id=<?= $last->id ?>" class="dropdown-item text-danger" onclick="return confirm('Hapus progres terakhir?')">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                Hapus Progres Terakhir
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <h3 class="aspirasi-title"><?= htmlspecialchars($row->judul) ?></h3>
            <p class="aspirasi-text"><?= htmlspecialchars($row->pesan) ?></p>

            <?php if (!empty($row->foto)) : ?>
            <div class="aspirasi-foto-container">
                <p style="font-size:0.65rem; font-weight:800; color:#cbd5e1; margin-bottom:5px; text-transform:uppercase;">Foto Bukti Siswa:</p>
                <img src="../../assets/image/<?= $row->foto ?>" class="aspirasi-img" onclick="window.open(this.src)" title="Klik untuk melihat bukti laporan">
            </div>
            <?php endif; ?>

            <section class="progress-section">
                <div class="progress-label">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    LOG PERBAIKAN (ADMIN)
                </div>
                <?php if (!empty($riwayat)) : 
                    foreach ($riwayat as $p) : ?>
                        <div class="progress-item">
                            <?php if ($p->foto) : ?>
                                <img src="../../assets/image/<?= $p->foto ?>" class="progress-thumb" onclick="window.open(this.src)" title="Klik untuk memperbesar">
                            <?php else: ?>
                                <div style="width:65px; height:65px; background:#f1f5f9; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#94a3b8; flex-shrink:0;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                </div>
                            <?php endif; ?>
                            <div style="flex:1;">
                                <div style="font-size:0.9rem; font-weight:700; color:var(--text-main); margin-bottom:4px;"><?= htmlspecialchars($p->keterangan) ?></div>
                                <div style="font-size:0.75rem; color:var(--text-muted); display:flex; gap:12px; align-items:center;">
                                    <span style="display:flex; align-items:center; gap:4px;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                        <?= date('d M Y', strtotime($p->tanggal)) ?>
                                    </span>
                                    <span style="color:var(--primary); font-weight:700; display:flex; align-items:center; gap:4px;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                        Est: <?= date('d M Y', strtotime($p->estimasi_selesai)) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; 
                else : ?>
                    <p style="font-size:0.8rem; color:#94a3b8; font-style:italic; margin:0; padding:10px 0; text-align:center;">Belum ada riwayat perbaikan.</p>
                <?php endif; ?>
            </section>
        </article>
    <?php endforeach; ?>
</div>

<div id="modalTambah" class="modal-overlay">
    <div class="modal-box">
        <header class="modal-header">
            <h4 style="font-weight:800; font-size:1rem; color:var(--text-main);">Tambah Progres Baru</h4>
            <button onclick="closeModal('modalTambah')" style="background:none; border:none; cursor:pointer; color:#94a3b8;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </header>
        <form action="../../controllers/c_progress_perbaikan.php?aksi=tambah" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="aspirasi_id" id="tambah_id">
                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Keterangan Progres</label>
                    <textarea name="keterangan" class="form-input" placeholder="Apa yang sedang dikerjakan?" style="min-height: 100px; resize: vertical;" required></textarea>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Estimasi Selesai</label>
                    <input type="date" name="estimasi_selesai" class="form-input" required>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label class="form-label">Foto Dokumentasi</label>
                    <input type="file" name="foto" class="form-input" accept="image/*" style="padding: 5px;">
                </div>
                <button type="submit" style="width:100%; background:var(--primary); color:white; border:none; padding:0.8rem; border-radius:10px; font-weight:700; cursor:pointer; transition:0.2s; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);">Simpan Progres</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleDropdown(id) {
        document.querySelectorAll('.dropdown-menu').forEach(m => {
            if (m.id !== id) m.classList.remove('show');
        });
        document.getElementById(id).classList.toggle('show');
    }

    function openModalTambah(id) {
        document.getElementById('modalTambah').style.display = 'block';
        document.getElementById('tambah_id').value = id;
    }

    function closeModal(id) { document.getElementById(id).style.display = 'none'; }

    window.onclick = (e) => {
        if (!e.target.closest('.menu-container')) {
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));
        }
        if (e.target.className === 'modal-overlay') {
            e.target.style.display = 'none';
        }
    }
</script>

<?php include '../layout/footer.php'; ?>