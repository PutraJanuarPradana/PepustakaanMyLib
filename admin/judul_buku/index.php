<?php

require "../../config/auth.php";
require "../../config/koneksi.php";

if ($_SESSION['role'] != "admin") {
    header("Location: " . BASE_URL . "login.php");
    exit;
}

$cari = "";

if (isset($_GET['cari'])) {
    $cari = mysqli_real_escape_string($koneksi, trim($_GET['cari']));
}

$query = "
SELECT *
FROM judul_buku
WHERE judul LIKE '%$cari%'
OR penulis LIKE '%$cari%'
OR penerbit LIKE '%$cari%'
ORDER BY id DESC
";

$data = mysqli_query($koneksi, $query);

include "../../templates/header.php";

?>

<?php include "../../templates/sidebar_admin.php"; ?>

<div class="main">

<?php include "../../templates/navbar.php"; ?>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

<h4 class="mb-0">
Data Judul Buku
</h4>

<a href="tambah.php" class="btn btn-light">
+ Tambah Buku
</a>

</div>

<div class="card-body">

<form method="GET" class="mb-3">

<div class="input-group">

<input
type="text"
name="cari"
class="form-control"
placeholder="Cari judul, penulis atau penerbit..."
value="<?= htmlspecialchars($cari); ?>">

<button class="btn btn-primary">
Cari
</button>

<a href="index.php" class="btn btn-secondary">
Reset
</a>

</div>

</form>

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th>No</th>
<th>Cover</th>
<th>Judul</th>
<th>Penulis</th>
<th>Penerbit</th>
<th>Tahun</th>
<th>ISBN</th>
<th>Kategori</th>
<th>Rak</th>
<th>Aksi</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($data)==0){

?>

<tr>

<td colspan="10" class="text-center">

Belum ada data buku.

</td>

</tr>

<?php

}

$no=1;

while($buku=mysqli_fetch_assoc($data)){

?>

<tr>

<td><?= $no++; ?></td>

<td width="90">

<img
src="<?= BASE_URL ?>assets/upload/cover/<?= htmlspecialchars($buku['cover']); ?>"
width="70"
height="100"
style="object-fit:cover;">

</td>

<td><?= htmlspecialchars($buku['judul']); ?></td>

<td><?= htmlspecialchars($buku['penulis']); ?></td>

<td><?= htmlspecialchars($buku['penerbit']); ?></td>

<td><?= htmlspecialchars($buku['tahun']); ?></td>

<td><?= htmlspecialchars($buku['isbn']); ?></td>

<td><?= htmlspecialchars($buku['kategori']); ?></td>

<td><?= htmlspecialchars($buku['rak']); ?></td>

<td width="130">

<a
href="edit.php?id=<?= $buku['id']; ?>"
class="btn btn-warning btn-sm">

Edit

</a>

<a
href="hapus.php?id=<?= $buku['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Hapus buku ini?')">

Hapus

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</div>

<?php include "../../templates/footer.php"; ?>