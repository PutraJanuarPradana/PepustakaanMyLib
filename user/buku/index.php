<?php

require "../../config/auth.php";
require "../../config/koneksi.php";

if ($_SESSION['role'] != "user") {
    header("Location: " . BASE_URL . "login.php");
    exit;
}


/* =========================
   PENCARIAN
========================= */

$keyword = "";

if (isset($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string(
        $koneksi,
        $_GET['keyword']
    );
}


/* =========================
   DATA BUKU
========================= */

$query = mysqli_query($koneksi, "

    SELECT
        judul_buku.*,

        COUNT(eksemplar_buku.id) AS total_eksemplar,

        SUM(
            CASE
                WHEN eksemplar_buku.status = 'Tersedia'
                THEN 1
                ELSE 0
            END
        ) AS tersedia

    FROM judul_buku

    LEFT JOIN eksemplar_buku
        ON judul_buku.id = eksemplar_buku.judul_id

    WHERE
        judul_buku.judul LIKE '%$keyword%'
        OR judul_buku.penulis LIKE '%$keyword%'
        OR judul_buku.penerbit LIKE '%$keyword%'
        OR judul_buku.kategori LIKE '%$keyword%'

    GROUP BY judul_buku.id

    ORDER BY judul_buku.id DESC

");


include "../../templates/header.php";

?>

<?php include "../../templates/sidebar_user.php"; ?>


<div class="main">

<?php include "../../templates/navbar.php"; ?>


<div class="container mt-4">


<!-- JUDUL -->

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="mb-1">
            Daftar Buku
        </h3>

        <p class="text-muted mb-0">
            Cari dan temukan buku yang tersedia di perpustakaan.
        </p>

    </div>

</div>



<!-- PENCARIAN -->

<div class="card shadow-sm mb-4">

    <div class="card-body">

        <form method="GET">

            <div class="input-group">

                <input
                    type="text"
                    name="keyword"
                    class="form-control"
                    placeholder="Cari judul, penulis, penerbit, atau kategori..."
                    value="<?= htmlspecialchars($keyword); ?>"
                >

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Cari
                </button>

            </div>

        </form>

    </div>

</div>



<!-- DAFTAR BUKU -->

<div class="row">


<?php

if (mysqli_num_rows($query) > 0) {

    while ($buku = mysqli_fetch_assoc($query)) {

?>


<div class="col-lg-3 col-md-4 col-sm-6 mb-4">


<div class="card h-100 shadow-sm">


<!-- COVER -->

<div class="text-center p-3">

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
    class="card-img-top"
    style="height:250px; object-fit:cover;"
    alt="<?= htmlspecialchars($buku['judul']); ?>"
>

<?php

} else {

?>

<img
    src="<?= BASE_URL ?>assets/upload/cover/default.png"
    class="card-img-top"
    style="height:250px; object-fit:cover;"
    alt="Cover tidak tersedia"
>

<?php

}

?>

</div>



<div class="card-body d-flex flex-column">


<!-- JUDUL -->

<h5 class="card-title">

<?= htmlspecialchars($buku['judul']); ?>

</h5>



<!-- PENULIS -->

<p class="mb-1">

<strong>Penulis:</strong>

<?= htmlspecialchars($buku['penulis']); ?>

</p>



<!-- KATEGORI -->

<p class="mb-2">

<strong>Kategori:</strong>

<?= htmlspecialchars($buku['kategori']); ?>

</p>



<!-- STATUS -->

<?php

if ($buku['tersedia'] > 0) {

?>

<span class="badge bg-success mb-3">

Tersedia

</span>

<?php

} else {

?>

<span class="badge bg-danger mb-3">

Tidak Tersedia

</span>

<?php

}

?>



<!-- DETAIL -->

<a
    href="detail.php?id=<?= $buku['id']; ?>"
    class="btn btn-primary mt-auto"
>

Lihat Detail

</a>


</div>

</div>


</div>


<?php

    }

} else {

?>

<div class="col-12">

<div class="alert alert-warning">

Buku tidak ditemukan.

</div>

</div>

<?php

}

?>


</div>


</div>


<?php include "../../templates/footer.php"; ?>