<?php
session_start();
include "connectdb.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 1. ดึงข้อมูลผู้ใช้จากตาราง users (ชื่อ, เบอร์โทร, วันที่สมัคร)
$user_q = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user_data = $user_q->fetch_assoc();

// 2. ดึงประวัติการสั่งซื้อ (ต้องมีตาราง orders)
$orders = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>โปรไฟล์ | Goods Secret Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #0f172a; color: #fff;
            background-image: radial-gradient(circle at top right, #3d1263, transparent), 
                              radial-gradient(circle at bottom left, #1e1b4b, transparent);
            min-height: 100vh; font-family: 'Segoe UI', sans-serif;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px);
            border: 1.5px solid rgba(187, 134, 252, 0.3); border-radius: 20px; padding: 30px;
        }
        .user-avatar {
            width: 100px; height: 100px; margin: 0 auto 20px;
            background: linear-gradient(135deg, #bb86fc, #f107a3);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem; font-weight: bold; box-shadow: 0 0 20px rgba(187, 134, 252, 0.5);
        }
        .info-box { background: rgba(255,255,255,0.05); padding: 15px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.1); }
        .text-neon-cyan { color: #00f2fe; text-shadow: 0 0 10px rgba(0, 242, 254, 0.5); }
        .table { color: #fff !important; --bs-table-bg: transparent; }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="glass-panel text-center">
                <div class="user-avatar"><?= strtoupper(substr($user_data['username'], 0, 1)) ?></div>
                <h3 class="fw-bold text-neon-cyan mb-1"><?= $user_data['username'] ?></h3>
                <p class="text-white-50 small mb-4">ID: #<?= str_pad($user_data['id'], 5, '0', STR_PAD_LEFT) ?></p>
                
                <div class="text-start info-box">
                    <div class="mb-3">
                        <label class="text-white-50 small d-block">เบอร์โทรศัพท์</label>
                        <span class="fw-bold"><?= $user_data['phone'] ?: 'ไม่ได้ระบุ' ?></span>
                    </div>
                    <div class="mb-0">
                        <label class="text-white-50 small d-block">วันที่เป็นสมาชิก</label>
                        <span class="fw-bold"><?= date('d/m/Y', strtotime($user_data['created_at'])) ?></span>
                    </div>
                </div>
                <a href="logout.php" class="btn btn-outline-danger w-100 rounded-pill mt-4">ออกจากระบบ</a>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="glass-panel h-100">
                <h4 class="mb-4 text-white"><i class="bi bi-clock-history me-2"></i>ประวัติการสั่งซื้อ</h4>
                <?php if($orders && $orders->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr class="text-white-50 small">
                                    <th>ออเดอร์</th>
                                    <th>วันที่</th>
                                    <th>ราคารวม</th>
                                    <th class="text-end">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $orders->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold">#<?= str_pad($row['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                    <td class="small"><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                    <td class="text-neon-cyan">฿<?= number_format($row['total_price']) ?></td>
                                    <td class="text-end"><span class="badge bg-info bg-opacity-10 text-info border border-info px-3">สำเร็จ</span></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 opacity-25">
                        <i class="bi bi-cart-x display-1 d-block mb-3"></i>
                        <p>ยังไม่มีประวัติการซื้อสินค้า</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>