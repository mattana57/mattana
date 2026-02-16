<?php
session_start();
require_once "connectdb.php";

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if(empty($username) || empty($password)){
        $error = "กรุณากรอกข้อมูลให้ครบถ้วน";
    }else{

        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username=?");
        $stmt->bind_param("s",$username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 1){
            $user = $result->fetch_assoc();

            if(password_verify($password,$user["password"])){

                $_SESSION["user"] = $user["username"];
                $_SESSION["role"] = $user["role"];
                $_SESSION["user_id"] = $user["id"];

                header("Location: index.php");
                exit();

            }else{
                $error = "รหัสผ่านไม่ถูกต้อง";
            }

        }else{
            $error = "ไม่พบผู้ใช้งานนี้";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>เข้าสู่ระบบ | Goods Secret</title>
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
width:400px;
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
<h3 class="text-center mb-4">เข้าสู่ระบบ</h3>

<?php if($error != ""){ ?>
<div class="alert alert-danger text-center">
<?= $error ?>
</div>
<?php } ?>

<form method="POST">

<div class="mb-3">
<label>ชื่อผู้ใช้</label>
<input type="text" name="username" class="form-control" required>
</div>

<div class="mb-3">
<label>รหัสผ่าน</label>
<input type="password" name="password" class="form-control" required>
</div>

<button type="submit" class="btn brand-btn w-100">
เข้าสู่ระบบ
</button>

<div class="text-center mt-3">
ยังไม่มีบัญชี ?
<a href="register.php" class="text-warning">สมัครสมาชิก</a>
</div>

</form>
</div>

</body>
</html>
