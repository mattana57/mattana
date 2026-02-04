<?php
include_once("check_login.php");
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Customers | Aurora Admin</title>

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

.glass{
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(18px);
    border-radius: 22px;
    border: 1px solid rgba(255,255,255,0.25);
    box-shadow: 0 35px 70px rgba(0,0,0,0.45);
}

.table thead{
    background: rgba(255,255,255,0.95);
}

.table tbody tr{
    transition: .2s;
}
.table tbody tr:hover{
    background: rgba(99,102,241,.08);
}

.badge-role{
    background: linear-gradient(135deg,#22d3ee,#6366f1);
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

        <div class="text-white fw-medium">
            <i class="bi bi-person-circle"></i>
            <?php echo $_SESSION['aname']; ?>
            <a href="logout.php" class="btn btn-sm btn-outline-light ms-3">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="container">

    <!-- Header -->
    <div class="glass p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-1 text-white">
                <i class="bi bi-people-fill text-info"></i>
                จัดการลูกค้า
            </h3>
            <p class="text-white-50 mb-0">
                ดู แก้ไข และจัดการข้อมูลลูกค้าในระบบ
            </p>
        </div>

        <!-- Search -->
        <div class="w-25 d-none d-md-block">
            <input type="text" class="form-control rounded-pill"
                   placeholder="🔍 ค้นหาลูกค้า...">
        </div>
    </div>

    <!-- Customer Table -->
    <div class="glass p-4">
        <h5 class="text-white mb-3">
            👥 รายชื่อลูกค้า
        </h5>

        <div class="table-responsive">
            <table class="table table-hover align-middle bg-white rounded overflow-hidden">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ชื่อลูกค้า</th>
                        <th>Email</th>
                        <th>เบอร์โทร</th>
                        <th>สถานะ</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- ตัวอย่างข้อมูล -->
                    <tr>
                        <td>1</td>
                        <td>สมชาย ใจดี</td>
                        <td>somchai@email.com</td>
                        <td>081-234-5678</td>
                        <td>
                            <span class="badge badge-role text-white">
                                Active
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

</div>

</body>
</html>
