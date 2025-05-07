<?php
include 'koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$hasil = [];

if (isset($_GET['tanggal'])) {
    $tanggal = $_GET['tanggal'];

    if (!empty($tanggal)) {
        $query = "SELECT * FROM penjualan 
        WHERE tanggal_penjualan = '$tanggal' ORDER BY id_penjualan ASC";
        $result = mysqli_query($koneksi, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $hasil[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
</head>
<body>
    <h2>Laporan Penjualan</h2>

    <form method="get">
        <label>Tanggal:</label>
        <input type="date" name="tanggal" 
        value="<?= $_GET['tanggal'] ?? date('Y-m-d') ?>">
        <button type="submit">Tampilkan</button>
    </form>

    <?php if (!empty($hasil)) { ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode Barang</th>
                <th>Nama Produk</th>
                <th>Total</th>
                <th>Bayar</th>
                <th>Kembali</th>
            </tr>
            <?php 
            $no = 1; 
            $grand_total = 0;
            foreach ($hasil as $data) { 
                $grand_total += $data['total'];
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $data['tanggal_penjualan'] ?></td>
                    <td><?= htmlspecialchars($data['kode_barang']) ?></td>
                    <td><?= htmlspecialchars($data['nama_produk']) ?></td>
                    <td>Rp <?= number_format($data['total'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($data['bayar'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($data['kembali'], 0, ',', '.') ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="4" align="right"><strong>Total Pendapatan</strong></td>
                <td colspan="3"><strong>
                    Rp <?= number_format($grand_total, 0, ',', '.') ?></strong></td>
            </tr>
        </table>
    <?php } elseif (isset($_GET['tanggal'])) { ?>
        <p><em>Tidak ada data penjualan pada tanggal tersebut.</em></p>
    <?php } ?>
    
    <br><a href="beranda.php">Kembali</a>
</body>
</html>
