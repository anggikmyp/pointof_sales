<?php
include 'koneksi.php';
session_start();
// Tambah produk ke session
if (!empty($_POST['kode_barang'])) {
    $kode = $_POST['kode_barang'];
    $query = mysqli_query($koneksi, "SELECT * FROM produk WHERE kode_barang = '$kode'");
    if ($produk = mysqli_fetch_assoc($query)) {
        $_SESSION['penjualan'][] = [
            'kode_barang' => $produk['kode_barang'],
            'nama_produk' => $produk['nama_produk'],
            'harga' => $produk['harga'],
            'expired' => $produk['expired'],
            'jumlah' => 1,
            'total' => $produk['harga']
        ];
    }
    header("Location: penjualan.php");
    exit();
}
// Hapus item dari session
if (isset($_GET['hapus'])) {
    unset($_SESSION['penjualan'][$_GET['hapus']]);
    $_SESSION['penjualan'] = array_values($_SESSION['penjualan']);
    header("Location: penjualan.php");
    exit();
}
// Proses pembayaran
if (isset($_POST['bayar'])) {
    $total_semua = intval($_POST['total_bayar']);
    $bayar = intval($_POST['jumlah_bayar']);
    $kembalian = $bayar - $total_semua;

    if ($bayar < $total_semua) {
        echo "<script>alert('Uang kurang!'); window.location='penjualan.php';</script>";
        exit();
    }
    $tanggal_penjualan = date('Y-m-d H:i:s');
    $id_awal = null;

    foreach ($_SESSION['penjualan'] as $item) {
        $kode_barang = $item['kode_barang'];
        $nama_produk = $item['nama_produk'];
        $harga = $item['harga'];
        $jumlah = $item['jumlah'];
        $total = $item['total'];

        $query = "INSERT INTO penjualan (tanggal_penjualan, kode_barang, nama_produk, harga, jumlah, total, bayar, kembali) 
                  VALUES ('$tanggal_penjualan', '$kode_barang', '$nama_produk', '$harga', '$jumlah', '$total', '$bayar', '$kembalian')";
        mysqli_query($koneksi, $query);

        if ($id_awal === null) {
            $id_awal = mysqli_insert_id($koneksi); // hanya simpan id dari transaksi pertama
        }
    }
    // Simpan data struk
    $_SESSION['struk'] = [
        'id_penjualan' => $id_awal,
        'total_bayar' => $total_semua,
        'jumlah_bayar' => $bayar,
        'kembalian' => $kembalian
    ];
    // Kosongkan keranjang
    unset($_SESSION['penjualan']);

    // Arahkan ke cetak struk
    header("Location: cetak_struk.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penjualan</title>
    <script>
        function cekBayar() {
            var total = parseInt(document.getElementById('total_bayar').value);
            var bayar = parseInt(document.getElementById('jumlah_bayar').value);
            if (bayar < total) {
                alert("Uang kurang!");
                return false;
            }
            return true;
        }
        function hapusItem(index) {
            if (confirm("Hapus item ini?")) {
                window.location.href = "penjualan.php?hapus=" + index;
            }
        }
    </script>
</head>
<body>
    <h1 align="center">PENJUALAN</h1>

    <!-- Form Tambah Barang -->
    <form method="post">
        <input type="text" name="kode_barang" placeholder="Kode Barang" required>
        <button type="submit">Tambah</button>
    </form>

    <!-- Tabel Daftar Produk -->
    <table border="1" align="center">
        <tr>
            <th>Kode</th><th>Nama</th><th>Harga</th><th>Jumlah</th><th>Total</th><th>Expired</th><th>Aksi</th>
        </tr>
        <?php 
        $total_harga = 0;
        if (!empty($_SESSION['penjualan'])) {
            foreach ($_SESSION['penjualan'] as $i => $item) {
                echo "<tr>
                    <td>{$item['kode_barang']}</td><td>{$item['nama_produk']}</td>
                    <td>Rp {$item['harga']}</td><td>{$item['jumlah']}</td>
                    <td>Rp {$item['total']}</td><td>{$item['expired']}</td>
                    <td><button onclick='hapusItem($i)'>Hapus</button></td>
                </tr>";
                $total_harga += $item['total'];
            }
        }
        ?>
        <tr><td colspan="4" align="right"><b>Total:</b></td><td><b>Rp <?= $total_harga ?></b></td><td colspan="2"></td></tr>
    </table>

    <!-- Form Pembayaran -->
    <form method="post" action="penjualan.php" onsubmit="return cekBayar()">
        <input type="hidden" id="total_bayar" name="total_bayar" value="<?= $total_harga ?>">
        <input type="number" id="jumlah_bayar" name="jumlah_bayar" placeholder="Masukkan Uang" required>
        <button type="submit" name="bayar">Bayar & Cetak</button>
    </form>

    <br><a href="beranda.php">Kembali</a>
</body>
</html>
