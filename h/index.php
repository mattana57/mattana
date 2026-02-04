<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>66010914057 มัทนา รัตนแสง(น้ำฝน)</title>
</head>

<body>
    <h1>เข้าสู่ระบบหลังบ้าน-มัทนา</h1>

    <form method="post" action="">
        Username <input type="text" name="auser" autofocus require><br>
        Password <input type="password" name="apwd"  require><br>
        <button type="submit" name="Submit">LOGIN</button>
    </form>

    <?php
        if(isset($_POST['Submin'])){
            include_once("connectdb.php");
            $sql = "SELECT*FROM admin WHERE a_username='{$_POST['auser']}' AND a_password='{$_POST['apwd']}' LIMIT1";
            $rs = mysqli_query($conn,$sql);
            $num = mysqli_num_rows($rs);

            echo $num;
        }
    ?>

</body>
</html>