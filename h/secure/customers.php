<?php
include_once("check_login.php");
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>จัดการลูกค้า | Backend System</title>

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

.table thead{
    background: rgba(255,255,255,0.9);
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
        <div class="text-white">
            <i class="bi bi-person-circle"></i>
            <?php echo $_SESSION['aname']; ?>
        </div>
    </div>
</nav>

<div class="container">

    <!-- Header -->
    <div class="glass p-4 mb-4">
        <h3 class="mb-1">
            <i class="bi bi-people-fill text-warning"></i>
            จัดการลูกค้า
        </h3>
        <p class="text-muted mb-0">ดูและจัดการข้อมูลลูกค้าในระบบ</p>
    </div>

    <!-- Customer Table -->
    <div class="glass p-4">
        <h5 class="mb-3">👥 รายชื่อลูกค้า</h5>

        <table class="table table-hover align-middle bg-white rounded">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ชื่อลูกค้า</th>
                    <th>Email</th>
                    <th>เบอร์โทร</th>
                    <th class="text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <!-- ตัวอย่างข้อมูล -->
                <tr>
                    <td>1</td>
                    <td>สมชาย ใจดี</td>
                    <td>somchai@email.com</td>
                    <td>0812345678</td>
                    <td class="text-center">
                        <a href="#" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
