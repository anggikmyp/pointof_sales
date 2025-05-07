<?php
$koneksi = new mysqli
($servername = "localhost", $username = "root", $password = "", $database = "pointof_sales");

if($koneksi->connect_error){
  die("gagal koneksi".$koneksi->connect_error);
}


?>

