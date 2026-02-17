<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>66010914057 มัทนา รัตนแสง(น้ำฝน)</title>
</head>

<body>
<h1>งานi --มัทนา รัตนแสง(น้ำฝน)</h1>

<form method="post" action="" enctype="multipart/form-data">
    ชื่อจังหวัด <input type="text" name="pname" autofocus required><br>
    รูปภาพ <input type="file" name="pimage" required><br>

    ภาค
    <select name="rid">
    <?php
include_once("connectdb.php");
$sql3 = "SELECT * FROM `regions`";
$rs3 = mysqli_query($conn,$sql3);
while($data3 = mysqli_fetch_array($rs3)){
?>
        <option value="<?php echo $data3['r_id']; ?>"><?php echo $data3['r_name']; ?></option>
    <?php } ?>
    </select>
    <br>

    <button type="submit" name="Submit"> บันทึก </button>
</form><br><br>

<?php
if(isset($_POST['Submit'])){
    include_once("connectdb.php");

    $pname = $_POST['pname'];
    $ext = pathinfo($_FILES['pimage']['name'], PATHINFO_EXTENSION);
    $rid = $_POST['rid'];

    $sql2 = "INSERT INTO `provinces` VALUES (NULL, '{$pname}','{$ext}','{$rid}')";
    mysqli_query($conn,$sql2) or die ("เพิ่มข้อมูลไม่ได้");
    $pid = mysqli_insert_id($conn);
    //copy($_FILES['pimage']['tmp_name'],"images/".$pid.".".$ext);
    move_uploaded_file($_FILES['pimage']['tmp_name'],"images/".$pid.".".$ext);
}
?>

<table border="1">
    <tr>
        <th>รหัสจังหวัด</th>
        <th>ชื่อจังหวัด</th>
        <th>รูป</th>
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
        <td><img src="images/<?php echo $data['p_id']; ?>.<?php echo $data['p_ext']; ?>" width="140"></td>
        <td width="80" align="center"><a href="delete_region.php?id=<?php echo $data['r_id']; ?>" onClick="return confirm('ยืนยันการลบ?');"><img src="images/delete.png" width="20"></a></td>
    </tr>
<?php } ?>
</table>

<?php
mysqli_close($conn);
?>
</body>
</html>

