<?php
session_start();

// Memanggil file koneksi dari folder models
require_once "../../models/m_koneksi.php";

// Membuat objek dari class koneksi
$db_obj = new koneksi();
$conn = $db_obj->koneksi; // Mengambil properti $koneksi dari dalam class

if (!isset($_SESSION['data']) || $_SESSION['data']['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}

// 1. Hitung Total Aspirasi
$query_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM aspirasi");
$data_total = mysqli_fetch_assoc($query_total);

// 2. Hitung Proses (Status 'diproses')
$query_proses = mysqli_query($conn, "SELECT COUNT(*) as total FROM aspirasi WHERE status = 'diproses'");
$data_proses = mysqli_fetch_assoc($query_proses);

// 3. Hitung Selesai (Status 'selesai')
$query_selesai = mysqli_query($conn, "SELECT COUNT(*) as total FROM aspirasi WHERE status = 'selesai'");
$data_selesai = mysqli_fetch_assoc($query_selesai);
?>

<?php include '../layout/header.php'; ?>
<?php include '../layout/sidebar.php'; ?>

<main class="content">
<style>
    .container {
        padding: 40px;
    }
    .stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    .stat-box {
        background: rgba(255, 255, 255, 0.9);
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0,0,0,.08);
        transition: transform 0.3s ease;
    }
    .stat-box:hover {
        transform: translateY(-5px);
    }
    .stat-box h3 {
        font-size: 32px;
        margin: 0;
        color: #1e293b;
    }
    .stat-box p {
        margin-top: 5px;
        color: #64748b;
        font-weight: 500;
    }
    footer {
        text-align: center;
        margin-top: 30px;
        color: #64748b;
    }
</style>

<div class="container">
    <div class="stats">
        <div class="stat-box">
            <h3><?php echo $data_total['total'] ?? 0; ?></h3>
            <p>Total Aspirasi</p>
        </div>
        <div class="stat-box">
            <h3 style="color: #f59e0b;"><?php echo $data_proses['total'] ?? 0; ?></h3>
            <p>Proses Perbaikan</p>
        </div>
        <div class="stat-box">
            <h3 style="color: #10b981;"><?php echo $data_selesai['total'] ?? 0; ?></h3>
            <p>Selesai</p>
        </div>
    </div>

    <footer>© 2026 Sistem Aspirasi Sekolah</footer>
</div>
</main>

<?php include '../layout/footer.php'; ?>