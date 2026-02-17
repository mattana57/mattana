<?php
session_start();
include "connectdb.php";

/* ================= GET CATEGORY ================= */
$category_slug = $_GET['category'] ?? "";
$search = $_GET['search'] ?? "";

/* ================= GET ALL CATEGORIES ================= */
$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");

/* ================= BASE SQL ================= */
$sql = "
SELECT products.*, categories.name as category_name, categories.slug
FROM products
LEFT JOIN categories ON products.category_id = categories.id
WHERE 1
";

/* Filter Category */
if($category_slug && $category_slug != "all"){
    $sql .= " AND categories.slug = '".$conn->real_escape_string($category_slug)."'";
}

/* Filter Search */
if($search){
    $sql .= " AND products.name LIKE '%".$conn->real_escape_string($search)."%'";
}

/* หน้าแรกไม่แสดงทั้งหมด */
$showLanding = (!$category_slug && !$search);

if(!$showLanding){
    $products = $conn->query($sql);
}

/* Landing Sections */
$recommended = $conn->query("
SELECT * FROM products WHERE featured=1 LIMIT 8
");

$newArrival = $conn->query("
SELECT * FROM products ORDER BY created_at DESC LIMIT 8
");

$discountProducts = $conn->query("
SELECT * FROM products WHERE discount > 0 LIMIT 8
");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Goods Secret Store</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
background:
radial-gradient(circle at 20% 30%, #4b2c63 0%, transparent 40%),
radial-gradient(circle at 80% 70%, #6a1b9a 0%, transparent 40%),
linear-gradient(135deg,#120018,#2a0845,#3d1e6d);
color:#fff;
font-family:'Segoe UI',sans-serif;
}

.navbar{
background:linear-gradient(90deg,#1a0028,#3d1e6d);
}

.modern-btn{
background:linear-gradient(135deg,#E0BBE4,#bb86fc);
color:#2a0845;
border:none;
padding:8px 18px;
border-radius:30px;
font-weight:600;
transition:.3s;
box-shadow:0 0 10px rgba(187,134,252,.5);
text-decoration:none;
display:inline-block;
}

.modern-btn:hover{
transform:translateY(-3px);
box-shadow:0 0 20px #bb86fc;
color:#000;
}

.active-category{
background:#fff;
color:#2a0845;
}

.product-card{
background:rgba(255,255,255,0.05);
border:1px solid rgba(255,255,255,0.1);
backdrop-filter:blur(10px);
transition:.3s;
color:#fff;
}

.product-card:hover{
transform:translateY(-8px);
box-shadow:0 0 20px #bb86fc;
}

.section-title{
border-left:5px solid #bb86fc;
padding-left:10px;
margin-bottom:20px;
}

/* Modern Glass Modal */
.auth-modal {
    background: rgba(40, 0, 70, 0.85);
    backdrop-filter: blur(15px);
    border-radius: 20px;
}

/* Gradient Primary Button */
.btn-gradient {
    background: linear-gradient(135deg, #7b2ff7, #f107a3);
    border: none;
    color: white;
    font-weight: 500;
    padding: 12px;
    border-radius: 12px;
    transition: 0.3s;
}

.btn-gradient:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.4);
}

/* Outline Secondary */
.btn-outline-modern {
    border: 2px solid rgba(255,255,255,0.3);
    color: white;
    padding: 12px;
    border-radius: 12px;
    transition: 0.3s;
}

.btn-outline-modern:hover {
    background: rgba(255,255,255,0.1);
}

.btn-warning {
    background: linear-gradient(135deg, #bb86fc, #7b2ff7);
    border: none;
    color: #fff;
    font-weight: 600;
    border-radius: 8px;
    transition: 0.3s;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #d1a3ff, #9b4dff);
    transform: translateY(-2px);
    box-shadow: 0 0 15px rgba(187,134,252,0.7);
}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark py-3">
<div class="container">

<a class="navbar-brand fw-bold text-white" href="index.php">
🎵 Goods Secret Store
</a>

<div class="ms-auto d-flex align-items-center gap-3">

<form method="GET" class="d-flex">
<input class="form-control me-2" 
type="search"
name="search"
placeholder="ค้นหาสินค้า...">
<button class="modern-btn">
<i class="bi bi-search"></i>
</button>
</form>

<?php if(isset($_SESSION['user_id'])){ ?>
<a href="cart.php" class="modern-btn">
<i class="bi bi-cart"></i>
</a>
<a href="logout.php" class="modern-btn">ออกจากระบบ</a>
<?php } else { ?>
<a href="login.php" class="modern-btn">เข้าสู่ระบบ</a>
<a href="register.php" class="modern-btn">สมัครสมาชิก</a>
<?php } ?>

</div>
</div>
</nav>

<div class="container mt-4">
<div id="mainBanner" class="carousel slide carousel-fade shadow-lg rounded-4 overflow-hidden"
     data-bs-ride="carousel"
     data-bs-interval="3500">

<div class="carousel-inner">

<div class="carousel-item active">
<img src="images/BN1.png" class="d-block w-100"
     style="height:420px;object-fit:cover;">
</div>

<div class="carousel-item">
<img src="images/BN2.png" class="d-block w-100"
     style="height:420px;object-fit:cover;">
</div>

</div>
</div>
</div>


<div class="container text-center mt-4">

<a href="index.php?category=all"
class="modern-btn m-1 <?= ($category_slug=='all')?'active-category':'' ?>">
ทั้งหมด
</a>

<?php while($cat = $categories->fetch_assoc()){ ?>
<a href="index.php?category=<?= $cat['slug']; ?>"
class="modern-btn m-1 <?= ($category_slug==$cat['slug'])?'active-category':'' ?>">
<?= $cat['name']; ?>
</a>
<?php } ?>

</div>

<div class="container my-5">

<?php if($showLanding){ ?>

<h4 class="section-title">⭐ สินค้าแนะนำ</h4>
<div class="row">
<?php while($p = $recommended->fetch_assoc()){ ?>
<div class="col-md-3 mb-4">
<div class="card product-card p-3 text-center h-100">
<img src="images/<?= $p['image']; ?>" class="img-fluid mb-2 rounded" style="height:200px; object-fit:cover;">
<h6><?= $p['name']; ?></h6>
<p><?= number_format($p['price']); ?> บาท</p>
<div class="mt-auto">
    <a href="product.php?id=<?= $p['id'] ?>" class="btn btn-light btn-sm mt-2 w-100">รายละเอียด</a>
    <?php if(isset($_SESSION['user_id'])){ ?>
        <a href="add_to_cart.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm mt-2 w-100">เพิ่มลงตะกร้า</a>
    <?php } else { ?>
        <button class="btn btn-warning btn-sm mt-2 w-100" data-bs-toggle="modal" data-bs-target="#loginModal">เพิ่มลงตะกร้า</button>
    <?php } ?>
</div>
</div>
</div>
<?php } ?>
</div>

<h4 class="section-title mt-5">🆕 สินค้ามาใหม่</h4>
<div class="row">
<?php while($p = $newArrival->fetch_assoc()){ ?>
<div class="col-md-3 mb-4">
<div class="card product-card p-3 text-center h-100">
<img src="images/<?= $p['image']; ?>" class="img-fluid mb-2 rounded" style="height:200px; object-fit:cover;">
<h6><?= $p['name']; ?></h6>
<p><?= number_format($p['price']); ?> บาท</p>
<div class="mt-auto">
    <a href="product.php?id=<?= $p['id'] ?>" class="btn btn-light btn-sm mt-2 w-100">รายละเอียด</a>
    <?php if(isset($_SESSION['user_id'])){ ?>
        <a href="add_to_cart.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm mt-2 w-100">เพิ่มลงตะกร้า</a>
    <?php } else { ?>
        <button class="btn btn-warning btn-sm mt-2 w-100" data-bs-toggle="modal" data-bs-target="#loginModal">เพิ่มลงตะกร้า</button>
    <?php } ?>
</div>
</div>
</div>
<?php } ?>
</div>

<h4 class="section-title mt-5">🔥 สินค้าลดราคา</h4>
<div class="row">
<?php while($p = $discountProducts->fetch_assoc()){ ?>
<div class="col-md-3 mb-4">
<div class="card product-card p-3 text-center h-100">
<img src="images/<?= $p['image']; ?>" class="img-fluid mb-2 rounded" style="height:200px; object-fit:cover;">
<h6><?= $p['name']; ?></h6>
<p>
<span class="text-danger fw-bold"><?= number_format($p['price'] - $p['discount']); ?> บาท</span>
<small class="text-decoration-line-through text-light opacity-50 ms-1"><?= number_format($p['price']); ?></small>
</p>
<div class="mt-auto">
    <a href="product.php?id=<?= $p['id'] ?>" class="btn btn-light btn-sm mt-2 w-100">รายละเอียด</a>
    <?php if(isset($_SESSION['user_id'])){ ?>
        <a href="add_to_cart.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm mt-2 w-100">เพิ่มลงตะกร้า</a>
    <?php } else { ?>
        <button class="btn btn-warning btn-sm mt-2 w-100" data-bs-toggle="modal" data-bs-target="#loginModal">เพิ่มลงตะกร้า</button>
    <?php } ?>
</div>
</div>
</div>
<?php } ?>
</div>

<?php } else { ?>

<h4 class="section-title">ผลลัพธ์สินค้า</h4>
<div class="row">
<?php while($p = $products->fetch_assoc()){ ?>
<div class="col-md-3 mb-4">
<div class="card product-card p-3 text-center h-100">
<img src="images/<?= $p['image']; ?>" class="img-fluid mb-2 rounded" style="height:200px; object-fit:cover;">
<h6><?= $p['name']; ?></h6>
<p><?= number_format($p['price']); ?> บาท</p>
<div class="mt-auto">
    <a href="product.php?id=<?= $p['id'] ?>" class="btn btn-light btn-sm mt-2 w-100">รายละเอียด</a>
    <?php if(isset($_SESSION['user_id'])){ ?>
        <a href="add_to_cart.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm mt-2 w-100">เพิ่มลงตะกร้า</a>
    <?php } else { ?>
        <button class="btn btn-warning btn-sm mt-2 w-100" data-bs-toggle="modal" data-bs-target="#loginModal">เพิ่มลงตะกร้า</button>
    <?php } ?>
</div>
</div>
</div>
<?php } ?>
</div>

<?php } ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="modal fade" id="loginModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow-lg auth-modal">
      <div class="modal-body text-center p-5 text-white">
        <h4 class="fw-bold mb-4">💫กรุณาเข้าสู่ระบบก่อนสั่งซื้อสินค้า</h4>
        <a href="login.php" class="btn btn-gradient w-100 mb-3">เข้าสู่ระบบ</a>
        <a href="register.php" class="btn btn-outline-modern w-100">สมัครสมาชิก</a>
      </div>
    </div>
  </div>
</div>
</body>
</html>