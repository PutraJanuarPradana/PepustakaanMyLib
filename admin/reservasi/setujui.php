<?php

require "../../config/auth.php";
require "../../config/koneksi.php";


/* =========================
   CEK ADMIN
========================= */

if ($_SESSION['role'] != "admin") {

    header("Location: " . BASE_URL . "login.php");
    exit;

}


/* =========================
   CEK ID RESERVASI
========================= */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    header("Location: index.php");
    exit;

}

$id = (int) $_GET['id'];


/* =========================
   AMBIL DATA RESERVASI
========================= */

$query = mysqli_query($koneksi, "

    SELECT

        reservasi.*,

        users.nama,
        users.nis,

        judul_buku.judul,

        eksemplar_buku.kode_buku,
        eksemplar_buku.status AS status_buku

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


/* =========================
   HANYA RESERVASI DIAJUKAN
========================= */

if ($r['status'] != "Diajukan") {

    header("Location: index.php");
    exit;

}


$error = "";


/* =========================
   PROSES PERSETUJUAN
========================= */

if (isset($_POST['setujui'])) {


    $tanggal_pinjam = $_POST['tanggal_pinjam'];

    $lama_pinjam = (int) $_POST['lama_pinjam'];


    /* =========================
       VALIDASI
    ========================= */

    if (empty($tanggal_pinjam)) {

        $error = "Tanggal pinjam harus diisi.";

    } elseif ($lama_pinjam <= 0) {

        $error = "Lama peminjaman harus lebih dari 0 hari.";

    } elseif ($lama_pinjam > 365) {

        $error = "Lama peminjaman maksimal 365 hari.";

    } else {


        /* =========================
           HITUNG BATAS KEMBALI
        ========================= */

        $tanggal = new DateTime($tanggal_pinjam);

        $tanggal->modify("+$lama_pinjam days");

        $batas_kembali = $tanggal->format("Y-m-d");


        /* =========================
           MULAI TRANSAKSI
        ========================= */

        mysqli_begin_transaction($koneksi);


        try {


            /* =========================
               KUNCI DAN CEK BUKU
            ========================= */

            $cek = mysqli_query($koneksi, "

                SELECT status

                FROM eksemplar_buku

                WHERE id = {$r['eksemplar_id']}

                FOR UPDATE

            ");


            if (!$cek) {

                throw new Exception(
                    "Gagal mengecek status buku."
                );

            }


            $buku = mysqli_fetch_assoc($cek);


            if (!$buku) {

                throw new Exception(
                    "Data buku tidak ditemukan."
                );

            }


            if ($buku['status'] != "Tersedia") {

                throw new Exception(
                    "Buku sudah tidak tersedia."
                );

            }


            /* =========================
               BUAT PEMINJAMAN
            ========================= */

            $insert = mysqli_query($koneksi, "

                INSERT INTO riwayat_peminjaman

                (
                    user_id,
                    eksemplar_id,
                    tanggal_pinjam,
                    lama_pinjam,
                    batas_kembali,
                    tanggal_kembali,
                    status
                )

                VALUES

                (
                    {$r['user_id']},
                    {$r['eksemplar_id']},
                    '$tanggal_pinjam',
                    $lama_pinjam,
                    '$batas_kembali',
                    NULL,
                    'Dipinjam'
                )

            ");


            if (!$insert) {

                throw new Exception(
                    "Gagal membuat data peminjaman: "
                    . mysqli_error($koneksi)
                );

            }


            /* =========================
               UBAH STATUS BUKU
            ========================= */

            $update_buku = mysqli_query($koneksi, "

                UPDATE eksemplar_buku

                SET status = 'Dipinjam'

                WHERE id = {$r['eksemplar_id']}

            ");


            if (!$update_buku) {

                throw new Exception(
                    "Gagal mengubah status buku."
                );

            }


            /* =========================
               UBAH STATUS RESERVASI
            ========================= */

            $update_reservasi = mysqli_query($koneksi, "

                UPDATE reservasi

                SET status = 'Disetujui'

                WHERE id = $id

            ");


            if (!$update_reservasi) {

                throw new Exception(
                    "Gagal mengubah status reservasi."
                );

            }


            /* =========================
               SIMPAN SEMUA
            ========================= */

            mysqli_commit($koneksi);


            echo "

            <script>

                alert(
                    'Reservasi berhasil disetujui dan peminjaman berhasil dibuat.'
                );

                window.location.href = 'index.php';

            </script>

            ";

            exit;


        } catch (Exception $e) {


            mysqli_rollback($koneksi);

            $error = $e->getMessage();

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


<div class="card-header bg-success text-white">

    <h4 class="mb-0">

        Setujui Reservasi

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


<p class="mb-1">

    NIS:

    <strong>

        <?= htmlspecialchars($r['nis'] ?? '-'); ?>

    </strong>

</p>


<p class="mb-1">

    Kode Buku:

    <strong>

        <?= htmlspecialchars($r['kode_buku']); ?>

    </strong>

</p>


<p class="mb-0">

    Status Buku:

    <span class="badge bg-success">

        <?= htmlspecialchars($r['status_buku']); ?>

    </span>

</p>


</div>


<?php if ($error != "") { ?>

<div class="alert alert-danger">

    <?= htmlspecialchars($error); ?>

</div>

<?php } ?>


<form method="POST">


<div class="row">


<!-- TANGGAL PINJAM -->

<div class="col-md-6 mb-3">

    <label class="form-label">

        Tanggal Pinjam

    </label>


    <input

        type="date"

        name="tanggal_pinjam"

        class="form-control"

        value="<?= date('Y-m-d'); ?>"

        required

    >

</div>



<!-- LAMA PINJAM -->

<div class="col-md-6 mb-3">

    <label class="form-label">

        Lama Peminjaman

    </label>


    <div class="input-group">

        <input

            type="number"

            name="lama_pinjam"

            id="lama_pinjam"

            class="form-control"

            value="7"

            min="1"

            max="365"

            required

        >

        <span class="input-group-text">

            hari

        </span>

    </div>

</div>


</div>



<!-- PREVIEW BATAS KEMBALI -->

<div class="alert alert-info">


<strong>

    Batas Pengembalian:

</strong>


<span id="preview_kembali">

    <?= date(
        'd-m-Y',
        strtotime('+7 days')
    ); ?>

</span>


</div>



<div class="alert alert-warning">

    Masukkan jumlah hari peminjaman.
    Sistem akan otomatis menghitung
    tanggal batas pengembalian.

</div>



<button

    type="submit"

    name="setujui"

    class="btn btn-success"

>

    Setujui & Buat Peminjaman

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


<script>

/* =========================
   PREVIEW BATAS KEMBALI
========================= */

const tanggalPinjam =
    document.querySelector(
        'input[name="tanggal_pinjam"]'
    );

const lamaPinjam =
    document.querySelector(
        'input[name="lama_pinjam"]'
    );

const previewKembali =
    document.getElementById(
        'preview_kembali'
    );


function hitungTanggalKembali() {

    if (
        tanggalPinjam.value === "" ||
        lamaPinjam.value === ""
    ) {

        return;

    }


    const tanggal =
        new Date(
            tanggalPinjam.value
            + "T00:00:00"
        );


    const lama =
        parseInt(
            lamaPinjam.value
        );


    if (isNaN(lama) || lama <= 0) {

        return;

    }


    tanggal.setDate(
        tanggal.getDate() + lama
    );


    const hari =
        String(
            tanggal.getDate()
        ).padStart(2, '0');


    const bulan =
        String(
            tanggal.getMonth() + 1
        ).padStart(2, '0');


    const tahun =
        tanggal.getFullYear();


    previewKembali.textContent =
        `${hari}-${bulan}-${tahun}`;

}


tanggalPinjam.addEventListener(
    'change',
    hitungTanggalKembali
);


lamaPinjam.addEventListener(
    'input',
    hitungTanggalKembali
);

</script>