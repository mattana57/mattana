<?php
include_once("check_login.php");
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>จัดการออเดอร์ | Backend System</title>

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

.nav-link{
    color: #fff !important;
    font-weight: 500;
}

.nav-link:hover{
    text-decoration: underline;
}
</style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg glass mx-3 mt-3 mb-4">
    <div class="container-fluid">
        <a class="navbar-brand text-white fw-bold" href="index2.php">
            <i class="bi bi-stars"></i> Aurora Admin
        </a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <span class="nav-link">
                    <i class="bi bi-person-circle"></i>
                    <?php echo $_SESSION['aname']; ?>
                </span>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="logout.php">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- Content -->
<div class="container">

    <div class="glass p-4 mb-4">
        <h3 class="mb-2">
            <i class="bi bi-receipt text-primary"></i>
            จัดการออเดอร์
        </h3>
        <p class="text-muted">ดู แก้ไข และจัดการคำสั่งซื้อทั้งหมด</p>
    </div>

    <!-- Placeholder ตารางออเดอร์ -->
    <div class="glass p-4">
        <h5 class="mb-3">📦 รายการออเดอร์</h5>
        <p class="text-muted">
            (พื้นที่สำหรับแสดงตารางออเดอร์ในอนาคต)
        </p>

        <!-- ตัวอย่างโครงตาราง -->
        <table class="table table-hover align-middle bg-white rounded">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>ชื่อลูกค้า</th>
                    <th>วันที่สั่ง</th>
                    <th>สถานะ</th>
                    <th class="text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>ตัวอย่าง</td>
                    <td>2026-01-01</td>
                    <td><span class="badge bg-warning">รอดำเนินการ</span></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-primary">ดู</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
