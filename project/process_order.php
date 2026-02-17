<?php
session_start();
include "connectdb.php";

// 1. ตรวจสอบการเข้าสู่ระบบและการส่งข้อมูลแบบ POST
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
$order_status = "pending"; // สถานะเริ่มต้น: รอตรวจสอบ

// 2. คำนวณยอดรวมสุทธิจากตะกร้าอีกครั้งเพื่อความถูกต้อง
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

// 3. จัดการไฟล์สลิปเงินโอน (ถ้ามี)
$slip_name = "";
if ($payment_method === 'bank' && isset($_FILES['slip_image']) && $_FILES['slip_image']['error'] == 0) {
    $extension = pathinfo($_FILES['slip_image']['name'], PATHINFO_EXTENSION);
    $slip_name = "slip_" . time() . "_" . uniqid() . "." . $extension;
    move_uploaded_file($_FILES['slip_image']['tmp_name'], "uploads/slips/" . $slip_name);
}

// 4. บันทึกข้อมูลลงตาราง orders (ควรมีตารางนี้ในฐานข้อมูล)
$conn->begin_transaction(); // ใช้ Transaction เพื่อป้องกันข้อมูลผิดพลาด

try {
    $sql_order = "INSERT INTO orders (user_id, total_price, fullname, phone, address, province, zipcode, payment_method, slip_image, status, created_at) 
                  VALUES ('$user_id', '$total_price', '$fullname', '$phone', '$address', '$province', '$zipcode', '$payment_method', '$slip_name', '$order_status', NOW())";
    
    if ($conn->query($sql_order)) {
        $order_id = $conn->insert_id;

        // 5. ย้ายสินค้าจากตะกร้าไปยังรายการสั่งซื้อ (order_details)
        $cart_items->data_seek(0); // กลับไปเริ่มวนลูปใหม่
        while ($item = $cart_items->fetch_assoc()) {
            $p_id = $item['product_id'];
            $qty = $item['quantity'];
            $price = $item['price'];
            
            $sql_details = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                            VALUES ('$order_id', '$p_id', '$qty', '$price')";
            $conn->query($sql_details);
        }

        // 6. ลบสินค้าออกจากตะกร้า
        $conn->query("DELETE FROM cart WHERE user_id = $user_id");

        $conn->commit();
        echo "<script>
                alert('สั่งซื้อสินค้าสำเร็จ! ขอบคุณที่ไว้ใจความลับของเรา 🔮');
                window.location.href = 'index.php';
              </script>";
    }
} catch (Exception $e) {
    $conn->rollback();
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}

$conn->close();
?>