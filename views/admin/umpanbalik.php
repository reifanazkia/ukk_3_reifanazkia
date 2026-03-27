<?php
session_start();
if (!isset($_SESSION['data']) || $_SESSION['data']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

include_once __DIR__ . '/../../controllers/c_umpanbalik.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$data = $umpanbalik->tampil_data();
?>

<style>
    /* ================= CSS ASLI ANDA ================= */
    .page-title { margin-bottom: 5px; color: #111827; font-weight: 700; }
    .page-subtitle { color: #6b7280; font-size: 14px; margin-bottom: 25px; }
    
    .card-wrapper {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .card-aspirasi {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f3f4f6;
    }

    .user-info { display: flex; align-items: center; gap: 10px; }
    .user-name { font-weight: 700; color: #1f2937; font-size: 15px; }
    
    .badge-kelas {
        font-size: 11px;
        background: #eff6ff;
        color: #2563eb;
        padding: 2px 10px;
        border-radius: 6px;
        font-weight: 600;
    }

    .status-pill {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        text-transform: uppercase;
    }
    .status-pill.menunggu { background: #f3f4f6; color: #6b7280; }
    .status-pill.diproses { background: #fef3c7; color: #92400e; }
    .status-pill.selesai  { background: #d1fae5; color: #065f46; }
    .status-pill.ditolak  { background: #fee2e2; color: #991b1b; }

    .judul-aspirasi { font-size: 17px; font-weight: 700; margin-bottom: 8px; color: #111827; }
    .isi-aspirasi { font-size: 14px; color: #4b5563; line-height: 1.6; margin-bottom: 20px; }

    /* Container foto agar tetap rapi */
    .foto-lampiran {
        margin-bottom: 15px;
        border-radius: 8px;
        overflow: hidden;
        max-width: 250px;
        border: 1px solid #e5e7eb;
    }
    .foto-lampiran img {
        width: 100%;
        height: auto;
        display: block;
        cursor: pointer;
    }

    .riwayat-section {
        background: #f8fafc;
        border-radius: 10px;
        padding: 15px;
        border: 1px solid #e2e8f0;
    }
    .riwayat-title {
        font-size: 11px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .riwayat-list { list-style: none; padding: 0; margin: 0; }
    .riwayat-item {
        font-size: 13.5px;
        padding: 8px 0;
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
    }
    .riwayat-item:last-child { border-bottom: none; }
    .timestamp { font-size: 11px; color: #94a3b8; float: right; }

    .btn-balas {
        background: #2563eb;
        color: white;
        border: none;
        padding: 8px 18px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: 0.2s;
    }
    .btn-balas:hover { background: #1d4ed8; }

    .modal { 
        display: none; 
        position: fixed; 
        inset: 0; 
        background: rgba(0,0,0,0.6); 
        z-index: 9999; 
        backdrop-filter: blur(2px);
    }
    .modal-content {
        background: white;
        width: 95%;
        max-width: 550px;
        margin: 40px auto;
        padding: 0;
        border-radius: 16px;
        overflow: hidden;
    }
</style>

<h2 class="page-title">Umpan Balik Aspirasi</h2>
<p class="page-subtitle">Berikan tanggapan resmi Anda untuk setiap aspirasi siswa.</p>

<div class="card-wrapper">
    <?php if ($data && mysqli_num_rows($data) > 0): ?>
        <?php while ($row = mysqli_fetch_object($data)) : ?>
            <div class="card-aspirasi">
                <div class="card-header">
                    <div class="user-info">
                        <span class="user-name"><?= htmlspecialchars($row->nama_lengkap) ?></span>
                        <span class="badge-kelas"><?= htmlspecialchars($row->kelas) ?></span>
                        <span class="status-pill <?= strtolower($row->status) ?>"><?= $row->status ?></span>
                    </div>
                    <button class="btn-balas" 
                            onclick="openModal('<?= $row->id ?>', '<?= htmlspecialchars($row->nama_lengkap) ?>', '<?= htmlspecialchars($row->judul) ?>', '<?= htmlspecialchars($row->pesan) ?>')">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                        Balas Aspirasi
                    </button>
                </div>

                <div class="judul-aspirasi"><?= htmlspecialchars($row->judul) ?></div>
                
                <?php 
                $file_path = "../../assets/image/" . $row->foto;
                if($row->foto && file_exists($file_path)): ?>
                    <div class="foto-lampiran">
                        <img src="<?= $file_path ?>" onclick="window.open(this.src)" title="Klik untuk memperbesar">
                    </div>
                <?php endif; ?>

                <p class="isi-aspirasi"><?= nl2br(htmlspecialchars($row->pesan)) ?></p>

                <div class="riwayat-section">
                    <div class="riwayat-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        Riwayat Tanggapan Admin
                    </div>
                    <ul class="riwayat-list">
                        <?php 
                        $riwayat = $umpanbalik->riwayat($row->id);
                        if(mysqli_num_rows($riwayat) > 0): 
                            while ($r = mysqli_fetch_object($riwayat)) : ?>
                                <li class="riwayat-item">
                                    <?= htmlspecialchars($r->tanggapan) ?>
                                    <span class="timestamp"><?= date('d M, H:i', strtotime($r->created_at)) ?></span>
                                </li>
                            <?php endwhile; 
                        else: ?>
                            <li class="riwayat-item" style="color:#94a3b8; font-style:italic; font-size:13px;">Belum ada tanggapan yang dikirim.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div style="text-align:center; padding:50px; background:white; border-radius:12px; border:1px solid #e5e7eb;">
            <p style="color:#94a3b8;">Tidak ada data aspirasi yang perlu ditanggapi.</p>
        </div>
    <?php endif; ?>
</div>

<div id="modalBalas" class="modal">
    <div class="modal-content">
        <div class="modal-header" style="display:flex; justify-content:space-between; align-items:center; padding:20px; background:#f8fafc; border-bottom:1px solid #e2e8f0;">
            <h3 style="margin:0; font-size:18px;">Kirim Tanggapan</h3>
            <button onclick="closeModal()" style="background:none; border:none; cursor:pointer; color:#94a3b8;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form action="../../controllers/c_umpanbalik.php?aksi=tambah" method="POST">
            <div class="modal-body" style="padding:20px;">
                <input type="hidden" name="aspirasi_id" id="m_id">
                <div id="modal_info_siswa" style="background:#f1f5f9; padding:12px; border-radius:8px; margin-bottom:15px; font-size:13px; border-left:4px solid #cbd5e1;">
                    <div id="m_nama" style="font-weight:700;"></div>
                    <div id="m_judul" style="color:#475569;"></div>
                </div>
                <textarea name="tanggapan" required style="width:100%; border:1px solid #cbd5e1; border-radius:10px; padding:12px; min-height:120px;" placeholder="Tulis tanggapan..."></textarea>
            </div>
            <div class="modal-footer" style="padding:15px 20px; background:#f8fafc; text-align:right; border-top:1px solid #e2e8f0;">
                <button type="submit" class="btn-balas" style="margin-left:auto;">Kirim Tanggapan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id, nama, judul, pesan) {
        document.getElementById('modalBalas').style.display = 'block';
        document.getElementById('m_id').value = id;
        document.getElementById('m_nama').innerText = nama;
        document.getElementById('m_judul').innerText = judul;
    }
    function closeModal() { document.getElementById('modalBalas').style.display = 'none'; }
</script>

<?php include '../layout/footer.php'; ?>