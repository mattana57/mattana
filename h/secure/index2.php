<?php
include_once("check_login.php");
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Admin Dashboard</title>

<!-- Bootstrap 5.3 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body{
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #22d3ee, #6366f1, #a855f7);
    min-height: 100vh;
}

.glass{
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(16px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.3);
    box-shadow: 0 25px 50px rgba(0,0,0,0.25);
}

.menu-card{
    transition: 0.3s;
    cursor: pointer;
}

.menu-card:hover{
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}
</style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg glass mx-3 mt-3 mb-4">
    <div class="container-fluid">
        <a class="navbar-brand text-white fw-bold" href="#">
            <i class="bi bi-speedometer2"></i> Admin Dashboard
        </a>
        <div class="text-white">
            <i class="bi bi-person-circle"></i>
            <?php echo $_SESSION['aname']; ?>
        </div>
    </div>
</nav>

<!-- Content -->
<div class="container">
    <div class="row g-4 justify-content-center">

        <div class="col-md-3">
            <a href="product.php" class="text-decoration-none text-dark">
                <div class="glass p-4 text-center menu-card">
                    <i class="bi bi-box-seam fs-1 text-primary"></i>
                    <h5 class="mt-3">จัดการสินค้า</h5>
                    <p class="small text-muted">Products</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="orders.php" class="text-decoration-none text-dark">
                <div class="glass p-4 text-center menu-card">
                    <i class="bi bi-receipt fs-1 text-success"></i>
                    <h5 class="mt-3">จัดการออเดอร์</h5>
                    <p class="small text-muted">Orders</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="customers.php" class="text-decoration-none text-dark">
                <div class="glass p-4 text-center menu-card">
                    <i class="bi bi-people-fill fs-1 text-warning"></i>
                    <h5 class="mt-3">จัดการลูกค้า</h5>
                    <p class="small text-muted">Customers</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="logout.php" class="text-decoration-none text-dark">
                <div class="glass p-4 text-center menu-card">
                    <i class="bi bi-box-arrow-right fs-1 text-danger"></i>
                    <h5 class="mt-3">ออกจากระบบ</h5>
                    <p class="small text-muted">Logout</p>
                </div>
            </a>
        </div>

    </div>
</div>

</body>
</html>
