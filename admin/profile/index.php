<?php

require "../../config/auth.php";
require "../../config/koneksi.php";

if ($_SESSION['role'] != "admin") {
    header("Location: " . BASE_URL . "login.php");
    exit;
}

$id = (int) $_SESSION['id'];

$query = mysqli_query($koneksi, "
    SELECT *
    FROM users
    WHERE id = $id
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {
    header("Location: " . BASE_URL . "login.php");
    exit;
}

$user = mysqli_fetch_assoc($query);

$error = "";
$success = "";


/* =========================
   UPDATE PROFILE
========================= */

if (isset($_POST['simpan'])) {

    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);

    if ($nama == "" || $username == "") {

        $error = "Nama dan username wajib diisi.";

    } else {

        $nama = mysqli_real_escape_string(
            $koneksi,
            $nama
        );

        $username = mysqli_real_escape_string(
            $koneksi,
            $username
        );


        /* Cek username sudah digunakan */
        $cek = mysqli_query($koneksi, "

            SELECT id
            FROM users
            WHERE username = '$username'
            AND id != $id
            LIMIT 1

        ");


        if (mysqli_num_rows($cek) > 0) {

            $error = "Username sudah digunakan.";

        } else {


            /* =========================
               UPDATE DATA
            ========================= */

            $update = mysqli_query($koneksi, "

                UPDATE users

                SET
                    nama = '$nama',
                    username = '$username'

                WHERE id = $id

            ");


            if ($update) {

                $_SESSION['nama'] = $nama;

                $success =
                    "Profil berhasil diperbarui.";

                $user['nama'] = $nama;
                $user['username'] = $username;

            } else {

                $error =
                    "Gagal memperbarui profil: "
                    . mysqli_error($koneksi);

            }
        }
    }
}


/* =========================
   GANTI PASSWORD
========================= */

if (isset($_POST['ganti_password'])) {

    $password_lama =
        $_POST['password_lama'];

    $password_baru =
        $_POST['password_baru'];

    $konfirmasi =
        $_POST['konfirmasi_password'];


    if (
        $password_lama == "" ||
        $password_baru == "" ||
        $konfirmasi == ""
    ) {

        $error =
            "Semua password wajib diisi.";

    } elseif (
        !password_verify(
            $password_lama,
            $user['password']
        )
    ) {

        $error =
            "Password lama salah.";

    } elseif (strlen($password_baru) < 6) {

        $error =
            "Password baru minimal 6 karakter.";

    } elseif (
        $password_baru != $konfirmasi
    ) {

        $error =
            "Konfirmasi password tidak cocok.";

    } else {

        $password_hash =
            password_hash(
                $password_baru,
                PASSWORD_DEFAULT
            );

        $password_hash =
            mysqli_real_escape_string(
                $koneksi,
                $password_hash
            );


        $update_password = mysqli_query(
            $koneksi,
            "

            UPDATE users

            SET password = '$password_hash'

            WHERE id = $id

            "
        );


        if ($update_password) {

            $success =
                "Password berhasil diubah.";

            $user['password'] =
                $password_hash;

        } else {

            $error =
                "Gagal mengubah password.";

        }
    }
}


include "../../templates/header.php";

?>

<?php include "../../templates/sidebar_admin.php"; ?>


<div class="main">

<?php include "../../templates/navbar.php"; ?>


<div class="container mt-4">

    <div class="mb-4">

        <h3>Profil Admin</h3>

        <p class="text-muted">
            Kelola informasi akun admin.
        </p>

    </div>


    <?php if ($error != "") { ?>

        <div class="alert alert-danger">

            <?= htmlspecialchars($error); ?>

        </div>

    <?php } ?>


    <?php if ($success != "") { ?>

        <div class="alert alert-success">

            <?= htmlspecialchars($success); ?>

        </div>

    <?php } ?>


    <div class="row">


        <!-- DATA PROFILE -->

        <div class="col-md-6 mb-4">

            <div class="card shadow-sm">

                <div class="card-header">

                    <strong>
                        Informasi Akun
                    </strong>

                </div>


                <div class="card-body">

                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">
                                Nama
                            </label>

                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                value="<?= htmlspecialchars($user['nama']); ?>"
                                required
                            >

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Username
                            </label>

                            <input
                                type="text"
                                name="username"
                                class="form-control"
                                value="<?= htmlspecialchars($user['username']); ?>"
                                required
                            >

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Role
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="Admin"
                                disabled
                            >

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Status
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="<?= htmlspecialchars($user['status']); ?>"
                                disabled
                            >

                        </div>


                        <button
                            type="submit"
                            name="simpan"
                            class="btn btn-primary"
                        >

                            Simpan Perubahan

                        </button>


                    </form>

                </div>

            </div>

        </div>


        <!-- PASSWORD -->

        <div class="col-md-6 mb-4">

            <div class="card shadow-sm">

                <div class="card-header">

                    <strong>
                        Ganti Password
                    </strong>

                </div>


                <div class="card-body">

                    <form method="POST">


                        <div class="mb-3">

                            <label class="form-label">
                                Password Lama
                            </label>

                            <input
                                type="password"
                                name="password_lama"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Password Baru
                            </label>

                            <input
                                type="password"
                                name="password_baru"
                                class="form-control"
                                minlength="6"
                                required
                            >

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Konfirmasi Password
                            </label>

                            <input
                                type="password"
                                name="konfirmasi_password"
                                class="form-control"
                                minlength="6"
                                required
                            >

                        </div>


                        <button
                            type="submit"
                            name="ganti_password"
                            class="btn btn-warning"
                        >

                            Ganti Password

                        </button>


                    </form>

                </div>

            </div>

        </div>


    </div>

</div>


<?php include "../../templates/footer.php"; ?>