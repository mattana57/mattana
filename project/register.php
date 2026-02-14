<?php
session_start();
include "connectdb.php";

if(isset($_POST['register'])){

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($check)>0){
        $error = "อีเมลนี้ถูกใช้งานแล้ว";
    } else {

        mysqli_query($conn,"INSERT INTO users(fullname,email,password)
        VALUES('$fullname','$email','$password')");

        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>สมัครสมาชิก</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include "navbar.php"; ?>

<div class="container mt-5">
<div class="col-md-6 mx-auto card p-4">

<h3>สมัครสมาชิก</h3>

<?php if(isset($error)){ ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<form method="POST">
<input type="text" name="fullname" class="form-control mb-3" placeholder="ชื่อ-นามสกุล" required>
<input type="email" name="email" class="form-control mb-3" placeholder="อีเมล" required>
<input type="password" name="password" class="form-control mb-3" placeholder="รหัสผ่าน" required>
<button name="register" class="btn btn-warning w-100">สมัครสมาชิก</button>
</form>

</div>
</div>

</body>
</html>
