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

$sql = "select * from lose";
$res =  WHDBmysql_query($sql);
while( $rec = mysqli_fetch_assoc($res) ){
    $batch_code = date('ymd',strtotime($rec['plant_date']));
    $batch_code = $batch_code.$rec['variety_code'];
    $sql = "UPDATE lose set batch_code='".$batch_code."' where id ='".$rec['id']."';";
    echo $sql;
    WHDBmysql_query($sql);
}
    
require("menu.php");
require("../public/footer.php");
?>
