<?php
session_start();
include "connectdb.php";

/* ================= ADD TO CART ================= */
if(isset($_GET['add'])){
    if(!isset($_SESSION['user'])){
        header("Location: login.php");
        exit();
    }

    $product_id = intval($_GET['add']);
    $user_id = $_SESSION['user'];

    // เช็คว่ามีในตะกร้าแล้วหรือยัง
    $check = $conn->query("SELECT * FROM cart 
                           WHERE user_id=$user_id 
                           AND product_id=$product_id");

    if($check->num_rows > 0){
        $conn->query("UPDATE cart 
                      SET quantity = quantity + 1 
                      WHERE user_id=$user_id 
                      AND product_id=$product_id");
    } else {
        $conn->query("INSERT INTO cart(user_id,product_id,quantity) 
                      VALUES($user_id,$product_id,1)");
    }

    header("Location: index.php");
    exit();
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
/* ====== THEME (เหมือนเดิม) ====== */
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
}

.product-card:hover{
transform:translateY(-10px);
box-shadow:0 0 20px #bb86fc;
}
</style>
</head>
<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
<div class="container">

<a class="navbar-brand fw-bold text-white" href="#">
🎵 Goods Secret Store
</a>

<div class="ms-auto d-flex align-items-center gap-2">

<a href="cart.php" class="brand-btn position-relative">
<i class="bi bi-cart"></i>
<span class="position-absolute top-0 start-100 translate-middle badge bg-danger">
<?php
if(isset($_SESSION['user'])){
$count = $conn->query("SELECT SUM(quantity) as c FROM cart 
                       WHERE user_id=".$_SESSION['user'])->fetch_assoc();
echo $count['c'] ?? 0;
}else{
echo 0;
}
?>
</span>
</a>

<?php if(isset($_SESSION['user'])){ ?>
<a href="logout.php" class="brand-btn">ออกจากระบบ</a>
<?php } else { ?>
<a href="login.php" class="brand-btn">เข้าสู่ระบบ</a>
<a href="register.php" class="brand-btn">สมัครสมาชิก</a>
<?php } ?>

</div>
</div>
</nav>

<!-- ================= PRODUCTS ================= -->
<div class="container my-5">
<div class="row">

<?php
$result = $conn->query("
SELECT products.*, categories.slug 
FROM products 
LEFT JOIN categories ON products.category_id = categories.id
");

while($p = $result->fetch_assoc()){
?>

<div class="col-md-4 col-lg-3 mb-4">
<div class="card product-card p-3 text-center">
<img src="images/<?= $p['image']; ?>" class="img-fluid mb-3">
<h6><?= $p['name']; ?></h6>
<p><?= number_format($p['price']); ?> บาท</p>

<?php if(isset($_SESSION['user'])){ ?>
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

</body>
</html>
