<?php
if (!isset($_SESSION['data'])) {
  header("Location: ../index.php");
  exit;
}

$role = $_SESSION['data']['role'] ?? null;
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Sistem Aspirasi Sekolah</title>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: Arial, sans-serif;
}

body {
  display: flex;
  background: #eef5fb;
}

.sidebar {
  width: 230px;
  background: #ffffff;
  min-height: 100vh;
  padding: 20px;
  box-shadow: 2px 0 8px rgba(0,0,0,0.05);
  transition: 0.3s;
}

.sidebar.hide {
  width: 70px;
}

.sidebar.hide h3,
.sidebar.hide a span {
  display: none;
}

.sidebar h3 {
  margin-bottom: 25px;
  font-size: 18px;
  color: #333;
}

.sidebar a.menu-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 14px;
  margin-bottom: 12px;
  text-decoration: none;
  color: #555;
  border-radius: 8px;
  transition: 0.3s;
  font-size: 14px;
}

.menu-icon {
  width: 18px;
  height: 18px;
  stroke: currentColor;
  flex-shrink: 0;
  transition: 0.3s;
}

.sidebar a.menu-item:hover {
  background: #f1f5f9;
  color: #333;
  transform: translateX(2px);
}

.sidebar a.menu-item.active {
  background: #e3f0ff;
  color: #2c3e50;
  font-weight: 500;
}

.main {
  flex: 1;
  transition: 0.3s;
}

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
  color: #333;
}

.topbar-right {
  display: flex;
  align-items: center;
  gap: 15px;
}

.role-text {
  font-size: 14px;
  color: #555;
}

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

<div class="sidebar" id="sidebar">

<?php if ($role === 'admin') : ?>
  <h3>Admin</h3>

  <a href="dashboard.php" class="menu-item <?= $current=='dashboard.php'?'active':'' ?>">
    <svg class="menu-icon" fill="none" stroke-width="2" viewBox="0 0 24 24">
      <path d="M3 12l9-9 9 9M4 10v10h6v-6h4v6h6V10"/>
    </svg>
    <span>Home</span>
  </a>

  <a href="../admin/aspirasi.php" class="menu-item <?= $current=='aspirasi.php'?'active':'' ?>">
    <svg class="menu-icon" fill="none" stroke-width="2" viewBox="0 0 24 24">
      <path d="M8 10h8M8 14h6M4 6h16v12H4z"/>
    </svg>
    <span>Aspirasi</span>
  </a>

  <a href="../admin/umpanbalik.php" class="menu-item <?= $current=='umpanbalik.php'?'active':'' ?>">
    <svg class="menu-icon" fill="none" stroke-width="2" viewBox="0 0 24 24">
      <path d="M21 15a4 4 0 01-4 4H7l-4 4V5a4 4 0 014-4h10a4 4 0 014 4z"/>
    </svg>
    <span>Umpan Balik</span>
  </a>

  <a href="kategori.php" class="menu-item <?= $current=='kategori.php'?'active':'' ?>">
    <svg class="menu-icon" fill="none" stroke-width="2" viewBox="0 0 24 24">
      <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z"/>
    </svg>
    <span>Kategori</span>
  </a>

  <a href="history.php" class="menu-item <?= $current=='history.php'?'active':'' ?>">
    <svg class="menu-icon" fill="none" stroke-width="2" viewBox="0 0 24 24">
      <path d="M12 8v4l3 3M12 2a10 10 0 100 20 10 10 0 000-20z"/>
    </svg>
    <span>History</span>
  </a>

<?php elseif ($role === 'user') : ?>
  <h3>User</h3>

  <a href="dashboard.php" class="menu-item <?= $current=='dashboard.php'?'active':'' ?>">
    <svg class="menu-icon" fill="none" stroke-width="2" viewBox="0 0 24 24">
      <path d="M3 12l9-9 9 9M4 10v10h6v-6h4v6h6V10"/>
    </svg>
    <span>Home</span>
  </a>

  <a href="../user/aspirasi.php" class="menu-item <?= $current=='aspirasi.php'?'active':'' ?>">
    <svg class="menu-icon" fill="none" stroke-width="2" viewBox="0 0 24 24">
      <path d="M8 10h8M8 14h6M4 6h16v12H4z"/>
    </svg>
    <span>Aspirasi</span>
  </a>

  <a href="../user/umpanbalik.php" class="menu-item <?= $current=='umpanbalik.php'?'active':'' ?>">
    <svg class="menu-icon" fill="none" stroke-width="2" viewBox="0 0 24 24">
      <path d="M21 15a4 4 0 01-4 4H7l-4 4V5a4 4 0 014-4h10a4 4 0 014 4z"/>
    </svg>
    <span>Umpan Balik</span>
  </a>

  <a href="history.php" class="menu-item <?= $current=='history.php'?'active':'' ?>">
    <svg class="menu-icon" fill="none" stroke-width="2" viewBox="0 0 24 24">
      <path d="M12 8v4l3 3M12 2a10 10 0 100 20 10 10 0 000-20z"/>
    </svg>
    <span>History</span>
  </a>

  <a href="progres.php" class="menu-item <?= $current=='progres.php'?'active':'' ?>">
    <svg class="menu-icon" fill="none" stroke-width="2" viewBox="0 0 24 24">
      <path d="M4 12h16M4 6h16M4 18h16"/>
    </svg>
    <span>Progres Perbaikan</span>
  </a>

<?php endif; ?>

</div>

<div class="main">

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

<div class="container">