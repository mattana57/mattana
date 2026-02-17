<?php
session_start();
include 'connectdb.php'; 

if(!isset($_GET['id'])){
    header("Location: cart.php");
    exit();
}

$id = $_GET['id'];

/* สร้างตะกร้าใน session ถ้ายังไม่มี */
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

/* ถ้ามีสินค้าอยู่แล้ว เพิ่มจำนวน */
if(isset($_SESSION['cart'][$id])){
    $_SESSION['cart'][$id]++;
}else{
    $_SESSION['cart'][$id] = 1;
}

header("Location: cart.php");
exit();
?>
