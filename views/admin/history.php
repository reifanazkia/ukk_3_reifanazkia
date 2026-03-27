<?php
session_start();
if (!isset($_SESSION['data']) || $_SESSION['data']['role'] !== 'admin') {
    header("Location: ../../index.php"); exit;
}

include_once __DIR__ . '/../../models/m_aspirasi.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$m_aspirasi = new aspirasi();

// --- LOGIKA PAGINATION ADMIN ---
$jumlahDataPerHalaman = 10;
$totalData = $m_aspirasi-> hitung_total_aspirasi(); // Pastikan fungsi ini ada di model
$jumlahHalaman = ceil($totalData / $jumlahDataPerHalaman);
$halamanAktif = (isset($_GET['halaman'])) ? (int)$_GET['halaman'] : 1;
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

$data_history = $m_aspirasi->tampil_semua_history_paginated($awalData, $jumlahDataPerHalaman); 
?>

<style>
    /* 1. Configuration & Variables */
    :root {
        --primary: #2563eb;
        --primary-soft: #eff6ff;
        --bg-light: #f8fafc;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --white: #ffffff;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    }

    /* 2. Page Header */
    .page-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-main);
    }

    /* 3. Card Component */
    .card-aspirasi {
        background: var(--white);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-sm);
    }

    .card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.5rem;
        background: var(--bg-light);
        border-bottom: 1px solid var(--border-color);
    }

    .card-body {
        padding: 1.5rem;
    }

    /* 4. Elements & Typography */
    .user-info {
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 0.025em;
        margin-bottom: 0.5rem;
        display: block;
    }

    .aspirasi-text {
        font-size: 0.95rem;
        color: var(--text-muted);
        line-height: 1.6;
        background: #fafafa;
        padding: 1.25rem;
        border-radius: 12px;
        border: 1px dashed var(--border-color);
        margin-bottom: 1.25rem;
    }

    /* 5. Images */
    .history-img-container {
        margin-top: 1rem;
    }

    .history-label {
        display: block;
        font-size: 10px;
        font-weight: 800;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .history-img-admin {
        max-width: 220px;
        height: auto;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        cursor: zoom-in;
        transition: transform 0.2s ease-in-out;
    }

    .history-img-admin:hover {
        transform: scale(1.02);
    }

    /* 6. Badges */
    .badge {
        font-size: 0.7rem;
        font-weight: 800;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .badge-menunggu { background: #f1f5f9; color: #475569; }
    .badge-diproses { background: #fffbeb; color: #b45309; }
    .badge-selesai  { background: #f0fdf4; color: #166534; }

    /* 7. Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin: 2.5rem 0;
    }

    .pagination a {
        padding: 0.6rem 1.1rem;
        background: var(--white);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .pagination a:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .pagination a.active {
        background: var(--primary);
        color: var(--white);
        border-color: var(--primary);
        box-shadow: 0 4px 6px -1px rgb(37 99 235 / 0.2);
    }
</style>

<div class="page-header">
    <h2 class="page-title">Riwayat Seluruh Aspirasi (Admin)</h2>
</div>

<div class="container-cards">
    <?php if (empty($data_history)) : ?>
        <p style="text-align: center; color: var(--text-muted); padding: 3rem;">Belum ada data riwayat.</p>
    <?php else : ?>
        <?php foreach ($data_history as $row) : ?>
            <article class="card-aspirasi">
                <div class="card-top">
                    <div>
                        <span class="user-info">Oleh: <?= htmlspecialchars($row->nama_lengkap ?? 'User') ?></span>
                        <span class="badge badge-<?= strtolower($row->status) ?>"><?= $row->status ?></span>
                    </div>
                    <span style="font-weight: 800; color: var(--primary);">#<?= $row->id ?></span>
                </div>
                <div class="card-body">
                    <h3 style="font-weight: 800; margin-bottom: 0.5rem;"><?= htmlspecialchars($row->judul) ?></h3>
                    <p class="aspirasi-text"><?= nl2br(htmlspecialchars($row->pesan)) ?></p>
                    
                    <?php if (!empty($row->foto)) : ?>
                        <div>
                            <small style="display:block; font-size:10px; font-weight:800; color:#94a3b8; margin-bottom:5px; text-transform:uppercase;">Foto Bukti Siswa:</small>
                            <img src="../../assets/image/<?= $row->foto ?>" class="history-img-admin" onclick="window.open(this.src)" onerror="this.src='https://placehold.co/200x150?text=Foto+Tidak+Ditemukan'">
                        </div>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="pagination">
    <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
        <a href="?halaman=<?= $i ?>" class="<?= ($i == $halamanAktif) ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>

<?php include '../layout/footer.php'; ?>