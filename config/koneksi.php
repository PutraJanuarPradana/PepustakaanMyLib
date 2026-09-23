<?php

$host = "localhost";
$user = "root";
$pass = "root";
$db   = "perpustakaanMyLib";


$koneksi = mysqli_connect(
    $host,
    $user,
    $pass,
    $db
);


if(!$koneksi){

    die("Koneksi database gagal : " . mysqli_connect_error());

}


date_default_timezone_set("Asia/Jakarta");


if(!defined("BASE_URL")){
    define("BASE_URL","http://localhost/perpustakaan_final/");
}