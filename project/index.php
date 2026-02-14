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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>

body{
background:#f5f6fa;
font-family:'Segoe UI',sans-serif;
}

/* ===== NAVBAR ===== */
.navbar{
background:linear-gradient(135deg,#111,#2a0845);
padding:15px 0;
}

.navbar .btn{
border-radius:8px;
}

/* ปุ่มแนวนอน */
.auth-buttons{
display:flex;
gap:10px;
}

/* ===== HERO ===== */
.hero{
background:linear-gradient(135deg,#111,#6f42c1);
color:white;
padding:80px 0;
text-align:center;
}

/* ===== PRODUCT CARD ===== */
.product-card{
transition:.3s;
border:none;
border-radius:15px;
overflow:hidden;
}

.product-card:hover{
transform:translateY(-10px);
box-shadow:0 15px 35px rgba(0,0,0,0.2);
}

.badge-category{
position:absolute;
top:10px;
right:10px;
}

/* ===== PREMIUM MODAL ===== */
.custom-modal{
border-radius:15px;
overflow:hidden;
border:none;
box-shadow:0 20px 50px rgba(0,0,0,0.4);
}

.custom-header{
background:linear-gradient(135deg,#2a0845,#6441a5);
color:white;
border-bottom:none;
}

.modal-icon i{
font-size:60px;
background:linear-gradient(135deg,#6f42c1,#d63384);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}

.btn-gradient{
background:linear-gradient(135deg,#6f42c1,#d63384);
color:white;
border:none;
transition:.3s;
}

.btn-gradient:hover{
transform:translateY(-3px);
box-shadow:0 10px 25px rgba(111,66,193,0.5);
color:white;
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

<div class="ms-auto d-flex align-items-center">

<input id="searchInput" class="form-control me-3" placeholder="ค้นหาสินค้า...">

<button class="btn btn-warning position-relative me-3">
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
<li><a class="dropdown-item" href="logout.php">ออกจากระบบ</a></li>
</ul>
</div>

<?php } else { ?>

<div class="auth-buttons">
<a href="login.php" class="btn btn-outline-light px-4">
เข้าสู่ระบบ
</a>

<a href="register.php" class="btn btn-warning px-4">
สมัครสมาชิก
</a>
</div>

<?php } ?>

</div>
</div>
</nav>

<!-- HERO -->
<section class="hero">
<h1 class="display-5 fw-bold">Goods Secret Store</h1>
<p>ศิลปินเกาหลี | ศิลปินไทย | มันฮวา | มานฮัว</p>
</section>

<!-- PRODUCTS -->
<div class="container my-5">
<div class="row" id="productList"></div>
</div>

<!-- ===== LOGIN REQUIRED MODAL ===== -->
<div class="modal fade" id="loginRequiredModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content custom-modal">

<div class="modal-header custom-header">
<h5 class="modal-title">
<i class="bi bi-stars me-2"></i>
Goods Secret Exclusive
</h5>
<button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body text-center py-4">

<div class="modal-icon mb-3">
<i class="bi bi-person-lock"></i>
</div>

<h5 class="fw-bold mb-3">
กรุณาเข้าสู่ระบบก่อนทำการสั่งซื้อ
</h5>

<p class="text-muted">
สมัครสมาชิกเพื่อปลดล็อกสินค้า Secret ของคุณ ✨
</p>

<div class="d-flex justify-content-center gap-3 mt-4">

<a href="login.php" class="btn btn-gradient px-4">
<i class="bi bi-box-arrow-in-right"></i> เข้าสู่ระบบ
</a>

<a href="register.php" class="btn btn-warning px-4 shadow-sm">
<i class="bi bi-person-plus"></i> สมัครสมาชิก
</a>

</div>

</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

const isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;

const products = [
{ id:1, name:"BTS Lightstick", price:2500, category:"kpop", img:"https://via.placeholder.com/300" },
{ id:2, name:"BLACKPINK Album", price:1800, category:"kpop", img:"https://via.placeholder.com/300" },
{ id:3, name:"Billkin Photobook", price:1200, category:"thai", img:"https://via.placeholder.com/300" },
{ id:4, name:"4EVE Hoodie", price:1500, category:"thai", img:"https://via.placeholder.com/300" },
];

let cart=[];

function renderProducts(){
const list=document.getElementById("productList");
list.innerHTML="";

products.forEach(p=>{
list.innerHTML+=`
<div class="col-md-3 mb-4">
<div class="card product-card position-relative">
<img src="${p.img}" class="card-img-top">
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
let modalElement=document.getElementById('loginRequiredModal');
let modal=new bootstrap.Modal(modalElement);
modal.show();
modalElement.classList.add("animate__animated","animate__zoomIn");
return;
}

let item=cart.find(p=>p.id===id);
if(item){item.qty++;}
else{
let product=products.find(p=>p.id===id);
cart.push({...product,qty:1});
}

updateCart();
}

function updateCart(){
document.getElementById("cartCount").innerText=
cart.reduce((a,b)=>a+(b.qty||1),0);
}

renderProducts();
</script>

</body>
</html>
