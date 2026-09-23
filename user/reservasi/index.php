<?php

require "../../config/auth.php";
require "../../config/koneksi.php";

if ($_SESSION['role'] != "user") {

    header("Location: " . BASE_URL . "login.php");
    exit;

}


$user_id = $_SESSION['id'];


/* =========================
   AMBIL RESERVASI USER
========================= */

$query = mysqli_query($koneksi, "

    SELECT

        reservasi.id,
        reservasi.tanggal_reservasi,
        reservasi.status,
        reservasi.alasan_penolakan,

        judul_buku.judul,
        judul_buku.cover,

        eksemplar_buku.kode_buku

    FROM reservasi

    INNER JOIN eksemplar_buku
        ON reservasi.eksemplar_id = eksemplar_buku.id

    INNER JOIN judul_buku
        ON eksemplar_buku.judul_id = judul_buku.id

    WHERE reservasi.user_id = $user_id

    ORDER BY reservasi.id DESC

");


include "../../templates/header.php";

?>

<?php include "../../templates/sidebar_user.php"; ?>


<div class="main">

<?php include "../../templates/navbar.php"; ?>


<div class="container mt-4">


<div class="d-flex justify-content-between align-items-center mb-4">


<div>

    <h3 class="mb-1">
        Reservasi Saya
    </h3>

    <p class="text-muted mb-0">

        Daftar buku yang sedang atau pernah Anda reservasi.

    </p>

</div>


<a
    href="<?= BASE_URL ?>user/buku/index.php"
    class="btn btn-primary"
>

    + Cari Buku

</a>


</div>



<?php if (mysqli_num_rows($query) > 0) { ?>


<div class="table-responsive">


<table class="table table-bordered table-hover align-middle">


<thead class="table-light">

<tr>

    <th>No</th>
    <th>Buku</th>
    <th>Kode Buku</th>
    <th>Tanggal Reservasi</th>
    <th>Status</th>
    <th>Keterangan</th>

</tr>

</thead>


<tbody>


<?php

$no = 1;

while ($r = mysqli_fetch_assoc($query)) {

?>


<tr>


<td>

    <?= $no++; ?>

</td>


<td>


<div class="d-flex align-items-center">


<?php

if (
    !empty($r['cover']) &&
    file_exists(
        "../../assets/upload/cover/" . $r['cover']
    )
) {

?>

<img

    src="<?= BASE_URL ?>assets/upload/cover/<?= htmlspecialchars($r['cover']); ?>"

    style="
        width:60px;
        height:80px;
        object-fit:cover;
        border-radius:5px;
        margin-right:12px;
    "

>

<?php

} else {

?>

<img

    src="<?= BASE_URL ?>assets/upload/cover/default.png"

    style="
        width:60px;
        height:80px;
        object-fit:cover;
        border-radius:5px;
        margin-right:12px;
    "

>

<?php

}

?>


<strong>

    <?= htmlspecialchars($r['judul']); ?>

</strong>


</div>


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

    Menunggu Persetujuan

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

<div class="alert alert-info mb-0">

    Silakan datang ke perpustakaan setelah
    mendapat persetujuan dari admin.

</div>

<?php

} elseif ($r['status'] == "Disetujui") {

?>

<div class="alert alert-success mb-0">

    <strong>
        Reservasi disetujui.
    </strong>

    <br>

    Silakan datang ke perpustakaan untuk
    menerima buku pinjaman.

</div>

<?php

} elseif ($r['status'] == "Ditolak") {

?>

<div class="alert alert-danger mb-0">

    <strong>
        Reservasi ditolak.
    </strong>

    <br><br>

    <strong>
        Alasan Penolakan:
    </strong>

    <br>

    <?php

    if (!empty($r['alasan_penolakan'])) {

        echo nl2br(
            htmlspecialchars(
                $r['alasan_penolakan']
            )
        );

    } else {

        echo "Admin tidak memberikan alasan.";

    }

    ?>

</div>

<?php

} elseif ($r['status'] == "Selesai") {

?>

<div class="text-muted">

    Reservasi telah selesai.

</div>

<?php

}

?>


</td>


</tr>


<?php

}

?>


</tbody>

</table>


</div>


<?php } else { ?>


<div class="card shadow-sm">


<div class="card-body text-center py-5">


<h5>

    Belum Ada Reservasi

</h5>


<p class="text-muted">

    Anda belum melakukan reservasi buku.

</p>


<a
    href="<?= BASE_URL ?>user/buku/index.php"
    class="btn btn-primary"
>

    Lihat Daftar Buku

</a>


</div>

</div>


<?php } ?>


</div>


<?php include "../../templates/footer.php"; ?>