<?php
include 'koneksi.php';
session_start();

$loginError = false;
$loginSuccess = false;

if(isset($_POST['submit'])){
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  $sql ="SELECT * FROM akun WHERE user = '$username' AND pass = '$password'";
  $result = mysqli_query($koneksi, $sql);
  if(mysqli_num_rows($result)== 1){

    echo"<script>
    alert('Login Berhasil')
    window.location.href = 'beranda.php';
    </script>";

    exit();
  }else{
    $loginError = true;
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="index.css">
  <title>LOGIN</title>

  <?php if ($loginError): ?>
    <script>
      alert ("Login Gagal. Perbaiki!");
    </script>
    <?php endif; ?>

</head>
<body>
  <h1 align="center"> LOGIN
  <form action="" method="post">
    <input class="login" type="text" name="username" placeholder="username"><br>
    <input class="login" type="password" name="password" placeholder="password"><br>
    <button class="login" type="submit" name="submit">LOGIN</button>
  </form>
  </h1>
</body>
</html>

