<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['data']['role'] ?? null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Sistem Aspirasi Sekolah</title>

<style>
/* ===== RESET ===== */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: Arial, sans-serif;
}

/* ===== LAYOUT ===== */
body {
  display: flex;
  background: #eef5fb;
}

/* ===== SIDEBAR ===== */
.sidebar {
  width: 230px;
  background: #ffffff;
  min-height: 100vh;
  padding: 20px;
  box-shadow: 2px 0 6px rgba(0,0,0,0.05);
}

.sidebar h3 {
  margin-bottom: 20px;
}

.sidebar a {
  display: block;
  padding: 10px 12px;
  margin-bottom: 6px;
  text-decoration: none;
  color: #333;
  border-radius: 6px;
  transition: 0.3s;
}

.sidebar a:hover {
  background: #e3f0ff;
}

/* ===== MAIN ===== */
.main {
  flex: 1;
}

/* ===== TOPBAR ===== */
.topbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 20px;
  background: #ffffff;
  box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

.topbar-left {
  display: flex;
  align-items: center;
  gap: 15px;
}

.toggle-btn {
  font-size: 22px;
  cursor: pointer;
}

.topbar-left h2 {
  font-size: 18px;
}

/* ===== TOPBAR RIGHT ===== */
.topbar-right {
  display: flex;
  align-items: center;
  gap: 15px;
}

.role-text {
  font-size: 14px;
  color: #555;
}

/* ===== LOGOUT BUTTON ===== */
.logout-btn {
  text-decoration: none;
  padding: 6px 14px;
  background: #e74c3c;
  color: #fff;
  border-radius: 6px;
  font-size: 14px;
  transition: 0.3s;
}

.logout-btn:hover {
  background: #c0392b;
}

/* ===== CONTENT ===== */
.container {
  padding: 20px;
}
</style>

<script>
function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('hide');
}
</script>
</head>

<body>

<!-- ===== SIDEBAR ===== -->
<div class="sidebar" id="sidebar">

<?php if ($role === 'admin') : ?>
  <h3>Admin</h3>
  <a href="dashboard.php">Home</a>
  <a href="../admin/aspirasi.php">Aspirasi</a>
  <a href="../admin/umpanbalik.php">Umpan Balik</a>
  <a href="kategori.php">Kategori</a>
  <a href="history.php">History</a>

<?php elseif ($role === 'user') : ?>
  <h3>User</h3>
  <a href="dashboard.php">Home</a>
  <a href="../user/aspirasi.php">Aspirasi</a>
  <a href="../admin/umpanbalik.php">Umpan Balik</a>
  <a href="history.php">History</a>
  <a href="progres.php">Progres Perbaikan</a>

<?php else : ?>
  <p style="color:red;">Role tidak dikenali</p>
<?php endif; ?>

</div>

<!-- ===== MAIN ===== -->
<div class="main">

<!-- ===== TOPBAR ===== -->
<div class="topbar">
  <div class="topbar-left">
    <span class="toggle-btn" onclick="toggleSidebar()">☰</span>
    <h2>Sistem Aspirasi Sekolah</h2>
  </div>

  <div class="topbar-right">
    <?php if ($role): ?>
      <span class="role-text">Dashboard <?= ucfirst($role) ?></span>
    <?php endif; ?>

    <a href="../../controllers/c_login.php?aksi=logout"
       class="logout-btn"
       onclick="return confirm('Apakah anda yakin akan keluar?')">
       Logout
    </a>
  </div>
</div>

<!-- ===== CONTENT ===== -->
<div class="container">
