<?php

require "../../config/auth.php";
require "../../config/koneksi.php";


if($_SESSION['role'] != "admin"){

    header("Location: ".BASE_URL."login.php");
    exit;

}


$id = $_GET['id'];


// ambil data foto

$data = mysqli_query($koneksi,"
SELECT foto 
FROM users
WHERE id='$id'
");


$user = mysqli_fetch_assoc($data);



if($user){


    // hapus foto jika bukan default

    if(
        $user['foto'] != "default.png" &&
        file_exists(
        "../../assets/upload/profile/".$user['foto']
        )
    ){

        unlink(
        "../../assets/upload/profile/".$user['foto']
        );

    }



    mysqli_query($koneksi,"
    DELETE FROM users
    WHERE id='$id'
    ");


}


header("Location:index.php");
exit;

?>