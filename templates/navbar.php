<nav class="navbar navbar-expand-lg bg-white shadow-sm">

<div class="container-fluid">

<h4>MyLib</h4>

<div class="d-flex align-items-center">

<span class="me-3">

<?= $_SESSION['nama']; ?>

</span>

<img
src="<?= BASE_URL ?>assets/upload/profile/default.png"
class="profile">

<a
href="<?= BASE_URL ?>logout.php"
class="btn btn-danger btn-sm ms-3">

Logout

</a>

</div>

</div>

</nav>