<?php

require_once "session.php";
require_once "koneksi.php";


if(!isset($_SESSION['login'])){

    header("Location: ".BASE_URL."login.php");

    exit;

}