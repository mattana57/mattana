<?php
require_once "connectdb.php";

$success = "";
$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm = trim($_POST["confirm_password"]);

    if($password !== $confirm){
        $error = "รหัสผ่านไม่ตรงกัน";
    }else{

        $check = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
        $check->bind_param("ss",$username,$email);
        $check->execute();
        $result = $check->get_result();

        if($result->num_rows > 0){
            $error = "Username หรือ Email ถูกใช้งานแล้ว";
        }else{

            $hash = password_hash($password,PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users(username,email,password,role) VALUES(?,?,?,?)");
            $role = "member";
            $stmt->bind_param("ssss",$username,$email,$hash,$role);

            if($stmt->execute()){
                $success = "สมัครสมาชิกสำเร็จ! ไปหน้า Login ได้เลย";
            }else{
                $error = "เกิดข้อผิดพลาด กรุณาลองใหม่";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>สมัครสมาชิก | Goods Secret</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{
background:linear-gradient(135deg,#120018,#2a0845,#6a1b9a);
height:100vh;
display:flex;
justify-content:center;
align-items:center;
color:white;
font-family:'Segoe UI',sans-serif;
}
.card{
background:rgba(255,255,255,0.08);
backdrop-filter:blur(12px);
border:none;
border-radius:0;
color:white;
width:450px;
}
.brand-btn{
background:#E0BBE4;
border:none;
border-radius:0;
font-weight:600;
}
.brand-btn:hover{
background:#d39ddb;
}
input{
border-radius:0 !important;
}
</style>
</head>
<body>

<div class="card p-4 shadow-lg">
<h3 class="text-center mb-4">สมัครสมาชิก</h3>

<?php if($error != ""){ ?>
<div class="alert alert-danger text-center">
<?= $error ?>
</div>
<?php } ?>

<?php if($success != ""){ ?>
<div class="alert alert-success text-center">
<?= $success ?>
</div>
<?php } ?>

<form method="POST">

<div class="mb-3">
<label>ชื่อผู้ใช้</label>
<input type="text" name="username" class="form-control" required>
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label>รหัสผ่าน</label>
<input type="password" name="password" class="form-control" required>
</div>

<div class="mb-3">
<label>ยืนยันรหัสผ่าน</label>
<input type="password" name="confirm_password" class="form-control" required>
</div>

<button type="submit" class="btn brand-btn w-100">
สมัครสมาชิก
</button>

<div class="text-center mt-3">
มีบัญชีแล้ว ?
<a href="login.php" class="text-warning">เข้าสู่ระบบ</a>
</div>

</form>
</div>

</body>
</html>
