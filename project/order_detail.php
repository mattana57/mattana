<?php
session_start();
include "connectdb.php";

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: profile.php");
    exit();
}

$order_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];
$update_msg = "";

// --- [ฟังก์ชันใหม่]: อัปเดตข้อมูลผู้รับในบิลนี้ ---
if (isset($_POST['update_shipping'])) {
    $new_fullname = $conn->real_escape_string($_POST['fullname']);
    $new_phone = $conn->real_escape_string($_POST['phone']);
    $new_address = $conn->real_escape_string($_POST['address']);
    $new_province = $conn->real_escape_string($_POST['province']);
    $new_zipcode = $conn->real_escape_string($_POST['zipcode']);

    $sql_upd = "UPDATE orders SET 
                fullname = '$new_fullname', phone = '$new_phone', address = '$new_address', 
                province = '$new_province', zipcode = '$new_zipcode' 
                WHERE id = $order_id AND user_id = $user_id AND status = 'pending'";
    if($conn->query($sql_upd)) {
        $update_msg = "อัปเดตข้อมูลผู้รับเรียบร้อยแล้ว ✨";
    }
}

// ดึงข้อมูลออเดอร์
$order_q = $conn->query("SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id");
$order = $order_q->fetch_assoc();
if (!$order) { die("ไม่พบข้อมูลออเดอร์"); }

