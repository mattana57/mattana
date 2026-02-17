<?php include "connectdb.php";

if(!isset($_SESSION['user'])) header("Location:login.php");

$user=$_SESSION['user'];

if(isset($_GET['add'])){
$pid=$_GET['add'];
$conn->query("INSERT INTO cart(user_id,product_id) VALUES($user,$pid)");
}

$items=$conn->query("SELECT products.*,cart.quantity FROM cart 
JOIN products ON cart.product_id=products.id
WHERE cart.user_id=$user");
?>

<div class="container mt-5">
<h3>ตะกร้าสินค้า</h3>
<table class="table">
<tr><th>สินค้า</th><th>ราคา</th></tr>
<?php while($i=$items->fetch_assoc()){ ?>
<tr>
<td><?=$i['name']?></td>
<td><?=$i['price']?></td>
</tr>
<?php } ?>
</table>
</div>
<body>
    <?php include "includes/navbar.php"; ?>
</body>
