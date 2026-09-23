<?php

require "config/session.php";

if (isset($_SESSION['login'])) {

    if ($_SESSION['role'] == "admin") {

        header("Location: admin/dashboard.php");

    } else {

        header("Location: user/dashboard.php");

    }

} else {

    header("Location: login.php");

}

exit;