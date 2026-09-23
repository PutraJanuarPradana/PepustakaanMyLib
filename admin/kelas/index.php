<?php

require "../../config/auth.php";
require "../../config/koneksi.php";


if($_SESSION['role']!="admin"){

    header("Location: ".BASE_URL."login.php");
    exit;

}


$data = mysqli_query($koneksi,"
SELECT *
FROM kelas
ORDER BY id DESC
");


include "../../templates/header.php";

?>


<?php include "../../templates/sidebar_admin.php"; ?>


<div class="main">


<?php include "../../templates/navbar.php"; ?>


<div class="container mt-4">


<div class="card shadow">


<div class="card-header bg-primary text-white d-flex justify-content-between">

<h4 class="mb-0">

Data Kelas

</h4>


<a href="tambah.php" class="btn btn-light">

+ Tambah Kelas

</a>


</div>



<div class="card-body">


<table class="table table-bordered table-striped">


<thead>

<tr>

<th>No</th>

<th>Nama Kelas</th>

<th>Dibuat</th>

<th>Aksi</th>

</tr>

</thead>


<tbody>


<?php

$no=1;


while($d=mysqli_fetch_assoc($data)){


?>


<tr>


<td>

<?= $no++; ?>

</td>


<td>

<?= htmlspecialchars($d['nama_kelas']); ?>

</td>


<td>

<?= $d['created_at']; ?>

</td>


<td>


<a href="edit.php?id=<?= $d['id']; ?>"

class="btn btn-warning btn-sm">

Edit

</a>



<a href="hapus.php?id=<?= $d['id']; ?>"

class="btn btn-danger btn-sm"

onclick="return confirm('Hapus kelas ini?')">

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



<?php include "../../templates/footer.php"; ?>