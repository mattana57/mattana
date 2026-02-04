<?php
session_start();
include_once("connectdb.php");
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Aurora Admin Login</title>

<!-- Bootstrap 5.3 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
body{
    font-family: 'Inter', sans-serif;
    background: radial-gradient(circle at top left, #22d3ee, transparent 40%),
                radial-gradient(circle at bottom right, #a855f7, transparent 40%),
                linear-gradient(135deg, #0f172a, #020617);
    min-height: 100vh;
}

/* Glass Card */
.glass-card{
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(18px);
    border-radius: 24px;
    border: 1px solid rgba(255,255,255,0.25);
    box-shadow: 0 30px 60px rgba(0,0,0,0.45);
    animation: fadeUp 0.9s ease;
}

@keyframes fadeUp{
    from{opacity:0; transform:translateY(50px);}
    to{opacity:1; transform:translateY(0);}
}

/* Inputs */
.form-control{
    background: rgba(255,255,255,0.9);
    border-radius: 14px;
    border: none;
    padding: 12px;
}

.form-control:focus{
    box-shadow: 0 0 0 0.25rem rgba(56,189,248,.35);
}

/* Button */
.btn-aurora{
    background: linear-gradient(135deg, #22d3ee, #6366f1, #a855f7);
    border: none;
    border-radius: 30px;
    font-weight: 600;
    color: #fff;
    padding: 12px;
    transition: 0.3s;
    box-shadow: 0 0 20px rgba(99,102,241,0.6);
}

.btn-aurora:hover{
    transform: translateY(-2px);
    box-shadow: 0 0 35px rgba(99,102,241,0.9);
}

/* Icon */
.brand-icon{
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg, #22d3ee, #a855f7);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: auto;
    box-shadow: 0 0 30px rgba(168,85,247,0.7);
}

.toggle-password{
    cursor: pointer;
}
</style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="glass-card p-4 px-5" style="width:100%; max-width:420px;">

        <div class="text-center text-white mb-4">
            <div class="brand-icon mb-3">
                <i class="bi bi-stars fs-3"></i>
            </div>
            <h3 class="fw-bold">Aurora Admin</h3>
            <p class="text-white-50 small">Sign in to your dashboard</p>
        </div>

        <form method="post">
            <div class="mb-3">
                <label class="form-label text-white small">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-0">
                        <i class="bi bi-person-fill"></i>
                    </span>
                    <input type="text" name="auser" class="form-control" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label text-white small">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-0">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input type="password" name="apwd" id="password" class="form-control" required>
                    <span class="input-group-text bg-white border-0 toggle-password" onclick="togglePassword()">
                        <i class="bi bi-eye-fill" id="eye"></i>
                    </span>
                </div>
            </div>

            <button type="submit" name="submit" class="btn btn-aurora w-100">
                Login
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
                    echo "<div class='alert alert-danger mt-3 text-center small'>Invalid username or password</div>";
                }
            } else {
                echo "<div class='alert alert-danger mt-3 text-center small'>Invalid username or password</div>";
            }
        }
        ?>

        <p class="text-center text-white-50 mt-4 small">
            © 2026 Mattana · Secure Admin Panel
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
