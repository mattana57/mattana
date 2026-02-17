<?php
include "connectdb.php";
include "navbar.php"; 
$id = intval($_GET['id']);
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
$product_images = $conn->query("
    SELECT * FROM product_images 
    WHERE product_id = $id
");
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
<style>
.product-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.08);
}

.product-title {
    font-weight: 600;
    font-size: 28px;
}

.product-price {
    color: #e11d48;
    font-size: 26px;
    font-weight: 700;
}

.btn-cart {
    background: linear-gradient(135deg,#7c3aed,#a855f7);
    border: none;
    border-radius: 12px;
    padding: 12px 25px;
    font-weight: 500;
    color: #fff;
    transition: 0.3s;
}

.btn-cart:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(124,58,237,0.4);
}

</style>

</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">

<div class="row">
<div class="col-md-5">
<div class="row">
    <div class="col-md-12 text-center">
        <!-- รูปหลัก -->
        <img id="mainImage"
             src="images/<?= $product['image']; ?>"
             class="img-fluid mb-3 rounded shadow">
    </div>

    <!-- รูปย่อย -->
    <div class="col-12 d-flex gap-2 justify-content-center flex-wrap">
        <?php while($img = $product_images->fetch_assoc()){ ?>
            <img src="images/<?= $img['image']; ?>"
                 class="img-thumbnail"
                 style="width:80px; cursor:pointer;"
                 onclick="changeImage(this.src)">
        <?php } ?>
    </div>
</div>
</div>

<div class="col-md-7">
    <div class="product-card">

        <h2 class="product-title"><?=$product['name']?></h2>

        <div class="product-price">
            ฿<?=number_format($product['price'],2)?>
        </div>

        <?php if($product['old_price']>0){ ?>
            <div class="mt-2">
                <span class="badge bg-danger">SALE</span>
                <small class="text-muted text-decoration-line-through ms-2">
                    ฿<?=number_format($product['old_price'],2)?>
                </small>
            </div>
        <?php } ?>

        <p class="mt-3"><?=$product['description']?></p>

        <a href="cart.php?add=<?=$product['id']?>" 
        class="btn btn-cart mt-3">
            เพิ่มลงตะกร้า 🛒
        </a>

        <div class="mt-3 text-muted small">
            ✔ สินค้าพร้อมส่ง <br>
            ✔ จัดส่งภายใน 1-2 วัน <br>
            ✔ รับประกันสินค้า
        </div>

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
<script>
function changeImage(src){
    document.getElementById("mainImage").src = src;
}
</script>
</body>
</html>
