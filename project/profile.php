<?php
session_start();
include "connectdb.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// --- ส่วนที่เพิ่ม: รับค่าการแก้ไขข้อมูล ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $province = $conn->real_escape_string($_POST['province']);
    $zipcode = $conn->real_escape_string($_POST['zipcode']);

    $conn->query("UPDATE users SET 
        fullname = '$fullname', 
        phone = '$phone', 
        address = '$address', 
        province = '$province', 
        zipcode = '$zipcode' 
        WHERE id = $user_id");
    echo "<script>alert('อัปเดตข้อมูลเรียบร้อย!'); window.location.href='profile.php';</script>";
}

// --- ส่วนที่เพิ่ม: ลบข้อมูลที่อยู่ (ล้างค่าว่าง) ---
if (isset($_GET['clear_address'])) {
    $conn->query("UPDATE users SET fullname='', address='', province='', zipcode='' WHERE id = $user_id");
    header("Location: profile.php");
}

// 1. ดึงข้อมูลผู้ใช้ (รวมคอลัมน์ใหม่ที่เพิ่มเข้าไป)
$user_q = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user_data = $user_q->fetch_assoc();

// 2. ดึงประวัติการสั่งซื้อ
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
        .card-profile {
            background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; overflow: hidden;
        }
        .text-neon-cyan { color: #00f2fe; text-shadow: 0 0 10px rgba(0, 242, 254, 0.5); }
        .form-control { background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255,255,255,0.1); color: #fff; }
        .form-control:focus { background: rgba(15, 23, 42, 0.8); border-color: #00f2fe; color: #fff; box-shadow: 0 0 10px rgba(0, 242, 254, 0.2); }
        .btn-update { background: linear-gradient(45deg, #00f2fe, #00c6ff); color: #0f172a; border: none; font-weight: bold; }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card-profile p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="text-neon-cyan mb-0"><i class="bi bi-person-gear me-2"></i> ข้อมูลส่วนตัว</h4>
                    <a href="?clear_address=1" class="btn btn-outline-danger btn-sm" onclick="return confirm('ล้างข้อมูลที่อยู่ใช่ไหม?')">
                        <i class="bi bi-trash"></i> ล้างข้อมูล
                    </a>
                </div>
                
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="small opacity-75">ชื่อ-นามสกุล</label>
                        <input type="text" name="fullname" class="form-control" value="<?= $user_data['fullname'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label class="small opacity-75">อีเมล</label>
                        <input type="text" class="form-control" value="<?= $user_data['email'] ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="small opacity-75">เบอร์โทรศัพท์</label>
                        <input type="tel" name="phone" class="form-control" value="<?= $user_data['phone'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label class="small opacity-75">ที่อยู่จัดส่ง</label>
                        <textarea name="address" class="form-control" rows="3"><?= $user_data['address'] ?? '' ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="small opacity-75">จังหวัด</label>
                            <input type="text" name="province" class="form-control" value="<?= $user_data['province'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small opacity-75">รหัสไปรษณีย์</label>
                            <input type="text" name="zipcode" class="form-control" value="<?= $user_data['zipcode'] ?? '' ?>">
                        </div>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-update w-100 py-2 mt-2">บันทึกการเปลี่ยนแปลง</button>
                </form>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card-profile p-4">
                <h4 class="text-neon-cyan mb-4"><i class="bi bi-clock-history me-2"></i> ประวัติการสั่งซื้อ</h4>
                <?php if($orders->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover border-secondary">
                            <thead>
                                <tr class="small text-uppercase opacity-50">
                                    <th>ออเดอร์</th>
                                    <th>วันที่</th>
                                    <th>ราคารวม</th>
                                    <th class="text-end">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $orders->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold text-info">#<?= str_pad($row['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                    <td class="small"><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                    <td class="text-neon-cyan">฿<?= number_format($row['total_price']) ?></td>
                                    <td class="text-end">
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info px-3">
                                            <?= $row['status'] == 'pending' ? 'รอตรวจสอบ' : 'สำเร็จ' ?>
                                        </span>
                                    </td>
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