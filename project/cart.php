<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>ตะกร้าสินค้า</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">
<h3>🛒 ตะกร้าสินค้า</h3>

<table class="table table-bordered mt-4">
<thead class="table-dark">
<tr>
<th>สินค้า</th>
<th>ราคา</th>
<th>จำนวน</th>
<th>รวม</th>
<th>ลบ</th>
</tr>
</thead>
<tbody id="cartTable"></tbody>
</table>

<h4 class="text-end">
รวมทั้งหมด: <span id="grandTotal">0</span> บาท
</h4>

<button class="btn btn-success float-end"
onclick="checkout()">
ยืนยันการสั่งซื้อ
</button>

</div>

<script>
let cart = JSON.parse(localStorage.getItem("cart")) || [];

function renderCart(){
let table = document.getElementById("cartTable");
let total=0;
table.innerHTML="";

cart.forEach(item=>{
let sum=item.price*(item.qty||1);
total+=sum;

table.innerHTML+=`
<tr>
<td>${item.name}</td>
<td>${item.price}</td>
<td>${item.qty||1}</td>
<td>${sum}</td>
<td>
<button class="btn btn-danger btn-sm"
onclick="removeItem(${item.id})">
ลบ
</button>
</td>
</tr>`;
});

document.getElementById("grandTotal").innerText=total;
}

function removeItem(id){
cart=cart.filter(p=>p.id!==id);
localStorage.setItem("cart",JSON.stringify(cart));
renderCart();
}

function checkout(){
alert("สั่งซื้อสำเร็จ 🎉");
localStorage.removeItem("cart");
cart=[];
renderCart();
}

renderCart();
</script>

</body>
</html>
