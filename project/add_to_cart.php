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
// เพิ่ม: รับค่า qty ถ้าไม่มีให้เป็น 1
$qty = isset($_GET['qty']) ? intval($_GET['qty']) : 1; 
$action = $_GET['action'] ?? '';

if($product_id > 0 && $qty > 0){
    $check = $conn->query("SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id");
    if($check->num_rows > 0){
        // ปรับ: บวกเพิ่มตามจำนวนที่ส่งมาจริง
        $conn->query("UPDATE cart SET quantity = quantity + $qty WHERE user_id = $user_id AND product_id = $product_id");
    } else {
        // ปรับ: ใส่ข้อมูลเริ่มต้นตามจำนวนที่เลือก
        $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $qty)");
    }
}

if(isset($_GET['ajax'])){
    $q = $conn->query("SELECT SUM(quantity) as total FROM cart WHERE user_id = $user_id");
    $row = $q->fetch_assoc();
    echo json_encode(['status' => 'success', 'total' => $row['total'] ?? 0]);
    exit();
}

if($action == 'buy'){
    header("Location: cart.php");
} else {
    header("Location: " . $_SERVER['HTTP_REFERER']);
}
exit();
?>