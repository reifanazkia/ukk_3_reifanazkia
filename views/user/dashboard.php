<?php
session_start();

if (!isset($_SESSION['data']) || $_SESSION['data']['role'] !== 'user') {
  header("Location: ../../index.php");
  exit;
} elseif (!isset($_SESSION['data']) || $_SESSION['data']['role'] !== 'user') {
  header("Location: ../../registrasi.php");
  exit;
}
?>

<?php include '../layout/header.php'; ?>
<?php include '../layout/sidebar.php'; ?>

<main class="content">
  <!-- <h2>Dashboard Admin</h2> -->
</main>

<?php include '../layout/footer.php'; ?>
