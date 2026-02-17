<?php
session_start();
include 'connectdb.php'; 

// ตรวจสอบ Login
if(!isset($_SESSION['user_id'])){
    // ถ้ายังไม่ login ให้เก็บลง session ชั่วคราว หรือไล่ไป login
    header("Location: login.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($product_id > 0){
    // ตรวจสอบว่ามีสินค้าชิ้นนี้ในตะกร้าหรือยัง
    $check = $conn->query("SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id");
    
    if($check->num_rows > 0){
        // ถ้ามีแล้ว ให้บวกจำนวนเพิ่ม (Update)
        $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id");
    } else {
        // ถ้ายังไม่มี ให้เพิ่มใหม่ (Insert)
        $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
    }
}

header("Location: cart.php");
exit();
?>