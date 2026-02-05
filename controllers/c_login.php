<?php
session_start();
include_once '../models/m_login.php';

$login = new login();

if (isset($_GET['aksi'])) {

  if ($_GET['aksi'] == 'login') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $login->login($username, $password);

   } elseif ($_GET['aksi'] == 'logout') {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit;
  

  } elseif ($_GET['aksi'] == 'registrasi') {

    $nis = $_POST['nis'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $kelas = $_POST['kelas'];
    $username  = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $login->registrasi($nis, $nama_lengkap, $email, $kelas, $username, $password, $role);
  }
}
