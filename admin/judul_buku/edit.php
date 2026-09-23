<?php

require "../../config/auth.php";
require "../../config/koneksi.php";

if ($_SESSION['role'] != "admin") {
    header("Location: " . BASE_URL . "login.php");
    exit;
}


// ==========================
// Ambil ID Buku
// ==========================

if (!isset($_GET['id'])) {

    header("Location:index.php");
    exit;

}

$id = (int)$_GET['id'];

$data = mysqli_query($koneksi,"
SELECT *
FROM judul_buku
WHERE id='$id'
");

if(mysqli_num_rows($data)==0){

    header("Location:index.php");
    exit;

}

$buku = mysqli_fetch_assoc($data);



// ==========================
// Proses Update
// ==========================

if(isset($_POST['update'])){


    $judul = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['judul'])
    );

    $penulis = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['penulis'])
    );

    $penerbit = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['penerbit'])
    );

    $tahun = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['tahun'])
    );

    $isbn = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['isbn'])
    );

    $kategori = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['kategori'])
    );

    $rak = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['rak'])
    );

    $sinopsis = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['sinopsis'])
    );



    if(
        empty($judul) ||
        empty($penulis) ||
        empty($penerbit)
    ){

        echo "

        <script>

        alert('Judul, Penulis dan Penerbit wajib diisi!');

        history.back();

        </script>

        ";

        exit;

    }



    // cover lama

    $cover = $buku['cover'];



    // =====================
    // Upload Cover Baru
    // =====================

    if(!empty($_FILES['cover']['name'])){


        $nama = $_FILES['cover']['name'];

        $tmp = $_FILES['cover']['tmp_name'];

        $size = $_FILES['cover']['size'];



        $ext = strtolower(
            pathinfo(
                $nama,
                PATHINFO_EXTENSION
            )
        );



        $allowed = [

            "jpg",
            "jpeg",
            "png"

        ];



        if(!in_array($ext,$allowed)){

            echo "

            <script>

            alert('Format cover harus JPG/JPEG/PNG');

            history.back();

            </script>

            ";

            exit;

        }



        if($size>2097152){

            echo "

            <script>

            alert('Ukuran maksimal 2 MB');

            history.back();

            </script>

            ";

            exit;

        }



        $coverBaru = uniqid().".".$ext;



        if(
            move_uploaded_file(
                $tmp,
                "../../assets/upload/cover/".$coverBaru
            )
        ){


            // hapus cover lama

            if(

                $cover!="default_book.png"

                &&

                file_exists(

                    "../../assets/upload/cover/".$cover

                )

            ){

                unlink(

                    "../../assets/upload/cover/".$cover

                );

            }



            $cover = $coverBaru;

        }

    }



    $query = "

    UPDATE judul_buku SET

    judul='$judul',

    penulis='$penulis',

    penerbit='$penerbit',

    tahun='$tahun',

    isbn='$isbn',

    kategori='$kategori',

    rak='$rak',

    sinopsis='$sinopsis',

    cover='$cover'

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

Edit Judul Buku

</h4>

</div>

<div class="card-body">

<form method="POST" enctype="multipart/form-data">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Judul Buku

</label>

<input
type="text"
name="judul"
class="form-control"
value="<?= htmlspecialchars($buku['judul']); ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Penulis

</label>

<input
type="text"
name="penulis"
class="form-control"
value="<?= htmlspecialchars($buku['penulis']); ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Penerbit

</label>

<input
type="text"
name="penerbit"
class="form-control"
value="<?= htmlspecialchars($buku['penerbit']); ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Tahun Terbit

</label>

<input
type="number"
name="tahun"
class="form-control"
min="1900"
max="<?= date('Y'); ?>"
value="<?= htmlspecialchars($buku['tahun']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

ISBN

</label>

<input
type="text"
name="isbn"
class="form-control"
value="<?= htmlspecialchars($buku['isbn']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Kategori

</label>

<input
type="text"
name="kategori"
class="form-control"
value="<?= htmlspecialchars($buku['kategori']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Rak

</label>

<input
type="text"
name="rak"
class="form-control"
value="<?= htmlspecialchars($buku['rak']); ?>">

</div>

<div class="col-12 mb-3">

<label class="form-label">

Sinopsis

</label>

<textarea
name="sinopsis"
class="form-control"
rows="5"><?= htmlspecialchars($buku['sinopsis']); ?></textarea>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Ganti Cover (Opsional)

</label>

<input
type="file"
name="cover"
class="form-control"
accept=".jpg,.jpeg,.png"
onchange="previewCover(event)">

<small class="text-muted">

Kosongkan jika tidak ingin mengganti cover.

</small>

</div>

<div class="col-md-6 text-center">

<label class="form-label d-block">

Cover Saat Ini

</label>

<img

id="preview"

src="<?= BASE_URL ?>assets/upload/cover/<?= htmlspecialchars($buku['cover']); ?>"

style="width:180px;height:250px;object-fit:cover;border:2px solid #ddd;border-radius:8px;">

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
</div>

<script>

function previewCover(event){

    const input = event.target;

    if(input.files && input.files[0]){

        const reader = new FileReader();

        reader.onload = function(e){

            document.getElementById("preview").src = e.target.result;

        }

        reader.readAsDataURL(input.files[0]);

    }

}

</script>

<?php include "../../templates/footer.php"; ?>