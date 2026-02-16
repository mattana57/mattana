<?php
session_start();
include "connectdb.php";

$id = intval($_GET['id']);
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#2a0845;color:white">

<div class="container py-5 text-center">

<img src="images/<?= $product['image']; ?>" width="300">
<h2><?= $product['name']; ?></h2>
<p><?= number_format($product['price']); ?> บาท</p>

<p><?= $product['description']; ?></p>

<?php if(isset($_SESSION['user_id'])){ ?>
<a href="add_to_cart.php?id=<?= $product['id']; ?>" class="btn btn-warning">
เพิ่มลงตะกร้า
</a>
<?php } else { ?>
<button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#loginModal">
เพิ่มลงตะกร้า
</button>
<?php } ?>

</div>

<!-- MODAL -->
<div class="modal fade" id="loginModal">
<div class="modal-dialog">
<div class="modal-content text-center p-4">
<p>กรุณาเข้าสู่ระบบก่อนสั่งซื้อ</p>
<a href="login.php" class="btn btn-primary">เข้าสู่ระบบ</a>
<a href="register.php" class="btn btn-secondary">สมัครสมาชิก</a>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
