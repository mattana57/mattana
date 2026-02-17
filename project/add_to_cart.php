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
$variant_id = isset($_GET['variant_id']) ? intval($_GET['variant_id']) : 0; // รับค่า Variant ID
$action = $_GET['action'] ?? '';

if($product_id > 0 && $qty > 0){
    // ตรวจสอบทั้ง Product ID และ Variant ID เพื่อไม่ให้บวกรวมกันมั่วถ้าคนละแบบ
    $check = $conn->query("SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id AND variant_id = $variant_id");
    
    if($check->num_rows > 0){
        $conn->query("UPDATE cart SET quantity = quantity +<?php
session_start();
include 'connectdb.php'; 

if(!isset($_SESSION['user_id'])){
    if(isset($_GET['ajax'])){ echo json_encode(['status' => 'error']); exit(); }
    header("Location: login.php"); exit();
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_GET['id']);
$qty = isset($_GET['qty']) ? intval($_GET['qty']) : 1; 
$variant_id = isset($_GET['variant_id']) ? intval($_GET['variant_id']) : 0; 
$action = $_GET['action'] ?? '';

if($product_id > 0 && $qty > 0){
    // ตรวจสอบว่ามีสินค้ารหัสนี้ และ "แบบ" นี้อยู่ในตะกร้าหรือยัง
    $check = $conn->query("SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id AND variant_id = $variant_id");
    
    if($check->num_rows > 0){
        $conn->query("UPDATE cart SET quantity = quantity + $qty WHERE user_id = $user_id AND product_id = $product_id AND variant_id = $variant_id");
    } else {
        $conn->query("INSERT INTO cart (user_id, product_id, quantity, variant_id) VALUES ($user_id, $product_id, $qty, $variant_id)");
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
?> $qty WHERE user_id = $user_id AND product_id = $product_id AND variant_id = $variant_id");
    } else {
        $conn->query("INSERT INTO cart (user_id, product_id, quantity, variant_id) VALUES ($user_id, $product_id, $qty, $variant_id)");
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