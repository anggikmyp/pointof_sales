<?php
include 'koneksi.php';
session_start();

$loginError = false;
$loginSuccess = false;

if (isset($_POST['submit'])) {
  $username = mysqli_real_escape_string($koneksi, $_POST['username']);
  $password = mysqli_real_escape_string($koneksi, $_POST['password']);

  // Cek user dan password langsung (jika belum pakai hash)
  $sql = "SELECT * FROM akun WHERE user = '$username' AND pass = '$password'";
  $result = mysqli_query($koneksi, $sql);

  if ($result && mysqli_num_rows($result) == 1) {
    $_SESSION['user'] = $username;
    $loginSuccess = true;
  } else {
    $loginError = true;
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>LOGIN</title>

  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="css/index.css">
</head>
<body>

<?php if ($loginError): ?>
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Login Gagal!',
      text: 'Username atau password salah.',
      confirmButtonColor: '#d33'
    });
  </script>
<?php elseif ($loginSuccess): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Login Berhasil!',
      text: 'Anda akan diarahkan ke beranda.',
      showConfirmButton: false,
      timer: 2000
    }).then(() => {
      window.location.href = 'beranda.php';
    });
  </script>
<?php endif; ?>

<div class="login-container">
  <h1>LOGIN</h1>
  <form action="" method="post">
    <input class="login" type="text" name="username" placeholder="Username" required><br>
    <input class="login" type="password" name="password" placeholder="Password" required><br>
    <button class="login" type="submit" name="submit">LOGIN</button>
  </form>
</div>

</body>
</html>
