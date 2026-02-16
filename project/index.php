<?php
session_start();
include "connectdb.php";

/* ================= SEARCH ================= */
$search = "";
if(isset($_GET['search'])){
    $search = trim($_GET['search']);
}

/* ================= ADD TO CART ================= */
if(isset($_GET['add'])){

    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit();
    }

    $product_id = intval($_GET['add']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT id FROM cart WHERE user_id=? AND product_id=?");
    $stmt->bind_param("ii",$user_id,$product_id);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0){
        $update = $conn->prepare("UPDATE cart SET quantity = quantity+1 WHERE user_id=? AND product_id=?");
        $update->bind_param("ii",$user_id,$product_id);
        $update->execute();
    }else{
        $insert = $conn->prepare("INSERT INTO cart(user_id,product_id,quantity) VALUES(?,?,1)");
        $insert->bind_param("ii",$user_id,$product_id);
        $insert->execute();
    }

    header("Location: index.php");
    exit();
}

/* ================= GET CATEGORY ================= */
$currentCategory = isset($_GET['category']) ? $_GET['category'] : "";

/* ================= GET PRODUCTS ================= */
$sql = "
SELECT products.*, categories.slug 
FROM products 
LEFT JOIN categories ON products.category_id = categories.id
WHERE 1
";

if($search != ""){
    $sql .= " AND products.name LIKE '%".$conn->real_escape_string($search)."%'";
}

if($currentCategory != ""){
    $sql .= " AND categories.slug = '".$conn->real_escape_string($currentCategory)."'";
}

$products = $conn->query($sql);

/* ================= GET CATEGORIES ================= */
$categories = $conn->query("SELECT * FROM categories");

/* ================= CART COUNT ================= */
$cartCount = 0;
if(isset($_SESSION['user_id'])){
    $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id=?");
    $stmt->bind_param("i",$_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $cartCount = $result['total'] ?? 0;
}
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
background-attachment: fixed;
color:#fff;
font-family:'Segoe UI',sans-serif;
}

.navbar{
background:linear-gradient(90deg,#1a0028,#3d1e6d);
padding:15px 0;
}

.brand-btn{
background:#E0BBE4;
color:#2a0845;
border:none;
border-radius:0;
font-weight:600;
padding:8px 18px;
transition:.3s;
}

.brand-btn:hover{
background:#d39ddb;
color:#000;
transform:translateY(-2px);
}

.product-card{
background:rgba(255,255,255,0.05);
border:1px solid rgba(255,255,255,0.1);
border-radius:0;
backdrop-filter:blur(8px);
transition:.3s;
color:#fff;
}

.product-card:hover{
transform:translateY(-10px);
box-shadow:0 0 20px #bb86fc;
}

.carousel-item img{
height:400px;
object-fit:cover;
border-radius:10px;
}

.category-btn{
margin:5px;
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
<div class="container">

<a class="navbar-brand fw-bold text-white" href="index.php">
🎵 Goods Secret Store
</a>

<!-- SEARCH -->
<form class="d-flex ms-4" method="GET">
<input class="form-control me-2" type="search" name="search"
placeholder="ค้นหาสินค้า..." value="<?= htmlspecialchars($search) ?>">
<button class="brand-btn">ค้นหา</button>
</form>

<div class="ms-auto d-flex align-items-center gap-2">

<a href="cart.php" class="brand-btn position-relative">
<i class="bi bi-cart"></i>
<span class="position-absolute top-0 start-100 translate-middle badge bg-danger">
<?= $cartCount ?>
</span>
</a>

<?php if(isset($_SESSION['user_id'])){ ?>
<a href="logout.php" class="brand-btn">ออกจากระบบ</a>
<?php } else { ?>
<a href="login.php" class="brand-btn">เข้าสู่ระบบ</a>
<a href="register.php" class="brand-btn">สมัครสมาชิก</a>
<?php } ?>

</div>
</div>
</nav>

<!-- BANNER -->
<div class="container mt-3">
<div id="mainBanner" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
<div class="carousel-inner">
<div class="carousel-item active">
<img src="images/BN1.png" class="d-block w-100">
</div>
<div class="carousel-item">
<img src="images/BN2.png" class="d-block w-100">
</div>
</div>
</div>
</div>

<!-- CATEGORY BUTTONS -->
<div class="container text-center mt-4">
<a href="index.php" class="btn btn-outline-light category-btn">ทั้งหมด</a>

<?php while($cat = $categories->fetch_assoc()){ ?>
<a href="?category=<?= $cat['slug']; ?>" 
class="btn btn-outline-light category-btn">
<?= $cat['name']; ?>
</a>
<?php } ?>

</div>

<!-- PRODUCTS -->
<div class="container my-5">
<div class="row">

<?php while($p = $products->fetch_assoc()){ ?>

<div class="col-md-4 col-lg-3 mb-4">
<div class="card product-card p-3 text-center">

<img src="images/<?= $p['image']; ?>" class="img-fluid mb-3">

<h6><?= $p['name']; ?></h6>
<p><?= number_format($p['price']); ?> บาท</p>

<?php if(isset($_SESSION['user_id'])){ ?>
<a href="?add=<?= $p['id']; ?>" class="brand-btn w-100">
เพิ่มลงตะกร้า
</a>
<?php } else { ?>
<a href="login.php" class="brand-btn w-100">
เข้าสู่ระบบก่อนซื้อ
</a>
<?php } ?>

</div>
</div>

<?php } ?>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
