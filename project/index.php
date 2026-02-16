<?php include "connectdb.php"; ?>
<!DOCTYPE html>
<html>
<head>
<title>ComicShop</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-4">

<h4 class="mb-3">🔥 หนังสือแนะนำ</h4>
<div class="row">
<?php
$trending = $conn->query("SELECT * FROM products WHERE is_trending=1 ORDER BY created_at DESC LIMIT 8");
while($p = $trending->fetch_assoc()){
?>
<div class="col-md-3 mb-4">
<div class="card shadow-sm h-100">
<img src="images/<?=$p['image']?>" height="260" style="object-fit:cover;">
<div class="card-body">
<h6><?=$p['name']?></h6>
<p class="text-danger fw-bold">฿<?=$p['price']?></p>
<?php if($p['old_price']>0){ ?>
<small class="text-muted text-decoration-line-through">฿<?=$p['old_price']?></small>
<?php } ?>
<a href="product.php?id=<?=$p['id']?>" class="btn btn-primary w-100 mt-2">
ดูรายละเอียด
</a>
</div>
</div>
</div>
<?php } ?>
</div>

<hr>

<h4 class="mb-3">🔥 กำลังมาแรง</h4>
<div class="row">
<?php
$hot = $conn->query("SELECT * FROM products WHERE is_hot=1 LIMIT 8");
while($h = $hot->fetch_assoc()){
?>
<div class="col-md-3 mb-4">
<div class="card shadow-sm h-100">
<img src="images/<?=$h['image']?>" height="260" style="object-fit:cover;">
<div class="card-body">
<h6><?=$h['name']?></h6>
<p class="text-danger fw-bold">฿<?=$h['price']?></p>
<a href="product.php?id=<?=$h['id']?>" class="btn btn-outline-primary w-100 mt-2">
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
