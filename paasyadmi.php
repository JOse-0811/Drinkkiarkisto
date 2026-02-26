<?php
include_once("yhteys.php");
if (!isset($_SESSION['rooli']) || ($_SESSION['rooli'] != 1)){
    header('location:kirjaudu.php');
    exit();
}
?>