<?php

require "../../config/auth.php";
require "../../config/koneksi.php";


if($_SESSION['role']!="admin"){

    header("Location: ".BASE_URL."login.php");
    exit;

}



// cek id

if(!isset($_GET['id'])){

    header("Location:index.php");
    exit;

}



$id = (int)$_GET['id'];



// cek data

$cek = mysqli_query($koneksi,"

SELECT *

FROM eksemplar_buku

WHERE id='$id'

");



if(mysqli_num_rows($cek)==0){

    header("Location:index.php");
    exit;

}



// hapus data

mysqli_query($koneksi,"

DELETE FROM eksemplar_buku

WHERE id='$id'

");



header("Location:index.php");

exit;


?>