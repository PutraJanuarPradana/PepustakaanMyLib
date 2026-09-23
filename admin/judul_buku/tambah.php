<?php

require "../../config/auth.php";
require "../../config/koneksi.php";

if ($_SESSION['role'] != "admin") {
    header("Location: " . BASE_URL . "login.php");
    exit;
}

if (isset($_POST['simpan'])) {

    $judul     = mysqli_real_escape_string($koneksi, trim($_POST['judul']));
    $penulis   = mysqli_real_escape_string($koneksi, trim($_POST['penulis']));
    $penerbit  = mysqli_real_escape_string($koneksi, trim($_POST['penerbit']));
    $tahun     = mysqli_real_escape_string($koneksi, trim($_POST['tahun']));
    $isbn      = mysqli_real_escape_string($koneksi, trim($_POST['isbn']));
    $kategori  = mysqli_real_escape_string($koneksi, trim($_POST['kategori']));
    $rak       = mysqli_real_escape_string($koneksi, trim($_POST['rak']));
    $sinopsis  = mysqli_real_escape_string($koneksi, trim($_POST['sinopsis']));

    if (empty($judul) || empty($penulis) || empty($penerbit)) {
        echo "<script>
                alert('Judul, Penulis, dan Penerbit wajib diisi!');
                history.back();
              </script>";
        exit;
    }

    // Cover default
    $cover = "default_book.png";

    // Upload cover
    if (!empty($_FILES['cover']['name'])) {

        $namaFile = $_FILES['cover']['name'];
        $tmp      = $_FILES['cover']['tmp_name'];
        $ukuran   = $_FILES['cover']['size'];

        $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowed)) {
            echo "<script>
                    alert('Format cover harus JPG, JPEG, atau PNG!');
                    history.back();
                  </script>";
            exit;
        }

        if ($ukuran > 2097152) {
            echo "<script>
                    alert('Ukuran cover maksimal 2 MB!');
                    history.back();
                  </script>";
            exit;
        }

        $cover = uniqid() . "." . $ext;

        if (!move_uploaded_file(
            $tmp,
            "../../assets/upload/cover/" . $cover
        )) {

            echo "<script>
                    alert('Upload cover gagal!');
                    history.back();
                  </script>";
            exit;
        }
    }

    $query = "
    INSERT INTO judul_buku
    (
        judul,
        penulis,
        penerbit,
        tahun,
        isbn,
        kategori,
        rak,
        sinopsis,
        cover
    )
    VALUES
    (
        '$judul',
        '$penulis',
        '$penerbit',
        '$tahun',
        '$isbn',
        '$kategori',
        '$rak',
        '$sinopsis',
        '$cover'
    )
    ";

    if (!mysqli_query($koneksi, $query)) {
        die("Gagal menyimpan data: " . mysqli_error($koneksi));
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

Tambah Judul Buku

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
max="<?= date('Y'); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

ISBN

</label>

<input
type="text"
name="isbn"
class="form-control">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Kategori

</label>

<input
type="text"
name="kategori"
class="form-control"
placeholder="Contoh: Novel">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Rak

</label>

<input
type="text"
name="rak"
class="form-control"
placeholder="Contoh: A-01">

</div>

<div class="col-12 mb-3">

<label class="form-label">

Sinopsis

</label>

<textarea
name="sinopsis"
class="form-control"
rows="5"></textarea>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Cover Buku

</label>

<input
type="file"
name="cover"
class="form-control"
accept=".jpg,.jpeg,.png"
onchange="previewCover(event)">

<small class="text-muted">

Format: JPG, JPEG, PNG (Maksimal 2 MB)

</small>

</div>

<div class="col-md-6 text-center">

<img

id="preview"

src="<?= BASE_URL ?>assets/upload/cover/default_book.png"

style="width:180px;height:250px;object-fit:cover;border:2px solid #ddd;border-radius:8px;">

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
