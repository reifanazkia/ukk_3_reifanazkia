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
    :root { 
        --primary: #2563eb; 
        --text-main: #111827; 
        --text-muted: #64748b; 
        --border-color: #e5e7eb;
    }
    
    .card-aspirasi {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .card-top {
        display: flex;
        align-items: center; 
        justify-content: space-between; /* Memisahkan konten ke kiri dan kanan */
        padding: 16px;
        border-bottom: 1px solid #f3f4f6;
    }

    .card-body { padding: 20px; }

    .user-meta { display: flex; align-items: center; gap: 12px; }
    
    .avatar {
        width: 38px; height: 38px; background: #eff6ff; color: var(--primary);
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 14px;
    }

    .name { font-weight: 700; color: var(--text-main); font-size: 15px; text-transform: uppercase; }
    
    .badge { font-size: 10px; font-weight: 800; padding: 4px 12px; border-radius: 99px; text-transform: uppercase; letter-spacing: 0.5px; }
    .badge-menunggu { background: #f1f5f9; color: #475569; }
    .badge-diproses { background: #fef3c7; color: #92400e; }
    .badge-selesai  { background: #dcfce7; color: #166534; }

    .aspirasi-title { font-size: 18px; font-weight: 800; color: var(--text-main); margin-bottom: 8px; }
    .aspirasi-text { font-size: 14px; color: var(--text-muted); line-height: 1.6; margin-bottom: 20px; }

    .user-foto-aspirasi { 
        max-width: 220px; 
        border-radius: 8px; 
        margin-bottom: 20px; 
        border: 1px solid #f1f5f9; 
        cursor: zoom-in; 
        transition: opacity 0.2s;
    }
    .user-foto-aspirasi:hover { opacity: 0.9; }

    /* SECTION UPDATE ADMIN */
    .progress-section { 
        background: #f8fafc; 
        border-radius: 12px; 
        padding: 16px; 
        border: 1px solid #f1f5f9; 
    }
    
    .progress-label { 
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11px; 
        font-weight: 800; 
        color: #94a3b8; 
        margin-bottom: 16px; 
        text-transform: uppercase; 
        letter-spacing: 0.8px; 
    }

    .progress-item { 
        display: flex; 
        gap: 16px; 
        padding: 16px 0; 
        border-bottom: 1px solid #eef2f6; 
        align-items: flex-start; 
    }
    .progress-item:last-child { border-bottom: none; padding-bottom: 0; }
    .progress-item:first-child { padding-top: 0; }

    .progress-thumb { 
        width: 90px; height: 90px; 
        border-radius: 8px; 
        object-fit: cover; 
        border: 2px solid #fff; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        cursor: zoom-in; 
        flex-shrink: 0; 
    }

    .empty-thumb {
        width: 90px; height: 90px; background: #f1f5f9; border-radius: 8px;
        display: flex; align-items: center; justify-content: center; color: #cbd5e1; flex-shrink: 0;
    }

    .meta-info {
        display: flex; 
        align-items: center; 
        gap: 12px; 
        font-size: 12px; 
        color: var(--text-muted); 
        margin-top: 6px;
    }

    .meta-item { display: flex; align-items: center; gap: 4px; }
    
    .icon-svg { stroke: currentColor; fill: none; flex-shrink: 0; }
</style>

<h2 class="page-title" style="font-weight:800; color:#111827; letter-spacing:-0.5px;">Progres Perbaikan Saya</h2>
<p style="color:#64748b; font-size:14px; margin-bottom:24px;">Pantau hasil pengerjaan sarana prasarana sekolah secara real-time.</p>

<div class="container-cards">
    <?php if (empty($data_aspirasi)) : ?>
        <div style="text-align: center; background: #fff; padding: 50px; border-radius: 12px; border: 1px dashed #cbd5e1;">
            <svg class="icon-svg" style="margin: 0 auto 15px; color:#cbd5e1;" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5">
                <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p style="color: #94a3b8; font-style: italic;">Belum ada laporan yang Anda buat.</p>
        </div>
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
                            <span style="font-size: 11px; color: var(--text-muted);">Pelapor</span>
                        </div>
                    </div>
                    <div class="status-container">
                        <span class="badge badge-<?= strtolower($row->status) ?>"><?= $row->status ?></span>
                    </div>
                </div>

                <div class="card-body">
                    <h3 class="aspirasi-title"><?= htmlspecialchars($row->judul) ?></h3>
                    <p class="aspirasi-text"><?= nl2br(htmlspecialchars($row->pesan)) ?></p>

                    <?php if (!empty($row->foto)) : ?>
                    <div style="margin-bottom: 20px;">
                        <span class="progress-label">
                            <svg class="icon-svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                            Lampiran Bukti
                        </span>
                        <img src="../../assets/image/<?= $row->foto ?>" 
                             class="user-foto-aspirasi" 
                             onclick="window.open(this.src)" 
                             onerror="this.src='https://placehold.co/220x150?text=Foto+Tidak+Ditemukan'">
                    </div>
                    <?php endif; ?>

                    <section class="progress-section">
                        <div class="progress-label">
                            <svg class="icon-svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            Log Perkembangan
                        </div>
                        
                        <?php if (!empty($riwayat)) : 
                            foreach ($riwayat as $p) : ?>
                                <div class="progress-item">
                                    <?php if ($p->foto) : ?>
                                        <img src="../../assets/image/<?= $p->foto ?>" class="progress-thumb" onclick="window.open(this.src)" onerror="this.src='https://placehold.co/100x100?text=Error'">
                                    <?php else : ?>
                                        <div class="empty-thumb">
                                            <svg class="icon-svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div style="flex:1;">
                                        <div style="font-size:14px; font-weight:700; color:var(--text-main); line-height:1.4; margin-bottom:6px;"><?= htmlspecialchars($p->keterangan) ?></div>
                                        
                                        <div class="meta-info">
                                            <div class="meta-item">
                                                <svg class="icon-svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                                <?= date('d M Y', strtotime($p->tanggal)) ?>
                                            </div>
                                            <div class="meta-item" style="color:var(--primary); font-weight:600;">
                                                <svg class="icon-svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                                Estimasi: <?= date('d M Y', strtotime($p->estimasi_selesai)) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; 
                        else : ?>
                            <div style="display:flex; align-items:center; gap:8px; padding:10px 0;">
                                <svg class="icon-svg" style="color:#cbd5e1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                <p style="font-size:13px; color:#94a3b8; font-style:italic; margin:0;">Admin belum mengunggah progres pengerjaan.</p>
                            </div>
                        <?php endif; ?>
                    </section>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include '../layout/footer.php'; ?>