<?php
session_start();
include "connectdb.php";

/* ================= ข้อมูลระบบ (คงเดิม) ================= */
$category_slug = $_GET['category'] ?? "";
$search = $_GET['search'] ?? "";
$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");
$sql = "SELECT products.*, categories.name as category_name, categories.slug FROM products LEFT JOIN categories ON products.category_id = categories.id WHERE 1";
if($category_slug && $category_slug != "all"){ $sql .= " AND categories.slug = '".$conn->real_escape_string($category_slug)."'"; }
if($search){ $sql .= " AND products.name LIKE '%".$conn->real_escape_string($search)."%'"; }
$showLanding = (!$category_slug && !$search);
if(!$showLanding){ $products = $conn->query($sql); }
$recommended = $conn->query("SELECT * FROM products WHERE featured=1 LIMIT 8");
$newArrival = $conn->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 8");
$discountProducts = $conn->query("SELECT * FROM products WHERE discount > 0 LIMIT 8");

/* --- ดึงจำนวนสินค้า --- */
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
<title>Goods Secret Store</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background: radial-gradient(circle at 20% 30%, #4b2c63 0%, transparent 40%), 
                radial-gradient(circle at 80% 70%, #6a1b9a 0%, transparent 40%), 
                linear-gradient(135deg,#120018,#2a0845,#3d1e6d);
    color: #fff;
    font-family: 'Segoe UI', sans-serif;
}
.navbar { background: rgba(26, 0, 40, 0.8); backdrop-filter: blur(10px); position: sticky; top: 0; z-index: 1000; border-bottom: 1px solid rgba(187, 134, 252, 0.2); }

/* Card Styling */
.product-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    display: block;
}
.product-card:hover {
    transform: translateY(-12px);
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(187, 134, 252, 0.5);
    box-shadow: 0 15px 30px rgba(0,0,0,0.5), 0 0 20px rgba(187, 134, 252, 0.2);
}

/* ปุ่มลงตะกร้าแบบม่วงนีออน */
.btn-neon-purple {
    background: rgba(187, 134, 252, 0.1);
    border: 1px solid #bb86fc;
    color: #bb86fc;
    font-weight: 600;
    transition: 0.3s;
    border-radius: 10px;
}
.btn-neon-purple:hover {
    background: #bb86fc;
    color: #120018;
    box-shadow: 0 0 15px #bb86fc;
}

/* ปุ่มสั่งซื้อแบบชมพู Magenta นีออน */
.btn-neon-pink {
    background: linear-gradient(135deg, #f107a3, #ff0080);
    border: none;
    color: white;
    font-weight: bold;
    border-radius: 10px;
    transition: 0.3s;
    box-shadow: 0 4px 15px rgba(241, 7, 163, 0.3);
}
.btn-neon-pink:hover {
    transform: scale(1.05);
    box-shadow: 0 0 20px #f107a3;
    color: white;
}

.modern-btn { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color:#fff; padding:8px 18px; border-radius:30px; transition:.3s; backdrop-filter: blur(5px); text-decoration: none; }
.modern-btn:hover { background: #bb86fc; color:#120018; border-color: #bb86fc; }
.active-category { background: #bb86fc; color: #120018; border-color: #bb86fc; }
.section-title { border-left: 5px solid #f107a3; padding-left: 15px; margin-bottom: 25px; text-shadow: 0 0 10px rgba(241, 7, 163, 0.5); }
.badge-cart { position: absolute; top: -5px; right: -5px; background: #f107a3; color: white; border-radius: 50%; padding: 3px 7px; font-size: 10px; }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark py-3">
<div class="container">
    <a class="navbar-brand fw-bold" href="index.php">🎵 Goods Secret Store</a>
    <div class="ms-auto d-flex align-items-center gap-3">
        <form method="GET" class="d-flex">
            <input class="form-control me-2 bg-dark border-secondary text-white" type="search" name="search" placeholder="ค้นหาความลับ...">
            <button class="modern-btn"><i class="bi bi-search"></i></button>
        </form>
        <?php if(isset($_SESSION['user_id'])){ ?>
            <a href="cart.php" class="modern-btn position-relative"><i class="bi bi-cart"></i><span id="cart-badge" class="badge-cart" style="<?= ($cart_count > 0) ? '' : 'display:none;' ?>"><?= $cart_count ?></span></a>
            <a href="logout.php" class="modern-btn">ออกจากระบบ</a>
        <?php } else { ?>
            <a href="login.php" class="modern-btn">เข้าสู่ระบบ</a>
            <a href="register.php" class="modern-btn">สมัครสมาชิก</a>
        <?php } ?>
    </div>
</div>
</nav>

<div class="container my-5">
    <?php 
    function renderProductGrid($title, $result, $show = true) {
        if(!$show) return;
        echo '<h4 class="section-title">'.$title.'</h4>';
        echo '<div class="row">';
        while($p = $result->fetch_assoc()) {
            $price = number_format($p['price']);
            $final_price = isset($p['discount']) ? number_format($p['price'] - $p['discount']) : $price;
            ?>
            <div class="col-md-3 mb-4">
                <div class="card product-card p-3 h-100" onclick="location.href='product.php?id=<?= $p['id'] ?>'">
                    <img src="images/<?= $p['image']; ?>" class="img-fluid mb-3 rounded" style="height:200px; object-fit:cover;">
                    <h6 class="text-truncate"><?= $p['name']; ?></h6>
                    <p class="mb-3 text-info fw-bold"><?= $final_price; ?> บาท</p>
                    
                    <div class="mt-auto d-flex gap-2" onclick="event.stopPropagation();">
                        <?php if(isset($_SESSION['user_id'])){ ?>
                            <button onclick="addToCart(<?= $p['id'] ?>)" class="btn btn-neon-purple btn-sm w-50">
                                <i class="bi bi-cart-plus"></i> ลงตะกร้า
                            </button>
                            <a href="add_to_cart.php?id=<?= $p['id'] ?>&action=buy" class="btn btn-neon-pink btn-sm w-50 d-flex align-items-center justify-content-center">
                                สั่งซื้อ
                            </a>
                        <?php } else { ?>
                            <button class="btn btn-neon-purple btn-sm w-100" data-bs-toggle="modal" data-bs-target="#loginModal">
                                เข้าสู่ระบบเพื่อซื้อ
                            </button>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    }

    if($showLanding) {
        renderProductGrid('⭐ สินค้าแนะนำ', $recommended);
        renderProductGrid('🆕 สินค้ามาใหม่', $newArrival);
        renderProductGrid('🔥 สินค้าลดราคา', $discountProducts);
    } else {
        renderProductGrid('🔍 ผลลัพธ์การค้นหา', $products);
    }
    ?>
</div>

<script>
function addToCart(productId) {
    fetch('add_to_cart.php?id=' + productId + '&ajax=1')
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            const badge = document.getElementById('cart-badge');
            if(badge) { badge.textContent = data.total; badge.style.display = 'block'; }
            // เปลี่ยน alert เป็นแบบสวยขึ้นได้ในอนาคต
            alert('เพิ่มเข้าตะกร้าแล้ว! 🔮');
        } else { window.location.href = 'login.php'; }
    });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="modal fade" id="loginModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow-lg bg-dark text-white p-4" style="border: 1px solid #f107a3 !important;">
      <div class="modal-body text-center p-4">
        <h4 class="fw-bold mb-4">💫 เข้าสู่ระบบเพื่อดูความลับ</h4>
        <a href="login.php" class="btn btn-neon-pink w-100 mb-3 py-2">เข้าสู่ระบบ</a>
        <a href="register.php" class="btn btn-outline-light w-100 py-2 rounded-3">สมัครสมาชิก</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>