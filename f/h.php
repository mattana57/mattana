<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>66010914057 มัทนา รัตนแสง (น้ำฝน)</title>
</head>

<body>
    <h1>มัทนา รัตนแสง (น้ำฝน)</h1>

    <form method="post" action"">
        กรอกรหัสนิสิตของท่าน <input type="number" name="a" autofocus required>
        <button type="submit" name="Submit">OK</button>
    </form>
    <hr>
    
    <?php
        if(isset($_POST['Submit'])){
            $id = $_POST['a'];
            $y = substr($id,0,2);
            echo "<img src='http://202.28.32.211/picture/student/{$y}/{$id}.jpg' width='300'>";
        }
    ?>

   
</body>
</html>