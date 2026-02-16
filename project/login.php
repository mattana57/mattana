<?php
session_start();
require_once "connectdb.php";

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $identity = trim($_POST["identity"]);
    $password = trim($_POST["password"]);

    if(empty($identity) || empty($password)){
        $error = "กรุณากรอกข้อมูลให้ครบ";
    }else{

        $stmt = $conn->prepare("SELECT * FROM users WHERE username=? OR email=? OR phone=?");
        $stmt->bind_param("sss",$identity,$identity,$identity);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 1){
            $user = $result->fetch_assoc();

            if(password_verify($password,$user["password"])){
                $_SESSION["user"] = $user["username"];
                $_SESSION["role"] = $user["role"];
                header("Location:index.php");
                exit();
            }else{
                $error = "รหัสผ่านไม่ถูกต้อง";
            }
        }else{
            $error = "ไม่พบผู้ใช้งาน";
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
width:420px;
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

.social-btn{
border-radius:0;
}
</style>
</head>
<body>

<div class="card p-4">

<h3 class="text-center mb-4">เข้าสู่ระบบ</h3>

<?php if($error!=""){ ?>
<div class="alert alert-danger text-center"><?= $error ?></div>
<?php } ?>

<form method="POST">

<div class="mb-3">
<label>Username / Email / เบอร์โทร</label>
<input type="text" name="identity" class="form-control" autofocus required>
</div>

<div class="mb-3 position-relative">
<label>รหัสผ่าน</label>
<input type="password" name="password" id="password" class="form-control" required>
<i class="bi bi-eye-slash position-absolute"
style="right:10px; top:38px; cursor:pointer;"
onclick="togglePassword()"></i>
</div>

<button type="submit" class="btn brand-btn w-100 mb-3">
เข้าสู่ระบบ
</button>

<div class="text-center mb-3">
<a href="#" class="text-warning">เข้าสู่ระบบด้วย SMS</a>
</div>

<hr>

<button type="button" class="btn btn-light social-btn w-100 mb-2">
<i class="bi bi-google"></i> ดำเนินการต่อด้วย Google
</button>

<button type="button" class="btn btn-primary social-btn w-100">
<i class="bi bi-facebook"></i> ดำเนินการต่อด้วย Facebook
</button>

<div class="text-center mt-3">
ยังไม่มีบัญชี ?
<a href="register.php" class="text-warning">สมัครสมาชิก</a>
</div>

</form>
</div>

<script>
function togglePassword(){
let pass=document.getElementById("password");
pass.type = pass.type==="password"?"text":"password";
}
</script>

</body>
</html>
