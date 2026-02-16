<?php
session_start();
include "connectdb.php";

/* ================= SEARCH ================= */
$search = $_GET['search'] ?? "";

/* ================= GET CATEGORIES ================= */
$categories = $conn->query("SELECT * FROM categories");

/* ================= RECOMMENDED ================= */
$recommended = $conn->query("
SELECT * FROM products 
WHERE featured = 1 
ORDER BY RAND() 
LIMIT 8
");

/* ================= NEW ARRIVAL ================= */
$newArrival = $conn->query("
SELECT * FROM products 
ORDER BY created_at DESC 
LIMIT 8
");

/* ================= DISCOUNT ================= */
$discountProducts = $conn->query("
SELECT * FROM products 
WHERE discount > 0 
LIMIT 8
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

.search-box{
border-radius:30px;
padding:6px 15px;
border:none;
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
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark py-3">
<div class="container">

<a class="navbar-brand fw-bold text-white" href="index.php">
🎵 Goods Secret Store
</a>

<div class="ms-auto d-flex align-items-center gap-3">

<form action="category.php" method="GET" class="d-flex">
<input class="form-control search-box me-2"
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

<!-- BANNER -->
<div class="container mt-3">
<div id="mainBanner" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
<div class="carousel-inner">
<div class="carousel-item active">
<img src="images/BN1.png" class="d-block w-100" style="height:400px;object-fit:cover;border-radius:10px;">
</div>
<div class="carousel-item">
<img src="images/BN2.png" class="d-block w-100" style="height:400px;object-fit:cover;border-radius:10px;">
</div>
</div>
</div>
</div>

<!-- CATEGORY BUTTONS -->
<div class="container text-center mt-4">
<?php while($cat = $categories->fetch_assoc()){ ?>
<a href="category.php?slug=<?= $cat['slug']; ?>" 
class="modern-btn m-1">
<?= $cat['name']; ?>
</a>
<?php } ?>
</div>

<!-- RECOMMENDED -->
<div class="container my-5">
<h4 class="section-title">⭐ สินค้าแนะนำ</h4>
<div class="row">
<?php while($p = $recommended->fetch_assoc()){ ?>
<div class="col-md-3 mb-4">
<div class="card product-card p-3 text-center">
<img src="images/<?= $p['image']; ?>" class="img-fluid mb-2">
<h6><?= $p['name']; ?></h6>
<p><?= number_format($p['price']); ?> บาท</p>
</div>
</div>
<?php } ?>
</div>
</div>

<!-- NEW ARRIVAL -->
<div class="container my-5">
<h4 class="section-title">🆕 สินค้ามาใหม่</h4>
<div class="row">
<?php while($p = $newArrival->fetch_assoc()){ ?>
<div class="col-md-3 mb-4">
<div class="card product-card p-3 text-center">
<img src="images/<?= $p['image']; ?>" class="img-fluid mb-2">
<h6><?= $p['name']; ?></h6>
<p><?= number_format($p['price']); ?> บาท</p>
</div>
</div>
<?php } ?>
</div>
</div>

<!-- DISCOUNT -->
<div class="container my-5">
<h4 class="section-title">🔥 สินค้าลดราคา</h4>
<div class="row">
<?php while($p = $discountProducts->fetch_assoc()){ ?>
<div class="col-md-3 mb-4">
<div class="card product-card p-3 text-center">
<img src="images/<?= $p['image']; ?>" class="img-fluid mb-2">
<h6><?= $p['name']; ?></h6>
<p>
<span class="text-danger fw-bold">
<?= number_format($p['price'] - $p['discount']); ?> บาท
</span>
<small class="text-decoration-line-through text-light">
<?= number_format($p['price']); ?>
</small>
</p>
</div>
</div>
<?php } ?>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
