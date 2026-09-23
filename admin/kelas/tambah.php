<?php

require "../../config/auth.php";
require "../../config/koneksi.php";


if($_SESSION['role']!="admin"){

    header("Location: ".BASE_URL."login.php");
    exit;

}



if(isset($_POST['simpan'])){


    $nama_kelas = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['nama_kelas'])
    );


    if(empty($nama_kelas)){


        echo "
        <script>
        alert('Nama kelas tidak boleh kosong!');
        history.back();
        </script>
        ";

        exit;

    }



    // cek kelas sudah ada

    $cek = mysqli_query($koneksi,"
    SELECT *
    FROM kelas
    WHERE nama_kelas='$nama_kelas'
    ");



    if(mysqli_num_rows($cek)>0){


        echo "
        <script>
        alert('Kelas sudah ada!');
        history.back();
        </script>
        ";

        exit;

    }



    mysqli_query($koneksi,"
    INSERT INTO kelas
    (
        nama_kelas
    )
    VALUES
    (
        '$nama_kelas'
    )
    ");



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

<h4>
Tambah Kelas
</h4>

</div>



<div class="card-body">


<form method="POST">


<div class="mb-3">


<label class="form-label">

Nama Kelas

</label>


<input

type="text"

name="nama_kelas"

class="form-control"

placeholder="Contoh: XI TJKT 1"

required

>


</div>



<button

name="simpan"

class="btn btn-success">

Simpan

</button>


<a

href="index.php"

class="btn btn-secondary">

Kembali

</a>


</form>


</div>


</div>


</div>


</div>



<?php include "../../templates/footer.php"; ?>