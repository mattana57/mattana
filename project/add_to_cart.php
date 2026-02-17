<?php
session_start();
include 'connectdb.php'; 

if(!isset($_SESSION['user_id'])){
    if(isset($_GET['ajax'])){ echo json_encode(['status' => 'error']); exit(); }
    header("Location: login.php"); exit();
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_GET['id']);
$qty = isset($_GET['qty']) ? intval($_GET['qty']) : 1; 
$action = $_GET['action'] ?? '';

if($product_id > 0 && $qty > 0){
    $check = $conn->query("SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id");
    if($check->num_rows > 0){
        $conn->query("UPDATE cart SET quantity = quantity + $qty WHERE user_id = $user_id AND product_id = $product_id");
    } else {
        $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $qty)");
    }
}

if(isset($_GET['ajax'])){
    $q = $conn->query("SELECT SUM(quantity) as total FROM cart WHERE user_id = $user_id");
    $row = $q->fetch_assoc();
    echo json_encode(['status' => 'success', 'total' => $row['total'] ?? 0]);
    exit();
}

header("Location: " . ($action == 'buy' ? 'cart.php' : $_SERVER['HTTP_REFERER']));
exit();
?>