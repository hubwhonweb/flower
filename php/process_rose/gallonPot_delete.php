<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/gallonPot_delete.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if( checkUrlNumber() == FALSE){
	header("Location: " . $BASE_DIR . "process_rose/gallonPot.php");
}
else{
	$id = $_GET['id'];
}
deleteFromTable("plant_merge",$id);
header("Location: " . $BASE_DIR . "process_rose/gallonPot.php");

require("menu.php");
require("../public/footer.php");
?>
