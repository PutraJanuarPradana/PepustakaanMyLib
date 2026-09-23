<?php

require "../../config/auth.php";
require "../../config/koneksi.php";


if($_SESSION['role']!="admin"){

    header("Location: ".BASE_URL."login.php");
    exit;

}



if(!isset($_GET['id'])){

    header("Location:index.php");
    exit;

}


$id = (int)$_GET['id'];



// ambil data reservasi

$data = mysqli_query($koneksi,"

SELECT *

FROM reservasi

WHERE id='$id'

");


if(mysqli_num_rows($data)==0){

    header("Location:index.php");
    exit;

}


$reservasi = mysqli_fetch_assoc($data);



$user_id = $reservasi['user_id'];

$eksemplar_id = $reservasi['eksemplar_id'];




// cek apakah sudah disetujui

if($reservasi['status']!="Disetujui"){

    echo "

    <script>

    alert('Reservasi belum disetujui!');

    location='index.php';

    </script>

    ";

    exit;

}



// tanggal pinjam

$tanggal_pinjam = date("Y-m-d");


// batas kembali 7 hari

$batas_kembali = date(
    "Y-m-d",
    strtotime("+7 days")
);




// masukkan ke riwayat peminjaman

mysqli_query($koneksi,"

INSERT INTO riwayat_peminjaman

(

user_id,

eksemplar_id,

tanggal_pinjam,

batas_kembali,

status

)

VALUES

(

'$user_id',

'$eksemplar_id',

'$tanggal_pinjam',

'$batas_kembali',

'Dipinjam'

)

");





// ubah status buku

mysqli_query($koneksi,"

UPDATE eksemplar_buku

SET status='Dipinjam'

WHERE id='$eksemplar_id'

");





// ubah status reservasi

mysqli_query($koneksi,"

UPDATE reservasi

SET status='Selesai'

WHERE id='$id'

");




header("Location:index.php");

exit;


?>