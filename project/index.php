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

/* ================= GLOBAL THEME ================= */
body{
background:
radial-gradient(circle at 20% 30%, #4b2c63 0%, transparent 40%),
radial-gradient(circle at 80% 70%, #6a1b9a 0%, transparent 40%),
linear-gradient(135deg,#120018,#2a0845,#3d1e6d);
background-attachment: fixed;
color:#fff;
font-family:'Segoe UI',sans-serif;
}

/* ================= NAVBAR ================= */
.navbar{
background:linear-gradient(90deg,#1a0028,#3d1e6d);
padding:15px 0;
}

.navbar .form-control{
background:#2a0845;
border:1px solid #6f42c1;
color:#fff;
}

.navbar .form-control::placeholder{
color:#ccc;
}

/* ปุ่มหลักสีแบรนด์ */
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

/* ================= HERO ================= */
.hero{
background:
linear-gradient(rgba(0,0,0,.6),rgba(0,0,0,.6)),
linear-gradient(135deg,#2a0845,#6a1b9a,#9c27b0);
padding:100px 0;
text-align:center;
box-shadow:0 10px 40px rgba(0,0,0,.5);
}

.hero h1{
font-size:3rem;
font-weight:bold;
letter-spacing:2px;
text-shadow:0 0 15px #bb86fc;
}

.hero p{
color:#e1bee7;
}

/* ================= FILTER BUTTONS ================= */
.filter-btn{
border-radius:0;
font-weight:500;
}

.filter-btn.active{
background:#E0BBE4;
color:#2a0845;
border:none;
}

/* ================= PRODUCT CARD ================= */
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

.product-card h6{
color:#fff;
}

.product-card p{
color:#E0BBE4;
font-weight:bold;
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

<input id="searchInput" class="form-control me-2" placeholder="ค้นหาสินค้า...">

<button class="brand-btn position-relative">
<i class="bi bi-cart"></i>
<span id="cartCount"
class="position-absolute top-0 start-100 translate-middle badge bg-danger">0</span>
</button>

<?php if(isset($_SESSION['user'])){ ?>

<div class="dropdown">
<button class="brand-btn dropdown-toggle"
data-bs-toggle="dropdown">
<?= $_SESSION['user']; ?>
</button>
<ul class="dropdown-menu dropdown-menu-end">
<li><a class="dropdown-item" href="logout.php">ออกจากระบบ</a></li>
</ul>
</div>

<?php } else { ?>

<a href="login.php" class="brand-btn">
เข้าสู่ระบบ
</a>

<a href="register.php" class="brand-btn">
สมัครสมาชิก
</a>

<?php } ?>

</div>
</div>
</nav>

<!-- ================= HERO ================= -->
<section class="hero">
<h1>Goods Secret Store</h1>
<p>ศิลปินเกาหลี | ศิลปินไทย | มันฮวา | มานฮัว | มังงะ | การ์ตูนไทย</p>
</section>

<!-- ================= FILTER ================= -->
<div class="container my-5 text-center">
<div class="d-flex flex-wrap justify-content-center gap-3">

<button class="btn btn-outline-light filter-btn active" data-category="all">ทั้งหมด</button>
<button class="btn btn-outline-light filter-btn" data-category="kpop">ศิลปินเกาหลี</button>
<button class="btn btn-outline-light filter-btn" data-category="thai">ศิลปินไทย</button>
<button class="btn btn-outline-light filter-btn" data-category="manhwa">มันฮวาเกาหลี</button>
<button class="btn btn-outline-light filter-btn" data-category="manhua">มานฮัวจีน</button>
<button class="btn btn-outline-light filter-btn" data-category="manga">Manga – มังงะ (ญี่ปุ่น)</button>
<button class="btn btn-outline-light filter-btn" data-category="thaicomic">Thai Comic – การ์ตูนไทย</button>

</div>
</div>

<!-- ================= PRODUCTS ================= -->
<div class="container">
<div class="row" id="productList"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

const products = [
{ id:1,name:"BTS Lightstick",price:2500,category:"kpop",img:"https://via.placeholder.com/300"},
{ id:2,name:"Solo Leveling",price:900,category:"manhwa",img:"https://via.placeholder.com/300"},
{ id:3,name:"Heaven Official",price:1100,category:"manhua",img:"https://via.placeholder.com/300"},
{ id:4,name:"One Piece Vol.1",price:350,category:"manga",img:"https://via.placeholder.com/300"},
{ id:5,name:"ขายหัวเราะ",price:120,category:"thaicomic",img:"https://via.placeholder.com/300"},
];

function renderProducts(filter="all"){
const list=document.getElementById("productList");
list.innerHTML="";

products
.filter(p=>filter==="all"||p.category===filter)
.forEach(p=>{
list.innerHTML+=`
<div class="col-md-4 col-lg-3 mb-4">
<div class="card product-card p-3 text-center">
<img src="${p.img}" class="img-fluid mb-3">
<h6>${p.name}</h6>
<p>${p.price} บาท</p>
<button class="brand-btn w-100">
เพิ่มลงตะกร้า
</button>
</div>
</div>`;
});
}

document.querySelectorAll(".filter-btn").forEach(btn=>{
btn.addEventListener("click",function(){
document.querySelectorAll(".filter-btn").forEach(b=>b.classList.remove("active"));
this.classList.add("active");
renderProducts(this.dataset.category);
});
});

renderProducts();

</script>

</body>
</html>
