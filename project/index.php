<?php
session_start();
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
body { background:#f8f9fa; font-family:'Segoe UI',sans-serif; }

.hero {
    background:linear-gradient(135deg,#111,#6f42c1);
    color:white;
    padding:80px 0;
    text-align:center;
}

.product-card:hover {
    transform:translateY(-8px);
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
}

/* ปุ่มกรอบแนวนอน */
.auth-box {
    border:1px solid #ffffff55;
    padding:6px 12px;
    border-radius:8px;
    display:flex;
    gap:8px;
}
</style>
</head>
<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
<div class="container-fluid px-4">

<a class="navbar-brand fw-bold" href="index.php">
🎵 Goods Secret Store
</a>

<div class="d-flex align-items-center w-100 justify-content-end">

<input id="searchInput" class="form-control me-3" style="max-width:350px;" placeholder="ค้นหาสินค้า...">

<button class="btn btn-warning position-relative me-3"
        data-bs-toggle="offcanvas"
        data-bs-target="#cartCanvas">
<i class="bi bi-cart"></i>
<span id="cartCount"
class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
</button>

<?php if(isset($_SESSION['user'])){ ?>

<div class="dropdown">
<button class="btn btn-outline-light dropdown-toggle"
        data-bs-toggle="dropdown">
👤 <?= $_SESSION['user']; ?>
</button>

<ul class="dropdown-menu dropdown-menu-end">
<li><a class="dropdown-item" href="profile.php">โปรไฟล์</a></li>
<li><a class="dropdown-item" href="orders.php">คำสั่งซื้อ</a></li>
<li><hr class="dropdown-divider"></li>
<li><a class="dropdown-item" href="logout.php">ออกจากระบบ</a></li>
</ul>
</div>

<?php } else { ?>

<div class="auth-box">
<a href="login.php" class="btn btn-outline-light btn-sm">
เข้าสู่ระบบ
</a>

<a href="register.php" class="btn btn-warning btn-sm">
สมัครสมาชิก
</a>
</div>

<?php } ?>

</div>
</div>
</nav>
<!-- ================= END NAVBAR ================= -->


<!-- Hero -->
<section class="hero">
<h1 class="display-5 fw-bold">Goods Secret Store</h1>
<p>ศิลปินเกาหลี | ศิลปินไทย | มันฮวา | มานฮัว</p>
</section>

<!-- Products -->
<div class="container mt-5">
<div class="row" id="productList"></div>
</div>

<!-- ================= LOGIN REQUIRED MODAL ================= -->
<div class="modal fade" id="loginRequiredModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">

<div class="modal-header bg-danger text-white">
<h5 class="modal-title">
<i class="bi bi-exclamation-triangle"></i>
กรุณาเข้าสู่ระบบ
</h5>
<button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body text-center">
<p>กรุณาเข้าสู่ระบบเพื่อทำการสั่งซื้อสินค้า</p>
<a href="login.php" class="btn btn-primary me-2">
เข้าสู่ระบบ
</a>
<a href="register.php" class="btn btn-warning">
สมัครสมาชิก
</a>
</div>

</div>
</div>
</div>
<!-- ================= END MODAL ================= -->


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const isLoggedIn = <?= isset($_SESSION['user']) ? 'true' : 'false'; ?>;

const products = [
{ id:1, name:"BTS Lightstick", price:2500 },
{ id:2, name:"BLACKPINK Album", price:1800 },
{ id:3, name:"Solo Leveling Artbook", price:900 },
{ id:4, name:"4EVE Hoodie", price:1500 }
];

let cart = JSON.parse(localStorage.getItem("cart")) || [];

function renderProducts(){
let list = document.getElementById("productList");
list.innerHTML="";

products.forEach(p=>{
list.innerHTML += `
<div class="col-md-3 mb-4">
<div class="card product-card">
<div class="card-body text-center">
<h6>${p.name}</h6>
<p class="fw-bold">${p.price} บาท</p>
<button class="btn btn-primary w-100"
onclick="addToCart(${p.id})">
เพิ่มลงตะกร้า
</button>
</div>
</div>
</div>`;
});
}

function addToCart(id){

if(!isLoggedIn){
    let modal = new bootstrap.Modal(document.getElementById('loginRequiredModal'));
    modal.show();
    return;
}

let item = cart.find(p=>p.id===id);
if(item){ item.qty++; }
else{
let product = products.find(p=>p.id===id);
cart.push({...product, qty:1});
}

localStorage.setItem("cart",JSON.stringify(cart));
updateCartCount();
}

function updateCartCount(){
document.getElementById("cartCount").innerText =
cart.reduce((a,b)=>a+(b.qty||1),0);
}

renderProducts();
updateCartCount();
</script>

</body>
</html>
