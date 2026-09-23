<?php
require "../../config/auth.php";
require "../../config/koneksi.php";

if ($_SESSION['role'] != "admin") {
    header("Location: ../../login.php");
    exit;
}

$kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");

if (isset($_POST['simpan'])) {

    $nama      = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $username  = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);
$nis = !empty($_POST['nis']) 
? mysqli_real_escape_string($koneksi, trim($_POST['nis'])) 
: NULL;
    $kelas_id = !empty($_POST['kelas_id']) 
? $_POST['kelas_id'] 
: NULL;

    $cek = mysqli_query($koneksi, "SELECT id FROM users WHERE username='$username'");

    if (mysqli_num_rows($cek) > 0) {

        echo "<script>
        alert('Username sudah digunakan!');
        window.history.back();
        </script>";
        exit;
    }

    $foto = "default.png";

    if (!empty($_FILES['foto']['name'])) {

        $namaFile = $_FILES['foto']['name'];
        $tmp      = $_FILES['foto']['tmp_name'];
        $ukuran   = $_FILES['foto']['size'];

        $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowed)) {

            echo "<script>
            alert('Format gambar harus JPG, JPEG atau PNG');
            history.back();
            </script>";
            exit;
        }

        if ($ukuran > 2097152) {

            echo "<script>
            alert('Ukuran maksimal 2 MB');
            history.back();
            </script>";
            exit;
        }

        $foto = uniqid() . "." . $ext;

        move_uploaded_file(
            $tmp,
            "../../assets/upload/profile/" . $foto
        );
    }

$query = "
INSERT INTO users
(
nama,
username,
password,
role,
status,
nis,
kelas_id,
foto
)
VALUES
(
'$nama',
'$username',
'$password',
'user',
'Aktif',
".($nis ? "'$nis'" : "NULL").",
".($kelas_id ? "'$kelas_id'" : "NULL").",
'$foto'
)
";


mysqli_query($koneksi,$query);

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

Tambah Pengguna

</h4>

</div>

<div class="card-body">

<form method="POST" enctype="multipart/form-data">

<div class="row">

<div class="col-md-6">

<label class="form-label">

Nama

</label>

<input
type="text"
name="nama"
class="form-control"
required>

</div>

<div class="col-md-6">

<label class="form-label">

Username

</label>

<input
type="text"
name="username"
class="form-control"
required>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Password

</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

NIS

</label>

<input
type="text"
name="nis"
class="form-control"
placeholder="Kosongkan jika bukan siswa">

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Kelas

</label>

<select
name="kelas_id"
class="form-select">

<option value="">
-- Tidak Memiliki Kelas --
</option>

<?php while($k=mysqli_fetch_assoc($kelas)){ ?>

<option value="<?= $k['id']; ?>">

<?= $k['nama_kelas']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Foto

</label>

<input
type="file"
name="foto"
class="form-control"
accept=".jpg,.jpeg,.png"
onchange="previewFoto(event)">

</div>

<div class="col-12 mt-4 text-center">

<img
id="preview"
src="../../assets/upload/profile/default.png"
style="width:170px;height:170px;border-radius:50%;object-fit:cover;border:3px solid #ddd;">

</div>

<div class="col-12 mt-4">

<button
class="btn btn-success"
name="simpan">

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

</div>

<script>

function previewFoto(event){

const reader=new FileReader();

reader.onload=function(){

document.getElementById("preview").src=reader.result;

}

reader.readAsDataURL(event.target.files[0]);

}

</script>

<?php include "../../templates/footer.php"; ?>