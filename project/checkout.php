<?php
session_start();
include "connectdb.php";

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลสินค้าในตะกร้าเพื่อคำนวณยอดรวมสุทธิ
$sql = "SELECT cart.*, products.name, products.price 
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: cart.php"); // ถ้าตะกร้าว่างให้กลับไปหน้าตะกร้า
    exit();
}

$grand_total = 0;
$items = [];
while($row = $result->fetch_assoc()) {
    $grand_total += ($row['price'] * $row['quantity']);
    $items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ชำระเงิน | Goods Secret Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #0f172a;
            color: #ffffff !important;
            background-image: radial-gradient(circle at top right, #3d1263, transparent), 
                              radial-gradient(circle at bottom left, #1e1b4b, transparent),
                              linear-gradient(135deg,#120018,#2a0845,#3d1e6d);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.03) !important;
            backdrop-filter: blur(15px);
            border: 1.5px solid rgba(187, 134, 252, 0.3) !important;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            padding: 30px;
            margin-bottom: 20px;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(187, 134, 252, 0.3) !important;
            color: #fff !important;
            border-radius: 10px;
            padding: 12px;
        }

        .form-control:focus {
            box-shadow: 0 0 10px rgba(187, 134, 252, 0.5);
            border-color: #bb86fc !important;
        }

        .text-neon-cyan { color: #00f2fe !important; text-shadow: 0 0 10px rgba(0, 242, 254, 0.5); }
        .text-purple-light { color: #bb86fc; }

        .btn-confirm {
            background: linear-gradient(135deg, #f107a3, #ff0080) !important;
            border: none !important;
            color: #ffffff !important;
            font-weight: 700;
            padding: 15px;
            border-radius: 50px;
            box-shadow: 0 5px 20px rgba(241, 7, 163, 0.5);
            width: 100%;
            transition: 0.3s;
        }

        .btn-confirm:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(241, 7, 163, 0.8);
        }

        .payment-option {
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            transition: 0.3s;
            background: rgba(255, 255, 255, 0.02);
        }

        .payment-option:hover {
            background: rgba(187, 134, 252, 0.1);
            border-color: #bb86fc;
        }

        .form-check-input:checked + .payment-option {
            background: rgba(187, 134, 252, 0.2);
            border-color: #bb86fc;
        }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container py-5">
    <h2 class="mb-5 text-white"><i class="bi bi-shield-lock text-neon-cyan me-3"></i> ชำระเงินส่วนตัว</h2>

    <form action="process_order.php" method="POST" enctype="multipart/form-data">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="glass-panel">
                    <h4 class="mb-4 text-purple-light"><i class="bi bi-geo-alt me-2"></i> ข้อมูลการจัดส่ง</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">ชื่อ-นามสกุล</label>
                            <input type="text" name="fullname" class="form-control" placeholder="ระบุชื่อผู้รับ" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">เบอร์โทรศัพท์</label>
                            <input type="tel" name="phone" class="form-control" placeholder="08x-xxx-xxxx" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">ที่อยู่จัดส่ง</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="บ้านเลขที่, ถนน, แขวง/ตำบล..." required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">จังหวัด</label>
                            <input type="text" name="province" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">รหัสไปรษณีย์</label>
                            <input type="text" name="zipcode" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="glass-panel mt-4">
                    <h4 class="mb-4 text-purple-light"><i class="bi bi-credit-card me-2"></i> วิธีการชำระเงิน</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="radio" name="payment_method" id="bank" value="bank" class="form-check-input d-none" checked>
                            <label for="bank" class="payment-option d-block">
                                <i class="bi bi-bank fs-3 mb-2 d-block"></i>
                                <strong>โอนเงินผ่านธนาคาร</strong>
                                <small class="d-block text-secondary">แนบสลิปเพื่อตรวจสอบด่วน</small>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <input type="radio" name="payment_method" id="cod" value="cod" class="form-check-input d-none">
                            <label for="cod" class="payment-option d-block">
                                <i class="bi bi-truck fs-3 mb-2 d-block"></i>
                                <strong>เก็บเงินปลายทาง</strong>
                                <small class="d-block text-secondary">มีค่าธรรมเนียมเพิ่มเติม</small>
                            </label>
                        </div>
                    </div>

                    <div id="slip-section" class="mt-4">
                        <label class="form-label">แนบหลักฐานการโอนเงิน (สลิป)</label>
                        <input type="file" name="slip_image" class="form-control">
                        <p class="small text-secondary mt-2 text-purple-light">* ธนาคารกสิกรไทย: 000-0-00000-0 (บจก. กู้ดส์ ซีเคร็ท)</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="glass-panel sticky-top" style="top: 100px;">
                    <h4 class="mb-4 text-white">สรุปรายการสั่งซื้อ</h4>
                    <div class="mb-4">
                        <?php foreach($items as $item): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-white-50 small"><?= $item['name'] ?> (x<?= $item['quantity'] ?>)</span>
                            <span class="small">฿<?= number_format($item['price'] * $item['quantity']) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <hr class="border-secondary">
                    <div class="d-flex justify-content-between mb-3 fs-5">
                        <span class="text-white-50">รวมยอดสินค้า</span>
                        <span>฿<?= number_format($grand_total) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="h4 text-white">ยอดสุทธิ</span>
                        <span class="h3 fw-bold text-neon-cyan">฿<?= number_format($grand_total) ?></span>
                    </div>

                    <button type="submit" class="btn btn-confirm">
                        ยืนยันการสั่งซื้อสินค้า <i class="bi bi-check-circle ms-2"></i>
                    </button>
                    
                    <p class="text-center mt-3 small text-white-50">
                        <i class="bi bi-shield-check me-1"></i> ข้อมูลของคุณจะถูกเก็บเป็นความลับ
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // ซ่อน/แสดงส่วนอัปโหลดสลิปตามวิธีชำระเงิน
    const bankRadio = document.getElementById('bank');
    const codRadio = document.getElementById('cod');
    const slipSection = document.getElementById('slip-section');

    codRadio.addEventListener('change', () => {
        if(codRadio.checked) slipSection.style.display = 'none';
    });
    bankRadio.addEventListener('change', () => {
        if(bankRadio.checked) slipSection.style.display = 'block';
    });
</script>
</body>
</html>