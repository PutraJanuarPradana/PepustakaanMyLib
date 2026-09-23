<?php

require "../../config/auth.php";
require "../../config/koneksi.php";


// Pastikan user
if ($_SESSION['role'] != "user") {

    header("Location: " . BASE_URL . "login.php");
    exit;

}


// Pastikan ID eksemplar ada
if (
    !isset($_GET['eksemplar_id']) ||
    !is_numeric($_GET['eksemplar_id'])
) {

    header("Location: " . BASE_URL . "user/buku/index.php");
    exit;

}


$eksemplar_id = (int) $_GET['eksemplar_id'];


// Ambil data buku
$query = mysqli_query($koneksi, "

    SELECT

        eksemplar_buku.id AS eksemplar_id,

        eksemplar_buku.kode_buku,

        eksemplar_buku.status,

        judul_buku.id AS judul_id,

        judul_buku.judul,

        judul_buku.penulis,

        judul_buku.cover

    FROM eksemplar_buku

    INNER JOIN judul_buku
        ON eksemplar_buku.judul_id = judul_buku.id

    WHERE eksemplar_buku.id = $eksemplar_id

");


if (mysqli_num_rows($query) == 0) {

    header("Location: " . BASE_URL . "user/buku/index.php");
    exit;

}


$buku = mysqli_fetch_assoc($query);


// Pastikan masih tersedia
if ($buku['status'] != "Tersedia") {

    echo "

    <script>

        alert('Buku ini sudah tidak tersedia.');

        window.location.href =
        '".BASE_URL."user/buku/detail.php?id=".$buku['judul_id']."';

    </script>

    ";

    exit;

}


// Proses reservasi
if (isset($_POST['reservasi'])) {

    $user_id = $_SESSION['id'];

    $eksemplar_id = (int) $_POST['eksemplar_id'];



    // Cek apakah user sudah memiliki reservasi aktif
    $cek = mysqli_query($koneksi, "

        SELECT id

        FROM reservasi

        WHERE user_id = $user_id

        AND eksemplar_id = $eksemplar_id

        AND status IN (
            'Diajukan',
            'Disetujui'
        )

    ");



    if (mysqli_num_rows($cek) > 0) {

        echo "

        <script>

            alert('Anda sudah memiliki reservasi untuk buku ini.');

            window.location.href =
            '".BASE_URL."user/reservasi/index.php';

        </script>

        ";

        exit;

    }



    // Pastikan buku masih tersedia
    $cek_buku = mysqli_query($koneksi, "

        SELECT status

        FROM eksemplar_buku

        WHERE id = $eksemplar_id

    ");


    $status_buku = mysqli_fetch_assoc($cek_buku);


    if (!$status_buku || $status_buku['status'] != "Tersedia") {

        echo "

        <script>

            alert('Buku sudah tidak tersedia.');

            window.location.href =
            '".BASE_URL."user/buku/detail.php?id=".$buku['judul_id']."';

        </script>

        ";

        exit;

    }



    // Simpan reservasi
    mysqli_query($koneksi, "

        INSERT INTO reservasi

        (
            user_id,
            eksemplar_id,
            tanggal_reservasi,
            status
        )

        VALUES

        (
            $user_id,
            $eksemplar_id,
            CURDATE(),
            'Diajukan'
        )

    ");



    echo "

    <script>

        alert(
            'Reservasi berhasil diajukan!\\n\\n' +
            'Silakan datang ke perpustakaan untuk ' +
            'meminta persetujuan dan mengambil buku pinjaman.'
        );

        window.location.href =
        '".BASE_URL."user/reservasi/index.php';

    </script>

    ";

    exit;

}


include "../../templates/header.php";

?>

<?php include "../../templates/sidebar_user.php"; ?>


<div class="main">

<?php include "../../templates/navbar.php"; ?>


<div class="container mt-4">


<a
    href="<?= BASE_URL ?>user/buku/detail.php?id=<?= $buku['judul_id']; ?>"
    class="btn btn-secondary mb-4"
>

    ← Kembali

</a>



<div class="card shadow-sm">


<div class="card-header">

    <h4 class="mb-0">

        Konfirmasi Reservasi

    </h4>

</div>



<div class="card-body">


<div class="row align-items-center">


<!-- COVER -->

<div class="col-md-4 text-center mb-3">


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

    class="img-fluid rounded"

    style="max-height:350px;"

>

<?php

} else {

?>

<img

    src="<?= BASE_URL ?>assets/upload/cover/default.png"

    class="img-fluid rounded"

    style="max-height:350px;"

>

<?php

}

?>

</div>



<!-- INFORMASI -->

<div class="col-md-8">


<h3>

<?= htmlspecialchars($buku['judul']); ?>

</h3>



<p>

<strong>Penulis:</strong>

<?= htmlspecialchars($buku['penulis']); ?>

</p>



<p>

<strong>Kode Buku:</strong>

<span class="badge bg-primary">

<?= htmlspecialchars($buku['kode_buku']); ?>

</span>

</p>



<p>

<strong>Status:</strong>

<span class="badge bg-success">

Tersedia

</span>

</p>



<hr>



<div class="alert alert-info">

<strong>Informasi Reservasi</strong>

<br><br>

Setelah reservasi diajukan, silakan datang ke
perpustakaan untuk meminta persetujuan dari
petugas dan mendapatkan buku pinjaman.

</div>



<form method="POST">


<input

    type="hidden"

    name="eksemplar_id"

    value="<?= $buku['eksemplar_id']; ?>"

>


<button

    type="submit"

    name="reservasi"

    class="btn btn-primary"

>

    Konfirmasi Reservasi

</button>



<a

    href="<?= BASE_URL ?>user/buku/detail.php?id=<?= $buku['judul_id']; ?>"

    class="btn btn-secondary"

>

    Batal

</a>


</form>


</div>


</div>


</div>


</div>


</div>


<?php include "../../templates/footer.php"; ?>