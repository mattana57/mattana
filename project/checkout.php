<?php
session_start();
include "connectdb.php";

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// --- [เพิ่ม]: ดึงข้อมูลที่อยู่จากตาราง users เพื่อนำมาแสดงใน Form อัตโนมัติ ---
$user_info_q = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user_info = $user_info_q->fetch_assoc();

// ดึงข้อมูลสินค้าในตะกร้าเพื่อคำนวณยอดรวม
$sql = "SELECT cart.*, products.name, products.price 
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: cart.php"); 
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
        /* --- CSS นีออนเดิมของพี่ --- */
        body { background-color: #0f172a; color: #fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .checkout-container { max-width: 1000px; margin: 50px auto; }
        .card-custom { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 30px; }
        .text-neon-cyan { color: #00f2fe; text-shadow: 0 0 10px rgba(0, 242, 254, 0.5); }
        .form-control { background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255,255,255,0.1); color: #fff; border-radius: 10px; padding: 12px; }
        .form-control:focus { background: rgba(15, 23, 42, 0.8); border-color: #00f2fe; color: #fff; box-shadow: 0 0 15px rgba(0, 242, 254, 0.3); }
        .btn-confirm { background: linear-gradient(135deg, #00f2fe, #00c6ff); border: none; color: #0f172a; font-weight: bold; width: 100%; padding: 15px; border-radius: 12px; font-size: 1.1rem; transition: 0.3s; margin-top: 20px; }
        .btn-confirm:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0, 242, 254, 0.4); }
        .payment-method-card { border: 2px solid rgba(255,255,255,0.1); border-radius: 15px; padding: 20px; cursor: pointer; transition: 0.3s; background: rgba(255,255,255,0.05); }
        .payment-method-card:hover { border-color: rgba(0, 242, 254, 0.5); }
        .form-check-input:checked + .payment-method-card { border-color: #00f2fe; background: rgba(0, 242, 254, 0.1); }
    </style>
</head>
<body>

<div class="container checkout-container">
    <h2 class="mb-4 fw-bold"><i class="bi bi-wallet2 text-neon-cyan me-2"></i> ชำระเงิน</h2>
    
    <form action="process_order.php" method="POST" enctype="multipart/form-data">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card-custom h-100">
                    <h4 class="mb-4 text-neon-cyan"><i class="bi bi-geo-alt me-2"></i>ที่อยู่สำหรับจัดส่ง</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small">ชื่อ-นามสกุล</label>
                            <input type="text" name="fullname" class="form-control" value="<?= $user_info['fullname'] ?? '' ?>" placeholder="ระบุชื่อผู้รับ" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">เบอร์โทรศัพท์</label>
                            <input type="tel" name="phone" class="form-control" value="<?= $user_info['phone'] ?? '' ?>" placeholder="08x-xxx-xxxx" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">ที่อยู่จัดส่ง</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="บ้านเลขที่, ถนน, แขวง/ตำบล..." required><?= $user_info['address'] ?? '' ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">จังหวัด</label>
                            <input type="text" name="province" class="form-control" value="<?= $user_info['province'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">รหัสไปรษณีย์</label>
                            <input type="text" name="zipcode" class="form-control" value="<?= $user_info['zipcode'] ?? '' ?>" required>
                        </div>
                    </div>

                    <h4 class="mt-5 mb-4 text-neon-cyan"><i class="bi bi-credit-card me-2"></i>เลือกวิธีชำระเงิน</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="radio" class="form-check-input d-none" name="payment_method" id="bank" value="bank_transfer" checked>
                            <label class="payment-method-card d-block text-center" for="bank">
                                <i class="bi bi-bank fs-3 mb-2"></i>
                                <div>โอนเงินผ่านธนาคาร</div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <input type="radio" class="form-check-input d-none" name="payment_method" id="cod" value="cod">
                            <label class="payment-method-card d-block text-center" for="cod">
                                <i class="bi bi-truck fs-3 mb-2"></i>
                                <div>เก็บเงินปลายทาง</div>
                            </label>
                        </div>
                    </div>

                    <div id="slip-section" class="mt-4 p-3 rounded-3" style="background: rgba(0,0,0,0.2); border: 1px dashed rgba(255,255,255,0.2);">
                        <p class="mb-2 small text-center"><i class="bi bi-qr-code-scan me-2"></i>สแกนเพื่อโอนชำระเงิน ฿<?= number_format($grand_total) ?></p>
                        <div class="text-center mb-3">
                            <img src="https://promptpay.io/0812345678/<?= $grand_total ?>.png" class="img-fluid rounded-3" style="max-height: 200px;">
                        </div>
                        <label class="form-label small">แนบสลิปการโอนเงิน</label>
                        <input type="file" name="slip_image" class="form-control">
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card-custom">
                    <h4 class="mb-4 text-neon-cyan"><i class="bi bi-receipt me-2"></i>สรุปรายการ</h4>
                    <?php foreach($items as $item): ?>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="opacity-75"><?= $item['name'] ?> x <?= $item['quantity'] ?></span>
                        <span class="fw-bold">฿<?= number_format($item['price'] * $item['quantity']) ?></span>
                    </div>
                    <?php endforeach; ?>
                    
                    <hr class="border-secondary border-opacity-50 my-4">
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="opacity-75">ยอดรวมสินค้า</span>
                        <span>฿<?= number_format($grand_total) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="opacity-75">ค่าธรรมเนียมจัดส่ง</span>
                        <span class="text-success fw-bold">ฟรี</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-4 pt-3 border-top border-secondary border-opacity-25">
                        <span class="h4">ยอดชำระสุทธิ</span>
                        <span class="h3 fw-bold text-neon-cyan">฿<?= number_format($grand_total) ?></span>
                    </div>

                    <button type="submit" class="btn btn-confirm">
                        ยืนยันการสั่งซื้อสินค้า <i class="bi bi-check-circle ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const bankRadio = document.getElementById('bank');
    const codRadio = document.getElementById('cod');
    const slipSection = document.getElementById('slip-section');

    codRadio.addEventListener('change', () => { if(codRadio.checked) slipSection.style.display = 'none'; });
    bankRadio.addEventListener('change', () => { if(bankRadio.checked) slipSection.style.display = 'block'; });
</script>
</body>
</html>