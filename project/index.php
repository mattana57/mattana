<?php
session_start();
include "connectdb.php";

/* =============================
   🔎 LIVE SEARCH AJAX MODE
============================= */
if(isset($_GET['ajax']) && $_GET['ajax']=="search"){
    $keyword = $_GET['keyword'] ?? '';
    if(strlen($keyword)>=1){
        $stmt=$conn->prepare("SELECT id,name FROM products 
                              WHERE name LIKE CONCAT('%',?,'%') LIMIT 5");
        $stmt->bind_param("s",$keyword);
        $stmt->execute();
        $result=$stmt->get_result();
        while($row=$result->fetch_assoc()){
            echo "<div onclick=\"window.location='?detail=".$row['id']."'\">".$row['name']."</div>";
        }
    }
    exit();
}

/* =============================
   🔎 FILTER CATEGORY
============================= */
$category_slug = $_GET['category'] ?? 'all';

$sql="SELECT p.*,c.slug FROM products p 
LEFT JOIN categories c ON p.category_id=c.id";

if($category_slug!='all'){
    $sql.=" WHERE c.slug='".$conn->real_escape_string($category_slug)."'";
}

$products=$conn->query($sql);
$categories=$conn->query("SELECT * FROM categories");

/* =============================
   📄 PRODUCT DETAIL
============================= */
if(isset($_GET['detail'])){
$id=intval($_GET['detail']);
$product=$conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<title><?= $product['name'] ?></title>
</head>
<body style="background:#2a0845;color:white">

<div class="container py-5 text-center">
<a href="index.php" class="btn btn-light mb-3">← กลับหน้าหลัก</a>
<img src="images/<?= $product['image'] ?>" width="300">
<h2><?= $product['name'] ?></h2>
<h4><?= number_format($product['price']) ?> บาท</h4>
<p><?= $product['description'] ?></p>

<?php if(isset($_SESSION['user_id'])){ ?>
<a href="add_to_cart.php?id=<?= $product['id'] ?>" class="btn btn-warning">
เพิ่มลงตะกร้า
</a>
<?php } else { ?>
<button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#loginModal">
เพิ่มลงตะกร้า
</button>
<?php } ?>
</div>

<div class="modal fade" id="loginModal">
<div class="modal-dialog">
<div class="modal-content text-center p-4">
<p>กรุณาเข้าสู่ระบบก่อนสั่งซื้อ</p>
<a href="login.php" class="btn btn-primary">เข้าสู่ระบบ</a>
<a href="register.php" class="btn btn-secondary">สมัครสมาชิก</a>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php exit(); } ?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>Goods Secret Store</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
background:linear-gradient(135deg,#120018,#2a0845,#3d1e6d);
color:white;
}
.category-btn{
background:linear-gradient(135deg,#E0BBE4,#bb86fc);
border:none;
border-radius:25px;
padding:6px 16px;
margin:5px;
color:black;
}
.search-wrapper{position:relative;}
#searchResult{
position:absolute;
top:45px;
width:100%;
background:white;
color:black;
border-radius:10px;
display:none;
z-index:999;
}
#searchResult div{padding:8px;cursor:pointer;}
#searchResult div:hover{background:#eee;}
.product-card{
background:rgba(255,255,255,0.05);
border:1px solid rgba(255,255,255,0.1);
}
</style>
</head>

<body>

<div class="container py-3">

<div class="d-flex justify-content-between align-items-center">
<h4>🎵 Goods Secret Store</h4>

<div class="search-wrapper">
<input type="text" id="liveSearch" class="form-control" placeholder="ค้นหาสินค้า...">
<div id="searchResult"></div>
</div>

<div>
<?php if(isset($_SESSION['user_id'])){ ?>
<a href="logout.php" class="btn btn-light btn-sm">ออกจากระบบ</a>
<?php } else { ?>
<a href="login.php" class="btn btn-light btn-sm">เข้าสู่ระบบ</a>
<a href="register.php" class="btn btn-light btn-sm">สมัครสมาชิก</a>
<?php } ?>
</div>
</div>

<hr>

<!-- CATEGORY BUTTONS -->
<div class="text-center">
<?php while($c=$categories->fetch_assoc()){ ?>
<a href="?category=<?= $c['slug'] ?>" class="category-btn">
<?= $c['name'] ?>
</a>
<?php } ?>
</div>

<hr>

<div class="row mt-4">
<?php while($p=$products->fetch_assoc()){ ?>
<div class="col-md-3 mb-4">
<div class="card product-card p-3 text-center">

<img src="images/<?= $p['image'] ?>" class="img-fluid mb-2">

<h6><?= $p['name'] ?></h6>
<p><?= number_format($p['price']) ?> บาท</p>

<a href="?detail=<?= $p['id'] ?>" class="btn btn-light btn-sm mb-2">
รายละเอียดสินค้า
</a>

<?php if(isset($_SESSION['user_id'])){ ?>
<a href="add_to_cart.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">
เพิ่มลงตะกร้า
</a>
<?php } else { ?>
<button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#loginModal">
เพิ่มลงตะกร้า
</button>
<?php } ?>

</div>
</div>
<?php } ?>
</div>

</div>

<!-- LOGIN MODAL -->
<div class="modal fade" id="loginModal">
<div class="modal-dialog">
<div class="modal-content text-center p-4">
<p>กรุณาเข้าสู่ระบบก่อนเพิ่มสินค้า</p>
<a href="login.php" class="btn btn-primary">เข้าสู่ระบบ</a>
<a href="register.php" class="btn btn-secondary">สมัครสมาชิก</a>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById("liveSearch").addEventListener("keyup",function(){
let keyword=this.value;
if(keyword.length>=1){
fetch("index.php?ajax=search&keyword="+keyword)
.then(res=>res.text())
.then(data=>{
document.getElementById("searchResult").style.display="block";
document.getElementById("searchResult").innerHTML=data;
});
}else{
document.getElementById("searchResult").style.display="none";
}
});
</script>

</body>
</html>
