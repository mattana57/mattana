<?php
session_start();
include "connectdb.php";
$id = intval($_GET['id']);
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
$product_images = $conn->query("SELECT * FROM product_images WHERE product_id = $id");

if(!$product){ header("Location:index.php"); exit(); }

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
/* --- Neon Mystery Master Style --- */
body {
    background: radial-gradient(circle at 20% 30%, #4b2c63 0%, transparent 40%), 
                radial-gradient(circle at 80% 70%, #6a1b9a 0%, transparent 40%), 
                linear-gradient(135deg,#120018,#2a0845,#3d1e6d);
    color: #ffffff !important;
    font-family: 'Segoe UI', sans-serif;
    min-height: 100vh;
}

.navbar { background: rgba(26, 0, 40, 0.85); backdrop-filter: blur(15px); position: sticky; top: 0; z-index: 1000; border-bottom: 1px solid rgba(187, 134, 252, 0.2); }

/* ปรับปรุงช่องค้นหาให้สว่างตามที่เคยขอ */
.search-input { background: #c6a9cdd5 !important; border: 1px solid rgba(187, 134, 252, 0.5) !important; color: #ffffff !important; border-radius: 25px !important; padding-left: 20px !important; }
.search-input::placeholder { color: rgba(255, 255, 255, 0.7) !important; }

.product-card-panel { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 40px; }
.product-title { font-weight: 700; font-size: 32px; color: #ffffff; text-shadow: 0 0 15px rgba(187, 134, 252, 0.5); }
.product-price { color: #00f2fe !important; font-size: 30px; font-weight: 700; text-shadow: 0 0 10px rgba(0, 242, 254, 0.4); }

/* ระบบเลือกจำนวน */
.qty-group { width: 150px; }
.qty-btn { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; transition: 0.3s; }
.qty-btn:hover { background: #bb86fc; color: #120018; }
#product_qty { background: transparent !important; color: white !important; border: 1px solid rgba(255,255,255,0.2) !important; font-weight: bold; text-align: center; }

/* ปุ่มนีออน */
.btn-neon-purple { background: rgba(187, 134, 252, 0.1); border: 1px solid #bb86fc; color: #bb86fc; font-weight: 600; border-radius: 12px; padding: 15px; transition: 0.3s; width: 100%; }
.btn-neon-purple:hover { background: #bb86fc; color: #120018; box-shadow: 0 0 20px #bb86fc; }
.btn-neon-pink { background: linear-gradient(135deg, #f107a3, #ff0080); border: none; color: white; font-weight: bold; border-radius: 12px; padding: 15px; transition: 0.3s; text-decoration: none; display: block; text-align: center; }
.btn-neon-pink:hover { transform: translateY(-3px); box-shadow: 0 0 25px #f107a3; color: white; }

.modern-btn { background: rgba(255,255,255,0.1); color:#fff; border: 1px solid rgba(255,255,255,0.2); padding: 8px 18px; border-radius: 30px; text-decoration: none; transition: 0.3s; }
.badge-cart { position: absolute; top: -5px; right: -5px; background: #f107a3; color: white; font-size: 11px; padding: 2px 6px; border-radius: 50%; border: 1px solid #1a0028; }

/* Modal Glassmorphism */
.modal-content.custom-popup { background: rgba(26, 0, 40, 0.9); backdrop-filter: blur(20px); border: 1px solid rgba(187, 134, 252, 0.4); border-radius: 25px; color: #fff; box-shadow: 0 0 40px rgba(187, 134, 252, 0.2); }
.neon-icon { font-size: 4rem; color: #bb86fc; text-shadow: 0 0 20px #bb86fc; animation: neon-glow 1.5s infinite alternate; }
@keyframes neon-glow { from { transform: scale(1); } to { transform: scale(1.1); color: #f107a3; text-shadow: 0 0 30px #f107a3; } }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark py-3">
<div class="container">
    <a class="navbar-brand fw-bold text-white" href="index.php">🎵 Goods Secret Store</a>
    <div class="ms-auto d-flex align-items-center gap-3">
        <form method="GET" action="index.php" class="d-flex">
            <input class="form-control me-2 search-input" type="search" name="search" placeholder="ค้นหาความลับ...">
            <button class="modern-btn"><i class="bi bi-search"></i></button>
        </form>
        <?php if(isset($_SESSION['user_id'])){ ?>
            <a href="cart.php" class="modern-btn position-relative">
                <i class="bi bi-cart"></i>
                <span id="cart-badge" class="badge-cart" style="<?= ($cart_count > 0) ? '' : 'display:none;' ?>"><?= $cart_count ?></span>
            </a>
            <a href="logout.php" class="modern-btn">ออกจากระบบ</a>
        <?php } else { ?>
            <a href="login.php" class="modern-btn">เข้าสู่ระบบ</a>
        <?php } ?>
    </div>
</div>
</nav>

<div class="container mt-5 py-4">
    <div class="row g-5">
        <div class="col-md-5 text-center">
            <img id="mainImage" src="images/<?= $product['image']; ?>" class="img-fluid mb-4 rounded-4 shadow-lg border border-secondary" style="max-height: 480px; object-fit: contain; background: rgba(0,0,0,0.2);">
        </div>
        <div class="col-md-7">
            <div class="product-card-panel shadow-lg">
                <h2 class="product-title mb-3"><?=$product['name']?></h2>
                <div class="product-price mb-4">฿<?=number_format($product['price'])?></div>
                <p class="mt-2" style="line-height: 1.8; opacity: 0.9;"><?=$product['description']?></p>

                <div class="mt-4 mb-4">
                    <label class="form-label d-block mb-3 small opacity-50">เลือกจำนวนความลับ</label>
                    <div class="input-group qty-group">
                        <button class="btn qty-btn" type="button" onclick="changeQty(-1)"><i class="bi bi-dash"></i></button>
                        <input type="number" id="product_qty" class="form-control" value="1" min="1" readonly>
                        <button class="btn qty-btn" type="button" onclick="changeQty(1)"><i class="bi bi-plus"></i></button>
                    </div>
                </div>

                <div class="mt-5">
                    <?php if(isset($_SESSION['user_id'])){ ?>
                        <div class="row g-3">
                            <div class="col-6">
                                <a href="javascript:void(0)" onclick="buyNow(<?=$product['id']?>)" class="btn btn-neon-pink">สั่งซื้อทันที</a>
                            </div>
                            <div class="col-6">
                                <button onclick="addToCart(<?=$product['id']?>)" class="btn btn-neon-purple">เพิ่มลงตะกร้า</button>
                            </div>
                        </div>
                    <?php } else { ?>
                        <a href="login.php" class="btn btn-neon-purple w-100 py-3">เข้าสู่ระบบเพื่อสั่งซื้อสินค้า</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-popup text-center py-5">
            <div class="modal-body">
                <i class="bi bi-magic neon-icon mb-4"></i>
                <h3 class="fw-bold mb-3" style="color: #00f2fe;">เพิ่มสินค้าสำเร็จ!</h3>
                <p class="fs-5 opacity-75 mb-4">ความลับจำนวน <span id="modal_qty" class="fw-bold text-white">1</span> ชิ้น ถูกเพิ่มแล้ว 🔮</p>
                <button type="button" class="btn btn-neon-close px-5 py-2 rounded-pill text-white" style="background: linear-gradient(45deg, #7c3aed, #db2777); border:none;" data-bs-dismiss="modal">ตกลง</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function changeQty(amt) {
    let q = document.getElementById('product_qty');
    let v = parseInt(q.value) + amt;
    if(v >= 1) q.value = v;
}

function addToCart(pid) {
    let qty = document.getElementById('product_qty').value;
    fetch('add_to_cart.php?id=' + pid + '&qty=' + qty + '&ajax=1')
    .then(r => r.json())
    .then(data => {
        if(data.status === 'success') {
            const badge = document.getElementById('cart-badge');
            if(badge) { badge.textContent = data.total; badge.style.display = 'block'; }
            document.getElementById('modal_qty').textContent = qty;
            new bootstrap.Modal(document.getElementById('cartModal')).show();
        } else { window.location.href = 'login.php'; }
    });
}

function buyNow(pid) {
    let qty = document.getElementById('product_qty').value;
    window.location.href = 'add_to_cart.php?id=' + pid + '&qty=' + qty + '&action=buy';
}
</script>
</body>
</html>