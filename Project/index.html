<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title> Goods Secret Store </title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background: #f8f9fa;
    font-family: 'Segoe UI', sans-serif;
}
.hero {
    background: linear-gradient(135deg,#111,#6f42c1);
    color:white;
    padding:80px 0;
    text-align:center;
}
.product-card {
    transition: 0.3s;
}
.product-card:hover {
    transform: translateY(-10px);
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

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
<div class="container">
<a class="navbar-brand fw-bold" href="#">🎵 Goods Secret Store</a>

<div class="ms-auto d-flex">
<input id="searchInput" class="form-control me-3" placeholder="ค้นหาสินค้า...">
<button class="btn btn-warning position-relative" data-bs-toggle="offcanvas" data-bs-target="#cartCanvas">
<i class="bi bi-cart"></i>
<span id="cartCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
</button>
</div>
</div>
</nav>

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
<button class="btn btn-success w-100 mt-3" onclick="checkout()">ชำระเงิน</button>
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
<div class="modal-body">
<p>ขอบคุณสำหรับการสั่งซื้อ 🎉</p>
</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const products = [
{ id:1, name:"BTS Official Lightstick", price:2500, category:"kpop", img:"https://via.placeholder.com/300" },
{ id:2, name:"BLACKPINK Album Set", price:1800, category:"kpop", img:"https://via.placeholder.com/300" },
{ id:3, name:"Billkin Photobook", price:1200, category:"thai", img:"https://via.placeholder.com/300" },
{ id:4, name:"4EVE Official Hoodie", price:1500, category:"thai", img:"https://via.placeholder.com/300" },
{ id:5, name:"Solo Leveling Artbook", price:900, category:"manhwa", img:"https://via.placeholder.com/300" },
{ id:6, name:"Tower of God Poster", price:500, category:"manhwa", img:"https://via.placeholder.com/300" },
{ id:7, name:"Heaven Official's Blessing Set", price:1100, category:"manhua", img:"https://via.placeholder.com/300" },
{ id:8, name:"The King's Avatar Figure", price:2200, category:"manhua", img:"https://via.placeholder.com/300" },
];

let cart = JSON.parse(localStorage.getItem("cart")) || [];

function renderProducts(filter="all"){
const list = document.getElementById("productList");
list.innerHTML="";
let search = document.getElementById("searchInput").value.toLowerCase();

products
.filter(p => (filter==="all"||p.category===filter))
.filter(p => p.name.toLowerCase().includes(search))
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
<i class="bi bi-cart-plus"></i> เพิ่มลงตะกร้า
</button>
</div>
</div>
</div>`;
});
}

function addToCart(id){
let item = cart.find(p=>p.id===id);
if(item){ item.qty++; }
else{
let product = products.find(p=>p.id===id);
cart.push({...product, qty:1});
}
updateCart();
}

function updateCart(){
localStorage.setItem("cart",JSON.stringify(cart));
document.getElementById("cartCount").innerText = cart.reduce((a,b)=>a+b.qty,0);

let list = document.getElementById("cartItems");
list.innerHTML="";
let total=0;

cart.forEach(item=>{
total += item.price * item.qty;
list.innerHTML+=`
<li class="list-group-item d-flex justify-content-between align-items-center">
<div>
${item.name}<br>
<small>${item.price} x ${item.qty}</small>
</div>
<div>
<button class="btn btn-sm btn-outline-secondary" onclick="changeQty(${item.id},-1)">-</button>
<button class="btn btn-sm btn-outline-secondary" onclick="changeQty(${item.id},1)">+</button>
<button class="btn btn-sm btn-danger" onclick="removeItem(${item.id})"><i class="bi bi-trash"></i></button>
</div>
</li>`;
});

document.getElementById("totalPrice").innerText=total;
}

function changeQty(id,delta){
let item = cart.find(p=>p.id===id);
item.qty += delta;
if(item.qty<=0) cart = cart.filter(p=>p.id!==id);
updateCart();
}

function removeItem(id){
cart = cart.filter(p=>p.id!==id);
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
