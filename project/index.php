<?php
session_start();
include "connectdb.php";

$category_slug = $_GET['category'] ?? "";
$search = $_GET['search'] ?? "";

$sql = "
SELECT products.*, categories.name as category_name
FROM products
LEFT JOIN categories ON products.category_id = categories.id
WHERE 1
";

if($category_slug && $category_slug != "all"){
    $sql .= " AND categories.slug='".$conn->real_escape_string($category_slug)."'";
}

if($search){
    $sql .= " AND products.name LIKE '%".$conn->real_escape_string($search)."%'";
}

$products = $conn->query($sql);
$categories = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>Goods Secret Store</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
background:linear-gradient(135deg,#120018,#2a0845,#3d1e6d);
color:white;
}
.modern-btn{
background:linear-gradient(135deg,#E0BBE4,#bb86fc);
border:none;
border-radius:25px;
padding:6px 16px;
}
.search-wrapper{
position:relative;
}
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
#searchResult div{
padding:8px;
cursor:pointer;
}
#searchResult div:hover{
background:#eee;
}
.product-card{
background:rgba(255,255,255,0.05);
border:1px solid rgba(255,255,255,0.1);
}
</style>
</head>
<body>

<nav class="navbar navbar-dark p-3">
<div class="container">
<a class="navbar-brand" href="index.php">🎵 Goods Secret Store</a>

<div class="search-wrapper">
<input type="text" id="liveSearch" class="form-control" placeholder="ค้นหาสินค้า...">
<div id="searchResult"></div>
</div>

<div>
<?php if(isset($_SESSION['user_id'])){ ?>
<a href="cart.php" class="modern-btn">🛒</a>
<a href="logout.php" class="modern-btn">ออกจากระบบ</a>
<?php } else { ?>
<a href="login.php" class="modern-btn">เข้าสู่ระบบ</a>
<a href="register.php" class="modern-btn">สมัครสมาชิก</a>
<?php } ?>
</div>

</div>
</nav>

<div class="container mt-4">
<div class="row">

<?php while($p = $products->fetch_assoc()){ ?>
<div class="col-md-3 mb-4">
<div class="card product-card p-3 text-center">

<img src="images/<?= $p['image']; ?>" class="img-fluid mb-2">

<h6><?= $p['name']; ?></h6>
<p><?= number_format($p['price']); ?> บาท</p>

<a href="product.php?id=<?= $p['id']; ?>" 
class="btn btn-light btn-sm mb-2">
รายละเอียดสินค้า
</a>

<button class="btn btn-warning btn-sm addToCart"
data-id="<?= $p['id']; ?>">
เพิ่มลงตะกร้า
</button>

</div>
</div>
<?php } ?>

</div>
</div>

<!-- LOGIN REQUIRED MODAL -->
<div class="modal fade" id="loginModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5>กรุณาเข้าสู่ระบบก่อน</h5>
</div>
<div class="modal-body text-center">
<p>คุณต้องเข้าสู่ระบบก่อนเพิ่มสินค้าลงตะกร้า</p>
<a href="login.php" class="btn btn-primary">เข้าสู่ระบบ</a>
<a href="register.php" class="btn btn-secondary">สมัครสมาชิก</a>
</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// LIVE SEARCH
document.getElementById("liveSearch").addEventListener("keyup", function(){
let keyword = this.value;
if(keyword.length >= 1){
fetch("search.php?keyword="+keyword)
.then(res=>res.text())
.then(data=>{
document.getElementById("searchResult").style.display="block";
document.getElementById("searchResult").innerHTML=data;
});
}else{
document.getElementById("searchResult").style.display="none";
}
});

// ADD TO CART
document.querySelectorAll(".addToCart").forEach(btn=>{
btn.addEventListener("click",function(){
<?php if(!isset($_SESSION['user_id'])){ ?>
var myModal = new bootstrap.Modal(document.getElementById('loginModal'));
myModal.show();
<?php } else { ?>
window.location.href="add_to_cart.php?id="+this.dataset.id;
<?php } ?>
});
});
</script>

</body>
</html>
