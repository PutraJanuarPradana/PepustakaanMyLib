<?php

require "../../config/auth.php";
require "../../config/koneksi.php";

if ($_SESSION['role'] != "admin") {
    header("Location: " . BASE_URL . "login.php");
    exit;
}


/* =========================
   AMBIL SEMUA RESERVASI
========================= */

$query = mysqli_query($koneksi, "

    SELECT

        reservasi.id,
        reservasi.tanggal_reservasi,
        reservasi.status,
        reservasi.alasan_penolakan,

        users.nama,
        users.nis,

        judul_buku.judul,
        judul_buku.cover,

        eksemplar_buku.kode_buku

    FROM reservasi

    INNER JOIN users
        ON reservasi.user_id = users.id

    INNER JOIN eksemplar_buku
        ON reservasi.eksemplar_id = eksemplar_buku.id

    INNER JOIN judul_buku
        ON eksemplar_buku.judul_id = judul_buku.id

    ORDER BY reservasi.id DESC

");


include "../../templates/header.php";

?>

<?php include "../../templates/sidebar_admin.php"; ?>


<div class="main">

<?php include "../../templates/navbar.php"; ?>


<div class="container mt-4">


<div class="mb-4">

    <h3>
        Kelola Reservasi
    </h3>

    <p class="text-muted">
        Kelola permintaan reservasi buku dari pengguna.
    </p>

</div>


<div class="card shadow-sm">


<div class="card-body">


<div class="table-responsive">


<table class="table table-bordered table-hover align-middle">


<thead class="table-light">

<tr>

    <th>No</th>
    <th>Pengguna</th>
    <th>Buku</th>
    <th>Kode Buku</th>
    <th>Tanggal</th>
    <th>Status</th>
    <th>Aksi</th>

</tr>

</thead>


<tbody>


<?php

$no = 1;

if (mysqli_num_rows($query) > 0) {

    while ($r = mysqli_fetch_assoc($query)) {

?>


<tr>


<td>
    <?= $no++; ?>
</td>


<td>

<strong>
    <?= htmlspecialchars($r['nama']); ?>
</strong>

<br>

<small class="text-muted">

    NIS:
    <?= htmlspecialchars($r['nis'] ?? '-'); ?>

</small>

</td>


<td>

    <?= htmlspecialchars($r['judul']); ?>

</td>


<td>

<span class="badge bg-primary">

    <?= htmlspecialchars($r['kode_buku']); ?>

</span>

</td>


<td>

    <?= date(
        'd-m-Y',
        strtotime($r['tanggal_reservasi'])
    ); ?>

</td>


<td>


<?php

if ($r['status'] == "Diajukan") {

?>

<span class="badge bg-warning text-dark">

    Diajukan

</span>

<?php

} elseif ($r['status'] == "Disetujui") {

?>

<span class="badge bg-success">

    Disetujui

</span>

<?php

} elseif ($r['status'] == "Ditolak") {

?>

<span class="badge bg-danger">

    Ditolak

</span>

<?php

} elseif ($r['status'] == "Selesai") {

?>

<span class="badge bg-secondary">

    Selesai

</span>

<?php

}

?>


</td>


<td>


<?php

if ($r['status'] == "Diajukan") {

?>


<a
    href="setujui.php?id=<?= $r['id']; ?>"
    class="btn btn-success btn-sm"
>

    Setujui

</a>


<a
    href="tolak.php?id=<?= $r['id']; ?>"
    class="btn btn-danger btn-sm"
>

    Tolak

</a>


<?php

} elseif ($r['status'] == "Ditolak") {

?>


<div class="text-danger">

<strong>
    Alasan:
</strong>

<br>

<?= nl2br(
    htmlspecialchars(
        $r['alasan_penolakan'] ?? '-'
    )
); ?>

</div>


<?php

} elseif ($r['status'] == "Disetujui") {

?>


<span class="text-success">

    Sudah disetujui

</span>


<?php

} else {

?>


<span class="text-muted">

    Selesai

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
    colspan="7"
    class="text-center text-muted"
>

    Belum ada reservasi.

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