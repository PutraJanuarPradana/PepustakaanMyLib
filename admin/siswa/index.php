<?php
require "../../config/auth.php";
require "../../config/koneksi.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: ../../login.php");
    exit;
}

$keyword = "";

if (isset($_GET['search'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_GET['search']);

    $query = mysqli_query($koneksi,"
        SELECT users.*, kelas.nama_kelas
        FROM users
        LEFT JOIN kelas
        ON users.kelas_id=kelas.id
        WHERE role='user'
        AND (
            users.nama LIKE '%$keyword%'
            OR users.username LIKE '%$keyword%'
            OR users.nis LIKE '%$keyword%'
            OR kelas.nama_kelas LIKE '%$keyword%'
        )
        ORDER BY users.nama ASC
    ");

} else {

    $query = mysqli_query($koneksi,"
        SELECT users.*, kelas.nama_kelas
        FROM users
        LEFT JOIN kelas
        ON users.kelas_id=kelas.id
        WHERE role='user'
        ORDER BY users.nama ASC
    ");

}

include "../../templates/header.php";
?>

<?php include "../../templates/sidebar_admin.php"; ?>

<div class="main">

<?php include "../../templates/navbar.php"; ?>

<div class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-3">

<h3>Data Pengguna</h3>

<a href="tambah.php" class="btn btn-primary">
Tambah Pengguna
</a>

</div>

<form method="GET">

<div class="input-group mb-3">

<input
type="text"
class="form-control"
name="search"
placeholder="Cari nama, username, NIS atau kelas..."
value="<?= htmlspecialchars($keyword) ?>">

<button class="btn btn-primary">
Cari
</button>

<a href="index.php" class="btn btn-secondary">
Reset
</a>

</div>

</form>

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-primary">

<tr>

<th width="60">No</th>

<th>Foto</th>

<th>Nama</th>

<th>Username</th>

<th>NIS</th>

<th>Kelas</th>

<th>Status</th>

<th width="180">Aksi</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

while($d=mysqli_fetch_assoc($query)){

?>

<tr>

<td><?= $no++ ?></td>

<td width="80">

<img
src="../../assets/upload/profile/<?= $d['foto']; ?>"
width="55"
height="55"
style="border-radius:50%;object-fit:cover;">

</td>

<td><?= htmlspecialchars($d['nama']); ?></td>

<td><?= htmlspecialchars($d['username']); ?></td>

<td>
<?= $d['nis'] ? htmlspecialchars($d['nis']) : "-" ?>
</td>

<td>
<?= !empty($d['nama_kelas']) 
? htmlspecialchars($d['nama_kelas']) 
: "-" ?>
</td>

<td><?= htmlspecialchars($d['status']); ?></td>

<td>

<a
href="edit.php?id=<?= $d['id']; ?>"
class="btn btn-warning btn-sm">

Edit

</a>

<a
href="hapus.php?id=<?= $d['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Hapus siswa ini?')">

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

<?php include "../../templates/footer.php"; ?>