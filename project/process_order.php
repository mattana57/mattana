<?php
session_start();
include "connectdb.php";

// ป้องกันการเข้าถึงโดยตรง
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$fullname = $conn->real_escape_string($_POST['fullname']);
$phone = $conn->real_escape_string($_POST['phone']);
$address = $conn->real_escape_string($_POST['address']);
$province = $conn->real_escape_string($_POST['province']);
$zipcode = $conn->real_escape_string($_POST['zipcode']);
$payment_method = $_POST['payment_method'];
$order_status = "pending";

// ดึงข้อมูลในตะกร้ามาคำนวณราคา
$sql_cart = "SELECT cart.*, products.price FROM cart 
             JOIN products ON cart.product_id = products.id 
             WHERE cart.user_id = $user_id";
$cart_items = $conn->query($sql_cart);

if ($cart_items->num_rows == 0) {
    die("ไม่มีสินค้าในตะกร้า");
}

$total_price = 0;
while ($item = $cart_items->fetch_assoc()) {
    $total_price += ($item['price'] * $item['quantity']);
}

// จัดการรูปภาพสลิป
$slip_name = "";
if ($payment_method === 'bank_transfer' && isset($_FILES['slip_image']) && $_FILES['slip_image']['error'] == 0) {
    $ext = pathinfo($_FILES['slip_image']['name'], PATHINFO_EXTENSION);
    $slip_name = "slip_" . time() . "_" . $user_id . "." . $ext;
    
    if (!is_dir("uploads/slips/")) { mkdir("uploads/slips/", 0777, true); }
    move_uploaded_file($_FILES['slip_image']['tmp_name'], "uploads/slips/" . $slip_name);
}

$conn->begin_transaction();

try {
    // --- [เพิ่ม]: อัปเดตข้อมูลที่อยู่จัดส่งกลับไปที่โปรไฟล์ลูกค้า เพื่อความสะดวกในครั้งหน้า ---
    $sql_update_user = "UPDATE users SET 
                        fullname = '$fullname', 
                        phone = '$phone', 
                        address = '$address', 
                        province = '$province', 
                        zipcode = '$zipcode' 
                        WHERE id = $user_id";
    $conn->query($sql_update_user);

    // บันทึกข้อมูลออเดอร์
    $sql_order = "INSERT INTO orders (user_id, total_price, fullname, phone, address, province, zipcode, payment_method, slip_image, status, created_at) 
                  VALUES ('$user_id', '$total_price', '$fullname', '$phone', '$address', '$province', '$zipcode', '$payment_method', '$slip_name', '$order_status', NOW())";
    
    if ($conn->query($sql_order)) {
        $order_id = $conn->insert_id;
        
        // บันทึกรายละเอียดสินค้าในออเดอร์
        $cart_items->data_seek(0);
        while ($item = $cart_items->fetch_assoc()) {
            $p_id = $item['product_id'];
            $qty = $item['quantity'];
            $price = $item['price'];
            $sql_details = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                            VALUES ('$order_id', '$p_id', '$qty', '$price')";
            $conn->query($sql_details);
        }
        
        // ล้างตะกร้าสินค้า
        $conn->query("DELETE FROM cart WHERE user_id = $user_id");
        
        $conn->commit();
        echo "<script>alert('สั่งซื้อสินค้าเรียบร้อยแล้ว!'); window.location.href='profile.php';</script>";
    }
} catch (Exception $e) {
    $conn->rollback();
    echo "เกิดข้อผิดพลาดในการประมวลผล: " . $e->getMessage();
}
?>