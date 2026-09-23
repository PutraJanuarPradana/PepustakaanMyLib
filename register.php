<?php

require "config/koneksi.php";


if(isset($_POST['daftar'])){


    $nama = htmlspecialchars($_POST['nama']);

    $username = htmlspecialchars($_POST['username']);

    $password = $_POST['password'];

    $nis = !empty($_POST['nis']) 
        ? $_POST['nis'] 
        : NULL;

    $kelas_id = !empty($_POST['kelas_id']) 
        ? $_POST['kelas_id'] 
        : NULL;



    // cek username

    $cek = mysqli_query($koneksi,"

    SELECT username 

    FROM users

    WHERE username='$username'

    ");



    if(mysqli_num_rows($cek)>0){


        echo "

        <script>

        alert('Username sudah digunakan!');

        history.back();

        </script>

        ";

        exit;

    }



    // password hash

    $password_hash = password_hash(

        $password,

        PASSWORD_DEFAULT

    );



    // foto default

    $foto="default.png";



    mysqli_query($koneksi,"

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

    '$password_hash',

    'user',

    'Aktif',

    '$nis',

    ".($kelas_id ? "'$kelas_id'" : "NULL").",

    '$foto'

    )

    ");



    echo "

    <script>

    alert('Pendaftaran berhasil! Silakan login');

    location='login.php';

    </script>

    ";


}


?>
<?php


// ambil data kelas

$kelas=mysqli_query($koneksi,"

SELECT *

FROM kelas

ORDER BY nama_kelas ASC

");


?>


<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


</head>


<body class="bg-light">


<div class="container">


<div class="row justify-content-center mt-5">


<div class="col-md-6">



<div class="card shadow">


<div class="card-header bg-primary text-white text-center">


<h4>

Daftar Akun Perpustakaan

</h4>


</div>



<div class="card-body">



<form method="POST">



<div class="mb-3">


<label class="form-label">

Nama Lengkap

</label>


<input

type="text"

name="nama"

class="form-control"

required>


</div>





<div class="mb-3">


<label class="form-label">

Username

</label>


<input

type="text"

name="username"

class="form-control"

required>


</div>





<div class="mb-3">


<label class="form-label">

Password

</label>


<input

type="password"

name="password"

class="form-control"

required>


</div>





<div class="mb-3">


<label class="form-label">

NIS (Opsional)

</label>


<input

type="text"

name="nis"

class="form-control">


</div>





<div class="mb-3">


<label class="form-label">

Kelas (Opsional)

</label>


<select

name="kelas_id"

class="form-select">


<option value="">

-- Tidak memilih kelas --

</option>



<?php while($k=mysqli_fetch_assoc($kelas)){ ?>


<option value="<?= $k['id']; ?>">


<?= htmlspecialchars($k['nama_kelas']); ?>


</option>


<?php } ?>


</select>


</div>





<button

name="daftar"

class="btn btn-primary w-100">


Daftar


</button>




</form>




<hr>



<p class="text-center">


Sudah punya akun?


<a href="login.php">

Login

</a>


</p>



</div>


</div>



</div>


</div>


</div>


</body>


</html>