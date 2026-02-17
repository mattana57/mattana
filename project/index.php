<?php
session_start();
include "connectdb.php";

/* ================= ข้อมูลระบบ (คงเดิม) ================= */
$category_slug = $_GET['category'] ?? "";
$search = $_GET['search'] ?? "";
$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");

$sql = "SELECT products.*, categories.name as category_name, categories.slug FROM products LEFT JOIN categories ON products.category_id = categories.id WHERE 1";
if($category_slug && $category_slug != "all"){
    $sql .= " AND categories.slug = '".$conn->real_escape_string($category_slug)."'";
}
if($search){
    $sql .= " AND products.name LIKE '%".$conn->real_escape_string($search)."%'";
}

$showLanding = (!$category_slug && !$search);
if(!$showLanding){ $products = $conn->query($sql); }

$recommended = $conn->query("SELECT * FROM products WHERE featured=1 LIMIT 8");
$newArrival = $conn->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 8");
$discountProducts = $conn->query("SELECT * FROM products WHERE discount > 0 LIMIT 8");

/* --- ดึงจำนวนสินค้ามาโชว์ที่ไอคอนตะกร้า --- */
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
    <title>Goods Secret Store | The Modern Mystery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --neon-purple: #bb86fc;
            --neon-pink: #f107a3;
            --deep-dark: #120018;
            --glass-bg: rgba(255, 255, 255, 0.05);
        }

        body {
            background: radial-gradient(circle at 20% 30%, #4b2c63 0%, transparent 40%), 
                        radial-gradient(circle at 80% 70%, #6a1b9a 0%, transparent 40%), 
                        linear-gradient(135deg, var(--deep-dark), #2a0845, #3d1e6d);
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
        }

        /* Modern Navbar */
        .navbar {
            background: rgba(26, 0, 40, 0.8);
            backdrop-filter: blur(15px);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(187, 134, 252, 0.2);
            padding: 1rem 0;
        }

        /* Clickable Product Card */
        .product-card {
            background: var(--glass-bg);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            overflow: hidden;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-12px);
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--neon-purple);
            box-shadow: 0 15px 35px rgba(0,0,0,0.5), 0 0 20px rgba(187, 134, 252, 0.2);
        }

        /* Neon Buttons */
        .btn-neon-purple {
            background: rgba(187, 134, 252, 0.1);
            border: 1px solid var(--neon-purple);
            color: var(--neon-purple);
            font-weight: 600;
            border-radius: 12px;
            transition: 0.3s;
        }

        .btn-neon-purple:hover {
            background: var(--neon-purple);
            color: var(--deep-dark);
            box-shadow: 0 0 15px var(--neon-purple);
        }

        .btn-neon-pink {
            background: linear-gradient(135deg, var(--neon-pink), #ff0080);
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 12px;
            transition: 0.3s;
            box-shadow: 0 4px 15px rgba(241, 7, 163, 0.3);
        }

        .btn-neon-pink:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px var(--neon-pink);
            color: white;
        }

        .modern-btn {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            padding: 10px 22px;
            border-radius: 30px;
            transition: 0.3s;
            backdrop-filter: blur(5px);
            text-decoration: none;
        }

        .modern-btn:hover, .active-category {
            background: var(--neon-purple);
            color: var(--deep-dark);
            border-color: var(--neon-purple);
        }

        .section-title {
            border-left: 5px solid var(--neon-pink);
            padding-left: 15px;
            margin-bottom: 30px;
            text-shadow: 0 0 10px rgba(241, 7, 163, 0.5);
            font-weight: 700;
        }

        .badge-cart {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--neon-pink);
            color: white;
            border-radius: 50%;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: bold;
        }

        .search-input {
            background: rgba(0,0,0,0.3) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            color: white !important;
            border-radius: 25px !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">🎵 Goods Secret Store</a>
        <div class="ms-auto d-flex align-items-center gap-3">
            <form method="GET" class="d-flex d-none d-md-flex">
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

<div class="container my-5">
    <?php if($showLanding){ ?>
        
        <h4 class="section-title">⭐ สินค้าแนะนำ</h4>
        <div class="row g-4 mb-5">
            <?php while($p = $recommended->fetch_assoc()){ ?>
                <div class="col-md-3">
                    <div class="card product-card p-3 h-100" onclick="location.href='product.php?id=<?= $p['id'] ?>'">
                        <img src="images/<?= $p['image']; ?>" class="img-fluid mb-3 rounded-4" style="height:220px; object-fit:cover;">
                        <h6 class="text-truncate px-1"><?= $p['name']; ?></h6>
                        <p class="text-info fw-bold mb-3 px-1"><?= number_format($p['price']); ?> บาท</p>
                        <div class="mt-auto d-flex gap-2" onclick="event.stopPropagation();">
                            <?php if(isset($_SESSION['user_id'])){ ?>
                                <button onclick="addToCart(<?= $p['id'] ?>)" class="btn btn-neon-purple btn-sm w-50 py-2">ลงตะกร้า</button>
                                <a href="add_to_cart.php?id=<?= $p['id'] ?>&action=buy" class="btn btn-neon-pink btn-sm w-50 py-2 d-flex align-items-center justify-content-center">สั่งซื้อ</a>
                            <?php } else { ?>
                                <button class="btn btn-neon-purple btn-sm w-100 py-2" data-bs-toggle="modal" data-bs-target="#loginModal">เข้าสู่ระบบเพื่อซื้อ</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <h4 class="section-title">🆕 สินค้ามาใหม่</h4>
        <div class="row g-4 mb-5">
            <?php while($p = $newArrival->fetch_assoc()){ ?>
                <div class="col-md-3">
                    <div class="card product-card p-3 h-100" onclick="location.href='product.php?id=<?= $p['id'] ?>'">
                        <img src="images/<?= $p['image']; ?>" class="img-fluid mb-3 rounded-4" style="height:220px; object-fit:cover;">
                        <h6 class="text-truncate px-1"><?= $p['name']; ?></h6>
                        <p class="text-info fw-bold mb-3 px-1"><?= number_format($p['price']); ?> บาท</p>
                        <div class="mt-auto d-flex gap-2" onclick="event.stopPropagation();">
                             <button onclick="addToCart(<?= $p['id'] ?>)" class="btn btn-neon-purple btn-sm w-50 py-2">ลงตะกร้า</button>
                             <a href="add_to_cart.php?id=<?= $p['id'] ?>&action=buy" class="btn btn-neon-pink btn-sm w-50 py-2 d-flex align-items-center justify-content-center">สั่งซื้อ</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

    <?php } else { ?>
        <h4 class="section-title">🔍 ผลลัพธ์การค้นหา</h4>
        <div class="row g-4">
            <?php while($p = $products->fetch_assoc()){ ?>
                <div class="col-md-3">
                    <div class="card product-card p-3 h-100" onclick="location.href='product.php?id=<?= $p['id'] ?>'">
                        <img src="images/<?= $p['image']; ?>" class="img-fluid mb-3 rounded-4" style="height:220px; object-fit:cover;">
                        <h6><?= $p['name']; ?></h6>
                        <p class="text-info fw-bold"><?= number_format($p['price']); ?> บาท</p>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg bg-dark text-white p-4" style="border: 1px solid var(--neon-pink) !important;">
            <div class="modal-body text-center p-4">
                <h4 class="fw-bold mb-4">💫 เข้าสู่ระบบเพื่อดูความลับ</h4>
                <a href="login.php" class="btn btn-neon-pink w-100 mb-3 py-2 text-decoration-none">เข้าสู่ระบบ</a>
                <a href="register.php" class="btn btn-outline-light w-100 py-2 rounded-3 text-decoration-none">สมัครสมาชิก</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function addToCart(productId) {
    fetch('add_to_cart.php?id=' + productId + '&ajax=1')
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            const badge = document.getElementById('cart-badge');
            if(badge) { 
                badge.textContent = data.total; 
                badge.style.display = 'block'; 
            }
            alert('เพิ่มเข้าตะกร้าแล้ว! 🔮');
        } else { 
            window.location.href = 'login.php'; 
        }
    });
}
</script>
</body>
</html>