<?php
session_start();
include 'connectdb.php'; 

if(!isset($_SESSION['user_id'])){
    if(isset($_GET['ajax'])){
        echo json_encode(['status' => 'error', 'message' => 'not_logged_in']);
        exit();
    }
    header("Location: login.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$action = $_GET['action'] ?? '';

if($product_id > 0){
    $check = $conn->query("SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id");
    if($check->num_rows > 0){
        $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id");
    } else {
        $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
    }
}

// กรณีเรียกผ่าน AJAX
if(isset($_GET['ajax'])){
    $q = $conn->query("SELECT SUM(quantity) as total FROM cart WHERE user_id = $user_id");
    $row = $q->fetch_assoc();
    echo json_encode(['status' => 'success', 'total' => $row['total'] ?? 0]);
    exit();
}

// กรณีปุ่ม "สั่งซื้อ"
if($action == 'buy'){
    header("Location: cart.php");
} else {
    header("Location: " . $_SERVER['HTTP_REFERER']);
}
exit();
?>