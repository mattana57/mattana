<?php
session_start();
include "connectdb.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ฟังก์ชันลบสินค้า (เชื่อมโยงจากปุ่มถังขยะ)
if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM cart WHERE user_id = $user_id AND product_id = $del_id");
    header("Location: cart.php");
}

// ดึงข้อมูลสินค้ามาแสดง
$sql = "SELECT cart.*, products.name, products.price, products.image 
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = $user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ตะกร้าสินค้า - Goods Secret</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        /* ปรับปรุงสีตัวอักษรหลักให้เป็นสีขาวสว่าง */
body {
    background-color: #0f172a;
    color: #ffffff !important; /* บังคับให้เป็นสีขาวบริสุทธิ์ */
    background-image: radial-gradient(circle at top right, #2e1065, transparent), 
                      radial-gradient(circle at bottom left, #1e1b4b, transparent);
    min-height: 100vh;
}

/* ปรับปรุงข้อความหัวข้อ */
h2, h5 {
    color: #ffffff !important;
    font-weight: 600;
}

/* ปรับปรุงความสว่างของกรอบ Glass Panel */
.glass-panel {
    background: rgba(255, 255, 255, 0.12); /* เพิ่มความสว่างพื้นหลัง */
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.3); /* เส้นขอบขาวชัดเจน */
    border-radius: 1rem;
    color: #ffffff !important;
}

/* ปรับปรุงตัวหนังสือในตาราง */
.table {
    color: #ffffff !important;
}

.table thead th {
    color: #ffffff !important;
    border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    text-transform: uppercase;
    font-size: 0.85rem;
}

/* ปรับปรุงสีของชื่อสินค้าและรายละเอียด */
.fw-bold.text-white, .text-white {
    color: #ffffff !important;
}

/* ปรับปรุงสีของข้อความรอง (Secondary) ให้สว่างขึ้นจากเดิม */
.text-secondary {
    color: #cbd5e1 !important; /* ปรับจากเทาเข้มเป็นเทาอ่อนสว่าง */
}

/* ปรับปรุงยอดเงินรวมสุทธิให้เด่นชัด */
.text-info {
    color: #00f2fe !important; /* สี Cyan Neon สว่าง */
    text-shadow: 0 0 10px rgba(0, 242, 254, 0.5);
}

/* ปรับปรุงข้อความเมื่อไม่มีสินค้าในตะกร้า */
.py-5 p.text-secondary {
    color: #e2e8f0 !important;
    font-size: 1.1rem;
}
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container py-5">
    <h2 class="mb-4"><i class="bi bi-bag-check text-purple"></i> ตะกร้าสินค้าของคุณ</h2>
    
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="glass-panel p-4">
                <?php if($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0" style="--bs-table-bg: transparent;">
                        <thead>
                            <tr class="text-secondary small">
                                <th>สินค้า</th>
                                <th class="text-end">ราคา</th>
                                <th class="text-center">จำนวน</th>
                                <th class="text-end">รวม</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $grand_total = 0;
                            while($row = $result->fetch_assoc()): 
                                $subtotal = $row['price'] * $row['quantity'];
                                $grand_total += $subtotal;
                            ?>
                            <tr class="align-middle">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="images/<?= $row['image'] ?>" class="product-img me-3">
                                        <div>
                                            <div class="fw-bold"><?= $row['name'] ?></div>
                                            <div class="text-secondary small">ID: #<?= $row['product_id'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">฿<?= number_format($row['price']) ?></td>
                                <td class="text-center"><?= $row['quantity'] ?></td>
                                <td class="text-end fw-bold">฿<?= number_format($subtotal) ?></td>
                                <td class="text-end">
                                    <a href="cart.php?delete_id=<?= $row['product_id'] ?>" 
                                       class="btn btn-sm btn-outline-danger border-0">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x display-1 opacity-25"></i>
                        <p class="mt-3 text-secondary">ยังไม่มีสินค้าในตะกร้า</p>
                        <a href="index.php" class="btn btn-purple btn-sm">เริ่มช้อปปิ้ง</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="glass-panel p-4">
                <h5 class="mb-4">สรุปการสั่งซื้อ</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">ยอดรวม</span>
                    <span>฿<?= number_format($grand_total ?? 0) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">ค่าส่ง</span>
                    <span class="text-success">ฟรี</span>
                </div>
                <hr class="my-4 opacity-10">
                <div class="d-flex justify-content-between mb-4">
                    <span class="h5">รวมสุทธิ</span>
                    <span class="h4 fw-bold text-info">฿<?= number_format($grand_total ?? 0) ?></span>
                </div>
                <button class="btn btn-checkout w-100 rounded-pill mb-3">
                    ชำระเงิน <i class="bi bi-arrow-right ms-2"></i>
                </button>
                <a href="index.php" class="btn btn-link w-100 text-secondary text-decoration-none small">
                    <i class="bi bi-chevron-left"></i> เลือกซื้อสินค้าต่อ
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>