<?php

require "../../config/auth.php";
require "../../config/koneksi.php";


// Pastikan user
if ($_SESSION['role'] != "user") {

    header("Location: " . BASE_URL . "login.php");
    exit;

}


// Cek ID buku
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    header("Location: index.php");
    exit;

}


$id = (int) $_GET['id'];


// Ambil data judul buku
$query = mysqli_query($koneksi, "

    SELECT *
    FROM judul_buku
    WHERE id = $id

");


if (mysqli_num_rows($query) == 0) {

    header("Location: index.php");
    exit;

}


$buku = mysqli_fetch_assoc($query);


// Ambil semua eksemplar buku
$eksemplar = mysqli_query($koneksi, "

    SELECT *
    FROM eksemplar_buku
    WHERE judul_id = $id
    ORDER BY id ASC

");


include "../../templates/header.php";

?>

<?php include "../../templates/sidebar_user.php"; ?>


<div class="main">

<?php include "../../templates/navbar.php"; ?>


<div class="container mt-4">


<!-- Tombol kembali -->

<a
    href="index.php"
    class="btn btn-secondary mb-4"
>

    ← Kembali ke Daftar Buku

</a>



<div class="card shadow-sm">


<div class="card-body">


<div class="row">


<!-- COVER -->

<div class="col-md-4 text-center mb-4">


<?php

if (
    !empty($buku['cover']) &&
    file_exists(
        "../../assets/upload/cover/" . $buku['cover']
    )
) {

?>

<img
    src="<?= BASE_URL ?>assets/upload/cover/<?= htmlspecialchars($buku['cover']); ?>"
    class="img-fluid rounded shadow-sm"
    style="max-height:450px; object-fit:cover;"
    alt="<?= htmlspecialchars($buku['judul']); ?>"
>

<?php

} else {

?>

<img
    src="<?= BASE_URL ?>assets/upload/cover/default.png"
    class="img-fluid rounded shadow-sm"
    style="max-height:450px; object-fit:cover;"
    alt="Cover tidak tersedia"
>

<?php

}

?>


</div>



<!-- INFORMASI BUKU -->

<div class="col-md-8">


<h2 class="mb-3">

<?= htmlspecialchars($buku['judul']); ?>

</h2>



<p>

<strong>Penulis:</strong><br>

<?= htmlspecialchars($buku['penulis']); ?>

</p>



<p>

<strong>Penerbit:</strong><br>

<?= htmlspecialchars($buku['penerbit']); ?>

</p>



<p>

<strong>Tahun:</strong><br>

<?= htmlspecialchars($buku['tahun']); ?>

</p>



<p>

<strong>ISBN:</strong><br>

<?= htmlspecialchars($buku['isbn']); ?>

</p>



<p>

<strong>Kategori:</strong><br>

<?= htmlspecialchars($buku['kategori']); ?>

</p>



<p>

<strong>Rak:</strong><br>

<?= htmlspecialchars($buku['rak']); ?>

</p>



<hr>



<h5>

Sinopsis

</h5>


<p class="text-muted">

<?= nl2br(
    htmlspecialchars($buku['sinopsis'] ?? '-')
); ?>

</p>


</div>


</div>



<hr>



<!-- EKSEMPLAR -->

<h4 class="mb-3">

Daftar Eksemplar Buku

</h4>



<div class="table-responsive">


<table class="table table-bordered align-middle">


<thead>

<tr>

<th>No</th>

<th>Kode Buku</th>

<th>Status</th>

<th>Aksi</th>

</tr>

</thead>


<tbody>


<?php

$no = 1;

if (mysqli_num_rows($eksemplar) > 0) {

    while ($e = mysqli_fetch_assoc($eksemplar)) {

?>


<tr>


<td>

<?= $no++; ?>

</td>


<td>

<strong>

<?= htmlspecialchars($e['kode_buku']); ?>

</strong>

</td>


<td>


<?php

if ($e['status'] == "Tersedia") {

?>

<span class="badge bg-success">

Tersedia

</span>

<?php

} else {

?>

<span class="badge bg-danger">

<?= htmlspecialchars($e['status']); ?>

</span>

<?php

}

?>

</td>


<td>


<?php

if ($e['status'] == "Tersedia") {

?>

<a
    href="<?= BASE_URL ?>user/reservasi/tambah.php?eksemplar_id=<?= $e['id']; ?>"
    class="btn btn-primary btn-sm"
>

    Reservasi

</a>

<?php

} else {

?>

<span class="text-muted">

Tidak tersedia

</span>

<?php

}

?>


</td>


</tr>


<?php

    }

} else {

?>


<tr>

<td
    colspan="4"
    class="text-center"
>

Belum ada eksemplar buku.

</td>

</tr>


<?php

}

?>


</tbody>

</table>


</div>


</div>

</div>


</div>


<?php include "../../templates/footer.php"; ?>