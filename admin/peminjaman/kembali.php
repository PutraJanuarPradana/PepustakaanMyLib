<?php

require "../../config/auth.php";
require "../../config/koneksi.php";


if($_SESSION['role']!="admin"){

    header("Location: ".BASE_URL."login.php");
    exit;

}



if(!isset($_GET['id'])){

    header("Location:index.php");
    exit;

}


$id = (int)$_GET['id'];



// Ambil data peminjaman

$data = mysqli_query($koneksi,"

SELECT *

FROM riwayat_peminjaman

WHERE id='$id'

");



if(mysqli_num_rows($data)==0){

    header("Location:index.php");
    exit;

}



$peminjaman = mysqli_fetch_assoc($data);



$eksemplar_id = $peminjaman['eksemplar_id'];



// update riwayat peminjaman

mysqli_query($koneksi,"

UPDATE riwayat_peminjaman

SET

tanggal_kembali=CURDATE(),

status='Selesai'

WHERE id='$id'

");



// kembalikan status buku

mysqli_query($koneksi,"

UPDATE eksemplar_buku

SET status='Tersedia'

WHERE id='$eksemplar_id'

");



header("Location:index.php");

exit;


?>