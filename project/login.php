<?php
session_start();
include "connectdb.php";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($query);

    if($user && password_verify($password,$user['password'])){
        $_SESSION['user'] = $user['fullname'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id'];

        header("Location: index.php");
        exit();
    } else {
        $error = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>เข้าสู่ระบบ</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include "navbar.php"; ?>

<div class="container mt-5">
<div class="col-md-6 mx-auto card p-4">

<h3>เข้าสู่ระบบ</h3>

<?php if(isset($error)){ ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<form method="POST">
<input type="email" name="email" class="form-control mb-3" placeholder="อีเมล" required>
<input type="password" name="password" class="form-control mb-3" placeholder="รหัสผ่าน" required>
<button name="login" class="btn btn-primary w-100">เข้าสู่ระบบ</button>
</form>

</div>
</div>

</body>
</html>
