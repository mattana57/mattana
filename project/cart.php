<?php
session_start();
include "connectdb.php";

// เช็คการเข้าสู่ระบบ (ตาม Logic เดิมของคุณ)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// --- ส่วนของ Logic จัดการตะกร้า ---

// 1. ลบสินค้า
if (isset($_GET['remove'])) {
    $pid = intval($_GET['remove']);
    $conn->query("DELETE FROM cart WHERE user_id = $user_id AND product_id = $pid");
    header("Location: cart.php");
}

// 2. ดึงข้อมูลสินค้าในตะกร้า (JOIN กับตาราง products)
$sql = "SELECT cart.*, products.name, products.price, products.image 
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = $user_id";
$items = $conn->query($sql);

$total_price = 0;
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า | Goods Secret Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: radial-gradient(circle at 20% 30%, #4b2c63 0%, transparent 40%),
                        radial-gradient(circle at 80% 70%, #6a1b9a 0%, transparent 40%),
                        linear-gradient(135deg,#120018,#2a0845,#3d1e6d);
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        }

        .cart-item-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid rgba(187, 134, 252, 0.3);
        }

        .table {
            color: #fff !important;
            vertical-align: middle;
        }

        .table thead th {
            border-bottom: 2px solid rgba(187, 134, 252, 0.3);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .table td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 20px 10px;
        }

        .btn-gradient-checkout {
            background: linear-gradient(135deg, #bb86fc, #7b2ff7);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            text-align: center;
        }

        .btn-gradient-checkout:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(123, 47, 247, 0.4);
            color: #fff;
        }

        .btn-remove {
            color: rgba(255, 255, 255, 0.5);
            transition: 0.3s;
            font-size: 1.2rem;
        }

        .btn-remove:hover {
            color: #ff4d4d;
        }

        .summary-box {
            background: rgba(187, 134, 252, 0.1);
            border-radius: 15px;
            padding: 20px;
        }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="glass-card mb-4">
                <h4 class="mb-4 d-flex align-items-center">
                    <i class="bi bi-cart3 me-2 text-warning"></i> ตะกร้าสินค้าของคุณ
                </h4>
                
                <?php if($items->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>สินค้า</th>
                                <th class="text-center">ราคา</th>
                                <th class="text-center">จำนวน</th>
                                <th class="text-center">รวม</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $items->fetch_assoc()): 
                                $subtotal = $row['price'] * $row['quantity'];
                                $total_price += $subtotal;
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="images/<?= $row['image'] ?>" class="cart-item-img me-3" alt="">
                                        <span class="fw-bold"><?= $row['name'] ?></span>
                                    </div>
                                </td>
                                <td class="text-center">฿<?= number_format($row['price']) ?></td>
                                <td class="text-center">
                                    <span class="badge bg-dark px-3 py-2"><?= $row['quantity'] ?></span>
                                </td>
                                <td class="text-center fw-bold text-warning">฿<?= number_format($subtotal) ?></td>
                                <td class="text-center">
                                    <a href="cart.php?remove=<?= $row['product_id'] ?>" class="btn-remove" onclick="return confirm('ลบสินค้านี้ใช่หรือไม่?')">
                                        <i class="bi bi-trash3"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x" style="font-size: 4rem; opacity: 0.3;"></i>
                        <p class="mt-3">ยังไม่มีสินค้าในตะกร้าของคุณ</p>
                        <a href="index.php" class="btn btn-outline-light btn-sm mt-2">ไปช้อปปิ้งกันเลย</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="glass-card">
                <h5 class="mb-4">สรุปยอดชำระ</h5>
                <div class="summary-box">
                    <div class="d-flex justify-content-between mb-2">
                        <span>ยอดรวมสินค้า</span>
                        <span>฿<?= number_format($total_price) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>ค่าจัดส่ง</span>
                        <span class="text-success">ฟรี</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="h5">ยอดสุทธิ</span>
                        <span class="h4 text-warning fw-bold">฿<?= number_format($total_price) ?></span>
                    </div>
                    
                    <button class="btn-gradient-checkout mb-3" <?= ($total_price == 0) ? 'disabled' : '' ?>>
                        ชำระเงิน <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                    
                    <a href="index.php" class="text-white text-decoration-none small opacity-75 d-block text-center hover-opacity-100">
                        <i class="bi bi-arrow-left me-1"></i> เลือกซื้อสินค้าต่อ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>