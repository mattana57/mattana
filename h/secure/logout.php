<?php
session_start();

// ล้าง session ทั้งหมด
$_SESSION = array();
session_destroy();
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Logout | Backend System</title>

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
    animation: fadeIn 0.6s ease;
}

@keyframes fadeIn{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1; transform:translateY(0);}
}
</style>

<script>
// redirect หลัง 3 วินาที
setTimeout(() => {
    window.location = "index.php";
}, 3000);
</script>
</head>

<body class="d-flex justify-content-center align-items-center">

<div class="glass p-5 text-center text-white">
    <i class="bi bi-check-circle-fill fs-1 text-success"></i>
    <h3 class="mt-3">ออกจากระบบสำเร็จ</h3>
    <p class="mt-2">กำลังพาคุณกลับไปหน้าเข้าสู่ระบบ...</p>

    <div class="spinner-border text-light mt-3" role="status"></div>

    <p class="mt-4 small text-white-50">
        หากไม่ถูกพาไปอัตโนมัติ
        <a href="login.php" class="text-white fw-bold">คลิกที่นี่</a>
    </p>
</div>

</body>
</html>
