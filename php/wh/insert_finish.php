<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("wh/insert_finish.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

$file = fopen('finish.csv','r');
    while ($data = fgetcsv($file)) {
        $sql = "INSERT INTO finish (variety_code,plant_date,cut_date,finish_date, mname, mtime)
        VALUES(
               '" . $data[0] . "',
               '" . $data[1] . "',
               '" . $data[2] . "',
               '" . $data[3] . "',
               '" . $_SESSION['WHOAMI'] . "',
               '" . date('Y-m-d',time()) . "');";
        WHDBmysql_query($sql);
        echo $sql;
        echo "</br>";
    }
    fclose($file);
?>
