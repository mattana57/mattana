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
        /* 1. บังคับตัวหนังสือขาวบริสุทธิ์ทั่งหน้าจอ */
        body {
            background-color: #0f172a;
            color: #ffffff !important;
            background-image: radial-gradient(circle at top right, #3d1263, transparent), 
                              radial-gradient(circle at bottom left, #1e1b4b, transparent),
                              linear-gradient(135deg,#120018,#2a0845,#3d1e6d);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        h2, h4, h5, label, .form-label, span, strong, small {
            color: #ffffff !important;
        }

        /* 2. ดีไซน์กรอบลอยเรืองแสง (Glassmorphism) */
        .glass-panel {
            background: rgba(255, 255, 255, 0.03) !important;
            backdrop-filter: blur(15px);
            border: 1.5px solid rgba(187, 134, 252, 0.3) !important;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            padding: 30px;
            margin-bottom: 20px;
        }

        /* 3. ปรับปรุงช่องกรอกข้อมูลให้เห็นตัวหนังสือขาวชัดเจน */
        .form-control {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(187, 134, 252, 0.4) !important;
            color: #ffffff !important;
            border-radius: 12px;
            padding: 12px;
        }
        .form-control:focus {
            box-shadow: 0 0 15px rgba(187, 134, 252, 0.4);
            border-color: #bb86fc !important;
            background: rgba(255, 255, 255, 0.12) !important;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4) !important;
        }

        /* 4. ส่วนแสดง QR Code */
        .qr-wrapper {
            background: #ffffff; /* พื้นขาวเพื่อให้สแกนติดง่าย */
            padding: 15px;
            border-radius: 15px;
            display: inline-block;
            box-shadow: 0 0 20px rgba(0, 242, 254, 0.4);
        }
        .qr-image {
            width: 220px;
            height: 220px;
            object-fit: contain;
        }

        /* 5. ตกแต่งปุ่มและสีนีออน */
        .text-neon-cyan { color: #00f2fe !important; text-shadow: 0 0 10px rgba(0, 242, 254, 0.5); }
        
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
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            transition: 0.3s;
            background: rgba(255, 255, 255, 0.02);
            display: block;
            text-align: center;
        }
        .form-check-input:checked + .payment-option {
            background: rgba(187, 134, 252, 0.2);
            border-color: #bb86fc;
            box-shadow: 0 0 15px rgba(187, 134, 252, 0.3);
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
                    <h4 class="mb-4" style="color: #bb86fc !important;"><i class="bi bi-geo-alt me-2"></i> ข้อมูลการจัดส่ง</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small">ชื่อ-นามสกุล</label>
                            <input type="text" name="fullname" class="form-control" placeholder="ระบุชื่อผู้รับ" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">เบอร์โทรศัพท์</label>
                            <input type="tel" name="phone" class="form-control" placeholder="08x-xxx-xxxx" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">ที่อยู่จัดส่ง</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="บ้านเลขที่, ถนน, แขวง/ตำบล..." required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">จังหวัด</label>
                            <input type="text" name="province" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">รหัสไปรษณีย์</label>
                            <input type="text" name="zipcode" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="glass-panel mt-4">
                    <h4 class="mb-4" style="color: #bb86fc !important;"><i class="bi bi-credit-card me-2"></i> วิธีการชำระเงิน</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="radio" name="payment_method" id="bank" value="bank" class="form-check-input d-none" checked>
                            <label for="bank" class="payment-option">
                                <i class="bi bi-qr-code-scan fs-2 mb-2 d-block" style="color: #00f2fe;"></i>
                                <strong>โอนเงิน / QR PromptPay</strong>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <input type="radio" name="payment_method" id="cod" value="cod" class="form-check-input d-none">
                            <label for="cod" class="payment-option">
                                <i class="bi bi-truck fs-2 mb-2 d-block" style="color: #f107a3;"></i>
                                <strong>เก็บเงินปลายทาง</strong>
                            </label>
                        </div>
                    </div>

                    <div id="slip-section" class="mt-5 text-center pt-4 border-top border-secondary border-opacity-25">
                        <p class="mb-3">สแกน QR Code เพื่อชำระเงินได้ทันที</p>
                        <div class="qr-wrapper mb-4">
                            <img src="images/promptpay_qr.png" alt="PromptPay QR" class="qr-image">
                        </div>
                        
                        <div class="text-start p-3 rounded-4 mb-4" style="background: rgba(187,134,252,0.1); border: 1px solid rgba(187,134,252,0.2);">
                            <p class="mb-2 fw-bold text-neon-cyan">รายละเอียดการโอนบัญชีธนาคาร:</p>
                            <p class="mb-1 small opacity-75">ธนาคาร: กสิกรไทย (K-Bank)</p>
                            <p class="mb-1 small opacity-75">ชื่อบัญชี: บจก. กู้ดส์ ซีเคร็ท สโตร์</p>
                            <p class="mb-0 small opacity-75">เลขบัญชี: 000-0-00000-0</p>
                        </div>

                        <div class="text-start">
                            <label class="form-label fw-bold small mb-2">แนบหลักฐานการโอนเงิน (สลิป)</label>
                            <input type="file" name="slip_image" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="glass-panel sticky-top" style="top: 100px;">
                    <h4 class="mb-4">สรุปรายการสั่งซื้อ</h4>
                    <div class="mb-4 border-bottom border-secondary border-opacity-25 pb-3">
                        <?php foreach($items as $item): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="opacity-75 small"><?= $item['name'] ?> (x<?= $item['quantity'] ?>)</span>
                            <span class="small">฿<?= number_format($item['price'] * $item['quantity']) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="opacity-75">รวมยอดสินค้า</span>
                        <span>฿<?= number_format($grand_total) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="opacity-75">ค่าจัดส่ง</span>
                        <span class="text-success fw-bold">ฟรี</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 pt-3 border-top border-secondary border-opacity-25">
                        <span class="h4">ยอดสุทธิ</span>
                        <span class="h3 fw-bold text-neon-cyan">฿<?= number_format($grand_total) ?></span>
                    </div>

                    <button type="submit" class="btn btn-confirm">
                        ยืนยันและสั่งซื้อสินค้า <i class="bi bi-check-circle ms-2"></i>
                    </button>
                    
                    <p class="text-center mt-4 small opacity-50">
                        <i class="bi bi-shield-lock-fill me-1"></i> ข้อมูลส่วนตัวของคุณจะถูกเก็บเป็นความลับสูงสุด
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // ระบบสลับการแสดงผลส่วน QR Code และ Slip ตามวิธีชำระเงิน
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