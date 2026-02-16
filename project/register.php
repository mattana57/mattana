<?php
require_once "connectdb.php";

$error="";
$success="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

$username=trim($_POST["username"]);
$email=trim($_POST["email"]);
$phone=trim($_POST["phone"]);
$password=$_POST["password"];
$confirm=$_POST["confirm"];

if($password!==$confirm){
$error="รหัสผ่านไม่ตรงกัน";
}else{

$check=$conn->prepare("SELECT id FROM users WHERE username=? OR email=? OR phone=?");
$check->bind_param("sss",$username,$email,$phone);
$check->execute();
$res=$check->get_result();

if($res->num_rows>0){
$error="ข้อมูลนี้ถูกใช้แล้ว";
}else{

$hash=password_hash($password,PASSWORD_DEFAULT);
$role="member";

$stmt=$conn->prepare("INSERT INTO users(username,email,phone,password,role) VALUES(?,?,?,?,?)");
$stmt->bind_param("sssss",$username,$email,$phone,$hash,$role);

if($stmt->execute()){
$success="สมัครสมาชิกสำเร็จ!";
}else{
$error="เกิดข้อผิดพลาด";
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
background:linear-gradient(135deg,#120018,#2a0845,#6a1b9a);
height:100vh;
display:flex;
align-items:center;
justify-content:center;
font-family:'Segoe UI',sans-serif;
color:white;
}

.card{
width:450px;
background:rgba(255,255,255,0.08);
backdrop-filter:blur(15px);
border:none;
border-radius:0;
box-shadow:0 0 25px #bb86fc;
}

input{
border-radius:0 !important;
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
</style>
</head>
<body>

<div class="card p-4">

<h3 class="text-center mb-4">สมัครสมาชิก</h3>

<?php if($error!=""){ ?>
<div class="alert alert-danger text-center"><?= $error ?></div>
<?php } ?>

<?php if($success!=""){ ?>
<div class="alert alert-success text-center"><?= $success ?></div>
<?php } ?>

<form method="POST">

<div class="mb-3">
<label>Username</label>
<input type="text" name="username" class="form-control" autofocus required>
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label>เบอร์โทรศัพท์</label>
<input type="text" name="phone" class="form-control" required>
</div>

<div class="mb-3">
<label>รหัสผ่าน</label>
<input type="password" name="password" class="form-control" required>
</div>

<div class="mb-3">
<label>ยืนยันรหัสผ่าน</label>
<input type="password" name="confirm" class="form-control" required>
</div>

<button type="submit" class="btn brand-btn w-100 mb-3">
สมัครสมาชิก
</button>

<hr>

<button type="button" class="btn btn-light w-100 mb-2">
<i class="bi bi-google"></i> สมัครด้วย Google
</button>

<button type="button" class="btn btn-primary w-100">
<i class="bi bi-facebook"></i> สมัครด้วย Facebook
</button>

<div class="text-center mt-3">
มีบัญชีแล้ว ?
<a href="login.php" class="text-warning">เข้าสู่ระบบ</a>
</div>

</form>
</div>

</body>
</html>
