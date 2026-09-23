<?php

require "../../config/auth.php";
require "../../config/koneksi.php";

if ($_SESSION['role'] != "admin") {
    header("Location: " . BASE_URL . "login.php");
    exit;
}


// cek id

if(!isset($_GET['id'])){

    header("Location:index.php");
    exit;

}


$id = (int)$_GET['id'];



// ambil data buku

$data = mysqli_query($koneksi,"
SELECT cover
FROM judul_buku
WHERE id='$id'
");


if(mysqli_num_rows($data)==0){

    header("Location:index.php");
    exit;

}


$buku = mysqli_fetch_assoc($data);



// hapus cover

if(

    $buku['cover']!="default_book.png"

    &&

    file_exists(

        "../../assets/upload/cover/".$buku['cover']

    )

){

    unlink(

        "../../assets/upload/cover/".$buku['cover']

    );

}



// hapus database

mysqli_query($koneksi,"
DELETE FROM judul_buku
WHERE id='$id'
");



header("Location:index.php");
exit;

?>