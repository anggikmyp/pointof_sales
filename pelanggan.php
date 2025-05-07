<?php
include 'koneksi.php';
session_start();

if(isset($_POST['tambah'])){
  $nama_pelanggan = $_POST['nama_pelanggan'];
  $alama = $_POST['nama_pelanggan'];

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pelanggan</title>
</head>
<body>
    <h1>DAFTAR PELANGGAN</h1>
    <form action="" method="post">
      <input type="text" name="nama_pelanggan" placeholder="Nama Pelanggan">
      <input type="text" name="alamat" placeholder="Alamat">
      <input type="number" name="no_tlp" placeholder="Nomor Telepon">
      <button type="submit" name="tambah">Tambah</button>
    </form>
    <table border="1" align="center">
      <tr>
        <th>No.</th>
        <th>Nama Pelanggan</th>
        <th>Alamat</th>
        <th>No. Telepon</th>

      </tr>
      <?php while ($row = mysqli_fetch_assoc($pelanggan)){
        ?>
        <tr>
          <td><?= $row['no++']?></td>
          <td><?= $row['nama_pelanggan']?></td>
          <td><?= $row['alamat']?></td>
          <td><?= $row['no_tlp']?></td>
          <td>
          <a href="pelanggan.php?hapus=<?= $row['id_pelanggan']?>">Hapus</a>
          </td>
        </tr>
      <?php }?>
    </table>
    <a href="beranda.php">Kembali</a>
</body>
</html>