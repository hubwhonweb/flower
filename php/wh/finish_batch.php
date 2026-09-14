<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/house_in_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

$sql = "select * from finish;";
$res =  WHDBmysql_query($sql);
while( $rec = mysqli_fetch_assoc($res) ){
    $sql = "UPDATE batch set second_date='".$rec['cut_date']."',
       finish_date='".$rec['finish_date']."' where variety_code ='".$rec['variety_code']."'
       and batch_date='".$rec['plant_date']."';";
    echo $sql;
    WHDBmysql_query($sql);
}
    
require("menu.php");
require("../public/footer.php");
?>
