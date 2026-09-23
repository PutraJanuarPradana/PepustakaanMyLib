<?php

require "../config/auth.php";
require "../config/koneksi.php";

if($_SESSION['role']!="admin"){
    header("Location: ../login.php");
    exit;
}

$totalJudul=mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM judul_buku"));

$totalEksemplar=mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM eksemplar_buku"));

$totalSiswa=mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM users WHERE role='user'"));

$totalKelas=mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM kelas"));

$totalPinjam=mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM riwayat_peminjaman WHERE status='Dipinjam'"));

$totalReservasi=mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM reservasi"));

include "../templates/header.php";

?>

<?php include "../templates/sidebar_admin.php"; ?>

<div class="main">

<?php include "../templates/navbar.php"; ?>

<div class="container mt-4">

<div class="row g-4">

<div class="col-md-4">

<div class="card shadow">

<div class="card-body">

<h6>Total Judul Buku</h6>

<h2><?= $totalJudul ?></h2>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow">

<div class="card-body">

<h6>Total Eksemplar</h6>

<h2><?= $totalEksemplar ?></h2>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow">

<div class="card-body">

<h6>Total Pengguna</h6>

<h2><?= $totalSiswa ?></h2>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow">

<div class="card-body">

<h6>Total Kelas</h6>

<h2><?= $totalKelas ?></h2>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow">

<div class="card-body">

<h6>Total Dipinjam</h6>

<h2><?= $totalPinjam ?></h2>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow">

<div class="card-body">

<h6>Total Reservasi</h6>

<h2><?= $totalReservasi ?></h2>

</div>

</div>

</div>

</div>

</div>

</div>

<?php include "../templates/footer.php"; ?>