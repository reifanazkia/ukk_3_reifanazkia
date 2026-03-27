<?php
session_start();

/**
 * Keamanan: Proteksi Halaman User
 */
if (!isset($_SESSION['data']) || $_SESSION['data']['role'] !== 'user') {
    // Kembali ke root jika session tidak valid
    header("Location: ../../index.php"); 
    exit;
}

include_once __DIR__ . '/../../models/m_aspirasi.php';
include_once __DIR__ . '/../../models/m_progress_perbaikan.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$m_aspirasi = new aspirasi();
$m_progress = new ProgressPerbaikan();

// Ambil ID User dari session untuk filter data
$user_id = $_SESSION['data']['id'];

// Mengambil aspirasi khusus milik user ini
$data_aspirasi = $m_aspirasi->tampil_data_per_user($user_id); 
?>

<style>
    :root {
        --primary: #2563eb;
        --text-main: #111827;
        --text-muted: #6b7280;
        --border-color: #e5e7eb;
    }

    /* Card Styling - Identik dengan Admin */
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

    /* User Info - Menggunakan data dari database aspirasi */
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

    /* Content Styling */
    .aspirasi-title { font-size: 0.95rem; font-weight: 800; color: var(--text-main); margin-bottom: 2px; }
    .aspirasi-text { font-size: 0.8rem; color: var(--text-muted); line-height: 1.4; margin-bottom: 0.75rem; }

    /* Log Perbaikan Styling */
    .progress-section { background: #f9fafb; border-radius: 8px; padding: 0.6rem 0.8rem; border: 1px solid #f1f5f9; }
    .progress-label { display: flex; align-items: center; gap: 6px; font-size: 0.65rem; font-weight: 800; color: #94a3b8; margin-bottom: 6px; text-transform: uppercase; }
    .progress-item { display: flex; gap: 10px; padding: 5px 0; border-bottom: 1px solid #eee; align-items: center; }
    .progress-item:last-child { border-bottom: none; }
    .progress-thumb { width: 32px; height: 32px; border-radius: 4px; object-fit: cover; border: 1px solid #ddd; }
</style>

<div class="page-header">
    <h2 class="page-title">Progres Perbaikan Saya</h2>
</div>

<div class="container-cards">
    <?php if (empty($data_aspirasi)) : ?>
        <p style="text-align: center; color: #94a3b8; padding: 3rem; font-style: italic;">Belum ada aspirasi yang Anda kirimkan.</p>
    <?php else : ?>
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
                    <small style="color: #cbd5e1; font-weight: 600; font-size: 0.7rem;">#<?= $row->id ?></small>
                </div>

                <div class="card-body">
                    <h3 class="aspirasi-title"><?= htmlspecialchars($row->judul) ?></h3>
                    <p class="aspirasi-text"><?= nl2br(htmlspecialchars($row->pesan)) ?></p>

                    <section class="progress-section">
                        <div class="progress-label">Update Progres dari Admin</div>
                        
                        <?php if (!empty($riwayat)) : 
                            foreach ($riwayat as $p) : ?>
                                <div class="progress-item">
                                    <?php if ($p->foto) : ?>
                                        <img src="../../assets/image/<?= $p->foto ?>" class="progress-thumb">
                                    <?php endif; ?>
                                    <div style="flex:1;">
                                        <div style="font-size:0.75rem; font-weight:700; color:var(--text-main);"><?= htmlspecialchars($p->keterangan) ?></div>
                                        <div style="font-size:0.65rem; color:var(--text-muted);">
                                            📅 <?= date('d M Y', strtotime($p->tanggal)) ?> | <span style="color:var(--primary); font-weight:600;">Est: <?= date('d M Y', strtotime($p->estimasi_selesai)) ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; 
                        else : ?>
                            <p style="font-size:0.7rem; color:#ccc; font-style:italic; margin:0;">Belum ada update progres.</p>
                        <?php endif; ?>
                    </section>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include '../layout/footer.php'; ?>