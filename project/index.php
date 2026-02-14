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
background:#f4f5f7;
font-family:'Segoe UI',sans-serif;
}

/* NAVBAR */
.navbar{
background:linear-gradient(135deg,#111,#2a0845);
padding:15px 0;
}

/* HERO */
.hero{
background:linear-gradient(135deg,#111,#6f42c1);
color:white;
padding:80px 0;
text-align:center;
}

/* PRODUCT */
.product-card{
border:none;
border-radius:15px;
transition:.3s;
overflow:hidden;
}

.product-card:hover{
transform:translateY(-10px);
box-shadow:0 15px 35px rgba(0,0,0,.2);
}

/* FILTER BUTTON ACTIVE */
.filter-btn.active{
background:#6f42c1;
color:white;
}

/* MODAL */
.custom-modal{
border-radius:15px;
overflow:hidden;
border:none;
box-shadow:0 20px 50px rgba(0,0,0,.4);
}

.custom-header{
background:linear-gradient(135deg,#2a0845,#6441a5);
color:white;
border:none;
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
box-shadow:0 10px 25px rgba(111,66,193,.5);
color:white;
}
</style>
</head>
<body>

<!-- NAVBAR -->
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
<button class="btn btn-light dropdown-toggle px-4"
data-bs-toggle="dropdown">
👤 <?= $_SESSION['user']; ?>
</button>
<ul class="dropdown-menu dropdown-menu-end">
<li><a class="dropdown-item" href="logout.php">ออกจากระบบ</a></li>
</ul>
</div>

<?php } else { ?>

<div class="d-flex gap-2">
<a href="login.php" class="btn btn-outline-light px-4 fw-semibold">
เข้าสู่ระบบ
</a>
<a href="register.php" class="btn btn-warning px-4 fw-semibold shadow">
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

<!-- FILTER -->
<div class="container my-4">
<div class="d-flex justify-content-center flex-wrap gap-3">

<button class="btn btn-outline-dark filter-btn active" data-category="all">
ทั้งหมด
</button>

<button class="btn btn-outline-primary filter-btn" data-category="kpop">
ศิลปินเกาหลี
</button>

<button class="btn btn-outline-success filter-btn" data-category="thai">
ศิลปินไทย
</button>

<button class="btn btn-outline-danger filter-btn" data-category="manhwa">
มันฮวาเกาหลี
</button>

<button class="btn btn-outline-warning filter-btn" data-category="manhua">
มานฮัวจีน
</button>

</div>
</div>

<!-- PRODUCTS -->
<div class="container">
<div class="row" id="productList"></div>
</div>

<!-- LOGIN REQUIRED MODAL -->
<div class="modal fade" id="loginRequiredModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content custom-modal">

<div class="modal-header custom-header">
<h5 class="modal-title">
<i class="bi bi-stars me-2"></i> Goods Secret Exclusive
</h5>
<button class="btn-close btn-close-white"
data-bs-dismiss="modal"></button>
</div>

<div class="modal-body text-center py-4">

<div class="modal-icon mb-3">
<i class="bi bi-person-lock"></i>
</div>

<h5 class="fw-bold mb-3">
กรุณาเข้าสู่ระบบก่อนทำการสั่งซื้อ
</h5>

<div class="d-flex justify-content-center gap-3 mt-4">
<a href="login.php" class="btn btn-gradient px-4">
เข้าสู่ระบบ
</a>

<a href="register.php" class="btn btn-warning px-4">
สมัครสมาชิก
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
{ id:1,name:"BTS Lightstick",price:2500,category:"kpop",img:"https://via.placeholder.com/300"},
{ id:2,name:"BLACKPINK Album",price:1800,category:"kpop",img:"https://via.placeholder.com/300"},
{ id:3,name:"Billkin Photobook",price:1200,category:"thai",img:"https://via.placeholder.com/300"},
{ id:4,name:"4EVE Hoodie",price:1500,category:"thai",img:"https://via.placeholder.com/300"},
{ id:5,name:"Solo Leveling Artbook",price:900,category:"manhwa",img:"https://via.placeholder.com/300"},
{ id:6,name:"Heaven Official Blessing Set",price:1100,category:"manhua",img:"https://via.placeholder.com/300"}
];

let cart=[];

function renderProducts(filter="all"){
const list=document.getElementById("productList");
list.innerHTML="";
let search=document.getElementById("searchInput").value.toLowerCase();

products
.filter(p=>filter==="all"||p.category===filter)
.filter(p=>p.name.toLowerCase().includes(search))
.forEach(p=>{
list.innerHTML+=`
<div class="col-md-4 col-lg-3 mb-4">
<div class="card product-card">
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
let modal=new bootstrap.Modal(document.getElementById('loginRequiredModal'));
modal.show();
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

document.querySelectorAll(".filter-btn").forEach(btn=>{
btn.addEventListener("click",function(){
document.querySelectorAll(".filter-btn").forEach(b=>b.classList.remove("active"));
this.classList.add("active");
renderProducts(this.dataset.category);
});
});

document.getElementById("searchInput")
.addEventListener("input",()=>renderProducts());

renderProducts();
</script>

</body>
</html>
