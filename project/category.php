<?php
include "connectdb.php";
$slug = $_GET['slug'] ?? 'all';

if($slug == "all"){
    $cat_name = "สินค้าทั้งหมด";
    $products = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
}else{
    $cat = $conn->query("SELECT * FROM categories WHERE slug='$slug'")->fetch_assoc();
    if(!$cat){
        header("Location:index.php");
        exit();
    }
    $cat_name = $cat['name'];
    $products = $conn->query("SELECT * FROM products WHERE category_id=".$cat['id']);
}
?>

<!DOCTYPE html>
<html>
<head>
<title><?=$cat_name?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-4">
<h4><?=$cat_name?></h4>

<div class="row">
<?php while($p = $products->fetch_assoc()){ ?>
<div class="col-md-3 mb-4">
<div class="card shadow-sm h-100">
<img src="images/<?=$p['image']?>" height="260" style="object-fit:cover;">
<div class="card-body">
<h6><?=$p['name']?></h6>
<p class="text-danger fw-bold">฿<?=$p['price']?></p>

<?php if($p['old_price']>0){ ?>
<span class="badge bg-danger">ลดราคา</span>
<small class="text-muted text-decoration-line-through">
฿<?=$p['old_price']?>
</small>
<?php } ?>

<a href="product.php?id=<?=$p['id']?>" 
class="btn btn-primary w-100 mt-2">
ดูรายละเอียด
</a>
</div>
</div>
</div>
<?php } ?>
</div>

</div>
</body>
</html>
