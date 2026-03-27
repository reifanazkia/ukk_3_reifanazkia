<?php
session_start();
if (!isset($_SESSION['data']) || $_SESSION['data']['role'] !== 'user') {
    header("Location: ../../index.php"); exit;
}

include_once __DIR__ . '/../../models/m_aspirasi.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$m_aspirasi = new aspirasi();
$user_id = $_SESSION['data']['id'];

// --- LOGIKA PAGINATION ---
$jumlahDataPerHalaman = 10;
$totalData = $m_aspirasi->hitung_total_aspirasi($user_id); 
$jumlahHalaman = ceil($totalData / $jumlahDataPerHalaman);
$halamanAktif = (isset($_GET['halaman'])) ? (int)$_GET['halaman'] : 1;
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

$data_history = $m_aspirasi->tampil_history_per_user_paginated($user_id, $awalData, $jumlahDataPerHalaman); 
?>

<style>
    /* Global Variables */
    :root {
        --primary-blue: #2563eb;
        --bg-body: #f1f5f9;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --border-soft: #e2e8f0;
        --white: #ffffff;
    }

    /* Header Styling */
    .history-header {
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border-soft);
    }
    .history-header h2 {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-dark);
    }

    /* Card Layout */
    .history-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .history-card {
        background: var(--white);
        border: 1px solid var(--border-soft);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    /* Card Header */
    .history-card__top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 20px;
        background: #f9fafb;
        border-bottom: 1px solid var(--border-soft);
    }
    .history-card__id {
        font-size: 12px;
        font-weight: 700;
        color: var(--primary-blue);
        background: #eff6ff;
        padding: 4px 10px;
        border-radius: 6px;
    }

    /* Badges */
    .status-badge {
        font-size: 11px;
        font-weight: 800;
        padding: 4px 12px;
        border-radius: 99px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-badge--menunggu { background: #f1f5f9; color: #475569; }
    .status-badge--diproses { background: #fffbeb; color: #b45309; }
    .status-badge--selesai  { background: #f0fdf4; color: #166534; }

    /* Card Body */
    .history-card__body {
        padding: 20px;
    }
    .history-card__title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 12px;
    }
    .history-card__desc {
        font-size: 14px;
        color: var(--text-muted);
        line-height: 1.6;
        background: #fafafa;
        padding: 14px;
        border-radius: 8px;
        border: 1px dashed var(--border-soft);
    }

    /* Media Section */
    .history-card__media {
        margin-top: 16px;
    }
    .history-card__label {
        display: block;
        font-size: 10px;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    .history-card__img {
        max-width: 220px;
        height: auto;
        border-radius: 8px;
        border: 1px solid var(--border-soft);
        cursor: zoom-in;
        transition: transform 0.2s ease;
    }
    .history-card__img:hover {
        transform: scale(1.02);
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin: 30px 0;
    }
    .pagination__link {
        padding: 8px 16px;
        background: var(--white);
        border: 1px solid var(--border-soft);
        border-radius: 8px;
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
    }
    .pagination__link.active {
        background: var(--primary-blue);
        color: var(--white);
        border-color: var(--primary-blue);
    }
</style>

<div class="history-header">
    <h2>Riwayat Aspirasi Saya</h2>
</div>

<div class="history-container">
    <?php if (empty($data_history)) : ?>
        <div style="text-align: center; padding: 50px; color: var(--text-muted);">
            <p>Belum ada riwayat aspirasi ditemukan.</p>
        </div>
    <?php else : ?>
        <?php foreach ($data_history as $row) : ?>
            <article class="history-card">
                <div class="history-card__top">
                    <span class="status-badge status-badge--<?= strtolower($row->status) ?>">
                        <?= htmlspecialchars($row->status) ?>
                    </span>
                    <span class="history-card__id">ID #<?= $row->id ?></span>
                </div>
                <div class="history-card__body">
                    <h3 class="history-card__title"><?= htmlspecialchars($row->judul) ?></h3>
                    <p class="history-card__desc"><?= nl2br(htmlspecialchars($row->pesan)) ?></p>
                    
                    <?php if (!empty($row->foto)) : ?>
                        <div class="history-card__media">
                            <span class="history-card__label">Foto Bukti</span>
                            <img src="../../assets/image/<?= $row->foto ?>" 
                                 class="history-card__img" 
                                 onclick="window.open(this.src)" 
                                 onerror="this.src='https://placehold.co/220x150?text=Foto+Tidak+Tersedia'">
                        </div>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="pagination">
    <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
        <a href="?halaman=<?= $i ?>" class="pagination__link <?= ($i == $halamanAktif) ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
</div>

<?php include '../layout/footer.php'; ?>