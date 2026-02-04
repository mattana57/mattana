<?php
session_start();
include_once("connectdb.php");
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Admin Login</title>

<!-- Bootstrap 5.3 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body{
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #22d3ee, #6366f1, #a855f7);
    min-height: 100vh;
}

.glass-card{
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(16px);
    border-radius: 22px;
    border: 1px solid rgba(255,255,255,0.3);
    box-shadow: 0 25px 50px rgba(0,0,0,0.25);
    animation: slideUp 0.9s ease;
}

@keyframes slideUp{
    from{opacity:0; transform:translateY(40px);}
    to{opacity:1; transform:translateY(0);}
}

.form-control{
    background: rgba(255,255,255,0.85);
    border-radius: 14px;
    border: none;
}

.form-control:focus{
    box-shadow: 0 0 0 0.2rem rgba(99,102,241,.35);
}

.btn-aurora{
    background: linear-gradient(135deg, #22d3ee, #6366f1, #a855f7);
    border: none;
    border-radius: 30px;
    font-weight: 600;
    color: #fff;
    transition: 0.3s;
}

.btn-aurora:hover{
    transform: translateY(-2px) scale(1.01);
    box-shadow: 0 15px 30px rgba(0,0,0,0.3);
}

.toggle-password{
    cursor: pointer;
}
</style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="glass-card p-4" style="width:100%; max-width:420px;">
        <div class="text-center mb-4 text-white">
            <i class="bi bi-stars fs-1"></i>
            <h3 class="mt-2">Aurora Admin</h3>
            <p class="small">Secure system login</p>
        </div>

        <form method="post">
            <div class="mb-3">
                <label class="form-label text-white">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-0">
                        <i class="bi bi-person-fill"></i>
                    </span>
                    <input type="text" name="auser" class="form-control" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label text-white">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-0">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input type="password" name="apwd" id="password" class="form-control" required>
                    <span class="input-group-text bg-white border-0 toggle-password"
                          onclick="togglePassword()">
                        <i class="bi bi-eye-fill" id="eye"></i>
                    </span>
                </div>
            </div>

            <button type="submit" name="submit" class="btn btn-aurora w-100 py-2">
                LOGIN
            </button>
        </form>

        <?php
        if (isset($_POST['submit'])) {

            $stmt = $conn->prepare(
                "SELECT a_id, a_name, a_password 
                 FROM admin 
                 WHERE a_username = ? 
                 LIMIT 1"
            );
            $stmt->bind_param("s", $_POST['auser']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $data = $result->fetch_assoc();

                if (password_verify($_POST['apwd'], $data['a_password'])) {
                    $_SESSION['aid']   = $data['a_id'];
                    $_SESSION['aname'] = $data['a_name'];
                    echo "<script>window.location='index2.php';</script>";
                } else {
                    echo "<div class='alert alert-danger mt-3 text-center'>❌ Login failed</div>";
                }
            } else {
                echo "<div class='alert alert-danger mt-3 text-center'>❌ Login failed</div>";
            }
        }
        ?>

        <p class="text-center text-white-50 mt-4 small">
            © 2026 | Aurora System
        </p>
    </div>
</div>

<script>
function togglePassword(){
    const pwd = document.getElementById("password");
    const eye = document.getElementById("eye");
    if(pwd.type === "password"){
        pwd.type = "text";
        eye.className = "bi bi-eye-slash-fill";
    }else{
        pwd.type = "password";
        eye.className = "bi bi-eye-fill";
    }
}
</script>

</body>
</html>
