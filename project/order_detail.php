<?php
session_start();
include "connectdb.php";

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: profile.php");
    exit();
}

$order_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// ดึงข้อมูลออเดอร์และที่อยู่
$sql_order = "SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id";
$res_order = $conn->query($sql_order);
$order = $res_order->fetch_assoc();

if (!$order) { die("ไม่พบข้อมูลออเดอร์นี้"); }

// ดึงรายการสินค้าในบิลนี้
$sql_items = "SELECT od.*, p.name, p.image FROM order_details od 
              JOIN products p ON od.product_id = p.id 
              WHERE od.order_id = $order_id";
$res_items = $conn->query($sql_items);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายละเอียดคำสั่งซื้อ #<?= $order_id ?> | Goods Secret Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: radial-gradient(circle at 20% 30%, #4b2c63 0%, transparent 40%), 
                        linear-gradient(135deg,#120018,#2a0845,#3d1e6d);
            color: #fff; font-family: 'Segoe UI', sans-serif; min-height: 100vh;
        }
        .detail-card { background: rgba(26, 0, 40, 0.7); backdrop-filter: blur(15px); border: 1px solid rgba(187, 134, 252, 0.3); border-radius: 25px; padding: 40px; }
        .text-neon-cyan { color: #00f2fe; text-shadow: 0 0 10px rgba(0, 242, 254, 0.5); }
        .status-badge { background: rgba(187, 134, 252, 0.1); color: #bb86fc; border: 1px solid #bb86fc; padding: 8px 20px; border-radius: 50px; }
        .item-row { background: rgba(255,255,255,0.03); border-radius: 15px; padding: 15px; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="detail-card mx-auto" style="max-width: 800px;">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="text-neon-cyan mb-1">รายละเอียดคำสั่งซื้อ</h2>
                <p class="opacity-50">หมายเลขบิล: #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></p>
            </div>
            <div class="status-badge fw-bold"><?= $order['status'] ?></div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <h5 class="text-neon-purple mb-3"><i class="bi bi-geo-alt me-2"></i>ที่อยู่จัดส่ง</h5>
                <p class="mb-1 fw-bold"><?= htmlspecialchars($order['fullname']) ?></p>
                <p class="mb-1 opacity-75"><?= htmlspecialchars($order['phone']) ?></p>
                <p class="small opacity-50"><?= htmlspecialchars($order['address']) ?> <?= htmlspecialchars($order['province']) ?> <?= htmlspecialchars($order['zipcode']) ?></p>
            </div>
            <div class="col-md-6 text-md-end">
                <h5 class="text-neon-purple mb-3">การชำระเงิน</h5>
                <p class="mb-1 opacity-75">วิธีชำระ: <?= $order['payment_method'] == 'bank' ? 'โอนเงินผ่านธนาคาร' : 'เก็บเงินปลายทาง' ?></p>
                <p class="mb-1 opacity-75">วันที่สั่ง: <?= date('d M Y H:i', strtotime($order['created_at'])) ?></p>
            </div>
        </div>

        <h5 class="text-neon-purple mb-3">รายการสินค้า</h5>
        <?php while($item = $res_items->fetch_assoc()): ?>
        <div class="item-row d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="ms-3">
                    <p class="mb-0 fw-bold"><?= $item['name'] ?></p>
                    <small class="opacity-50">จำนวน: <?= $item['quantity'] ?></small>
                </div>
            </div>
            <p class="mb-0 fw-bold text-neon-cyan">฿<?= number_format($item['price'] * $item['quantity']) ?></p>
        </div>
        <?php endwhile; ?>

        <div class="mt-5 pt-4 border-top border-secondary">
            <div class="d-flex justify-content-between h4 mb-5">
                <span>ยอดรวมสุทธิ</span>
                <span class="text-neon-cyan fw-bold">฿<?= number_format($order['total_price']) ?></span>
            </div>

            <div class="d-flex gap-3">
                <a href="profile.php" class="btn btn-outline-light w-100 rounded-pill py-3">กลับไปหน้าโปรไฟล์</a>
                
                <?php if($order['status'] == 'pending'): ?>
                <a href="profile.php?cancel_order=<?= $order['id'] ?>" 
                   class="btn btn-danger w-100 rounded-pill py-3"
                   onclick="return confirm('คุณแน่ใจหรือไม่ที่จะยกเลิกความลับรายการนี้?')">ยกเลิกคำสั่งซื้อ</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>