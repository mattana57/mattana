<?php
include "connectdb.php";
$id = intval($_GET['id']);
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
if(!$product){
    header("Location:index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title><?=$product['name']?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">

<div class="row">
<div class="col-md-5">
<img src="images/<?=$product['image']?>" class="img-fluid">
</div>

<div class="col-md-7">
<h3><?=$product['name']?></h3>

<h4 class="text-danger">฿<?=$product['price']?></h4>

<?php if($product['old_price']>0){ ?>
<p>
<span class="badge bg-danger">SALE</span>
<small class="text-muted text-decoration-line-through">
฿<?=$product['old_price']?>
</small>
</p>
<?php } ?>

<p><?=$product['description']?></p>

<a href="cart.php?add=<?=$product['id']?>" 
class="btn btn-success btn-lg">
เพิ่มลงตะกร้า
</a>
</div>
</div>

<hr>

<h5>🔥 สินค้าที่คุณอาจสนใจ</h5>
<div class="row">
<?php
$recommend = $conn->query("SELECT * FROM products WHERE is_trending=1 AND id!=$id LIMIT 4");
while($r = $recommend->fetch_assoc()){
?>
<div class="col-md-3">
<div class="card shadow-sm">
<img src="images/<?=$r['image']?>" height="200">
<div class="card-body">
<h6><?=$r['name']?></h6>
<p class="text-danger">฿<?=$r['price']?></p>
<a href="product.php?id=<?=$r['id']?>" 
class="btn btn-outline-primary w-100">
ดูสินค้า
</a>
</div>
</div>
</div>
<?php } ?>
</div>

</div>
</body>
</html>
