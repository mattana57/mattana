<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
<div class="container">
<a class="navbar-brand fw-bold" href="index.php">🎵 Goods Secret</a>

<div class="ms-auto d-flex align-items-center">

<?php if(isset($_SESSION['user'])){ ?>

<div class="dropdown">
<button class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown">
👤 <?= $_SESSION['user']; ?>
</button>

<ul class="dropdown-menu dropdown-menu-end">
<li><a class="dropdown-item" href="profile.php">โปรไฟล์</a></li>
<li><a class="dropdown-item" href="orders.php">คำสั่งซื้อ</a></li>

<?php if($_SESSION['role']=="admin"){ ?>
<li><a class="dropdown-item text-danger" href="admin/dashboard.php">Admin Panel</a></li>
<?php } ?>

<li><hr class="dropdown-divider"></li>
<li><a class="dropdown-item" href="logout.php">ออกจากระบบ</a></li>
</ul>
</div>

<?php } else { ?>

<a href="login.php" class="btn btn-outline-light me-2">เข้าสู่ระบบ</a>
<a href="register.php" class="btn btn-warning">สมัครสมาชิก</a>

<?php } ?>

</div>
</div>
</nav>
