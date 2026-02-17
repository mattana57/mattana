<?php
session_start();
include "connectdb.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$res = $conn->query("SELECT * FROM products WHERE id=$id");
$product = $res->fetch_assoc();

if(!$product){
    header("Location:index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?=$product['name']?> | Goods Secret</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background: #0f172a; color: white; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .product-main-img {
            width: 100%; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .btn-cart {
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            color: white; border: none; padding: 12px 30px; border-radius: 50px;
            font-weight: 600; width: 100%; transition: 0.3s;
        }
        .btn-cart:hover { transform: scale(1.02); filter: brightness(1.2); color: white; }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container my-5">
    <div class="row g-5">
        <div class="col-md-6 text-center">
            <img src="images/<?=$product['image']?>" class="product-main-img shadow" id="mainImg">
        </div>
        
        <div class="col-md-6">
            <div class="glass-panel">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="index.php" class="text-info text-decoration-none">หน้าแรก</a></li>
                    <li class="breadcrumb-item active text-light opacity-50"><?= $product['name'] ?></li>
                  </ol>
                </nav>

                <h1 class="display-5 fw-bold mb-3"><?= $product['name'] ?></h1>
                <h2 class="text-warning mb-4">฿<?= number_format($product['price']) ?></h2>
                
                <p class="text-light opacity-75 mb-4" style="line-height: 1.8;">
                    <?= nl2br($product['description']) ?>
                </p>

                <hr class="my-4 opacity-10">

                <div class="d-grid gap-3">
                    <?php if(isset($_SESSION['user_id'])){ ?>
                        <a href="add_to_cart.php?id=<?=$product['id']?>" class="btn btn-cart shadow-lg">
                            <i class="bi bi-cart-plus-fill me-2"></i> เพิ่มสินค้าลงตะกร้า
                        </a>
                    <?php } else { ?>
                        <button class="btn btn-outline-light py-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#loginModal">
                            เข้าสู่ระบบเพื่อสั่งซื้อสินค้า
                        </button>
                    <?php } ?>
                </div>

                <div class="mt-4 small text-secondary">
                    <i class="bi bi-shield-check text-success me-2"></i> รับประกันสินค้าแท้ 100%<br>
                    <i class="bi bi-truck text-success me-2"></i> จัดส่งฟรีเมื่อซื้อครบ 1,000 บาท
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>