<?php

require "../../config/auth.php";
require "../../config/koneksi.php";

if($_SESSION['role']!="admin"){
    header("Location: ".BASE_URL."login.php");
    exit;
}

$cari = "";

if(isset($_GET['cari'])){
    $cari = mysqli_real_escape_string(
        $koneksi,
        trim($_GET['cari'])
    );
}

$query = "

SELECT

eksemplar_buku.*,

judul_buku.judul

FROM eksemplar_buku

INNER JOIN judul_buku

ON eksemplar_buku.judul_id=judul_buku.id

WHERE

eksemplar_buku.kode_buku LIKE '%$cari%'

OR

judul_buku.judul LIKE '%$cari%'

ORDER BY eksemplar_buku.id DESC

";

$data = mysqli_query($koneksi,$query);

include "../../templates/header.php";

?>

<?php include "../../templates/sidebar_admin.php"; ?>

<div class="main">

<?php include "../../templates/navbar.php"; ?>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

<h4 class="mb-0">

Data Eksemplar Buku

</h4>

<a
href="tambah.php"
class="btn btn-light">

+ Tambah Eksemplar

</a>

</div>

<div class="card-body">

<form method="GET" class="mb-3">

<div class="input-group">

<input

type="text"

name="cari"

class="form-control"

placeholder="Cari kode buku atau judul..."

value="<?= htmlspecialchars($cari); ?>">

<button class="btn btn-primary">

Cari

</button>

<a
href="index.php"
class="btn btn-secondary">

Reset

</a>

</div>

</form>

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th>No</th>

<th>Kode Buku</th>

<th>Judul Buku</th>

<th>Status</th>

<th>Aksi</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($data)==0){

?>

<tr>

<td colspan="5" class="text-center">

Belum ada data eksemplar buku.

</td>

</tr>

<?php

}

$no=1;

while($d=mysqli_fetch_assoc($data)){

$status = $d['status'];

if($status=="Tersedia"){
    $badge="success";
}elseif($status=="Dipinjam"){
    $badge="warning";
}elseif($status=="Rusak"){
    $badge="danger";
}else{
    $badge="secondary";
}

?>

<tr>

<td><?= $no++; ?></td>

<td><?= htmlspecialchars($d['kode_buku']); ?></td>

<td><?= htmlspecialchars($d['judul']); ?></td>

<td>

<span class="badge bg-<?= $badge; ?>">

<?= htmlspecialchars($status); ?>

</span>

</td>

<td width="140">

<a
href="edit.php?id=<?= $d['id']; ?>"
class="btn btn-warning btn-sm">

Edit

</a>

<a
href="hapus.php?id=<?= $d['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Hapus eksemplar ini?')">

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