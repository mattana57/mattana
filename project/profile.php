<?php
session_start();
include "connectdb.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// --- ระบบบันทึกการแก้ไขข้อมูล ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $province = $conn->real_escape_string($_POST['province']);
    $zipcode = $conn->real_escape_string($_POST['zipcode']);

    $sql = "UPDATE users SET 
            fullname = '$fullname', 
            phone = '$phone', 
            address = '$address', 
            province = '$province', 
            zipcode = '$zipcode' 
            WHERE id = $user_id";
    
    if($conn->query($sql)) {
        echo "<script>alert('อัปเดตข้อมูลส่วนตัวเรียบร้อยแล้ว ✨'); window.location.href='profile.php';</script>";
    }
}

// --- ระบบล้างข้อมูลที่อยู่ ---
if (isset($_GET['clear_address'])) {
    $conn->query("UPDATE users SET fullname='', address='', province='', zipcode='', phone='' WHERE id = $user_id");
    header("Location: profile.php");
    exit();
}

// ดึงข้อมูลผู้ใช้ล่าสุด
$user_q = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user_data = $user_q->fetch_assoc();

// ดึงประวัติการสั่งซื้อ
$orders = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ | Goods Secret Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --neon-cyan: #00f2fe;
            --neon-purple: #bb86fc;
            --bg-dark: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
        }

        body {
            background-color: var(--bg-dark);
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            background-image: radial-gradient(circle at top right, #3d1263, transparent), 
                              radial-gradient(circle at bottom left, #1e1b4b, transparent);
            min-height: 100vh;
        }

        .card-profile {
            background: var(--card-bg);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .text-neon-cyan {
            color: var(--neon-cyan);
            text-shadow: 0 0 10px rgba(0, 242, 254, 0.5);
        }

        /* แต่ง Input ให้เข้ากับธีม */
        .custom-input {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(187, 134, 252, 0.3) !important;
            color: #fff !important;
            border-radius: 12px !important;
            padding: 12px;
            transition: 0.3s;
        }

        .custom-input:focus {
            border-color: var(--neon-cyan) !important;
            box-shadow: 0 0 15px rgba(0, 242, 254, 0.3) !important;
            background: rgba(255, 255, 255, 0.1) !important;
        }

        /* แก้ปัญหาช่องอีเมลสีไม่สวย */
        .custom-input-readonly {
            background: rgba(255, 255, 255, 0.02) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: rgba(255, 255, 255, 0.4) !important;
            cursor: not-allowed;
            border-radius: 12px !important;
        }

        .btn-neon-save {
            background: linear-gradient(45deg, #00f2fe, #00c6ff);
            color: #0f172a;
            border: none;
            font-weight: bold;
            border-radius: 12px;
            padding: 12px;
            transition: 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-neon-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 242, 254, 0.6);
            color: #000;
        }

        .table-custom {
            color: #fff;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table-custom tr {
            background: rgba(255, 255, 255, 0.03);
            transition: 0.3s;
        }

        .table-custom tr:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .table-custom td, .table-custom th {
            border: none;
            padding: 15px;
            vertical-align: middle;
        }

        .badge-status {
            background: rgba(0, 242, 254, 0.1);
            color: var(--neon-cyan);
            border: 1px solid var(--neon-cyan);
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card-profile p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="text-neon-cyan mb-0">
                        <i class="bi bi-person-bounding-box me-2"></i> บัญชีของฉัน
                    </h4>
                    <a href="?clear_address=1" class="btn btn-outline-danger btn-sm rounded-pill" onclick="return confirm('ต้องการล้างข้อมูลที่อยู่ทั้งหมดใช่หรือไม่?')">
                        <i class="bi bi-trash"></i>
                    </a>
                </div>
                
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="small opacity-75 mb-2">อีเมล (ใช้สำหรับเข้าสู่ระบบ)</label>
                        <input type="text" class="form-control custom-input-readonly" value="<?= $user_data['email'] ?>" readonly title="ไม่สามารถเปลี่ยนอีเมลได้">
                    </div>

                    <div class="mb-3">
                        <label class="small opacity-75 mb-2">ชื่อ-นามสกุลผู้รับ</label>
                        <input type="text" name="fullname" class="form-control custom-input" value="<?= $user_data['fullname'] ?? '' ?>" placeholder="ยังไม่ได้ระบุชื่อ">
                    </div>

                    <div class="mb-3">
                        <label class="small opacity-75 mb-2">เบอร์โทรศัพท์</label>
                        <input type="tel" name="phone" class="form-control custom-input" value="<?= $user_data['phone'] ?? '' ?>" placeholder="08x-xxx-xxxx">
                    </div>
                    
                    <div class="mb-3">
                        <label class="small opacity-75 mb-2">ที่อยู่จัดส่ง</label>
                        <textarea name="address" class="form-control custom-input" rows="3" placeholder="เลขที่บ้าน, ถนน, ซอย, แขวง/ตำบล..."><?= $user_data['address'] ?? '' ?></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small opacity-75 mb-2">จังหวัด</label>
                            <input type="text" name="province" class="form-control custom-input" value="<?= $user_data['province'] ?? '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="small opacity-75 mb-2">รหัสไปรษณีย์</label>
                            <input type="text" name="zipcode" class="form-control custom-input" value="<?= $user_data['zipcode'] ?? '' ?>">
                        </div>
                    </div>

                    <button type="submit" name="update_profile" class="btn btn-neon-save w-100 mt-4 shadow-sm">
                        <i class="bi bi-save2 me-2"></i> บันทึกข้อมูล
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card-profile p-4 h-100">
                <h4 class="text-neon-cyan mb-4">
                    <i class="bi bi-bag-check me-2"></i> ประวัติการสั่งซื้อ
                </h4>
                
                <?php if($orders->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr class="text-white-50 small">
                                    <th>เลขออเดอร์</th>
                                    <th>วันที่สั่งซื้อ</th>
                                    <th>ยอดรวม</th>
                                    <th class="text-end">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $orders->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold text-info">#<?= str_pad($row['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                    <td class="small"><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                    <td class="text-neon-cyan fw-bold">฿<?= number_format($row['total_price']) ?></td>
                                    <td class="text-end">
                                        <span class="badge-status">
                                            <?= ($row['status'] == 'pending') ? 'รอตรวจสอบ' : 'สำเร็จ' ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x display-1 opacity-10"></i>
                        <p class="mt-3 opacity-50">คุณยังไม่มีรายการสั่งซื้อในขณะนี้</p>
                        <a href="index.php" class="btn btn-outline-info btn-sm rounded-pill mt-2">ไปช้อปเลย!</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>