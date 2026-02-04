<?php
session_start();

// ล้าง session ทั้งหมด
$_SESSION = [];
session_destroy();
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Logout | Aurora Admin</title>

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
    border-radius: 24px;
    border: 1px solid rgba(255,255,255,0.25);
    box-shadow: 0 35px 70px rgba(0,0,0,0.5);
    animation: fadeUp .7s ease;
}

@keyframes fadeUp{
    from{opacity:0; transform:translateY(30px);}
    to{opacity:1; transform:translateY(0);}
}

.icon-glow{
    width:90px;
    height:90px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    background: rgba(34,211,238,.15);
    box-shadow: 0 0 40px rgba(34,211,238,.7);
    margin: auto;
}

.gradient-text{
    background: linear-gradient(135deg,#22d3ee,#a855f7);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
</style>

<script>
// redirect หลัง 3 วินาที
setTimeout(() => {
    window.location = "login.php";
}, 3000);
</script>
</head>

<body class="d-flex justify-content-center align-items-center">

<div class="glass p-5 text-center text-white" style="max-width:420px; width:100%;">
    
    <div class="icon-glow mb-3">
        <i class="bi bi-check2-circle fs-1 text-info"></i>
    </div>

    <h3 class="fw-bold gradient-text mb-2">
        ออกจากระบบสำเร็จ
    </h3>

    <p class="text-white-50 mb-4">
        กำลังพาคุณกลับไปหน้าเข้าสู่ระบบ
    </p>

    <div class="spinner-border text-info mb-3" role="status"></div>

    <p class="small text-white-50 mb-0">
        หากไม่ถูกพาไปอัตโนมัติ
        <br>
        <a href="login.php" class="text-info fw-semibold text-decoration-none">
            คลิกที่นี่เพื่อเข้าสู่ระบบ
        </a>
    </p>

</div>

</body>
</html>
