<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>66010914057 มัทนา รัตนแสง (น้ำฝน)</title>
</head>

<body>
    <h1> มัทนา รัตนแสง (น้ำฝน)</h1>

    <script language="javascript">
        document.write("คณะการบัญชีและการจัดการ");   //java โค้ดจะขึ้นเหมือน html
    </script>
    <br>

    <?php
    /* commentทั้งหมด บรรทัดเดียวใช้#,//
        echo"Web Programing <br>";
        echo"Mahasarakham University <br>";
        print"Mattana Rattanasang <br>";
    */
        echo"Web Programing <br>";
        echo"Mahasarakham University <br>";
        print"Mattana Rattanasang <br>";

        $name = "มัทนา รัตนแสง";
        $age = 21.3 ;
        $Name = "ปริมาภรณ์ วริปัญโญ";  //ใส่ . หน้า = คือการเอามาต่อกันทั้งสองชื่อ

        //echo gettype($age); //ตรวจว่าเป็นประเภทอะไร
        var_dump($age);   //ตรวจว่าเป็นประเภทอะไรแต่จะละเอียดกว่า
        echo "<hr>";

        echo $name . "<br>"; //เพิ่ม . "<br>" เพื่อให้ห่างและอยู่คนละบรรทัด , <hr> คือการทำเส้นกั้น
        echo $Name . "<hr>";

        //คำนวณ
        $a = 10;
        $b = 5;
        $c = 2;
        $x = ($a + $b) *$c;   // = 30
        //$x = $a - $b + $c;  // = 7
        echo $x;
    ?>

</body>
</html>