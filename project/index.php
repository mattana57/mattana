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
body { background:#f8f9fa; }
.product-card:hover {
    transform:translateY(-6px);
    box-shadow:0 10px 20px rgba(0,0,0,0.15);
}
</style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">
<div class="row" id="productList"></div>
</div>

<script>
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

<?php if(isset($_SESSION['user'])){ ?>
<button class="btn btn-primary w-100"
onclick="addToCart(${p.id})">
เพิ่มลงตะกร้า
</button>
<?php } else { ?>
<a href="login.php"
class="btn btn-secondary w-100">
เข้าสู่ระบบก่อนเพิ่มสินค้า
</a>
<?php } ?>

</div>
</div>
</div>
`;
});
}

function addToCart(id){

<?php if(!isset($_SESSION['user'])){ ?>
alert("กรุณาเข้าสู่ระบบก่อน");
window.location="login.php";
return;
<?php } ?>

let item = cart.find(p=>p.id===id);
if(item){ item.qty++; }
else{
let product = products.find(p=>p.id===id);
cart.push({...product, qty:1});
}
localStorage.setItem("cart",JSON.stringify(cart));
updateCount();
}

function updateCount(){
document.getElementById("cartCount").innerText =
cart.reduce((a,b)=>a+(b.qty||1),0);
}

renderProducts();
updateCount();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
