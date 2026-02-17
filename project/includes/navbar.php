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

<nav class="navbar-custom">
    <div class="container d-flex justify-content-between align-items-center">

        <!-- โลโก้ -->
        <div class="logo">
            🎵 Goods Secret Store
        </div>


        <!-- เมนูด้านขวา -->
        <div class="nav-right d-flex align-items-center gap-3">
            <!-- ค้นหา -->
            <form method="GET" class="d-flex">
            <input class="form-control me-2" type="search" name="search" placeholder="ค้นหาสินค้า...">
            <!--<button class="modern-btn">
                <i class="bi bi-search">ค้นหา</i>
            </button> -->
            </form>

            <?php if(isset($_SESSION['user_id'])){ ?>

                <!-- กรณีเข้าสู่ระบบแล้ว -->
                <a href="cart.php" class="icon-btn">🛒</a>

                <a href="logout.php" class="logout-btn">
                    ออกจากระบบ
                </a>

            <?php } else { ?>

                <!-- กรณียังไม่เข้าสู่ระบบ -->
                <a href="login.php" class="login-btn">
                    เข้าสู่ระบบ
                </a>

                <a href="register.php" class="register-btn">
                    สมัครสมาชิก
                </a>

            <?php } ?>
            
            <h4 class="section-title">ผลลัพธ์สินค้า</h4>
<div class="row">
<?php while($p = $products->fetch_assoc()){ ?>
<div class="col-md-3 mb-4">
<div class="card product-card p-3 text-center">
<img src="images/<?= $p['image']; ?>" class="img-fluid mb-2">
<h6><?= $p['name']; ?></h6>
<p><?= number_format($p['price']); ?> บาท</p>
<a href="product.php?id=<?= $p['id'] ?>" 
   class="btn btn-light btn-sm mt-2">
   รายละเอียด
</a>

        </div>

    </div>
</nav>
