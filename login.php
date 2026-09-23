<?php

require "config/koneksi.php";
require "config/session.php";


// Jika sudah login
if (isset($_SESSION['login'])) {


    if ($_SESSION['role'] == "admin") {

        header("Location: ".BASE_URL."admin/dashboard.php");

    } else {

        header("Location: ".BASE_URL."user/dashboard.php");

    }


    exit;

}



$error = "";



if (isset($_POST['login'])) {


    $username = mysqli_real_escape_string(
        $koneksi,
        $_POST['username']
    );


    $password = $_POST['password'];



    // cari username

    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM users WHERE username='$username'"
    );



    if(mysqli_num_rows($query)==1){


        $user = mysqli_fetch_assoc($query);



        // cek status akun

        if($user['status']=="Nonaktif"){


            $error = "Akun anda sedang dinonaktifkan oleh admin.";



        } 
        
        // cek password

        else if(password_verify($password,$user['password'])){



            $_SESSION['login'] = true;

            $_SESSION['id'] = $user['id'];

            $_SESSION['nama'] = $user['nama'];

            $_SESSION['username'] = $user['username'];

            $_SESSION['role'] = $user['role'];

            $_SESSION['foto'] = $user['foto'];




            // redirect berdasarkan role


            if($user['role']=="admin"){


                header(
                    "Location: ".BASE_URL."admin/dashboard.php"
                );


            }else{


                header(
                    "Location: ".BASE_URL."user/dashboard.php"
                );


            }


            exit;



        }else{


            $error = "Password salah.";


        }



    }else{


        $error = "Username tidak ditemukan.";


    }



}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Perpustakaan Final</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f5f7fb;
        }

        .login-card{
            max-width:420px;
            margin:auto;
            margin-top:80px;
            border:none;
            border-radius:18px;
            box-shadow:0 10px 30px rgba(0,0,0,.12);
        }

        .logo{
            width:90px;
        }

        .btn-login{
            width:100%;
            border-radius:10px;
        }
    </style>

</head>
<body>

<div class="container">

    <div class="card login-card">

        <div class="card-body p-4">

            <div class="text-center mb-4">

                <img src="assets/img/logo.png" class="logo">

                <h3 class="mt-3">
                    MyLib
                </h3>

                <p class="text-muted">
                    Silakan login
                </p>

            </div>

            <?php if($error!=""){ ?>

                <div class="alert alert-danger">
                    <?= $error ?>
                </div>

            <?php } ?>

            <form method="POST">

                <div class="mb-3">

                    <label>Username</label>

                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        required>

                </div>

                <div class="mb-3">

                    <label>Password</label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        required>

                </div>

                <div class="form-check mb-3">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        onclick="lihatPassword()">

                    <label class="form-check-label">
                        Lihat Password
                    </label>

                </div>

                <button
                    class="btn btn-primary btn-login"
                    name="login">

                    Login

                </button>

                <div class="text-center mt-3">

                <p class="mb-2">

                Belum punya akun?

                </p>

                <a href="<?= BASE_URL ?>register.php"

                class="btn btn-outline-success w-100">

                Daftar Akun

                </a>

                </div>

            </form>

        </div>

    </div>

</div>

<script>

function lihatPassword(){

    let x=document.getElementById("password");

    if(x.type==="password"){
        x.type="text";
    }else{
        x.type="password";
    }

}

</script>

</body>
</html>