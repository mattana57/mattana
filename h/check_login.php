<?php
    session_start("check_login.php");
    if(empty($_SESSION['aid'])){
        echo "Access Denied";
        echo "<meta http-equiv='refresh' content='3; url=index.php'>";
        exit;
    }
?>