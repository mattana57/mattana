<?php
include "includes/header.php";
include "includes/navbar.php";
include "connectdb.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = mysqli_prepare($conn, 
"SELECT * FROM users WHERE email=?");

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if($user && password_verify($password, $user['password'])){
    $_SESSION['user'] = $user;
    header("Location: index.php");
    exit();
}else{
    echo "<div class='container mt-3 alert alert-danger'>
    Email หรือ Password ไม่ถูกต้อง
    </div>";
}

mysqli_stmt_close($stmt);
}
?>

<div class="container mt-5" style="max-width:500px;">
<h2>เข้าสู่ระบบ</h2>
<form method="POST">
<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
<button class="btn btn-success w-100">เข้าสู่ระบบ</button>
</form>
</div>
