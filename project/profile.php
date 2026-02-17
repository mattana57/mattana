<?php
session_start();
include "connectdb.php";

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 1. ดึงข้อมูลผู้ใช้งาน
$user_q = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user_data = $user_q->fetch_assoc();

// 2. ดึงประวัติการสั่งซื้อ (เรียงจากล่าสุดขึ้นก่อน)
$order_sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
$orders = $conn->query($order_sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>บัญชีของฉัน | Goods Secret Store</title>
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
            margin-bottom: 30px;
        }
        .text-neon-cyan { color: #00f2fe; text-shadow: 0 0 10px rgba(0, 242, 254, 0.5); }
        .text-neon-purple { color: #bb86fc; text-shadow: 0 0 10px rgba(187, 134, 252, 0.5); }
        
        /* สไตล์ตารางประวัติการสั่งซื้อ */
        .table { color: #ffffff !important; --bs-table-bg: transparent; }
        .table thead th { color: #bb86fc; border-bottom: 2px solid rgba(187, 134, 252, 0.3); }
        
        /* สไตล์ป้ายสถานะ (Status Badge) */
        .badge-pending { background: rgba(255, 193, 7, 0.1); color: #ffc107; border: 1px solid #ffc107; }
        .badge-paid { background: rgba(0, 242, 254, 0.1); color: #00f2fe; border: 1px solid #00f2fe; }
        .badge-shipped { background: rgba(40, 255, 191, 0.1); color: #28ffbf; border: 1px solid #28ffbf; }
        
        .user-avatar {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, #bb86fc, #f107a3);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; font-weight: bold; margin-bottom: 15px;
        }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-4">
            <div class="glass-panel text-center">
                <div class="d-flex justify-content-center">
                    <div class="user-avatar text-white">
                        <?= strtoupper(substr($user_data['username'], 0, 1)) ?>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-white"><?= $user_data['username'] ?></h3>
                <p class="text-white-50 mb-4"><?= $user_data['email'] ?></p>
                <hr class="border-secondary opacity-25">
                <div class="text-start mt-4">
                    <p class="mb-2 small text-secondary-bright">ระดับสมาชิก: <span class="text-neon-purple">VIP Member</span></p>
                    <p class="mb-2 small text-secondary-bright">วันที่เข้าร่วม: <?= date('d/m/Y', strtotime($user_data['created_at'])) ?></p>
                </div>
                <a href="logout.php" class="btn btn-outline-danger w-100 rounded-pill mt-4">ออกจากระบบ</a>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="glass-panel">
                <h4 class="mb-4 text-neon-cyan"><i class="bi bi-box-seam me-2"></i>ประวัติการสั่งซื้อสินค้า</h4>
                
                <?php if($orders->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>ออเดอร์ #</th>
                                <th>วันที่สั่ง</th>
                                <th>ราคารวม</th>
                                <th>วิธีชำระ</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $orders->fetch_assoc()): ?>
                            <tr>
                                <td class="fw-bold text-white">#<?= str_pad($row['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                <td class="small text-white-50"><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                                <td class="text-neon-cyan">฿<?= number_format($row['total_price']) ?></td>
                                <td>
                                    <span class="small text-white">
                                        <?= $row['payment_method'] == 'bank' ? 'โอนเงิน (สลิป)' : 'เก็บเงินปลายทาง' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                        $s = $row['status'];
                                        $class = ($s == 'pending') ? 'badge-pending' : (($s == 'paid') ? 'badge-paid' : 'badge-shipped');
                                        $text = ($s == 'pending') ? 'รอตรวจสอบ' : (($s == 'paid') ? 'ชำระเงินแล้ว' : 'ส่งแล้ว');
                                    ?>
                                    <span class="badge <?= $class ?> rounded-pill px-3 py-2"><?= $text ?></span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5 opacity-50">
                    <i class="bi bi-cart-x display-1 d-block mb-3"></i>
                    <p>คุณยังไม่มีประวัติการสั่งซื้อในขณะนี้</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>