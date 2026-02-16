<?php
session_start();
include "connectdb.php";

$error = "";
$success = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = trim($_POST['username']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // ตรวจสอบรหัสผ่านตรงกัน
    if($password !== $confirm){
        $error = "รหัสผ่านไม่ตรงกัน";
    }

    // ตรวจสอบ username หรือ phone ซ้ำ
    else{

        $check = $conn->prepare("SELECT id FROM users WHERE username=? OR phone=?");
        $check->bind_param("ss",$username,$phone);
        $check->execute();
        $check->store_result();

        if($check->num_rows > 0){
            $error = "มีบัญชีนี้ในระบบแล้ว กรุณาเข้าสู่ระบบ";
        }
        else{

            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users(username,phone,password) VALUES(?,?,?)");
            $stmt->bind_param("sss",$username,$phone,$hashed);

            if($stmt->execute()){
                $success = "สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ";
            } else {
                $error = "เกิดข้อผิดพลาดในการสมัคร";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>สมัครสมาชิก</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
background: linear-gradient(135deg,#2a0845,#6a1b9a,#3d1e6d);
height:100vh;
display:flex;
justify-content:center;
align-items:center;
font-family:'Segoe UI',sans-serif;
}

.card{
background:rgba(255,255,255,0.05);
backdrop-filter:blur(12px);
border:none;
padding:40px;
width:420px;
box-shadow:0 0 40px rgba(187,134,252,.4);
color:#fff;
}

.card h2,
.card label,
.card p,
.card a{
color:#fff !important;
}

.form-control{
background:#2a0845;
border:1px solid #bb86fc;
color:#fff;
}

.form-control::placeholder{
color:#ccc;
}

.btn-brand{
background:#E0BBE4;
color:#2a0845;
font-weight:600;
}

.btn-brand:hover{
background:#d39ddb;
}
</style>
</head>
<body>

<div class="card">
<h2 class="text-center mb-4" >สมัครสมาชิก</h2>

<?php if($error){ ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<?php if($success){ ?>
<div class="alert alert-success"><?= $success ?></div>
<?php } ?>

<form method="POST">

<div class="mb-3">
<label>Username</label>
<input type="text" name="username" class="form-control" autofocus required>
</div>

<div class="mb-3">
<label>เบอร์โทรศัพท์</label>
<input type="text" name="phone" class="form-control" required>
</div>

<div class="mb-3">
<label>รหัสผ่าน</label>
<input type="password" name="password" class="form-control" required>
<i class="bi bi-eye-slash toggle-password" data-target="confirm_password"></i>
</div>

<div class="mb-3">
<label>ยืนยันรหัสผ่าน</label>
<input type="password" name="confirm_password" class="form-control" required>
<i class="bi bi-eye-slash toggle-password" data-target="confirm_password"></i>
</div>

<button class="btn btn-brand w-100">สมัครสมาชิก</button>

</form>

<hr class="my-4">

<div class="d-grid gap-2">

<a href="google_login.php" class="btn btn-light">
<img src="https://img.icons8.com/color/20/000000/google-logo.png"/>
</a>

<a href="facebook_login.php" class="btn btn-primary">
<i class="bi bi-facebook"></i>
 ดำเนินการต่อด้วย Facebook
</a>

<a href="line_login.php" class="btn" style="background:#06C755;color:white;">
 ดำเนินการต่อด้วย LINE
</a>

<a href="x_login.php" class="btn btn-dark">
 ดำเนินการต่อด้วย X
</a>

</div>


<p class="mt-3 text-center">
มีบัญชีแล้ว ? <a href="login.php">เข้าสู่ระบบ</a>
</p>

</div>

</body>
</html>
