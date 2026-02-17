<?php
session_start();
include "connectdb.php";
$id = intval($_GET['id']);
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
$product_images = $conn->query("
    SELECT * FROM product_images 
    WHERE product_id = $id
");

if(!$product){
    header("Location:index.php");
    exit();
}

/* --- ส่วนที่เพิ่ม: ดึงจำนวนสินค้ามาโชว์ที่บาร์ให้เหมือนหน้าแรก --- */
$cart_count = 0;
if(isset($_SESSION['user_id'])){
    $u_id = $_SESSION['user_id'];
    $q_count = $conn->query("SELECT SUM(quantity) as total FROM cart WHERE user_id = $u_id");
    $r_count = $q_count->fetch_assoc();
    $cart_count = $r_count['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=$product['name']?> | Goods Secret Store</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
/* --- Neon Mystery Theme & Typography Adjustment --- */
body {
    background: radial-gradient(circle at 20% 30%, #4b2c63 0%, transparent 40%), 
                radial-gradient(circle at 80% 70%, #6a1b9a 0%, transparent 40%), 
                linear-gradient(135deg,#120018,#2a0845,#3d1e6d);
    color: #ffffff; /* ปรับตัวอักษรเป็นขาวบริสุทธิ์ให้อ่านง่าย */
    font-family: 'Segoe UI', sans-serif;
    min-height: 100vh;
}

.navbar { 
    background: rgba(26, 0, 40, 0.85); 
    backdrop-filter: blur(15px); 
    position: sticky; 
    top: 0; 
    z-index: 1000; 
    border-bottom: 1px solid rgba(187, 134, 252, 0.2); 
}

/* Glass Panel Styling */
.product-card-panel { 
    background: rgba(255, 255, 255, 0.05); 
    backdrop-filter: blur(15px); 
    border: 1px solid rgba(255, 255, 255, 0.1); 
    border-radius: 20px; 
    padding: 40px; 
}

.product-title { 
    font-weight: 700; 
    font-size: 32px; 
    color: #ffffff; 
    text-shadow: 0 0 15px rgba(187, 134, 252, 0.5); 
}

.product-price { 
    color: #00f2fe; /* สี Cyan Neon ตัดกับธีมและอ่านง่ายขึ้น */
    font-size: 30px; 
    font-weight: 700; 
    text-shadow: 0 0 10px rgba(0, 242, 254, 0.4);
}

.product-card-panel p {
    color: rgba(255, 255, 255, 0.95) !important; /* ปรับรายละเอียดสินค้าให้สว่างขึ้น */
}

/* Search Input Styling */
.search-input {
    background: #c6a9cdd5 !important; /* เพิ่มความสว่างของพื้นหลังช่องค้นหา */
    border: 1px solid rgba(187, 134, 252, 0.5) !important; /* เพิ่มเส้นขอบสีม่วงนีออนจางๆ */
    color: #ffffff !important; /* กำหนดตัวอักษรเป็นสีขาวบริสุทธิ์ */
    border-radius: 25px !important;
    padding-left: 20px !important;
    transition: all 0.3s ease;
}
.search-input:focus {
    background: rgba(255, 255, 255, 0.25) !important;
    border-color: #bb86fc !important;
    box-shadow: 0 0 10px rgba(187, 134, 252, 0.5) !important;
    outline: none;
}

/* ปรับสีตัวอักษร Placeholder (คำเกริ่นในช่อง) */
.search-input::placeholder {
    color: rgba(255, 255, 255, 0.6) !important;
}

/* Button Colors (Neon Purple & Pink) */
.btn-neon-purple {
    background: rgba(187, 134, 252, 0.1);
    border: 1px solid #bb86fc;
    color: #bb86fc;
    font-weight: 600;
    border-radius: 12px;
    padding: 15px;
    transition: 0.3s;
}
.btn-neon-purple:hover {
    background: #bb86fc;
    color: #120018;
    box-shadow: 0 0 20px #bb86fc;
}

.btn-neon-pink {
    background: linear-gradient(135deg, #f107a3, #ff0080);
    border: none;
    color: white;
    font-weight: bold;
    border-radius: 12px;
    padding: 15px;
    transition: 0.3s;
    box-shadow: 0 4px 15px rgba(241, 7, 163, 0.3);
    text-decoration: none;
    display: block;
    text-align: center;
}
.btn-neon-pink:hover {
    transform: translateY(-3px);
    box-shadow: 0 0 25px #f107a3;
    color: white;
}

.badge-cart { 
    position: absolute; top: -5px; right: -5px; 
    background: #f107a3; color: white; 
    font-size: 11px; padding: 2px 6px; 
    border-radius: 50%; font-weight: bold; 
    border: 1px solid #1a0028; 
}

.modern-btn { 
    background: rgba(255,255,255,0.1); color:#fff; 
    border: 1px solid rgba(255,255,255,0.2); 
    padding: 8px 18px; border-radius: 30px; 
    text-decoration: none; transition: 0.3s; 
}
.modern-btn:hover { background: #bb86fc; color:#120018; }

/* ดีไซน์ Modal แบบ Glassmorphism */
.modal-content.custom-popup {
    background: rgba(26, 0, 40, 0.85);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(187, 134, 252, 0.3);
    border-radius: 25px;
    color: #fff;
    box-shadow: 0 0 30px rgba(187, 134, 252, 0.2);
}

/* ไอคอนนีออนกะพริบ */
.neon-icon {
    font-size: 4rem;
    color: #bb86fc;
    text-shadow: 0 0 10px #bb86fc, 0 0 20px #bb86fc;
    animation: neon-glow 1.5s ease-in-out infinite alternate;
}

@keyframes neon-glow {
    from { opacity: 0.8; transform: scale(1); }
    to { opacity: 1; transform: scale(1.1); text-shadow: 0 0 20px #f107a3, 0 0 30px #f107a3; color: #f107a3; }
}

/* ปุ่มตกลงดีไซน์ใหม่ */
.btn-neon-close {
    background: linear-gradient(135deg, #bb86fc, #7c3aed);
    border: none;
    border-radius: 30px;
    padding: 10px 40px;
    font-weight: 600;
    color: white;
    transition: 0.3s;
}

.btn-neon-close:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(187, 134, 252, 0.5);
    color: white;
}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark py-3">
<div class="container">
    <a class="navbar-brand fw-bold text-white" href="index.php">🎵 Goods Secret Store</a>
    <div class="ms-auto d-flex align-items-center gap-3">
        <form method="GET" action="index.php" class="d-flex">
            <input class="form-control me-2 search-input" type="search" name="search" placeholder="ค้นหาสินค้า...">
            <button class="modern-btn"><i class="bi bi-search"></i></button>
        </form>

        <?php if(isset($_SESSION['user_id'])){ ?>
            <a href="cart.php" class="modern-btn position-relative">
                <i class="bi bi-cart"></i>
                <span id="cart-badge" class="badge-cart" style="<?= ($cart_count > 0) ? '' : 'display:none;' ?>">
                    <?= $cart_count ?>
                </span>
            </a>
            <a href="logout.php" class="modern-btn">ออกจากระบบ</a>
        <?php } else { ?>
            <a href="login.php" class="modern-btn">เข้าสู่ระบบ</a>
            <a href="register.php" class="modern-btn">สมัครสมาชิก</a>
        <?php } ?>
    </div>
</div>
</nav>

<div class="container mt-5 py-4">
    <div class="row g-5">
        <div class="col-md-5">
            <div class="text-center">
                <img id="mainImage" src="images/<?= $product['image']; ?>" class="img-fluid mb-4 rounded-4 shadow-lg border border-secondary" style="max-height: 480px; object-fit: contain; background: rgba(0,0,0,0.2);">
                <div class="d-flex gap-2 justify-content-center flex-wrap">
                    <?php while($img = $product_images->fetch_assoc()){ ?>
                        <img src="images/<?= $img['image']; ?>" class="img-thumbnail border-secondary bg-dark" style="width: 80px; cursor: pointer;" onclick="document.getElementById('mainImage').src=this.src">
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="product-card-panel shadow-lg">
                <h2 class="product-title mb-3"><?=$product['name']?></h2>
                <div class="product-price mb-4">฿<?=number_format($product['price'])?></div>

                <?php if(isset($product['old_price']) && $product['old_price']>0){ ?>
                    <div class="mb-4">
                        <span class="badge bg-danger px-3 py-2">ลดราคาพิเศษ</span>
                        <small class="text-light text-decoration-line-through ms-2 opacity-50">฿<?=number_format($product['old_price'])?></small>
                    </div>
                <?php } ?>

                <p class="mt-2" style="line-height: 1.8; font-size: 1.1rem;">
                    <?=$product['description']?>
                </p>

                <div class="mt-5">
                    <?php if(isset($_SESSION['user_id'])){ ?>
                        <div class="row g-3">
                            <div class="col-6">
                                <a href="add_to_cart.php?id=<?=$product['id']?>&action=buy" class="btn btn-neon-pink">
                                    <i class="bi bi-bag-check-fill me-2"></i> สั่งซื้อทันที
                                </a>
                            </div>
                            <div class="col-6">
                                <button onclick="addToCart(<?=$product['id']?>)" class="btn btn-neon-purple w-100">
                                    <i class="bi bi-cart-plus me-2"></i> เพิ่มลงตะกร้า
                                </button>
                            </div>
                        </div>
                    <?php } else { ?>
                        <button class="btn btn-neon-purple w-100 py-3" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="bi bi-cart-plus me-2"></i> เข้าสู่ระบบเพื่อสั่งซื้อสินค้า
                        </button>
                    <?php } ?>
                </div>

                <div class="mt-5 border-top border-secondary pt-4 text-white-50 small">
                    <div class="row">
                        <div class="col-6 mb-2"><i class="bi bi-check2-circle text-success me-2"></i> สินค้าพร้อมส่ง</div>
                        <div class="col-6 mb-2"><i class="bi bi-truck text-info me-2"></i> จัดส่งภายใน 1-2 วัน</div>
                        <div class="col-12"><i class="bi bi-shield-check text-warning me-2"></i> รับประกันสินค้าแท้ 100% จาก Goods Secret Store</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-popup">
            <div class="modal-body text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-magic neon-icon"></i>
                </div>
                <h3 class="fw-bold mb-3" style="color: #00f2fe;">สำเร็จแล้ว!</h3>
                <p class="fs-5 opacity-75 mb-4">ความลับชิ้นนี้ถูกเพิ่มลงในตะกร้าของคุณแล้ว 🔮</p>
                <button type="button" class="btn btn-neon-close" data-bs-dismiss="modal">ตกลง</button>
            </div>
        </div>
    </div>
</div>
<script>
    function addToCart(productId) {
    // ส่งข้อมูลแบบ AJAX ไปยัง add_to_cart.php
    fetch('add_to_cart.php?id=' + productId + '&ajax=1')
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            // 1. อัปเดตตัวเลขที่ไอคอนตะกร้าบน Navbar
            const badge = document.getElementById('cart-badge');
            if(badge) { 
                badge.textContent = data.total; 
                badge.style.display = 'block'; 
            }
            
            // 2. เรียกใช้ Bootstrap Modal สวยๆ แทน alert() แบบเดิม
            var myModal = new bootstrap.Modal(document.getElementById('cartModal'));
            myModal.show();
            
        } else { 
            // กรณีไม่ได้ Login ให้เด้งไปหน้า Login
            window.location.href = 'login.php'; 
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
</body>
</html>