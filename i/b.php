<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>66010914057 มัทนา รัตนแสง(น้ำฝน)</title>
</head>

<body>
<h1>งานi --มัทนา รัตนแสง(น้ำฝน)</h1>

<form method="post" action="">
    ชื่อภาค <input type="text" name="rname" autofocus required>
    <button type="submit" name="Submit"> บันทึก </button>
</form><br><br>

<?php
if(isset($_POST['Submit'])){
    include_once("connectdb.php");
    $rname = $_POST['rname'];
    $sql2 = "INSERT INTO regions (p_id, p_name) VALUES (NULL, '{$rname}')";
    mysqli_query($conn,$sql2) or die ("เพิ่มข้อมูลไม่ได้");
}
?>

<table border="1">
    <tr>
        <th>รหัสจังหวัด</th>
        <th>ชื่อจังหวัด</th>
        <th>ลบ</th>
    </tr>
<?php
include_once("connectdb.php");
$sql = "SELECT * FROM `provinces`";
$rs = mysqli_query($conn,$sql);
while($data = mysqli_fetch_array($rs)){
?>
    <tr>
        <td><?php echo $data['p_id']; ?></td>
        <td><?php echo $data['p_name']; ?></td>
        <td width="80" align="center"><a href="delete_region.php?id=<?php echo $data['r_id']; ?>" onClick="return confirm('ยืนยันการลบ?');"><img src="images/delete.png" width="20"></a></td>
    </tr>
<?php } ?>
</table>

<?php
mysqli_close($conn);
?>
</body>
</html>

