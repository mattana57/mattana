<?php
    session_start
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>66010914057 มัทนา รัตนแสง(น้ำฝน)</title>
</head>

<body>
    <h1>d.php</h1>

    <?php
        echo @$_SESSION['name'] ."<br>";
        echo @$_SESSION['nickname'] ."<br>";
        echo @$_SESSION['p1'] ."<br>";
        echo @$_SESSION['p2'] ."<br>";
    ?>

</body>
</html>



