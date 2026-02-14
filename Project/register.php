<?php
include "includes/header.php";
include "includes/navbar.php";
include "connectdb.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

$username = $_POST['username'];
$email    = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$stmt = mysqli_prepare($conn, 
"INSERT INTO users (username,email,password) VALUES (?,?,?)");

mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);

if(mysqli_stmt_execute($stmt)){
    echo "<div class='container mt-3 alert alert-success'>
    สมัครสมาชิกสำเร็จ <a href='login.php'>เข้าสู่ระบบ</a>
    </div>";
}else{
    echo "<div class='container mt-3 alert alert-danger'>
    เกิดข้อผิดพลาด
    </div>";
}

mysqli_stmt_close($stmt);
}
?>

<div class="container mt-5" style="max-width:500px;">
<h2>สมัครสมาชิก</h2>
<form method="POST">
<input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
<button class="btn btn-primary w-100">สมัครสมาชิก</button>
</form>
</div>
