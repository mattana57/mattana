<meta charset="uft-8">
<?php
include_once("connectdb.php");

$id= $_GET['id'];
$ext = $_GET['ext'];
$sql = "DELETE FROM regions WHERE r_id ='{$id}'";
mysqli_query($conn,$sql) or die ("ลบข้อมูลไม่ได้");

unlink("images/".$id.".".$ext);

echo"<script>";
echo"window.location='a.php'";
echo"</script>";

?>