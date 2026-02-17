<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>66010914057 มัทนา รัตนแสง(น้ำฝน)</title>
</head>

<body>
<h1>งานi --มัทนา รัตนแสง(น้ำฝน)</h1>

<?php
include_once("connectdb.php");
$sql = "SELECT * FROM `regions`";
$rs = mysqli_query($conn,$sql);
while($data = mysqli_fetch_array($rs)){
    echo $data['r_id']. "<br>";
    echo $data['r_name']. "<hr>";
}

mysqli_close($conn);
?>
</body>
</html>

