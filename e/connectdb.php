<?php
$host = "localhost";
    $user = "root";
    $pwd = "groupCar_toon05";
    $db = "4057db";
    $conn = mysqli_connect($host,$user,$pwd,$db) or die ("เชื่อมฐานข้อมูลไม่ได้");
    mysqli_query($conn,"set NAMES utf8");
?>