<?php

require "../../config/auth.php";
require "../../config/koneksi.php";


if($_SESSION['role']!="admin"){

    header("Location: ".BASE_URL."login.php");
    exit;

}


$id = $_GET['id'];


$data = mysqli_query($koneksi,"
SELECT *
FROM users
WHERE id='$id'
");


$siswa = mysqli_fetch_assoc($data);



if(!$siswa){

    header("Location:index.php");
    exit;

}



$kelas = mysqli_query($koneksi,"
SELECT *
FROM kelas
ORDER BY nama_kelas ASC
");



if(isset($_POST['update'])){


    $nama = mysqli_real_escape_string(
        $koneksi,
        $_POST['nama']
    );


    $username = mysqli_real_escape_string(
        $koneksi,
        $_POST['username']
    );


    $nis = mysqli_real_escape_string(
        $koneksi,
        $_POST['nis']
    );


    $kelas_id = $_POST['kelas_id'];



    // cek username

    $cek = mysqli_query($koneksi,"
    SELECT *
    FROM users
    WHERE username='$username'
    AND id!='$id'
    ");



    if(mysqli_num_rows($cek)>0){


        echo "
        <script>
        alert('Username sudah digunakan!');
        history.back();
        </script>";

        exit;

    }



    $foto = $siswa['foto'];



    // upload foto baru

    if(!empty($_FILES['foto']['name'])){


        $namaFile = $_FILES['foto']['name'];

        $tmp = $_FILES['foto']['tmp_name'];

        $ukuran = $_FILES['foto']['size'];



        $ext = strtolower(
            pathinfo($namaFile,PATHINFO_EXTENSION)
        );



        $allowed = [
            "jpg",
            "jpeg",
            "png"
        ];



        if(!in_array($ext,$allowed)){


            echo "
            <script>
            alert('Format foto tidak sesuai!');
            history.back();
            </script>";

            exit;

        }



        if($ukuran > 2097152){


            echo "
            <script>
            alert('Ukuran foto maksimal 2MB!');
            history.back();
            </script>";

            exit;

        }



        $foto = uniqid().".".$ext;



        move_uploaded_file(
            $tmp,
            "../../assets/upload/profile/".$foto
        );



        // hapus foto lama

        if(
            $siswa['foto']!="default.png" &&
            file_exists(
            "../../assets/upload/profile/".$siswa['foto']
            )
        ){

            unlink(
            "../../assets/upload/profile/".$siswa['foto']
            );

        }


    }




    // password

    if(!empty($_POST['password'])){


        $password = password_hash(
            $_POST['password'],
            PASSWORD_DEFAULT
        );



        mysqli_query($koneksi,"
        UPDATE users SET

        nama='$nama',
        username='$username',
        password='$password',
        nis='$nis',
        kelas_id='$kelas_id',
        foto='$foto'

        WHERE id='$id'
        ");


    }else{


        mysqli_query($koneksi,"
        UPDATE users SET

        nama='$nama',
        username='$username',
        nis='$nis',
        kelas_id='$kelas_id',
        foto='$foto'

        WHERE id='$id'
        ");


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

<h4>Edit Pengguna</h4>

</div>



<div class="card-body">


<form method="POST" enctype="multipart/form-data">


<div class="row">



<div class="col-md-6 mb-3">

<label>Nama</label>

<input
type="text"
name="nama"
class="form-control"
value="<?= htmlspecialchars($siswa['nama']) ?>"
required>

</div>




<div class="col-md-6 mb-3">

<label>Username</label>

<input
type="text"
name="username"
class="form-control"
value="<?= htmlspecialchars($siswa['username']) ?>"
required>

</div>




<div class="col-md-6 mb-3">

<label>Password Baru</label>

<input
type="password"
name="password"
class="form-control">

<small class="text-muted">

Kosongkan jika tidak ingin mengganti password

</small>

</div>




<div class="col-md-6 mb-3">

<label>NIS</label>

<input
type="text"
name="nis"
class="form-control"
value="<?= htmlspecialchars($d['nama'] ?? '-'); ?>">

</div>




<div class="col-md-6 mb-3">

<label>Kelas</label>


<select
name="kelas_id"
class="form-select"
required>


<?php while($k=mysqli_fetch_assoc($kelas)){ ?>


<option

value="<?= $k['id'] ?>"

<?= $k['id']==$siswa['kelas_id']?'selected':'' ?>

>

<?= $k['nama_kelas'] ?>

</option>


<?php } ?>


</select>


</div>



<div class="col-md-6 mb-3">


<label>Foto</label>


<input

type="file"

name="foto"

class="form-control"

accept=".jpg,.jpeg,.png"

>


</div>



<div class="col-12 text-center">


<img

src="<?= BASE_URL ?>assets/upload/profile/<?= $siswa['foto'] ?>"

width="150"

height="150"

style="object-fit:cover;border-radius:50%"

>


</div>



<div class="mt-4">


<button

class="btn btn-primary"

name="update"

>

Update

</button>


<a

href="index.php"

class="btn btn-secondary"

>

Kembali

</a>


</div>



</div>


</form>


</div>


</div>


</div>


</div>



<?php include "../../templates/footer.php"; ?>