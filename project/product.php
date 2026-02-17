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
/* --- ธีมหลักและสไตล์ดั้งเดิมของคุณทั้งหมด --- */
body {
    background: radial-gradient(circle at 20% 30%, #4b2c63 0%, transparent 40%), 
                radial-gradient(circle at 80% 70%, #6a1b9a 0%, transparent 40%), 
                linear-gradient(135deg,#120018,#2a0845,#3d1e6d);
    color: #ffffff !important;
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
    color: #00f2fe !important;
    font-size: 30px; 
    font-weight: 700; 
    text-shadow: 0 0 10px rgba(0, 242, 254, 0.4);
}

.search-input {
    background: #c6a9cdd5 !important;
    border: 1px solid rgba(187, 134, 252, 0.5) !important;
    color: #ffffff !important;
    border-radius: 25px !important;
    padding-left: 20px !important;
}

.search-input::placeholder {
    color: rgba(255, 255, 255, 0.6) !important;
}

.btn-neon-purple {
    background: rgba(187, 134, 252, 0.1);
    border: 1px solid #bb86fc;
    color: #bb86fc;
    font-weight: 600;
    border-radius: 12px;
    padding: 15px;
    transition: 0.3s;
    width: 100%;
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

/* --- สไตล์ส่วนใหม่ (จำนวนสินค้า และ สินค้าแนะนำ) --- */
.qty-control {
    width: 140px;
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(187, 134, 252, 0.3);
    border-radius: 10px;
    overflow: hidden;
}
.qty-btn { background: transparent; border: none; color: white; width: 40px; height: 40px; transition: 0.3s; }
.qty-btn:hover { background: rgba(187, 134, 252, 0.2); }
#product_qty { background: transparent !important; border: none !important; color: white !important; text-align: center; width: 60px; font-weight: bold; }

.suggested-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    transition: 0.3s;
    cursor: pointer;
}
.suggested-card:hover {
    transform: translateY(-8px);
    border-color: #bb86fc;
    box-shadow: 0 10px 20px rgba(187, 134, 252, 0.2);
}

.modal-content.custom-popup {
    background: rgba(26, 0, 40, 0.85);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(187, 134, 252, 0.3);
    border-radius: 25px;
    color: #fff;
}
.neon-icon {
    font-size: 4rem; color: #bb86fc; text-shadow: 0 0 10px #bb86fc, 0 0 20px #bb86fc;
    animation: neon-glow 1.5s ease-in-out infinite alternate;
}
@keyframes neon-glow {
    from { opacity: 0.8; transform: scale(1); }
    to { opacity: 1; transform: scale(1.1); text-shadow: 0 0 20px #f107a3; color: #f107a3; }
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
                    <?php 
                    // รีเซ็ต pointer ของรูปภาพแกลเลอรี่เพื่อให้วนลูปแสดงผลได้
                    $product_images->data_seek(0);
                    while($img = $product_images->fetch_assoc()){ 
                    ?>
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

                <p class="mt-2" style="line-height: 1.8; font-size: 1.1rem; color: rgba(255, 255, 255, 0.9);">
                    <?=$product['description']?>
                </p>

                <div class="mt-4">
                    <label class="form-label small opacity-75">จำนวนสินค้า</label>
                    <div class="qty-control">
                        <button class="qty-btn" type="button" onclick="changeQty(-1)"><i class="bi bi-dash"></i></button>
                        <input type="number" id="product_qty" class="form-control" value="1" min="1" readonly>
                        <button class="qty-btn" type="button" onclick="changeQty(1)"><i class="bi bi-plus"></i></button>
                    </div>
                </div>

                <div class="mt-5">
                    <?php if(isset($_SESSION['user_id'])){ ?>
                        <div class="row g-3">
                            <div class="col-6">
                                <a href="javascript:void(0)" onclick="buyNow(<?=$product['id']?>)" class="btn btn-neon-pink">
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
                        <a href="login.php" class="btn btn-neon-purple w-100 py-3 text-center text-decoration-none d-block">
                            <i class="bi bi-cart-plus me-2"></i> เข้าสู่ระบบเพื่อสั่งซื้อสินค้า
                        </a>
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

    <div class="mt-5 pt-5">
        <h3 class="mb-4 text-white"><i class="bi bi-stars text-warning me-2"></i>สินค้าที่คุณอาจสนใจ</h3>
        <div class="row g-4">
            <?php 
            $related = $conn->query("SELECT * FROM products WHERE id != $id ORDER BY RAND() LIMIT 4");
            while($rp = $related->fetch_assoc()):
            ?>
            <div class="col-6 col-md-3">
                <div class="card suggested-card h-100 p-2 border-0" onclick="location.href='product.php?id=<?= $rp['id'] ?>'">
                    <img src="images/<?= $rp['image'] ?>" class="card-img-top rounded-3" style="height: 180px; object-fit: cover;">
                    <div class="card-body p-2 text-center">
                        <h6 class="text-white text-truncate small"><?= $rp['name'] ?></h6>
                        <p class="text-info fw-bold mb-0">฿<?= number_format($rp['price']) ?></p>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-popup text-center py-5">
            <div class="modal-body">
                <div class="mb-4">
                    <i class="bi bi-magic neon-icon"></i>
                </div>
                <h3 class="fw-bold mb-3" style="color: #00f2fe;">เพิ่มสินค้าสำเร็จ!</h3>
                <p class="fs-5 opacity-75 mb-4">สินค้าจำนวน <span id="modal_qty" class="fw-bold text-white">1</span> ชิ้น ถูกเพิ่มแล้ว 🔮</p>
                <button type="button" class="btn btn-neon-close" data-bs-dismiss="modal">ตกลง</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function changeQty(amt) {
        let q = document.getElementById('product_qty');
        let v = parseInt(q.value) + amt;
        if (v >= 1) q.value = v;
    }

    function buyNow(pid) {
        let qty = document.getElementById('product_qty').value;
        window.location.href = 'add_to_cart.php?id=' + pid + '&qty=' + qty + '&action=buy';
    }

    function addToCart(productId) {
        let qty = document.getElementById('product_qty').value;
        fetch('add_to_cart.php?id=' + productId + '&qty=' + qty + '&ajax=1')
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                const badge = document.getElementById('cart-badge');
                if(badge) { 
                    badge.textContent = data.total; 
                    badge.style.display = 'block'; 
                }
                document.getElementById('modal_qty').textContent = qty;
                var myModal = new bootstrap.Modal(document.getElementById('cartModal'));
                myModal.show();
            } else { 
                window.location.href = 'login.php'; 
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
</body>
</html>