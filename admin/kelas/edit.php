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
FROM kelas
WHERE id='$id'
");


$kelas = mysqli_fetch_assoc($data);



if(!$kelas){

    header("Location:index.php");
    exit;

}



if(isset($_POST['update'])){


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



    // cek nama kelas lain

    $cek = mysqli_query($koneksi,"
    SELECT *
    FROM kelas
    WHERE nama_kelas='$nama_kelas'
    AND id!='$id'
    ");



    if(mysqli_num_rows($cek)>0){


        echo "
        <script>
        alert('Nama kelas sudah digunakan!');
        history.back();
        </script>
        ";

        exit;

    }



    mysqli_query($koneksi,"
    UPDATE kelas SET

    nama_kelas='$nama_kelas'

    WHERE id='$id'
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


<div class="card-header bg-warning">

<h4>
Edit Kelas
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

value="<?= htmlspecialchars($kelas['nama_kelas']); ?>"

required

>


</div>



<button

name="update"

class="btn btn-primary">

Update

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