<?php
include 'koneksi.php';
session_start();
date_default_timezone_set('Asia/Jakarta');
// Fungsi untuk generate kode barang
function generateKodeBarang($koneksi) {
    $query = mysqli_query($koneksi, "SELECT kode_barang FROM produk ORDER BY id_produk DESC LIMIT 1");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        $lastKode = $data['kode_barang'];
        $number = (int)substr($lastKode, 1) + 1;
        return 'B' . str_pad($number, 4, '0', STR_PAD_LEFT);
    } else {
        return 'B0001'; // Jika tidak ada data, mulai dari B0001
    }
}
// Tambah produk
if (isset($_POST['tambah'])) {
    $kode_barang = generateKodeBarang($koneksi);
    $pelanggan = $_POST['pelanggan'];
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $expired = $_POST['expired'];

    mysqli_query($koneksi, 
    "INSERT INTO produk (kode_barang, pelanggan, nama_produk, harga, expired) 
    VALUES ('$kode_barang', '$pelanggan', '$nama_produk', '$harga', '$expired')");
    
    header("location: produk.php");
}
// Hapus produk
if (isset($_GET['hapus'])) {
    $id_produk = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM produk WHERE id_produk = '$id_produk'");
    header("location: produk.php");
}
// Edit produk
if (isset($_POST['update'])) {
    $id_produk = $_POST['id_produk'];
    $pelanggan = $_POST['pelanggan'];
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $expired = $_POST['expired'];

    mysqli_query($koneksi, 
    "UPDATE produk SET 
    pelanggan = '$pelanggan',
    nama_produk = '$nama_produk', 
    harga = '$harga', 
    expired = '$expired' 
    WHERE id_produk = '$id_produk'");
    
    header("location: produk.php");
}

// Ambil data produk
$produk = mysqli_query($koneksi, "SELECT * FROM produk");

// Jika ingin edit
if (isset($_GET['edit'])) {
    $id_produk = $_GET['edit'];
    $result = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk = '$id_produk'");
    $row_edit = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRODUK</title>
    <link rel="stylesheet" type="text/css" href="css/produk.css">
</head>

<body bgcolor="#25d18f">
    <h1 align="center">DAFTAR PRODUK</h1>
    
    <!-- Form untuk tambah atau edit produk -->
    <form class="menambah" method="post">
    <input type="hidden" name="id_produk" value="<?= isset($row_edit) ? $row_edit['id_produk'] : '' ?>">
        <input class="menambah" type="text" name="pelanggan" placeholder="Nama Pelanggan" 
        value="<?= isset($row_edit) ? $row_edit['pelanggan'] : '' ?>" required>
        <input class="menambah" type="text" name="nama_produk" placeholder="Nama Produk" 
        value="<?= isset($row_edit) ? $row_edit['nama_produk'] : '' ?>" required>
        <input class="menambah" type="number" name="harga" placeholder="Harga" 
        value="<?= isset($row_edit) ? $row_edit['harga'] : '' ?>" required>
        <input class="menambah" type="datetime-local" name="expired" placeholder="Expired" 
        value="<?= isset($row_edit) ? date('Y-m-d\TH:i', strtotime($row_edit['expired'])) : '' ?>" required>
        
        <button class="menambah" type="submit" name="<?= isset($row_edit) ? 'update' : 'tambah' ?>">
            <?= isset($row_edit) ? 'Update' : 'Tambah' ?>
        </button>
    </form>

    <!-- Tabel data produk -->
    <table border="1" align="center" cellpadding="5" cellspacing="0">
        <tr>
            <th>No.</th>
            <th>Kode Barang</th>
            <th>Nama Pelanggan</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Tanggal Expired</th>
            <th>Tanggal Input</th>
            <th>Tanggal Update</th>
            <th>Aksi</th>
        </tr>
        <?php 
        $no = 1;
        while ($row = mysqli_fetch_assoc($produk)) { ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['kode_barang'] ?></td>
                <td><?= htmlspecialchars($row['pelanggan']) ?></td>
                <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                <td><?= $row['expired'] ?></td>
                <td><?= $row['tgl_input'] ?? '-' ?></td>
                <td><?= $row['tgl_update'] ?? '-' ?></td>
                <td>
                    <a href="produk.php?edit=<?= $row['id_produk'] ?>">Edit</a> | 
                    <a href="produk.php?hapus=<?= $row['id_produk'] ?>" onclick="return confirm('Yakin ingin hapus produk ini?')">Hapus</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <br><center><a href="beranda.php">Kembali ke Beranda</a></center>
</body>

</html>
