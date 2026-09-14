<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("one/one_out_pay.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if( checkUrlNumber() == FALSE){
	header("Location: " . $BASE_DIR . "one/one_to_pay.php");
}
else{
	$id = $_GET['id'];
}

$sql = "UPDATE one_out SET one_flag='已付款' WHERE id = " . $id . ";";
WHDBmysql_query($sql);
header("Location: " . $BASE_DIR . "one/one_to_pay.php");

require("menu.php");
require("../public/footer.php");
?>