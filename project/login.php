<?php
session_start();
include "connectdb.php";


if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows>0){
        $user = $result->fetch_assoc();
        if(password_verify($password,$user['password'])){
            $_SESSION['user_id']=$user['id'];
            $_SESSION['username']=$user['username'];
            header("Location:index.php");
            exit();
        }else{
            echo "<script>alert('รหัสผ่านไม่ถูกต้อง');</script>";
        }
    }else{
        echo "<script>alert('ไม่พบผู้ใช้งาน');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>เข้าสู่ระบบ</title>

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

<h2 class="text-center mb-4">เข้าสู่ระบบ</h2>

<form method="POST">

<div class="mb-3">
<label>Username</label>
<input type="text" name="username" class="form-control" required>
</div>

<div class="mb-3 password-wrapper">
<label>รหัสผ่าน</label>
<input type="password" name="password" id="login_password" class="form-control" required>
<i class="bi bi-eye-slash toggle-password" data-target="login_password"></i>
</div>

<button type="submit" name="login" class="btn btn-light w-100">
เข้าสู่ระบบ
</button>

</form>

</div>
</div>

<script>
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
