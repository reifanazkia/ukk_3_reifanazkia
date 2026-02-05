<?php

include_once 'm_koneksi.php';

class login
{

  function registrasi($nis, $nama_lengkap, $email, $kelas, $username, $password, $role)
  {
    $conn = new koneksi();
    $sql = "INSERT INTO users  VALUES (NULL, '$nis', '$nama_lengkap', '$email', '$kelas', '$username', '$password', '$role')";
    $query = mysqli_query($conn->koneksi, $sql);

    if ($query) {
      echo "<script>
                alert('Registrasi berhasil');
                window.location='../index.php';
              </script>";
    } else {
      echo "<script>
                alert('Registrasi gagal');
                window.location='registrasi.php';
              </script>";
    }
  }

  function login($username, $password)
  {
    $conn = new koneksi();
    $sql = "SELECT * FROM users WHERE username='$username'";
    $query = mysqli_query($conn->koneksi, $sql);
    $data = mysqli_fetch_assoc($query);

    if ($data && password_verify($password, $data['password'])) {
      $_SESSION['data'] = $data;
      header("Location: ../views/{$data['role']}/dashboard.php");
      exit;
    } else {
      echo "<script>
        alert('Username atau Password salah');
        window.location='../index.php';
      </script>";
    }
  }
}
