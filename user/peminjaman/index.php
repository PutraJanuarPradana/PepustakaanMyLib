<?php

require "../../config/auth.php";
require "../../config/koneksi.php";


/* =========================
   CEK USER
========================= */

if ($_SESSION['role'] != "user") {

    header("Location: " . BASE_URL . "login.php");
    exit;

}


$user_id = (int) $_SESSION['id'];


/* =========================
   AMBIL DATA PEMINJAMAN
========================= */

$query = mysqli_query($koneksi, "

    SELECT

        riwayat_peminjaman.id,
        riwayat_peminjaman.tanggal_pinjam,
        riwayat_peminjaman.lama_pinjam,
        riwayat_peminjaman.batas_kembali,
        riwayat_peminjaman.tanggal_kembali,
        riwayat_peminjaman.status,

        judul_buku.judul,
        judul_buku.cover,

        eksemplar_buku.kode_buku

    FROM riwayat_peminjaman

    INNER JOIN eksemplar_buku
        ON riwayat_peminjaman.eksemplar_id = eksemplar_buku.id

    INNER JOIN judul_buku
        ON eksemplar_buku.judul_id = judul_buku.id

    WHERE riwayat_peminjaman.user_id = $user_id

    ORDER BY riwayat_peminjaman.id DESC

");


include "../../templates/header.php";

?>

<?php include "../../templates/sidebar_user.php"; ?>


<div class="main">

<?php include "../../templates/navbar.php"; ?>


<div class="container mt-4">


<div class="mb-4">

    <h3 class="mb-1">

        Peminjaman Saya

    </h3>

    <p class="text-muted">

        Daftar buku yang sedang dan pernah Anda pinjam.

    </p>

</div>



<?php if (mysqli_num_rows($query) > 0) { ?>


<div class="row">


<?php

while ($r = mysqli_fetch_assoc($query)) {


    /* =========================
       HITUNG SISA HARI
    ========================= */

    $hari_ini = new DateTime(
        date('Y-m-d')
    );


    $batas = new DateTime(
        $r['batas_kembali']
    );


    $selisih =
        (int) $hari_ini
        ->diff($batas)
        ->format('%r%a');


?>


<div class="col-md-6 col-lg-4 mb-4">


<div class="card shadow-sm h-100">


<!-- COVER -->

<?php

if (
    !empty($r['cover']) &&
    file_exists(
        "../../assets/upload/cover/"
        . $r['cover']
    )
) {

?>

<img
    src="<?= BASE_URL ?>assets/upload/cover/<?= htmlspecialchars($r['cover']); ?>"
    class="book-cover-peminjaman"
    alt="<?= htmlspecialchars($r['judul']); ?>"
>

<?php

} else {

?>

<img

    src="<?= BASE_URL ?>assets/upload/cover/default.png"

    class="card-img-top"

    style="
        height:260px;
        object-fit:cover;
    "

>

<?php

}

?>


<div class="card-body">


<h5 class="card-title">

    <?= htmlspecialchars($r['judul']); ?>

</h5>


<p class="mb-1">

    <strong>Kode Buku:</strong>

    <?= htmlspecialchars($r['kode_buku']); ?>

</p>


<p class="mb-1">

    <strong>Tanggal Pinjam:</strong>

    <?= date(
        'd-m-Y',
        strtotime($r['tanggal_pinjam'])
    ); ?>

</p>


<p class="mb-1">

    <strong>Lama Pinjam:</strong>

    <?= (int) $r['lama_pinjam']; ?> hari

</p>


<p class="mb-3">

    <strong>Batas Kembali:</strong>

    <?= date(
        'd-m-Y',
        strtotime($r['batas_kembali'])
    ); ?>

</p>



<?php if ($r['status'] == "Dipinjam") { ?>


<?php if ($selisih > 0) { ?>

<div class="alert alert-success text-center">

    <strong>

        🟢 Sisa <?= $selisih; ?> hari

    </strong>

</div>


<?php } elseif ($selisih == 0) { ?>

<div class="alert alert-warning text-center">

    <strong>

        ⚠️ Hari terakhir pengembalian

    </strong>

</div>


<?php } else { ?>

<div class="alert alert-danger text-center">

    <strong>

        🔴 Terlambat <?= abs($selisih); ?> hari

    </strong>

</div>

<?php } ?>


<div class="text-center">

<span class="badge bg-primary">

    Sedang Dipinjam

</span>

</div>


<?php } else { ?>


<div class="alert alert-secondary text-center">

    <strong>

        Buku sudah dikembalikan

    </strong>

    <?php if (!empty($r['tanggal_kembali'])) { ?>

    <br>

    <small>

        Dikembalikan:

        <?= date(
            'd-m-Y',
            strtotime($r['tanggal_kembali'])
        ); ?>

    </small>

    <?php } ?>

</div>


<div class="text-center">

<span class="badge bg-success">

    Selesai

</span>

</div>


<?php } ?>


</div>

</div>


</div>


<?php

}

?>


</div>


<?php } else { ?>


<div class="card shadow-sm">


<div class="card-body text-center py-5">


<h5>

    Belum Ada Peminjaman

</h5>


<p class="text-muted">

    Anda belum memiliki riwayat peminjaman buku.

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