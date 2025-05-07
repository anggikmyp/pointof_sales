<?php
session_start();
require('fpdf/fpdf.php');
include 'koneksi.php';

if (!isset($_SESSION['struk'])) {
    die('Data struk tidak tersedia.');
}

$id_penjualan = $_SESSION['struk']['id_penjualan'];
$total_bayar = $_SESSION['struk']['total_bayar'];
$jumlah_bayar = $_SESSION['struk']['jumlah_bayar'];
$kembalian = $_SESSION['struk']['kembalian'];

// Ambil semua data penjualan dari id_penjualan pertama (karena satu transaksi bisa beberapa produk)
$query = mysqli_query($koneksi, "SELECT * FROM penjualan WHERE id_penjualan >= '$id_penjualan'");
$data = [];
$tanggal = '';

while ($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
    $tanggal = $row['tanggal_penjualan'];
}

// Mulai cetak PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Struk Penjualan',0,1,'C');
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,'Tanggal: ' . $tanggal,0,1);
$pdf->Ln(5);

// Header tabel
$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,8,'Kode',1);
$pdf->Cell(50,8,'Nama Produk',1);
$pdf->Cell(25,8,'Harga',1);
$pdf->Cell(20,8,'Jml',1);
$pdf->Cell(30,8,'Total',1);
$pdf->Ln();

// Isi data
$pdf->SetFont('Arial','',11);
foreach ($data as $item) {
    $pdf->Cell(40,8,$item['kode_barang'],1);
    $pdf->Cell(50,8,$item['nama_produk'],1);
    $pdf->Cell(25,8,'Rp '.number_format($item['harga']),1);
    $pdf->Cell(20,8,$item['jumlah'],1);
    $pdf->Cell(30,8,'Rp '.number_format($item['total']),1);
    $pdf->Ln();
}

// Footer pembayaran
$pdf->Ln(5);
$pdf->Cell(0,8,'Total Bayar: Rp '.number_format($total_bayar),0,1);
$pdf->Cell(0,8,'Uang Diberikan: Rp '.number_format($jumlah_bayar),0,1);
$pdf->Cell(0,8,'Kembalian: Rp '.number_format($kembalian),0,1);

$pdf->Output();
exit;
