<?php

require "../../config/auth.php";
require "../../config/koneksi.php";


if($_SESSION['role']!="admin"){

    header("Location: ".BASE_URL."login.php");
    exit;

}



$id = $_GET['id'];


// cek apakah kelas dipakai pengguna

$cek = mysqli_query($koneksi,"
SELECT *
FROM users
WHERE kelas_id='$id'
");



if(mysqli_num_rows($cek)>0){


    echo "
    <script>
    alert('Kelas tidak bisa dihapus karena masih digunakan pengguna!');
    window.location='index.php';
    </script>
    ";

    exit;

}



// hapus kelas

mysqli_query($koneksi,"
DELETE FROM kelas
WHERE id='$id'
");



header("Location:index.php");
exit;

?>