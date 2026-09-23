<?php

require "../../config/auth.php";
require "../../config/koneksi.php";


if($_SESSION['role']!="admin"){

    header("Location: ".BASE_URL."login.php");
    exit;

}



// ambil semua data peminjaman

$peminjaman = mysqli_query($koneksi,"

SELECT


riwayat_peminjaman.*,

users.nama,

eksemplar_buku.kode_buku,

judul_buku.judul


FROM riwayat_peminjaman



INNER JOIN users

ON riwayat_peminjaman.user_id = users.id



INNER JOIN eksemplar_buku

ON riwayat_peminjaman.eksemplar_id = eksemplar_buku.id



INNER JOIN judul_buku

ON eksemplar_buku.judul_id = judul_buku.id



ORDER BY riwayat_peminjaman.id DESC


");



include "../../templates/header.php";

?>

<?php include "../../templates/sidebar_admin.php"; ?>


<div class="main">


<?php include "../../templates/navbar.php"; ?>


<div class="container mt-4">
<div class="card shadow">


<div class="card-header bg-primary text-white">

<h4 class="mb-0">

Data Peminjaman Buku

</h4>

</div>



<div class="card-body">


<div class="table-responsive">


<table class="table table-bordered table-hover align-middle">


<thead class="table-dark">


<tr>

<th>No</th>

<th>Peminjam</th>

<th>Judul Buku</th>

<th>Kode Buku</th>

<th>Tanggal Pinjam</th>

<th>Lama Pinjam</th>

<th>Batas Kembali</th>

<th>Status</th>

<th>Aksi</th>

</tr>


</thead>



<tbody>


<?php


if(mysqli_num_rows($peminjaman)==0){


?>


<tr>

<td colspan="9" class="text-center">

Belum ada data peminjaman.

</td>

</tr>


<?php

}



$no=1;


while($p=mysqli_fetch_assoc($peminjaman)){


?>


<tr>


<td>

<?= $no++; ?>

</td>


<td>

<?= htmlspecialchars($p['nama']); ?>

</td>


<td>

<?= htmlspecialchars($p['judul']); ?>

</td>


<td>

<?= htmlspecialchars($p['kode_buku']); ?>

</td>


<td>

<?= date(
"d-m-Y",
strtotime($p['tanggal_pinjam'])
); ?>

</td>


<td>

<?= $p['lama_pinjam']; ?> hari

</td>


<td>

<?= date(
"d-m-Y",
strtotime($p['batas_kembali'])
); ?>

</td>


<td>


<?php if($p['status']=="Dipinjam"){ ?>


<span class="badge bg-warning">

Dipinjam

</span>


<?php }else{ ?>


<span class="badge bg-success">

Selesai

</span>


<?php } ?>


</td>



<td>


<?php if($p['status']=="Dipinjam"){ ?>


<a

href="kembali.php?id=<?= $p['id']; ?>"

class="btn btn-success btn-sm"

onclick="return confirm('Konfirmasi pengembalian buku?')">

Kembalikan

</a>


<?php }else{ ?>


<span class="text-muted">

Selesai

</span>


<?php } ?>


</td>


</tr>


<?php } ?>


</tbody>


</table>


</div>


</div>


</div>
<?php include "../../templates/footer.php"; ?>