<?php
session_start();

// Proteksi akses
if (!isset($_SESSION['data']) || $_SESSION['data']['role'] !== 'user') {
    header("Location: ../index.php");
    exit;
}

include_once __DIR__ . '/../../controllers/c_umpanbalik.php';
include '../layout/header.php';
include '../layout/sidebar.php';

// Filter data berdasarkan ID User yang login
$userId = $_SESSION['data']['id'];
$data = $umpanbalik->tampil_data($userId); 
?>

<style>
    .page-title { margin-bottom: 5px; color: #111827; font-weight: 700; }
    .page-subtitle { color: #6b7280; font-size: 14px; margin-bottom: 25px; }
    .card-wrapper { display: grid; grid-template-columns: 1fr; gap: 20px; }

    .card-aspirasi {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .status-pill {
        font-size: 12px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 9999px;
        text-transform: capitalize;
    }
    .status-pill.menunggu { background: #f3f4f6; color: #6b7280; }
    .status-pill.diproses { background: #fef3c7; color: #92400e; }
    .status-pill.selesai  { background: #d1fae5; color: #065f46; }
    .status-pill.ditolak  { background: #fee2e2; color: #991b1b; }

    .judul-aspirasi { font-size: 18px; font-weight: 700; color: #111827; }
    .isi-aspirasi { font-size: 15px; color: #4b5563; line-height: 1.6; margin: 12px 0; }

    .user-foto-aspirasi { 
        max-width: 220px; 
        border-radius: 8px; 
        margin: 10px 0 20px 0; 
        border: 1px solid #f1f5f9; 
        cursor: zoom-in; 
        display: block;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    .user-foto-aspirasi:hover { transform: scale(1.02); }

    .riwayat-section {
        background: #f8fafc;
        border-radius: 10px;
        padding: 16px;
        border-left: 4px solid #e2e8f0;
        margin-top: 15px;
    }
    .riwayat-title { font-size: 13px; font-weight: 700; margin-bottom: 10px; color: #475569; }
    .riwayat-item {
        font-size: 14px;
        padding: 10px 0;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .riwayat-item:last-child { border-bottom: none; }
    .admin-label { font-weight: 700; color: #2563eb; font-size: 11px; text-transform: uppercase; margin-right: 8px; }
    .timestamp { font-size: 11px; color: #94a3b8; white-space: nowrap; margin-left: 10px; }
</style>

<h2 class="page-title">Aspirasi Saya</h2>
<p class="page-subtitle">Pantau status dan tanggapan admin atas aspirasi Anda.</p>

<div class="card-wrapper">
    <?php if ($data && mysqli_num_rows($data) > 0): ?>
        <?php while ($row = mysqli_fetch_object($data)) : ?>
            <div class="card-aspirasi">
                <div class="card-header">
                    <div class="judul-aspirasi"><?= htmlspecialchars($row->judul) ?></div>
                    <span class="status-pill <?= strtolower($row->status) ?>">
                        <?= ucfirst($row->status) ?>
                    </span>
                </div>

                <p class="isi-aspirasi"><?= nl2br(htmlspecialchars($row->pesan)) ?></p>

                <?php if (!empty($row->foto)) : ?>
                    <div>
                        <small style="display:block; font-size:10px; font-weight:800; color:#94a3b8; margin-bottom:5px; text-transform:uppercase;">Foto Bukti:</small>
                        <img src="../../assets/image/<?= $row->foto ?>" 
                             class="user-foto-aspirasi" 
                             onclick="window.open(this.src)" 
                             title="Klik untuk memperbesar"
                             onerror="this.src='https://placehold.co/220x150?text=Foto+Tidak+Ditemukan'">
                    </div>
                <?php endif; ?>

                <div class="riwayat-section">
                    <div class="riwayat-title">Tanggapan Admin:</div>
                    
                    <?php 
                    $riwayat = $umpanbalik->riwayat($row->id);
                    if(mysqli_num_rows($riwayat) > 0): 
                        while ($r = mysqli_fetch_object($riwayat)) : ?>
                            <div class="riwayat-item">
                                <div>
                                    <span class="admin-label">Admin:</span>
                                    <?= htmlspecialchars($r->tanggapan) ?>
                                </div>
                                <span class="timestamp">
                                    <?= date('d M Y', strtotime($r->created_at)) ?>
                                </span>
                            </div>
                        <?php endwhile; 
                    else: ?>
                        <p style="font-size:13px; color:#94a3b8; margin:0;">
                            <i>Belum ada tanggapan resmi.</i>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div style="text-align:center; padding:60px; background:#fff; border-radius:12px; border:1px solid #e5e7eb;">
            <p style="color:#94a3b8;">Anda belum mengirimkan aspirasi apa pun.</p>
        </div>
    <?php endif; ?>
</div>

<?php include '../layout/footer.php'; ?>