$items_q = $conn->query("SELECT od.*, p.name FROM order_details od JOIN products p ON od.product_id = p.id WHERE od.order_id = $order_id");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายละเอียดบิล #<?= $order_id ?> | Goods Secret Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        /* --- ธีมลึกลับม่วง-ดำ (Mystery Neon) --- */
        body { 
            background: radial-gradient(circle at 20% 30%, #4b2c63 0%, transparent 40%), 
                        linear-gradient(135deg,#120018,#2a0845,#3d1e6d); 
            color: #fff; font-family: 'Segoe UI', sans-serif; min-height: 100vh; 
        }
        .invoice-card { 
            background: rgba(26, 0, 40, 0.75); backdrop-filter: blur(20px); 
            border: 1px solid rgba(187, 134, 252, 0.3); border-radius: 30px; padding: 40px; 
        }
        .text-neon-cyan { color: #00f2fe; text-shadow: 0 0 10px #00f2fe; }
        .text-neon-purple { color: #bb86fc; text-shadow: 0 0 10px #bb86fc; }
        .status-pill { 
            background: rgba(187, 134, 252, 0.1); color: #bb86fc; 
            border: 1px solid #bb86fc; padding: 5px 20px; border-radius: 50px; 
        }
        .form-control { 
            background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(187, 134, 252, 0.3); 
            color: #fff; border-radius: 12px; 
        }
        .form-control:focus { background: rgba(255, 255, 255, 0.1); border-color: #00f2fe; color: #fff; box-shadow: none; }
        .item-list { background: rgba(255, 255, 255, 0.02); border-radius: 20px; padding: 25px; border: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body>
<div class="container py-5">
    <?php if($update_msg): ?>
        <div class="alert alert-info border-0 text-center mb-4" style="background: rgba(0, 242, 254, 0.1); color: #00f2fe; border-radius: 15px;">
            <?= $update_msg ?>
        </div>
    <?php endif; ?>

    <div class="invoice-card mx-auto" style="max-width: 900px;">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <a href="profile.php" class="btn btn-outline-light btn-sm rounded-pill"><i class="bi bi-arrow-left"></i> ย้อนกลับ</a>
            <h3 class="text-neon-cyan mb-0">ใบเสร็จความลับ</h3>
            <span class="status-pill fw-bold">
                <?= $order['status'] == 'pending' ? 'รอการตรวจสอบ' : $order['status'] ?>
            </span>
        </div>

        <form method="POST">
            <div class="row g-4 mb-5">
                <div class="col-md-7 border-end border-secondary border-opacity-25">
                    <h6 class="text-neon-purple opacity-75 mb-4 text-uppercase"><i class="bi bi-pencil-square me-2"></i>ข้อมูลจัดส่ง (แก้ไขได้ถ้ายังไม่ส่ง)</h6>
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="small opacity-50 mb-1">ชื่อผู้รับ</label>
                            <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($order['fullname']) ?>" <?= $order['status'] != 'pending' ? 'disabled' : '' ?>>
                        </div>
                        <div class="col-md-5">
                            <label class="small opacity-50 mb-1">เบอร์ติดต่อ</label>
                            <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($order['phone']) ?>" <?= $order['status'] != 'pending' ? 'disabled' : '' ?>>
                        </div>
                        <div class="col-12">
                            <label class="small opacity-50 mb-1">ที่อยู่โดยละเอียด</label>
                            <textarea name="address" class="form-control" rows="3" <?= $order['status'] != 'pending' ? 'disabled' : '' ?>><?= htmlspecialchars($order['address']) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="small opacity-50 mb-1">จังหวัด</label>
                            <input type="text" name="province" class="form-control" value="<?= htmlspecialchars($order['province']) ?>" <?= $order['status'] != 'pending' ? 'disabled' : '' ?>>
                        </div>
                        <div class="col-md-6">
                            <label class="small opacity-50 mb-1">รหัสไปรษณีย์</label>
                            <input type="text" name="zipcode" class="form-control" value="<?= htmlspecialchars($order['zipcode']) ?>" <?= $order['status'] != 'pending' ? 'disabled' : '' ?>>
                        </div>
                        <?php if($order['status'] == 'pending'): ?>
                        <div class="col-12">
                            <button type="submit" name="update_shipping" class="btn btn-sm btn-outline-info rounded-pill mt-2">บันทึกการเปลี่ยนแปลงที่อยู่</button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-5 ps-md-4">
                    <h6 class="text-neon-purple opacity-75 mb-4 text-uppercase">สรุปออเดอร์</h6>
                    <p class="mb-2">รหัสออเดอร์: <span class="text-neon-cyan">#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></span></p>
                    <p class="mb-2 opacity-50 small">สั่งซื้อเมื่อ: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                    <p class="mb-4 opacity-75 small">ช่องทางชำระ: <?= $order['payment_method'] == 'bank' ? 'โอนผ่านธนาคาร' : 'เก็บเงินปลายทาง' ?></p>
                    
                    <?php if($order['status'] == 'pending'): ?>
                    <hr class="opacity-10">
                    <p class="small text-danger mb-2"><i class="bi bi-exclamation-triangle me-1"></i>คุณสามารถเปลี่ยนที่อยู่หรือยกเลิกได้ก่อนสถานะเปลี่ยน</p>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <div class="item-list mb-5">
            <h6 class="text-neon-purple mb-4">รายการสินค้าในบิลนี้</h6>
            <?php while($item = $items_q->fetch_assoc()): ?>
            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom border-white border-opacity-10">
                <div>
                    <span class="fw-bold"><?= $item['name'] ?></span>
                    <br><small class="opacity-50 text-neon-purple">จำนวน: <?= $item['quantity'] ?> ชิ้น</small>
                </div>
                <span class="text-neon-cyan fw-bold">฿<?= number_format($item['price'] * $item['quantity']) ?></span>
            </div>
            <?php endwhile; ?>

            <div class="d-flex justify-content-between pt-4">
                <span class="h5 fw-bold">ราคาสุทธิ</span>
                <span class="h4 fw-bold" style="color:#f107a3; text-shadow: 0 0 10px #f107a3;">฿<?= number_format($order['total_price']) ?></span>
            </div>
        </div>

        <div class="d-flex gap-3 mt-4">
            <?php if($order['status'] == 'pending'): ?>
                <a href="profile.php?cancel_order=<?= $order['id'] ?>" class="btn btn-outline-danger w-100 rounded-pill py-3" onclick="return confirm('ยืนยันยกเลิกความลับรายการนี้?')">
                    ยกเลิกคำสั่งซื้อนี้
                </a>
            <?php endif; ?>
            <a href="index.php" class="btn btn-outline-info w-100 rounded-pill py-3">ไปช้อปต่อ</a>
        </div>
    </div>
</div>
</body>
</html>