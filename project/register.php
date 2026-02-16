<?php
session_start();
include "connectdb.php";

if(isset($_POST['register'])){
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username,phone,password) VALUES (?,?,?)");
    $stmt->bind_param("sss",$username,$phone,$password);
    $stmt->execute();

    echo "<script>alert('สมัครสมาชิกสำเร็จ');window.location='login.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>สมัครสมาชิก</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
background:linear-gradient(135deg,#4b0082,#6a0dad,#8a2be2);
color:white;
}
.form-box{
background:rgba(255,255,255,0.1);
padding:40px;
border-radius:15px;
}
.password-wrapper{
position:relative;
}
.password-wrapper i{
position:absolute;
right:15px;
top:50%;
transform:translateY(-50%);
cursor:pointer;
color:#ccc;
}
</style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
<div class="col-md-5 form-box">

<h2 class="text-center mb-4">สมัครสมาชิก</h2>

<form method="POST">

<div class="mb-3">
<label>Username</label>
<input type="text" name="username" class="form-control" required>
</div>

<div class="mb-3">
<label>เบอร์โทรศัพท์</label>
<input type="text" name="phone" class="form-control" required>
</div>

<div class="mb-3 password-wrapper">
<label>รหัสผ่าน</label>
<input type="password" name="password" id="password" class="form-control" required>
<i class="bi bi-eye-slash toggle-password" data-target="password"></i>
</div>

<div class="mb-3 password-wrapper">
<label>ยืนยันรหัสผ่าน</label>
<input type="password" id="confirm_password" class="form-control" required>
<i class="bi bi-eye-slash toggle-password" data-target="confirm_password"></i>
</div>

<button type="submit" name="register" class="btn btn-light w-100">
สมัครสมาชิก
</button>

</form>

</div>
</div>

<script>
// ฟังก์ชันเปิด/ปิดรหัสผ่าน
document.querySelectorAll(".toggle-password").forEach(icon=>{
    icon.addEventListener("click", function(){
        let input = document.getElementById(this.dataset.target);

        if(input.type === "password"){
            input.type = "text";
            this.classList.remove("bi-eye-slash");
            this.classList.add("bi-eye");
        }else{
            input.type = "password";
            this.classList.remove("bi-eye");
            this.classList.add("bi-eye-slash");
        }
    });
});
</script>

</body>
</html>
