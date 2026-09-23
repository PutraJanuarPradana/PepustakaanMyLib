<?php

require "../../config/auth.php";
require "../../config/koneksi.php";

if ($_SESSION['role'] != "admin") {
    header("Location: " . BASE_URL . "login.php");
    exit;
}


// =============================
// Ambil semua judul buku
// =============================

$judul = mysqli_query($koneksi,"
SELECT *
FROM judul_buku
ORDER BY judul ASC
");



// =============================
// Simpan Data
// =============================

if(isset($_POST['simpan'])){


    $judul_id = (int)$_POST['judul_id'];

    $kode_buku = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['kode_buku'])
    );

    $status = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['status'])
    );



    if(

        empty($judul_id)

        ||

        empty($kode_buku)

        ||

        empty($status)

    ){

        echo "

        <script>

        alert('Semua data wajib diisi!');

        history.back();

        </script>

        ";

        exit;

    }



    // ==========================
    // Cek kode buku
    // ==========================

    $cek = mysqli_query($koneksi,"

    SELECT id

    FROM eksemplar_buku

    WHERE kode_buku='$kode_buku'

    ");




    if(mysqli_num_rows($cek)>0){

        echo "

        <script>

        alert('Kode buku sudah digunakan!');

        history.back();

        </script>

        ";

        exit;

    }




    $query="

    INSERT INTO eksemplar_buku

    (

    judul_id,

    kode_buku,

    status

    )

    VALUES

    (

    '$judul_id',

    '$kode_buku',

    '$status'

    )

    ";



    if(!mysqli_query($koneksi,$query)){

        die(mysqli_error($koneksi));

    }



    header("Location:index.php");

    exit;

}



include "../../templates/header.php";

?>

<?php include "../../templates/sidebar_admin.php"; ?>

<div class="main">

<?php include "../../templates/navbar.php"; ?>

<div class="container mt-4">
    <div class="card shadow">

<div class="card-header bg-primary text-white">

<h4 class="mb-0">

Tambah Eksemplar Buku

</h4>

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Judul Buku

</label>

<select

name="judul_id"

class="form-select"

required>

<option value="">

-- Pilih Judul Buku --

</option>

<?php

mysqli_data_seek($judul,0);

while($j=mysqli_fetch_assoc($judul)){

?>

<option value="<?= $j['id']; ?>">

<?= htmlspecialchars($j['judul']); ?>

</option>

<?php } ?>

</select>

</div>



<div class="col-md-6 mb-3">

<label class="form-label">

Kode Buku

</label>

<input

type="text"

name="kode_buku"

class="form-control"

placeholder="Contoh : BK0001"

required>

</div>



<div class="col-md-6 mb-3">

<label class="form-label">

Status

</label>

<select

name="status"

class="form-select"

required>

<option value="Tersedia">

Tersedia

</option>

<option value="Dipinjam">

Dipinjam

</option>

<option value="Rusak">

Rusak

</option>

<option value="Hilang">

Hilang

</option>

</select>

</div>



<div class="col-12 mt-4">

<button

type="submit"

name="simpan"

class="btn btn-success">

Simpan

</button>

<a

href="index.php"

class="btn btn-secondary">

Kembali

</a>

</div>

</div>

</form>

</div>

</div>

<?php include "../../templates/footer.php"; ?>