<?php

require "../../config/auth.php";
require "../../config/koneksi.php";

if ($_SESSION['role'] != "admin") {
    header("Location: " . BASE_URL . "login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

/* Ambil data reservasi */
$query = mysqli_query($koneksi, "

    SELECT
        reservasi.*,
        users.nama,
        judul_buku.judul,
        eksemplar_buku.kode_buku

    FROM reservasi

    INNER JOIN users
        ON reservasi.user_id = users.id

    INNER JOIN eksemplar_buku
        ON reservasi.eksemplar_id = eksemplar_buku.id

    INNER JOIN judul_buku
        ON eksemplar_buku.judul_id = judul_buku.id

    WHERE reservasi.id = $id

");

if (mysqli_num_rows($query) == 0) {
    header("Location: index.php");
    exit;
}

$r = mysqli_fetch_assoc($query);

/* Hanya reservasi Diajukan yang boleh ditolak */
if ($r['status'] != "Diajukan") {
    header("Location: index.php");
    exit;
}

$error = "";


/* =========================
   PROSES PENOLAKAN
========================= */

if (isset($_POST['tolak'])) {

    $alasan = trim($_POST['alasan_penolakan']);

    if ($alasan == "") {

        $error = "Alasan penolakan wajib diisi.";

    } else {

        $alasan = mysqli_real_escape_string(
            $koneksi,
            $alasan
        );

        $update = mysqli_query($koneksi, "

            UPDATE reservasi

            SET
                status = 'Ditolak',
                alasan_penolakan = '$alasan'

            WHERE id = $id

        ");

        if (!$update) {

            $error = "Gagal menyimpan alasan penolakan: "
                . mysqli_error($koneksi);

        } else {

            echo "

            <script>

                alert('Reservasi berhasil ditolak.');

                window.location.href = 'index.php';

            </script>

            ";

            exit;
        }
    }
}


include "../../templates/header.php";

?>

<?php include "../../templates/sidebar_admin.php"; ?>


<div class="main">

<?php include "../../templates/navbar.php"; ?>


<div class="container mt-4">


<div class="card shadow-sm">


<div class="card-header bg-danger text-white">

    <h4 class="mb-0">
        Tolak Reservasi
    </h4>

</div>


<div class="card-body">


<div class="mb-4">

    <h5>
        <?= htmlspecialchars($r['judul']); ?>
    </h5>

    <p class="mb-1">

        Pengguna:

        <strong>
            <?= htmlspecialchars($r['nama']); ?>
        </strong>

    </p>

    <p>

        Kode Buku:

        <strong>
            <?= htmlspecialchars($r['kode_buku']); ?>
        </strong>

    </p>

</div>


<?php if ($error != "") { ?>

<div class="alert alert-danger">

    <?= htmlspecialchars($error); ?>

</div>

<?php } ?>


<form method="POST">


<div class="mb-3">

    <label class="form-label">

        Alasan Penolakan

    </label>

    <textarea
        name="alasan_penolakan"
        class="form-control"
        rows="5"
        placeholder="Contoh: Buku sedang digunakan untuk kegiatan sekolah."
        required
    ></textarea>

</div>


<button
    type="submit"
    name="tolak"
    class="btn btn-danger"
>

    Tolak Reservasi

</button>


<a
    href="index.php"
    class="btn btn-secondary"
>

    Batal

</a>


</form>


</div>

</div>


</div>


<?php include "../../templates/footer.php"; ?>