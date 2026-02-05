<?php
session_start();

/* =============================
   CEK LOGIN & ROLE ADMIN
============================= */
if (!isset($_SESSION['data']) || $_SESSION['data']['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit;
}
?>

<?php include '../layout/header.php'; ?>
<?php include '../layout/sidebar.php'; ?>

<main class="content">

<style>
.container{
    padding:40px;
}

.stats{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:30px;
}
.stat-box{
    background:rgba(255,255,255,0.9);
    padding:25px;
    border-radius:16px;
    box-shadow:0 8px 20px rgba(0,0,0,.08);
}
.stat-box h3{
    font-size:32px;
}
.stat-box p{
    margin-top:5px;
    color:#64748b;
}

footer{
    text-align:center;
    margin-top:30px;
    color:#64748b;
}
</style>

<div class="container">

    <div class="stats">
        <div class="stat-box">
            <h3>8</h3>
            <p>Total Aspirasi</p>
        </div>
        <div class="stat-box">
            <h3>3</h3>
            <p>Proses Perbaikan</p>
        </div>
        <div class="stat-box">
            <h3>4</h3>
            <p>Selesai</p>
        </div>
    </div>

    <footer>© 2026 Sistem Aspirasi Sekolah</footer>

</div>
</main>

<?php include '../layout/footer.php'; ?>
