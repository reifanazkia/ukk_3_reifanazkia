<?php
session_start();
if (!isset($_SESSION['data']) || $_SESSION['data']['role'] !== 'admin') {
    header("Location: ../../index.php"); exit;
}

include_once __DIR__ . '/../../models/m_aspirasi.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$m_aspirasi = new aspirasi();

// --- LOGIKA PAGINATION ---
$jumlahDataPerHalaman = 10;
$totalData = $m_aspirasi->hitung_total_aspirasi(); 
$jumlahHalaman = ceil($totalData / $jumlahDataPerHalaman);
$halamanAktif = (isset($_GET['halaman'])) ? (int)$_GET['halaman'] : 1;
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

// Menggunakan fungsi sesuai model Anda
$data_history = $m_aspirasi->tampil_semua_history_paginated($awalData, $jumlahDataPerHalaman); 
?>

<style>
    :root {
        --primary: #2563eb;
        --bg-light: #f8fafc;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    .page-header { margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid var(--border-color); }
    .container-cards { display: flex; flex-direction: column; gap: 1.25rem; }
    
    .card-aspirasi {
        background: #fff; border: 1px solid var(--border-color); border-radius: 16px;
        overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); transition: 0.2s;
    }

    .card-top {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1rem 1.25rem; background: var(--bg-light); border-bottom: 1px solid var(--border-color);
    }

    .avatar {
        width: 2.5rem; height: 2.5rem; background: var(--primary); color: #fff;
        border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800;
    }

    .badge {
        font-size: 0.7rem; font-weight: 800; padding: 0.25rem 0.6rem; border-radius: 6px;
        text-transform: uppercase; border: 1px solid transparent;
    }
    .badge-menunggu { background: #f1f5f9; color: #475569; border-color: #cbd5e1; }
    .badge-diproses { background: #fffbeb; color: #b45309; border-color: #fcd34d; }
    .badge-selesai  { background: #f0fdf4; color: #047857; border-color: #6ee7b7; }

    .card-body { padding: 1.5rem; }
    .aspirasi-text {
        font-size: 0.95rem; color: var(--text-muted); line-height: 1.6;
        background: #fafafa; padding: 1rem; border-radius: 12px; border: 1px dashed var(--border-color);
    }

    .card-footer {
        padding: 0.85rem 1.5rem; border-top: 1px solid var(--border-color);
        font-size: 0.75rem; color: var(--text-muted); display: flex; align-items: center; justify-content: space-between;
    }

    /* Pagination Styling */
    .pagination { display: flex; justify-content: center; gap: 8px; margin: 2rem 0; }
    .pagination a {
        padding: 8px 16px; border: 1px solid var(--border-color); border-radius: 10px;
        color: var(--text-muted); text-decoration: none; font-weight: 700; transition: 0.3s;
    }
    .pagination a.active { background: var(--primary); color: #fff; border-color: var(--primary); }
</style>

<div class="page-header">
    <h2 class="page-title">Riwayat Seluruh Aspirasi</h2>
    <p style="color: var(--text-muted);">Monitoring data (Halaman <?= $halamanAktif ?> dari <?= $jumlahHalaman ?>).</p>
</div>

<div class="container-cards">
    <?php foreach ($data_history as $row) : ?>
        <article class="card-aspirasi">
            <div class="card-top">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div class="avatar"><?= strtoupper(substr($row->nama_lengkap, 0, 1)) ?></div>
                    <div>
                        <span style="display: block; font-weight: 700; font-size: 0.9rem; text-transform: uppercase;"><?= htmlspecialchars($row->nama_lengkap) ?></span>
                        <span class="badge badge-<?= strtolower($row->status) ?>"><?= $row->status ?></span>
                    </div>
                </div>
                <div style="text-align: right;">
                    <small style="font-weight: 700; color: var(--text-muted); display: block; font-size: 0.65rem;">ID LAPORAN</small>
                    <span style="font-weight: 800; color: var(--primary);">#<?= $row->id ?></span>
                </div>
            </div>
            <div class="card-body">
                <h3 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 0.5rem;"><?= htmlspecialchars($row->judul) ?></h3>
                <p class="aspirasi-text"><?= nl2br(htmlspecialchars($row->pesan)) ?></p>
            </div>
            <div class="card-footer">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span>Dikirim: <?= date('d M Y', strtotime($row->created_at ?? 'now')) ?></span>
                </div>
                <span style="font-weight: 600;">SARPRAS SYSTEM</span>
            </div>
        </article>
    <?php endforeach; ?>
</div>

<div class="pagination">
    <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
        <a href="?halaman=<?= $i ?>" class="<?= ($i == $halamanAktif) ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>

<?php include '../layout/footer.php'; ?>