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
.product-card { transition:.3s; }
.product-card:hover {
    transform:translateY(-8px);
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
}
.badge-category {
    position:absolute;
    top:10px;
    right:10px;
}
</style>
</head>
<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
<div class="container">

<a class="navbar-brand fw-bold" href="index.php">
🎵 Goods Secret Store
</a>

<div class="ms-auto d-flex align-items-center">

<input id="searchInput" class="form-control me-3" placeholder="ค้นหาสินค้า...">

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

<li>
<a class="dropdown-item" href="profile.php">
<i class="bi bi-person"></i> โปรไฟล์
</a>
</li>

<li>
<a class="dropdown-item" href="orders.php">
<i class="bi bi-box"></i> คำสั่งซื้อ
</a>
</li>

<?php if($_SESSION['role']=="admin"){ ?>
<li>
<a class="dropdown-item text-danger"
   href="admin/dashboard.php">
<i class="bi bi-speedometer2"></i> Admin Panel
</a>
</li>
<?php } ?>

<li><hr class="dropdown-divider"></li>

<li>
<a class="dropdown-item" href="logout.php">
<i class="bi bi-box-arrow-right"></i> ออกจากระบบ
</a>
</li>

</ul>
</div>

<?php } else { ?>

<a href="login.php" class="btn btn-outline-light me-2">
เข้าสู่ระบบ
</a>

<a href="register.php" class="btn btn-warning">
สมัครสมาชิก
</a>

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

<!-- Filter -->
<div class="container my-4">
<div class="d-flex justify-content-center gap-3 flex-wrap">
<button class="btn btn-outline-dark filter-btn" data-category="all">ทั้งหมด</button>
<button class="btn btn-outline-primary filter-btn" data-category="kpop">ศิลปินเกาหลี</button>
<button class="btn btn-outline-success filter-btn" data-category="thai">ศิลปินไทย</button>
<button class="btn btn-outline-danger filter-btn" data-category="manhwa">มันฮวาเกาหลี</button>
<button class="btn btn-outline-warning filter-btn" data-category="manhua">มานฮัวจีน</button>
</div>
</div>

<!-- Products -->
<div class="container">
<div class="row" id="productList"></div>
</div>

<!-- Cart Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartCanvas">
<div class="offcanvas-header">
<h5>🛒 ตะกร้าสินค้า</h5>
<button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
</div>
<div class="offcanvas-body">

<ul class="list-group mb-3" id="cartItems"></ul>

<h5>รวม: <span id="totalPrice">0</span> บาท</h5>

<?php if(isset($_SESSION['user'])){ ?>
<button class="btn btn-success w-100 mt-3" onclick="checkout()">
ชำระเงิน
</button>
<?php } else { ?>
<a href="login.php" class="btn btn-primary w-100 mt-3">
เข้าสู่ระบบก่อนชำระเงิน
</a>
<?php } ?>

</div>
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5>ยืนยันการสั่งซื้อ</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body text-center">
<p>ขอบคุณสำหรับการสั่งซื้อ 🎉</p>
</div>
</div>
</div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const products = [
{ id:1, name:"BTS Lightstick", price:2500, category:"kpop", img:"https://via.placeholder.com/300" },
{ id:2, name:"BLACKPINK Album", price:1800, category:"kpop", img:"https://via.placeholder.com/300" },
{ id:3, name:"Billkin Photobook", price:1200, category:"thai", img:"https://via.placeholder.com/300" },
{ id:4, name:"4EVE Hoodie", price:1500, category:"thai", img:"https://via.placeholder.com/300" },
{ id:5, name:"Solo Leveling Artbook", price:900, category:"manhwa", img:"https://via.placeholder.com/300" },
{ id:6, name:"Tower of God Poster", price:500, category:"manhwa", img:"https://via.placeholder.com/300" },
{ id:7, name:"Heaven Official Blessing Set", price:1100, category:"manhua", img:"https://via.placeholder.com/300" },
{ id:8, name:"The King's Avatar Figure", price:2200, category:"manhua", img:"https://via.placeholder.com/300" },
];

let cart = JSON.parse(localStorage.getItem("cart")) || [];

function renderProducts(filter="all"){
const list=document.getElementById("productList");
list.innerHTML="";
let search=document.getElementById("searchInput").value.toLowerCase();

products
.filter(p=>filter==="all"||p.category===filter)
.filter(p=>p.name.toLowerCase().includes(search))
.forEach(p=>{
list.innerHTML+=`
<div class="col-md-3 mb-4">
<div class="card product-card position-relative">
<span class="badge bg-dark badge-category">${p.category}</span>
<img src="${p.img}" class="card-img-top">
<div class="card-body text-center">
<h6>${p.name}</h6>
<p class="fw-bold">${p.price} บาท</p>
<button class="btn btn-primary w-100" onclick="addToCart(${p.id})">
เพิ่มลงตะกร้า
</button>
</div>
</div>
</div>`;
});
}

function addToCart(id){
let item=cart.find(p=>p.id===id);
if(item){item.qty++;}
else{
let product=products.find(p=>p.id===id);
cart.push({...product,qty:1});
}
updateCart();
}

function updateCart(){
localStorage.setItem("cart",JSON.stringify(cart));
document.getElementById("cartCount").innerText=
cart.reduce((a,b)=>a+b.qty,0);

let list=document.getElementById("cartItems");
list.innerHTML="";
let total=0;

cart.forEach(item=>{
total+=item.price*item.qty;
list.innerHTML+=`
<li class="list-group-item d-flex justify-content-between">
<div>
${item.name}<br>
<small>${item.price} x ${item.qty}</small>
</div>
<div>
<button class="btn btn-sm btn-danger"
onclick="removeItem(${item.id})">ลบ</button>
</div>
</li>`;
});

document.getElementById("totalPrice").innerText=total;
}

function removeItem(id){
cart=cart.filter(p=>p.id!==id);
updateCart();
}

function checkout(){
cart=[];
updateCart();
new bootstrap.Modal(document.getElementById("checkoutModal")).show();
}

document.querySelectorAll(".filter-btn").forEach(btn=>{
btn.addEventListener("click",()=>{
renderProducts(btn.dataset.category);
});
});

document.getElementById("searchInput").addEventListener("input",()=>{
renderProducts();
});

renderProducts();
updateCart();
</script>

</body>
</html>
