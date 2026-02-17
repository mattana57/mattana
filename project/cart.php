<?php
session_start();
include "connectdb.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ฟังก์ชันลบสินค้า
if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM cart WHERE user_id = $user_id AND product_id = $del_id");
    header("Location: cart.php");
}

// ดึงข้อมูลสินค้า
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
        /* 1. ปรับพื้นหลังและการมองเห็นโดยรวม */
        body {
            background-color: #0f172a;
            color: #ffffff !important;
            background-image: radial-gradient(circle at top right, #3d1263, transparent), 
                              radial-gradient(circle at bottom left, #1e1b4b, transparent);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* 2. กรอบสินค้าแบบกระจกสว่าง (อ่านง่ายขึ้น 200%) */
        .glass-panel {
            background: rgba(255, 255, 255, 0.15) !important;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
            padding: 25px;
        }

        /* 3. ตารางข้อมูลสินค้า */
        .table {
            color: #ffffff !important;
            margin-bottom: 0;
        }
        .table thead th {
            color: #bb86fc !important; /* หัวข้อตารางเป็นสีม่วงอ่อนสว่าง */
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }
        .table td {
            vertical-align: middle;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1.5rem 0.75rem;
        }

        /* 4. รายละเอียดสินค้า */
        .product-img {
            width: 80px; height: 80px;
            object-fit: cover; border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .text-neon-cyan {
            color: #00f2fe !important;
            text-shadow: 0 0 10px rgba(0, 242, 254, 0.5);
        }
        .text-secondary-bright {
            color: #cbd5e1 !important;
        }

        /* 5. กู้คืนปุ่มชำระเงิน (Neon Magenta) */
        /* 1. ปุ่มชำระเงินแบบ Neon Glow */
.btn-checkout {
    background: linear-gradient(135deg, #f107a3, #ff0080) !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 700;
    padding: 15px 30px;
    border-radius: 50px;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); /* ลูกเล่นการเด้ง */
    box-shadow: 0 5px 15px rgba(241, 7, 163, 0.4);
    width: 100%;
    text-transform: uppercase;
    letter-spacing: 1px;
    position: relative;
    overflow: hidden;
}

/* เอฟเฟกต์แสงวิ่งผ่านปุ่ม (Shine Effect) */
.btn-checkout::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: rgba(255, 255, 255, 0.2);
    transform: rotate(45deg);
    transition: 0.5s;
    opacity: 0;
}

.btn-checkout:hover {
    transform: translateY(-5px) scale(1.02); /* ยกตัวขึ้นและขยายเล็กน้อย */
    box-shadow: 0 0 30px rgba(241, 7, 163, 0.8); /* เรืองแสงเข้มขึ้น */
    color: #ffffff !important;
}

.btn-checkout:hover::after {
    left: 120%;
    opacity: 1;
}

/* 2. ปุ่ม "เลือกซื้อสินค้าต่อ" แบบ Minimal Neon */
.btn-link {
    color: #cbd5e1 !important;
    transition: 0.3s;
    font-weight: 500;
}

.btn-link:hover {
    color: #bb86fc !important; /* เปลี่ยนเป็นสีม่วงนีออนเมื่อชี้ */
    text-shadow: 0 0 10px rgba(187, 134, 252, 0.6);
    text-decoration: none;
    transform: translateX(-5px); /* ขยับไปทางซ้ายเล็กน้อย */
}

/* 3. ปุ่ม "เริ่มช้อปปิ้ง" (กรณีตะกร้าว่าง) */
.btn-outline-info {
    border: 2px solid #00f2fe !important;
    color: #00f2fe !important;
    transition: 0.3s;
}

.btn-outline-info:hover {
    background: #00f2fe !important;
    color: #0f172a !important;
    box-shadow: 0 0 20px rgba(0, 242, 254, 0.6);
}

/* 4. ปุ่มลบสินค้าแบบไอคอนสั่น (Trash Icon) */
.btn-remove {
    color: rgba(255, 255, 255, 0.4);
    font-size: 1.2rem;
    transition: 0.3s;
}

.btn-remove:hover {
    color: #ff4d4d;
    transform: rotate(15deg) scale(1.2); /* หมุนและขยาย */
    filter: drop-shadow(0 0 8px #ff4d4d);
}
/* ดีไซน์ Modal ยืนยันการลบแบบ Glassmorphism */
.modal-content.delete-popup {
    background: rgba(40, 0, 10, 0.85); /* โทนแดงมืด */
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 77, 77, 0.3);
    border-radius: 25px;
    color: #fff;
    box-shadow: 0 0 30px rgba(255, 77, 77, 0.2);
}

/* ไอคอนถังขยะนีออนกะพริบ */
.neon-delete-icon {
    font-size: 4rem;
    color: #ff4d4d;
    text-shadow: 0 0 10px #ff4d4d, 0 0 20px #ff4d4d;
    animation: delete-glow 1.5s ease-in-out infinite alternate;
}

@keyframes delete-glow {
    from { opacity: 0.8; transform: scale(1); }
    to { opacity: 1; transform: scale(1.1); text-shadow: 0 0 20px #ff944d, 0 0 30px #ff944d; color: #ff944d; }
}

/* ปุ่มยืนยันและยกเลิก */
.btn-neon-confirm {
    background: linear-gradient(135deg, #ff4d4d, #ff944d);
    border: none; border-radius: 30px; padding: 10px 30px; font-weight: 600; color: white; transition: 0.3s;
}
.btn-neon-confirm:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(255, 77, 77, 0.5); color: white; }

.btn-neon-cancel {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 30px; padding: 10px 30px; font-weight: 600; color: white; transition: 0.3s;
}
.btn-neon-cancel:hover { background: rgba(255, 255, 255, 0.2); color: white; }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container py-5">
    <h2 class="mb-5"><i class="bi bi-cart3 text-neon-cyan me-3"></i>ตะกร้าสินค้าของคุณ</h2>
    
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="glass-panel">
                <?php if($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th>ข้อมูลสินค้า</th>
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
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="images/<?= $row['image'] ?>" class="product-img me-3 shadow">
                                        <div>
                                            <div class="fw-bold text-white fs-5"><?= $row['name'] ?></div>
                                            <div class="text-secondary-bright small">ID: #<?= $row['product_id'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">฿<?= number_format($row['price']) ?></td>
                                <td class="text-center">
                                    <span class="badge bg-dark px-3 py-2 border border-secondary"><?= $row['quantity'] ?></span>
                                </td>
                                <td class="text-end fw-bold text-neon-cyan">฿<?= number_format($subtotal) ?></td>
                                <td class="text-end">
                                    <a href="cart.php?delete_id=<?= $row['product_id'] ?>" 
                                       class="btn-remove" onclick="return confirm('ลบสินค้านี้ออก?')">
                                        <i class="bi bi-trash3-fill"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-bag-x display-1 opacity-25"></i>
                        <p class="mt-4 fs-4 text-secondary-bright">ยังไม่มีสินค้าในตะกร้าของคุณ</p>
                        <a href="index.php" class="btn btn-outline-info rounded-pill px-4 mt-2">กลับไปเลือกซื้อสินค้า</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="glass-panel h-100">
                <h4 class="mb-4 text-white">สรุปรายการสั่งซื้อ</h4>
                <div class="d-flex justify-content-between mb-3 fs-5">
                    <span class="text-secondary-bright">รวมยอดสินค้า</span>
                    <span>฿<?= number_format($grand_total ?? 0) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-3 fs-5">
                    <span class="text-secondary-bright">ค่าจัดส่ง</span>
                    <span class="text-success fw-bold">ฟรี</span>
                </div>
                <hr class="my-4 border-secondary opacity-50">
                <div class="d-flex justify-content-between mb-5">
                    <span class="h4 text-white">ยอดสุทธิ</span>
                    <span class="h3 fw-bold text-neon-cyan">฿<?= number_format($grand_total ?? 0) ?></span>
                </div>
                
                <button class="btn btn-checkout mb-4" <?= ($grand_total <= 0) ? 'disabled' : '' ?>>
                    ชำระเงิน <i class="bi bi-arrow-right-circle ms-2"></i>
                </button>
                
                <a href="index.php" class="btn btn-link w-100 text-secondary-bright text-decoration-none text-center">
                    <i class="bi bi-chevron-left"></i> เลือกซื้อสินค้าต่อ
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!--<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content delete-popup">
            <div class="modal-body text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-trash3 neon-delete-icon"></i>
                </div>
                <h3 class="fw-bold mb-3" style="color: #ff4d4d;">ยืนยันการลบ?</h3>
                <p class="fs-5 opacity-75 mb-4">คุณแน่ใจใช่ไหมที่จะลบความลับชิ้นนี้ออกจากตะกร้า?</p>
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-neon-cancel" data-bs-dismiss="modal">ยกเลิก</button>
                    <a id="confirmDeleteBtn" href="#" class="btn btn-neon-confirm text-decoration-none">ยืนยันการลบ</a>
                </div>
            </div>
        </div>
    </div>
</div> -->
<script>
    function showDeleteModal(productId) {
    // กำหนด Link สำหรับการลบจริงให้กับปุ่มยืนยันใน Modal
    const deleteUrl = 'cart.php?delete_id=' + productId;
    document.getElementById('confirmDeleteBtn').setAttribute('href', deleteUrl);
    
    // แสดง Modal
    var myModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    myModal.show();
}
</script>
</body>
</html>