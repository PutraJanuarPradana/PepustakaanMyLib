<?php

require "../../config/auth.php";
require "../../config/koneksi.php";

if ($_SESSION['role'] != "admin") {
    header("Location: " . BASE_URL . "login.php");
    exit;
}


// ============================
// Cek ID
// ============================

if(!isset($_GET['id'])){

    header("Location:index.php");
    exit;

}

$id = (int)$_GET['id'];



// ============================
// Ambil data eksemplar
// ============================

$data = mysqli_query($koneksi,"

SELECT *

FROM eksemplar_buku

WHERE id='$id'

");

if(mysqli_num_rows($data)==0){

    header("Location:index.php");
    exit;

}

$eksemplar = mysqli_fetch_assoc($data);



// ============================
// Ambil semua judul buku
// ============================

$judul = mysqli_query($koneksi,"

SELECT *

FROM judul_buku

ORDER BY judul ASC

");



// ============================
// Proses Update
// ============================

if(isset($_POST['update'])){


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

    WHERE

    kode_buku='$kode_buku'

    AND

    id!='$id'

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

    UPDATE eksemplar_buku SET

    judul_id='$judul_id',

    kode_buku='$kode_buku',

    status='$status'

    WHERE id='$id'

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

<div class="card-header bg-warning">

<h4 class="mb-0">

Edit Eksemplar Buku

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


while($j=mysqli_fetch_assoc($judul)){


?>


<option

value="<?= $j['id']; ?>"

<?php

if($j['id']==$eksemplar['judul_id']){

echo "selected";

}

?>

>


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

value="<?= htmlspecialchars($eksemplar['kode_buku']); ?>"

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


<option

value="Tersedia"

<?= ($eksemplar['status']=="Tersedia")?'selected':''; ?>

>

Tersedia

</option>



<option

value="Dipinjam"

<?= ($eksemplar['status']=="Dipinjam")?'selected':''; ?>

>

Dipinjam

</option>



<option

value="Rusak"

<?= ($eksemplar['status']=="Rusak")?'selected':''; ?>

>

Rusak

</option>



<option

value="Hilang"

<?= ($eksemplar['status']=="Hilang")?'selected':''; ?>

>

Hilang

</option>


</select>


</div>





<div class="col-12 mt-4">


<button

type="submit"

name="update"

class="btn btn-primary">


Update


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