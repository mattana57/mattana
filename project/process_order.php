<?php
// เปิดการแสดง Error ทั้งหมดเพื่อใช้ตรวจสอบจุดที่ผิดพลาด (เมื่อระบบใช้ได้จริงควรปิดส่วนนี้)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include "connectdb.php";

// 1. ตรวจสอบการเข้าสู่ระบบและการส่งข้อมูล
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

// 2. ดึงข้อมูลสินค้าในตะกร้ามาคำนวณยอดรวม
$sql_cart = "SELECT cart.*, products.price FROM cart 
             JOIN products ON cart.product_id = products.id 
             WHERE cart.user_id = $user_id";
$cart_items = $conn->query($sql_cart);

if ($cart_items->num_rows == 0) {
    die("ไม่พบสินค้าในตะกร้าของคุณ");
}

$total_price = 0;
while ($item = $cart_items->fetch_assoc()) {
    $total_price += ($item['price'] * $item['quantity']);
}

// 3. จัดการอัปโหลดไฟล์สลิป (เฉพาะการโอนเงิน)
$slip_name = "";
if ($payment_method === 'bank_transfer' && isset($_FILES['slip_image']) && $_FILES['slip_image']['error'] == 0) {
    $ext = pathinfo($_FILES['slip_image']['name'], PATHINFO_EXTENSION);
    $slip_name = "slip_" . time() . "_" . $user_id . "." . $ext;
    
    // ตรวจสอบและสร้างโฟลเดอร์สำหรับเก็บไฟล์
    if (!is_dir("uploads/slips/")) { 
        mkdir("uploads/slips/", 0777, true); 
    }
    move_uploaded_file($_FILES['slip_image']['tmp_name'], "uploads/slips/" . $slip_name);
}

// 4. เริ่มขั้นตอนบันทึกลงฐานข้อมูล
$conn->begin_transaction();

try {
    // อัปเดตข้อมูลที่อยู่จัดส่งลงในโปรไฟล์ผู้ใช้
    $sql_update_user = "UPDATE users SET 
                        fullname = '$fullname', 
                        phone = '$phone', 
                        address = '$address', 
                        province = '$province', 
                        zipcode = '$zipcode' 
                        WHERE id = $user_id";
    
    if(!$conn->query($sql_update_user)) {
        // หากเกิด Error ตรงนี้ แสดงว่าคอลัมน์ใน DB ยังไม่มี ให้รัน SQL เพิ่มคอลัมน์ครับ
        throw new Exception("Error Updating User: " . $conn->error);
    }

    // บันทึกข้อมูลคำสั่งซื้อหลักลงตาราง orders
    $sql_order = "INSERT INTO orders (user_id, total_price, fullname, phone, address, province, zipcode, payment_method, slip_image, status, created_at) 
                  VALUES ('$user_id', '$total_price', '$fullname', '$phone', '$address', '$province', '$zipcode', '$payment_method', '$slip_name', '$order_status', NOW())";
    
    if ($conn->query($sql_order)) {
        $order_id = $conn->insert_id;
        
        // บันทึกรายละเอียดสินค้าทีละชิ้นลงตาราง order_details
        $cart_items->data_seek(0);
        while ($item = $cart_items->fetch_assoc()) {
            $p_id = $item['product_id'];
            $qty = $item['quantity'];
            $price = $item['price'];
            $sql_details = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                            VALUES ('$order_id', '$p_id', '$qty', '$price')";
            $conn->query($sql_details);
        }
        
        // ล้างสินค้าออกจากตะกร้าเมื่อสั่งซื้อสำเร็จ
        $conn->query("DELETE FROM cart WHERE user_id = $user_id");
        
        $conn->commit();
        echo "<script>alert('สั่งซื้อสินค้าเรียบร้อยแล้ว!'); window.location.href='profile.php';</script>";
        exit();
    } else {
        throw new Exception("Error Inserting Order: " . $conn->error);
    }
} catch (Exception $e) {
    $conn->rollback();
    // ถ้าพัง ระบบจะหยุดและแจ้งเตือนทันที ไม่ปล่อยให้หน้าขาว
    die("เกิดข้อผิดพลาดในการประมวลผล: " . $e->getMessage());
}
?>