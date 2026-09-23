<?php

require "../../config/auth.php";
require "../../config/koneksi.php";


if($_SESSION['role']!="user"){

    header("Location: ".BASE_URL."login.php");
    exit;

}


$id=$_SESSION['id'];



// ambil data user

$data=mysqli_query($koneksi,"

SELECT *

FROM users

WHERE id='$id'

");


$user=mysqli_fetch_assoc($data);




// proses update

if(isset($_POST['update'])){


    $nama = htmlspecialchars($_POST['nama']);

    $password = $_POST['password'];

    $foto_lama = $user['foto'];

    $foto = $foto_lama;



    // upload foto

    if($_FILES['foto']['name']!=""){



        $file=$_FILES['foto'];


        $nama_file=$file['name'];

        $tmp=$file['tmp_name'];



        $ekstensi=strtolower(
            pathinfo($nama_file,PATHINFO_EXTENSION)
        );


        $allowed=[
            'jpg',
            'jpeg',
            'png'
        ];



        if(!in_array($ekstensi,$allowed)){


            echo "

            <script>

            alert('Format foto harus JPG, JPEG, atau PNG');

            history.back();

            </script>

            ";

            exit;


        }



        $nama_baru=uniqid().".".$ekstensi;



        move_uploaded_file(

            $tmp,

            "../../assets/upload/profile/".$nama_baru

        );



        $foto=$nama_baru;



        // hapus foto lama

        if(
            !empty($foto_lama)
            &&
            $foto_lama!="default.png"
        ){

            unlink(
            "../../assets/upload/profile/".$foto_lama
            );

        }


    }




    // update password jika diisi

    if(!empty($password)){


        $password_hash=password_hash(
            $password,
            PASSWORD_DEFAULT
        );


    }else{


        $password_hash=$user['password'];


    }





    mysqli_query($koneksi,"

    UPDATE users SET


    nama='$nama',

    password='$password_hash',

    foto='$foto'


    WHERE id='$id'

    ");




    header("Location:index.php");

    exit;


}


include "../../templates/header.php";

?>

<?php include "../../templates/sidebar_user.php"; ?>


<div class="main">

<?php include "../../templates/navbar.php"; ?>


<div class="container mt-4">
<div class="card shadow">


<div class="card-header bg-primary text-white">

<h4 class="mb-0">

Edit Profil

</h4>

</div>



<div class="card-body">


<form method="POST" enctype="multipart/form-data">


<div class="text-center mb-3">


<?php if(!empty($user['foto'])){ ?>


<img

src="<?= BASE_URL ?>assets/upload/profile/<?= $user['foto']; ?>"

width="120"

height="120"

class="rounded-circle"

style="object-fit:cover;">



<?php }else{ ?>


<img

src="<?= BASE_URL ?>assets/upload/profile/default.png"

width="120"

height="120"

class="rounded-circle"

style="object-fit:cover;">



<?php } ?>


</div>





<div class="mb-3">


<label class="form-label">

Nama Lengkap

</label>


<input

type="text"

name="nama"

class="form-control"

value="<?= htmlspecialchars($user['nama']); ?>"

required>


</div>





<div class="mb-3">


<label class="form-label">

Password Baru

</label>


<input

type="password"

name="password"

class="form-control"


placeholder="Kosongkan jika tidak ingin mengganti password">


</div>





<div class="mb-3">


<label class="form-label">

Ganti Foto Profil

</label>


<input

type="file"

name="foto"

class="form-control"

accept=".jpg,.jpeg,.png">


</div>




<div class="alert alert-info">

Jika password dikosongkan,
password lama tetap digunakan.

</div>




<button

type="submit"

name="update"

class="btn btn-success">

Simpan Perubahan

</button>



<a

href="index.php"

class="btn btn-secondary">

Kembali

</a>



</form>


</div>


</div>
<?php include "../../templates/footer.php"; ?>