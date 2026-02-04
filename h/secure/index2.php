<?php
include_once("check_login.php");
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Aurora Admin | Dashboard</title>

<!-- Bootstrap 5.3 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
body{
    font-family: 'Inter', sans-serif;
    background:
        radial-gradient(circle at top left, #22d3ee, transparent 35%),
        radial-gradient(circle at bottom right, #a855f7, transparent 35%),
        linear-gradient(135deg, #0f172a, #020617);
    min-height: 100vh;
}

/* Glass */
.glass{
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(18px);
    border-radius: 22px;
    border: 1px solid rgba(255,255,255,0.25);
    box-shadow: 0 30px 60px rgba(0,0,0,0.45);
}

/* Menu Card */
.menu-card{
    transition: 0.35s;
    cursor: pointer;
    color: #fff;
}

.menu-card:hover{
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 0 35px rgba(99,102,241,0.6);
}

/* Icon glow */
.icon-glow{
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: auto;
    margin-bottom: 15px;
    box-shadow: 0 0 25px currentColor;
}
</style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar glass mx-4 mt-4 mb-5">
    <div class="container-fluid px-4">
        <span class="navbar-brand text-white fw-bold fs-4">
            <i class="bi bi-stars"></i> Aurora Admin
        </span>
        <span class="text-white-50">
            <i class="bi bi-person-circle"></i>
            <?php echo $_SESSION['aname']; ?>
        </span>
    </div>
</nav>

<!-- Content -->
<div class="container">
    <div class="row g-4 justify-content-center">

        <div class="col-md-3">
            <a href="product.php" class="text-decoration-none">
                <div class="glass p-4 text-center menu-card">
                    <div class="icon-glow text-info">
                        <i class="bi bi-box-seam fs-3"></i>
                    </div>
                    <h5 class="fw-semibold">จัดการสินค้า</h5>
                    <p class="small text-white-50 mb-0">Product Management</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="orders.php" class="text-decoration-none">
                <div class="glass p-4 text-center menu-card">
                    <div class="icon-glow text-success">
                        <i class="bi bi-receipt fs-3"></i>
                    </div>
                    <h5 class="fw-semibold">จัดการออเดอร์</h5>
                    <p class="small text-white-50 mb-0">Order System</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="customers.php" class="text-decoration-none">
                <div class="glass p-4 text-center menu-card">
                    <div class="icon-glow text-warning">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                    <h5 class="fw-semibold">จัดการลูกค้า</h5>
                    <p class="small text-white-50 mb-0">Customer Data</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="logout.php" class="text-decoration-none">
                <div class="glass p-4 text-center menu-card">
                    <div class="icon-glow text-danger">
                        <i class="bi bi-box-arrow-right fs-3"></i>
                    </div>
                    <h5 class="fw-semibold">ออกจากระบบ</h5>
                    <p class="small text-white-50 mb-0">Logout</p>
                </div>
            </a>
        </div>

    </div>
</div>

</body>
</html>
