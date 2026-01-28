<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>66010914057 มัทนา รัตนแสง (น้ำฝน)</title>
</head>

<body>
    <h1>มัทนา รัตนแสง (น้ำฝน) - โปรแกรมสูตรคูณ</h1>

    <form method="post" action"">
        กรอกแม่สูตรคูณที่คุณต้องการ <input type="number" min="2" max="1000" name="a" autofocus required>
        <button type="submit" name="Submit">OK</button>
    </form>
    <hr>
    
    <?php
        if(isset($_POST['Submit'])){
            $m = $_POST['a'];
            for ($a=1; $a<=12; $a++) {
                $x =$m*$a;
                echo " {$m} x {$a} = {$x} <br>" ;
            }
        }
    ?>

   
</body>
</html>