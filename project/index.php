<?php
session_start();
include "connectdb.php";

/* ================= กรองสินค้า ================= */
$category_slug = $_GET['category'] ?? "";
$search = $_GET['search'] ?? "";

$sql = "SELECT products.*, categories.name as category_name 
        FROM products 
        LEFT JOIN categories ON products.category_id = categories.id WHERE 1";

if($category_slug && $category_slug != "all"){
    $sql .= " AND categories.slug = '".$conn->real_escape_string($category_slug)."'";
}
if($search){
    $sql .= " AND products.name LIKE '%".$conn->real_escape_string($search)."%'";
}

$products = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Goods Secret Store | หน้าแรก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background-color: #0f172a; color: white; }
        .product-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            transition: 0.3s;
            backdrop-filter: blur(10px);
        }
        .product-card:hover { transform: translateY(-10px); background: rgba(255, 255, 255, 0.1); }
        .product-img { height: 200px; object-fit: cover; border-radius: 10px; }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container my-5">
    <h2 class="mb-4">🔮 สินค้าแนะนำ</h2>
    <div class="row">
        <?php while($p = $products->fetch_assoc()){ ?>
        <div class="col-md-3 mb-4">
            <div class="card product-card p-3 text-center h-100">
                <img src="images/<?= $p['image']; ?>" class="product-img mb-2">
                <h6 class="text-truncate"><?= $p['name']; ?></h6>
                <p class="text-info fw-bold"><?= number_format($p['price']); ?> บาท</p>
                
                <div class="mt-auto">
                    <a href="product.php?id=<?= $p['id'] ?>" class="btn btn-outline-light btn-sm w-100 mb-2">รายละเอียด</a>
                    
                    <?php if(isset($_SESSION['user_id'])){ ?>
                        <a href="add_to_cart.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm w-100">
                           <i class="bi bi-cart-plus"></i> เพิ่มลงตะกร้า
                        </a>
                    <?php } else { ?>
                        <button class="btn btn-secondary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#loginModal">
                           เข้าสู่ระบบเพื่อซื้อ
                        </button>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered text-dark">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <i class="bi bi-person-lock display-1 text-warning"></i>
                <h4 class="mt-3">กรุณาเข้าสู่ระบบ</h4>
                <p>คุณต้องเข้าสู่ระบบก่อนจึงจะสามารถเพิ่มสินค้าลงตะกร้าได้</p>
                <a href="login.php" class="btn btn-primary w-100">ไปหน้าเข้าสู่ระบบ</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>