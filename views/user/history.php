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
$data_history = $m_aspirasi->tampil_history_per_user($user_id); 
?>

<style>
    :root {
        --primary: #2563eb;
        --bg-light: #f8fafc;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --white: #ffffff;
    }
    .page-header { margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid var(--border-color); }
    .container-cards { display: flex; flex-direction: column; gap: 1.25rem; }
    .card-aspirasi { background: var(--white); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
    .card-top { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; background: var(--bg-light); border-bottom: 1px solid var(--border-color); }
    .user-meta { display: flex; align-items: center; gap: 0.85rem; }
    .avatar { width: 2.5rem; height: 2.5rem; background: var(--primary); color: var(--white); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; text-transform: uppercase; }
    .user-info-row .name { display: block; font-weight: 700; color: var(--text-main); font-size: 0.9rem; text-transform: uppercase; margin-bottom: 4px; }
    .badge { font-size: 0.7rem; font-weight: 800; padding: 0.25rem 0.6rem; border-radius: 6px; text-transform: uppercase; border: 1px solid transparent; }
    .badge-menunggu { background: #f1f5f9; color: #475569; border-color: #cbd5e1; }
    .badge-diproses { background: #fffbeb; color: #b45309; border-color: #fcd34d; }
    .badge-selesai  { background: #f0fdf4; color: #047857; border-color: #6ee7b7; }
    .card-body { padding: 1.5rem; }
    .aspirasi-title { font-size: 1.1rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem; }
    .aspirasi-text { font-size: 0.95rem; color: var(--text-muted); line-height: 1.6; background: #fafafa; padding: 1rem; border-radius: 12px; border: 1px dashed var(--border-color); }
</style>

<div class="page-header">
    <h2 class="page-title">Riwayat Aspirasi Saya</h2>
</div>

<div class="container-cards">
    <?php if (empty($data_history)) : ?>
        <p style="text-align: center; color: var(--text-muted); padding: 3rem;">Anda belum pernah mengirim aspirasi.</p>
    <?php else : ?>
        <?php foreach ($data_history as $row) : ?>
            <article class="card-aspirasi">
                <div class="card-top">
                    <div class="user-meta">
                        <div class="avatar"><?= strtoupper(substr($row->nama_lengkap, 0, 1)) ?></div>
                        <div class="user-info-row">
                            <span class="name"><?= htmlspecialchars($row->nama_lengkap) ?></span>
                            <span class="badge badge-<?= strtolower($row->status) ?>"><?= $row->status ?></span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h3 class="aspirasi-title"><?= htmlspecialchars($row->judul) ?></h3>
                    <p class="aspirasi-text"><?= nl2br(htmlspecialchars($row->pesan)) ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include '../layout/footer.php'; ?>