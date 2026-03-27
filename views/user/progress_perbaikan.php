<?php
session_start();

if (!isset($_SESSION['data']) || $_SESSION['data']['role'] !== 'user') {
    header("Location: ../../index.php"); 
    exit;
}

include_once __DIR__ . '/../../models/m_aspirasi.php';
include_once __DIR__ . '/../../models/m_progress_perbaikan.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$m_aspirasi = new aspirasi();
$m_progress = new ProgressPerbaikan();
$user_id = $_SESSION['data']['id'];
$data_aspirasi = $m_aspirasi->tampil_data_per_user($user_id); 
?>

<style>
    :root { --primary: #2563eb; --text-main: #111827; --text-muted: #6b7280; }
    .card-aspirasi {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        border-bottom: 1px solid #f3f4f6;
    }
    .card-body { padding: 16px; }
    .user-meta { display: flex; align-items: center; gap: 10px; }
    .avatar {
        width: 32px; height: 32px; background: #eff6ff; color: var(--primary);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 12px;
    }
    .name { font-weight: 700; color: var(--text-main); font-size: 14px; text-transform: uppercase; }
    
    .badge { font-size: 10px; font-weight: 800; padding: 3px 10px; border-radius: 99px; text-transform: uppercase; }
    .badge-menunggu { background: #f3f4f6; color: #4b5563; }
    .badge-diproses { background: #fef3c7; color: #92400e; }
    .badge-selesai  { background: #d1fae5; color: #065f46; }

    .aspirasi-title { font-size: 16px; font-weight: 800; color: var(--text-main); margin-bottom: 4px; }
    .aspirasi-text { font-size: 14px; color: var(--text-muted); line-height: 1.5; margin-bottom: 15px; }

    /* Foto Bukti Laporan Siswa (Tetap 220px) */
    .user-foto-aspirasi { 
        max-width: 220px; 
        border-radius: 8px; 
        margin-bottom: 15px; 
        border: 1px solid #f1f5f9; 
        cursor: zoom-in; 
    }

    /* SECTION UPDATE ADMIN */
    .progress-section { background: #f9fafb; border-radius: 8px; padding: 12px; border: 1px solid #f1f5f9; }
    .progress-label { font-size: 11px; font-weight: 800; color: #94a3b8; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .progress-item { 
        display: flex; 
        gap: 15px; 
        padding: 12px 0; 
        border-bottom: 1px solid #eee; 
        align-items: flex-start; 
    }
    .progress-item:last-child { border-bottom: none; }

    /* FOTO DARI ADMIN PERBAIKAN (Sekarang diperbesar ke 100px) */
    .progress-thumb { 
        width: 100px; 
        height: 100px; 
        border-radius: 8px; 
        object-fit: cover; 
        border: 1px solid #ddd; 
        cursor: zoom-in; 
        flex-shrink: 0; 
        transition: transform 0.2s;
    }
    .progress-thumb:hover { transform: scale(1.05); }

    .empty-thumb {
        width: 100px; height: 100px; background: #f1f5f9; border-radius: 8px;
        display: flex; align-items: center; justify-content: center; color: #cbd5e1; flex-shrink: 0;
    }
</style>

<h2 class="page-title" style="font-weight:700; color:#111827;">Progres Perbaikan Saya</h2>
<p style="color:#6b7280; font-size:14px; margin-bottom:20px;">Pantau hasil pengerjaan tim sarpras sekolah.</p>

<div class="container-cards">
    <?php if (empty($data_aspirasi)) : ?>
        <p style="text-align: center; color: #94a3b8; padding: 3rem; font-style: italic;">Belum ada laporan yang diproses.</p>
    <?php else : ?>
        <?php foreach ($data_aspirasi as $row) : 
            $riwayat = $m_progress->tampil_data_per_id($row->id);
        ?>
            <article class="card-aspirasi">
                <div class="card-top">
                    <div class="user-meta">
                        <div class="avatar"><?= strtoupper(substr($row->nama_lengkap, 0, 1)) ?></div>
                        <div style="display:flex; flex-direction:column;">
                            <span class="name"><?= htmlspecialchars($row->nama_lengkap) ?></span>
                            <div><span class="badge badge-<?= strtolower($row->status) ?>"><?= $row->status ?></span></div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <h3 class="aspirasi-title"><?= htmlspecialchars($row->judul) ?></h3>
                    <p class="aspirasi-text"><?= nl2br(htmlspecialchars($row->pesan)) ?></p>

                    <?php if (!empty($row->foto)) : ?>
                    <div>
                        <small style="display:block; font-size:10px; font-weight:800; color:#94a3b8; margin-bottom:5px; text-transform:uppercase;">Foto Bukti Laporan:</small>
                        <img src="../../assets/image/<?= $row->foto ?>" 
                             class="user-foto-aspirasi" 
                             onclick="window.open(this.src)" 
                             onerror="this.src='https://placehold.co/220x150?text=Foto+Tidak+Ditemukan'">
                    </div>
                    <?php endif; ?>

                    <section class="progress-section">
                        <div class="progress-label">Update Progres dari Admin</div>
                        <?php if (!empty($riwayat)) : 
                            foreach ($riwayat as $p) : ?>
                                <div class="progress-item">
                                    <?php if ($p->foto) : ?>
                                        <img src="../../assets/image/<?= $p->foto ?>" class="progress-thumb" onclick="window.open(this.src)" onerror="this.src='https://placehold.co/100x100?text=Error'">
                                    <?php else : ?>
                                        <div class="empty-thumb">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                        </div>
                                    <?php endif; ?>
                                    <div style="flex:1; padding-top: 5px;">
                                        <div style="font-size:14px; font-weight:700; color:var(--text-main); margin-bottom: 5px;"><?= htmlspecialchars($p->keterangan) ?></div>
                                        <div style="font-size:11px; color:var(--text-muted); display: flex; gap: 10px;">
                                            <span>📅 <?= date('d M Y', strtotime($p->tanggal)) ?></span>
                                            <span style="color:var(--primary); font-weight:700;">Est. Selesai: <?= date('d M Y', strtotime($p->estimasi_selesai)) ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; 
                        else : ?>
                            <p style="font-size:12px; color:#ccc; font-style:italic; margin:0;">Belum ada progres pengerjaan.</p>
                        <?php endif; ?>
                    </section>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include '../layout/footer.php'; ?>