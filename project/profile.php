<?php
session_start();
include "connectdb.php";

// 1. ตรวจสอบ Login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. ดึงข้อมูลจากตาราง users (เชื่อมกับตารางที่คุณส่งรูปมา)
$user_q = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user_data = $user_q->fetch_assoc();

// 3. ดึงประวัติการสั่งซื้อจากตาราง orders (ถ้ายังไม่สร้างตารางจะไม่มีข้อมูลขึ้น)
$order_sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
$orders = $conn->query($order_sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>โปรไฟล์ของฉัน | Goods Secret Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #0f172a;
            color: #ffffff;
            background-image: radial-gradient(circle at top right, #3d1263, transparent), 
                              radial-gradient(circle at bottom left, #1e1b4b, transparent);
            min-height: 100vh;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1.5px solid rgba(187, 134, 252, 0.3);
            border-radius: 20px;
            padding: 30px;
        }
        .text-neon-cyan { color: #00f2fe; text-shadow: 0 0 10px rgba(0, 242, 254, 0.5); }
        .text-neon-purple { color: #bb86fc; text-shadow: 0 0 10px rgba(187, 134, 252, 0.5); }
        
        .user-avatar {
            width: 100px; height: 100px;
            background: linear-gradient(135deg, #bb86fc, #f107a3);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem; font-weight: bold; margin: 0 auto 20px;
            box-shadow: 0 0 20px rgba(187, 134, 252, 0.5);
        }
        .info-label { color: rgba(255,255,255,0.5); font-size: 0.9rem; }
        .info-value { font-size: 1.1rem; font-weight: 500; color: #fff; }
        
        .table { color: #ffffff !important; --bs-table-bg: transparent; }
        .table thead th { color: #bb86fc; border-bottom: 2px solid rgba(187, 134, 252, 0.3); }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="glass-panel text-center">
                <div class="user-avatar">
                    <?= strtoupper(substr($user_data['username'], 0, 1)) ?>
                </div>
                <h3 class="fw-bold mb-1 text-neon-cyan"><?= $user_data['username'] ?></h3>
                <p class="text-white-50 small mb-4">Member ID: #<?= str_pad($user_id, 5, '0', STR_PAD_LEFT) ?></p>
                
                <div class="text-start bg-white bg-opacity-10 p-3 rounded-4 mb-4">
                    <div class="mb-3">
                        <div class="info-label"><i class="bi bi-telephone me-2"></i>เบอร์โทรศัพท์</div>
                        <div class="info-value"><?= $user_data['phone'] ?: 'ไม่ได้ระบุเบอร์โทร' ?></div>
                    </div>
                    <div class="mb-0">
                        <div class="info-label"><i class="bi bi-calendar3 me-2"></i>วันที่เข้าร่วม</div>
                        <div class="info-value"><?= date('d/m/Y', strtotime($user_data['created_at'])) ?></div>
                    </div>
                </div>
                
                <a href="logout.php" class="btn btn-outline-danger w-100 rounded-pill">ออกจากระบบ</a>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="glass-panel h-100">
                <h4 class="mb-4 text-neon-purple"><i class="bi bi-bag-check me-2"></i>ประวัติการสั่งซื้อของคุณ</h4>
                
                <?php if($orders && $orders->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>เลขออเดอร์</th>
                                <th>วันที่</th>
                                <th>ยอดรวม</th>
                                <th class="text-end">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $orders->fetch_assoc()): ?>
                            <tr>
                                <td class="fw-bold text-white">#<?= str_pad($row['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                <td class="small"><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                <td class="text-neon-cyan">฿<?= number_format($row['total_price']) ?></td>
                                <td class="text-end">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2">สำเร็จ</span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5 opacity-50">
                    <i class="bi bi-cart-x display-1 mb-3 d-block"></i>
                    <p>ยังไม่มีรายการสั่งซื้อในระบบ</p>
                    <a href="index.php" class="btn btn-outline-info btn-sm rounded-pill mt-2">ไปช้อปปิ้งเลย</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>