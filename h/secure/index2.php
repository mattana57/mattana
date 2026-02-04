<?php
    include_once("check_login.php");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>66010914057 มัทนา รัตนแสง(น้ำฝน)</title>
</head>

<body>
<h1>หน้าหลักแอดมิน-มัทนา</h1>

<?php echo "แอดมิน:" .$_SESSION['aname']; ?><br>

<ul>
    <a href="product.php"><li>จัดการสินค้า</li>
    <a href="orders.php"><li>จัดการออเดอร์</li>
    <a href="customers.php"><li>จัดการลูกค้า</li>
    <a href="logout.php"><li>ออกจากระบบ</li>
</ul>
</body>
</html>
