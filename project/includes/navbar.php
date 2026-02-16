<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
include "connectdb.php";
?>

<style>
.nav-purple {
    background: linear-gradient(90deg,#3c1a5b,#5b2a86);
}
.category-btn {
    border:1px solid #fff;
    color:#fff;
}
.category-btn:hover {
    background:#fff;
    color:#3c1a5b;
}
</style>

<nav class="navbar navbar-expand-lg nav-purple navbar-dark">
<div class="container">

<a class="navbar-brand fw-bold text-white" href="index.php">
📚 ComicShop
</a>

<div class="d-flex flex-wrap gap-2">

<?php
$cats = $conn->query("SELECT * FROM categories");
while($c = $cats->fetch_assoc()){
?>
<a href="category.php?slug=<?=$c['slug']?>"
class="btn btn-sm category-btn">
<?=$c['name']?>
</a>
<?php } ?>

</div>

<div class="ms-auto d-flex gap-2">
<?php if(isset($_SESSION['user'])){ ?>
<a href="cart.php" class="btn btn-light px-4">ตะกร้า</a>
<a href="logout.php" class="btn btn-dark px-4">ออกจากระบบ</a>
<?php } else { ?>
<a href="login.php" class="btn btn-light px-4">เข้าสู่ระบบ</a>
<a href="register.php" class="btn btn-warning px-4">สมัครสมาชิก</a>
<?php } ?>
</div>

</div>
</nav>
