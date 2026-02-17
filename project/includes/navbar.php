<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<style>
.navbar-custom {
    background: linear-gradient(135deg,#2d0b4e,#5b21b6);
    padding: 15px 0;
    color: #fff;
}

.logo {
    font-size: 22px;
    font-weight: 600;
    color: #fff;
}

.search-box input {
    border: none;
    border-radius: 25px 0 0 25px;
    padding: 8px 15px;
    width: 220px;
    outline: none;
}

.search-box button {
    border: none;
    background: #c084fc;
    border-radius: 0 25px 25px 0;
    padding: 8px 15px;
    color: #fff;
    cursor: pointer;
}

.icon-btn {
    background: #c084fc;
    padding: 8px 12px;
    border-radius: 50%;
    text-decoration: none;
    color: #fff;
    font-size: 18px;
    transition: 0.3s;
}

.logout-btn,
.login-btn,
.register-btn {
    background: #c084fc;
    padding: 8px 18px;
    border-radius: 20px;
    text-decoration: none;
    color: #fff;
    font-weight: 500;
    transition: 0.3s;
}

.logout-btn:hover,
.login-btn:hover,
.register-btn:hover,
.icon-btn:hover {
    background: #a855f7;
}
</style>

<nav class="navbar navbar-expand-lg navbar-dark py-3">
<div class="container">

<a class="navbar-brand fw-bold text-white" href="index.php">
🎵 Goods Secret Store
</a>

<div class="ms-auto d-flex align-items-center gap-3">

<form method="GET" class="d-flex">
<input class="form-control me-2" 
type="search"
name="search"
placeholder="ค้นหาสินค้า...">
<button class="modern-btn">
<i class="bi bi-search"></i>
</button>
</form>

<?php if(isset($_SESSION['user_id'])){ ?>
<a href="cart.php" class="modern-btn">
<i class="bi bi-cart"></i>
</a>
<a href="logout.php" class="modern-btn">ออกจากระบบ</a>
<?php } else { ?>
<a href="login.php" class="modern-btn">เข้าสู่ระบบ</a>
<a href="register.php" class="modern-btn">สมัครสมาชิก</a>
<?php } ?>

</div>
</div>
</nav>