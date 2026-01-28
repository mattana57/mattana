<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>66010914057 มัทนา รัตนแสง (น้ำฝน)</title>
</head>

<body>
    <h1>มัทนา รัตนแสง (น้ำฝน)</h1>

    <form method="post" action"">
        กรอกตัวเลข <input type="number" name="a" autofocus required>
        <button type="submit" name="Submit">OK</button>
    </form>
    <hr>

    <?php
    if(isset($_POST['Submit'])){
        $gender = $_POST['a'];
        if($gender == 1){
            echo "เพศชาย";
        } elseif($gender == 2){
            echo "เพศหญิง";
        }elseif($gender == 3){
            echo "เพศทางเลือก";
        }else{
            echo "อื่นๆ";
        }
    }
    ?>
</body>
</html>