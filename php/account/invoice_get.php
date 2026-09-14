<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("account/invoice_get.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if( checkUrlNumber() == FALSE){
	header("Location: " . $BASE_DIR . "account/menu.php");
}
else{
	$id = $_GET['id'];
}


$sql = "UPDATE invoice 
		SET flag = '已收到发票' WHERE id = " . $id . ";";
WHDBmysql_query($sql);
header("Location: " . $BASE_DIR . "account/invoice.php");

require("menu.php");
require("../public/footer.php");
?>