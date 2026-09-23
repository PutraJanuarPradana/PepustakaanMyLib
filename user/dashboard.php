<?php

require "../config/auth.php";
require "../config/koneksi.php";

if($_SESSION['role']!="user"){
    header("Location: ../login.php");
    exit;
}

$bukuTersedia=mysqli_num_rows(mysqli_query($koneksi,"
SELECT * FROM eksemplar_buku
WHERE status='Tersedia'
"));

$bukuDipinjam=mysqli_num_rows(mysqli_query($koneksi,"
SELECT * FROM riwayat_peminjaman
WHERE user_id='".$_SESSION['id']."'
AND status='Dipinjam'
"));

$reservasi=mysqli_num_rows(mysqli_query($koneksi,"
SELECT * FROM reservasi
WHERE user_id='".$_SESSION['id']."'
AND status='Menunggu'
"));

include "../templates/header.php";

?>

<?php include "../templates/sidebar_user.php"; ?>

<div class="main">

<?php include "../templates/navbar.php"; ?>

<div class="container mt-4">

<div class="row">

<div class="col-md-4">

<div class="card shadow">

<div class="card-body">

<h6>Buku Tersedia</h6>

<h2><?= $bukuTersedia ?></h2>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow">

<div class="card-body">

<h6>Buku Dipinjam</h6>

<h2><?= $bukuDipinjam ?></h2>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow">

<div class="card-body">

<h6>Status Reservasi</h6>

<h2><?= $reservasi ?></h2>

</div>

</div>

</div>

</div>

<div class="card mt-4 shadow">

<div class="card-body">

<h4>Selamat Datang</h4>

<p>

Selamat datang di Website Perpustakaan Final.

Silakan gunakan menu di sebelah kiri untuk melakukan reservasi buku,
melihat peminjaman, dan mengelola profil Anda.

</p>

</div>

</div>

</div>

</div>

<?php include "../templates/footer.php"; ?>