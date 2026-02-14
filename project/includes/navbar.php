<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
<div class="container-fluid px-4">

<a class="navbar-brand fw-bold" href="index.php">
🎵 Goods Secret Store
</a>

<div class="d-flex align-items-center w-100 justify-content-end">

<input id="searchInput"
class="form-control me-3"
style="max-width:400px;"
placeholder="ค้นหาสินค้า...">

<a href="cart.php"
class="btn btn-warning position-relative me-3">
<i class="bi bi-cart"></i>
<span id="cartCount"
class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
0
</span>
</a>

<?php if(isset($_SESSION['user'])){ ?>

<div class="dropdown">
<button class="btn btn-outline-light dropdown-toggle"
data-bs-toggle="dropdown">
👤 <?= $_SESSION['user']; ?>
</button>

<ul class="dropdown-menu dropdown-menu-end">
<li><a class="dropdown-item" href="#">โปรไฟล์</a></li>
<li><a class="dropdown-item" href="#">คำสั่งซื้อ</a></li>
<li><hr class="dropdown-divider"></li>
<li><a class="dropdown-item" href="logout.php">ออกจากระบบ</a></li>
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
