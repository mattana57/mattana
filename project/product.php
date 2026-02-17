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

/* --- ส่วนที่เพิ่ม: ดึงจำนวนสินค้ามาโชว์ที่บาร์ --- */
$cart_count = 0;
if(isset($_SESSION['user_id'])){
    $u_id = $_SESSION['user_id'];
    $q_count = $conn->query("SELECT SUM(quantity) as total FROM cart WHERE user_id = $u_id");
    $r_count = $q_count->fetch_assoc();
    $cart_count = $r_count['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?=$product['name']?> | Goods Secret Store</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
/* --- Style เดิมของคุณทั้งหมด --- */
body{
background: radial-gradient(circle at 20% 30%, #4b2c63 0%, transparent 40%), radial-gradient(circle at 80% 70%, #6a1b9a 0%, transparent 40%), linear-gradient(135deg,#120018,#2a0845,#3d1e6d);
color:#fff;
font-family:'Segoe UI',sans-serif;
min-height: 100vh;
}
.navbar{ background:linear-gradient(90deg,#1a0028,#3d1e6d); position: sticky; top: 0; z-index: 1000; }
.modern-btn{ background:linear-gradient(135deg,#E0BBE4,#bb86fc); color:#2a0845; border:none; padding:8px 18px; border-radius:30px; font-weight:600; transition:.3s; box-shadow:0 0 10px rgba(187,134,252,.5); text-decoration:none; display:inline-block; }
.modern-btn:hover{ transform:translateY(-3px); box-shadow:0 0 20px #bb86fc; color:#000; }
.product-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 30px; color: #fff; }
.product-title { font-weight: 600; font-size: 28px; }
.product-price { color: #bb86fc; font-size: 26px; font-weight: 700; }
.btn-cart { background: linear-gradient(135deg,#7c3aed,#a855f7); border: none; border-radius: 12px; padding: 12px 25px; font-weight: 600; color: #fff; transition: 0.3s; width: 100%; text-align: center; text-decoration: none; display: block; }
.btn-cart:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(124,58,237,0.4); color: #fff; }
.auth-modal { background: rgba(40, 0, 70, 0.85); backdrop-filter: blur(15px); border-radius: 20px; }
.btn-gradient { background: linear-gradient(135deg, #7b2ff7, #f107a3); border: none; color: white; font-weight: 500; padding: 12px; border-radius: 12px; }

/* --- Style ที่เพิ่มมา (Badge ตะกร้า และปุ่มซื้อ) --- */
.badge-cart { position: absolute; top: -5px; right: -5px; background: #ff4d4d; color: white; font-size: 11px; padding: 2px 6px; border-radius: 50%; font-weight: bold; border: 1px solid #1a0028; }
.btn-buy-now { background: linear-gradient(135deg, #ff0080, #ff8c00); border: none; color: white; font-weight: bold; border-radius: 12px; transition: 0.3s; padding: 12px 25px; display: block; width: 100%; text-align: center; text-decoration: none; }
.btn-buy-now:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(255, 0, 128, 0.4); color: white; }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark py-3">
<div class="container">
    <a class="navbar-brand fw-bold text-white" href="index.php">🎵 Goods Secret Store</a>
    <div class="ms-auto d-flex align-items-center gap-3">
        <form method="GET" action="index.php" class="d-flex">
            <input class="form-control me-2" type="search" name="search" placeholder="ค้นหาสินค้า...">
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
    <div class="row">
        <div class="col-md-5">
            <div class="row text-center">
                <div class="col-12">
                    <img id="mainImage" src="images/<?= $product['image']; ?>" class="img-fluid mb-3 rounded-4 shadow-lg border border-secondary" style="max-height: 450px; object-fit: contain;">
                </div>
                <div class="col-12 d-flex gap-2 justify-content-center flex-wrap">
                    <?php while($img = $product_images->fetch_assoc()){ ?>
                        <img src="images/<?= $img['image']; ?>" class="img-thumbnail bg-dark border-secondary" style="width:80px; height:80px; cursor:pointer; object-fit:cover;" onclick="changeImage(this.src)">
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="product-card shadow-lg">
                <h2 class="product-title"><?=$product['name']?></h2>
                <div class="product-price">฿<?=number_format($product['price'])?></div>

                <?php if(isset($product['old_price']) && $product['old_price']>0){ ?>
                    <div class="mt-2">
                        <span class="badge bg-danger">SALE</span>
                        <small class="text-light text-decoration-line-through ms-2 opacity-50">฿<?=number_format($product['old_price'])?></small>
                    </div>
                <?php } ?>

                <p class="mt-4 text-light opacity-75" style="line-height: 1.6;"><?=$product['description']?></p>

                <div class="mt-4">
                    <?php if(isset($_SESSION['user_id'])){ ?>
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="add_to_cart.php?id=<?=$product['id']?>&action=buy" class="btn btn-buy-now shadow">
                                    <i class="bi bi-bag-check-fill me-2"></i> สั่งซื้อทันที
                                </a>
                            </div>
                            <div class="col-6">
                                <button onclick="addToCart(<?=$product['id']?>)" class="btn btn-cart">
                                    <i class="bi bi-cart-plus me-2"></i> เพิ่มลงตะกร้า
                                </button>
                            </div>
                        </div>
                    <?php } else { ?>
                        <button class="btn btn-cart py-3" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="bi bi-cart-plus me-2"></i> เพิ่มลงตะกร้า
                        </button>
                    <?php } ?>
                </div>

                <div class="mt-4 border-top border-secondary pt-3 text-light opacity-50 small">
                    <div class="mb-1"><i class="bi bi-check2-circle me-2"></i> สินค้าพร้อมส่ง</div>
                    <div class="mb-1"><i class="bi bi-truck me-2"></i> จัดส่งภายใน 1-2 วัน</div>
                    <div><i class="bi bi-shield-check me-2"></i> รับประกันสินค้าแท้ 100%</div>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-5 opacity-10">
    <h5 class="mb-4 text-info fw-bold"><i class="bi bi-stars me-2"></i> สินค้าที่คุณอาจสนใจ</h5>
    <div class="row g-4">
        <?php
        $recommend = $conn->query("SELECT * FROM products WHERE id!=$id LIMIT 4");
        while($r = $recommend->fetch_assoc()){
        ?>
        <div class="col-md-3">
            <div class="card product-card p-3 h-100 text-center" style="background: rgba(255,255,255,0.03);">
                <img src="images/<?=$r['image']?>" class="rounded mb-2" style="height:150px; object-fit:cover;">
                <div class="card-body p-0">
                    <h6 class="text-truncate"><?=$r['name']?></h6>
                    <p class="text-info fw-bold mb-2">฿<?=number_format($r['price'])?></p>
                    <a href="product.php?id=<?=$r['id']?>" class="btn btn-outline-light btn-sm w-100">ดูสินค้า</a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<div class="modal fade" id="loginModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow-lg auth-modal text-white text-center p-5">
        <h4 class="fw-bold mb-4">💫กรุณาเข้าสู่ระบบก่อนสั่งซื้อสินค้า</h4>
        <a href="login.php" class="btn btn-gradient w-100 mb-3">เข้าสู่ระบบ</a>
        <a href="register.php" class="btn btn-outline-light w-100 py-2 rounded-3 text-white border-white">สมัครสมาชิก</a>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ฟังก์ชัน AJAX สำหรับเพิ่มสินค้า */
function addToCart(productId) {
    fetch('add_to_cart.php?id=' + productId + '&ajax=1')
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            const badge = document.getElementById('cart-badge');
            if(badge) { badge.textContent = data.total; badge.style.display = 'block'; }
            alert('เพิ่มสินค้าลงตะกร้าแล้ว! 🔮');
        } else { window.location.href = 'login.php'; }
    });
}
function changeImage(src){ document.getElementById("mainImage").src = src; }
</script>
</body>
</html>