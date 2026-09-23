<?php

require "../../config/auth.php";
require "../../config/koneksi.php";


if($_SESSION['role']!="user"){

    header("Location: ".BASE_URL."login.php");
    exit;

}


$id=$_SESSION['id'];



$data=mysqli_query($koneksi,"

SELECT *

FROM users

WHERE id='$id'

");


$user=mysqli_fetch_assoc($data);



include "../../templates/header.php";

?>

<?php include "../../templates/sidebar_user.php"; ?>


<div class="main">


<?php include "../../templates/navbar.php"; ?>


<div class="container mt-4">
<div class="card shadow">


<div class="card-header bg-primary text-white">

<h4 class="mb-0">

Profil Saya

</h4>

</div>



<div class="card-body text-center">


<?php if(!empty($user['foto'])){ ?>


<img 

src="<?= BASE_URL ?>assets/upload/profile/<?= $user['foto']; ?>"

class="rounded-circle mb-3"

width="150"

height="150"

style="object-fit:cover;">



<?php }else{ ?>


<img 

src="<?= BASE_URL ?>assets/upload/profile/default.png"

class="rounded-circle mb-3"

width="150"

height="150"

style="object-fit:cover;">



<?php } ?>



<h4>

<?= htmlspecialchars($user['nama']); ?>

</h4>


<p>

Username:

<b>

<?= htmlspecialchars($user['username']); ?>

</b>

</p>



<p>

Role:

<b>

<?= htmlspecialchars($user['role']); ?>

</b>

</p>



<a 

href="update.php"

class="btn btn-success">

Edit Profil

</a>



</div>


</div>
<?php include "../../templates/footer.php"; ?>